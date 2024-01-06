<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Slot;
use DB;

class SlotsController extends Controller
{
    public function getAllSlots(Request $request) {

        $date = $request->input('date');
        $venue_id = $request->venue_id;

        $slots = DB::table('venue_slots as vs')->select('s.slot_id','s.slot',)->
        leftjoin('slots as s','vs.slot_id', '=', 's.slot_id')->where('venue_id',$venue_id)->get();
        
        $slot_array = array();
        foreach($slots as $slot) {
            $is_booked_slot = DB::table('bookings')->where('slot_id',$slot->slot_id)->where('venue_id',$venue_id)->where('booking_date',$date)->value('booking_id');

            $data = new Slot;
            $data->slot_id = $slot->slot_id;
            $data->slot_name = $slot->slot;
            $data->status = !empty($is_booked_slot) ? 'Slot Not Available' : 'Slot Available';
            $slot_array[] = $data;
        }
        return response()->json($slot_array,200);
    }
}
