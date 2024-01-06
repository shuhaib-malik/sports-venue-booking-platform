<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Venue_Slot;
use App\Models\Category;
use App\Models\Booking;
use App\Models\Venue;
use Carbon\Carbon;
use DB;
use Log;

class VenueController extends Controller
{
    public $successStatus = 200;

    public function venueBooking(Request $request) {

        $validator = Validator::make($request->all(),[
            'user_id' => 'required|integer',
            'venue_id' => 'required|integer',
            'slots' => 'required',
            'booking_date' => 'required'
        ]);

        //if request is not valid , send failed response
        if($validator->fails()){
            return response()->json(['error' => $validator->messages()], $this->successStatus);
        }

        try {
            DB::BeginTransaction();
            $user_id = request('user_id');
            $venue_id = request('venue_id');
            $slots = request('slots');
            $booking_date = Carbon::parse(request('booking_date'))->toDateString();
            $upto_booking_date = Carbon::now()->addMonths(2)->toDateString();
            
            // check if booking date is not more than 2 months from todays date
            if($booking_date < $upto_booking_date) {
                foreach($slots as $key => $slot) {

                        $booked_slot = Booking::where('venue_id',$venue_id)->where('slot_id',$slot)->where('booking_date',$booking_date)->value('booking_id');

                        // check if the slot is already booked
                        if(empty($booked_slot)) {
                            $booking = Booking::create([
                                'user_id' => $user_id,
                                'venue_id' => $venue_id,
                                'slot_id' => $slot,
                                'booking_date' => $booking_date
                            ]);
                            
                        }else {
                            return response()->json([
                                'status' => 'Unavailable',
                                'message' => 'Slot Not Available',
                                'error_code' => 1
                            ],$this->successStatus);
                        }
                }
            }else {
                return response()->json([
                    'status' => 'Unavailable',
                    'message' => 'Bookings are allowed for maximum two months',
                    'error_code' => 2
                ],$this->successStatus);
            }
            DB::commit();
            return response()->json([
                'status' => 'success',
                'error_code' => 0,
                'message' => 'Bookings Confirmed',
            ],$this->successStatus);

        } catch (\Exception $e) {
            Log::error($e);
            DB::rollback();
            return response()->json([
                'status' => 'failed',
                'error_code' => 3,
                'message' => 'Something went wrong'
            ]);
        }
    }

    public function getAllVenues(Request $request) {

        $category_id = (int) $request->category_id;

        $builder = DB::table('venues as v')->select('v.venue_id','v.venue_name');

        $builder->when($request->has('category_id') ?? false, fn($builder) => $builder->leftjoin('venue_categories as vc', 'v.venue_id', '=' ,'vc.venue_id')->
        where('vc.category_id',$category_id));
        
        $venues = $builder->get();

        $venues_array = array();

        foreach($venues as $venue) {

            $venue_category = DB::table('categories as c')->select('c.category_id','c.category_name')->
                              leftjoin('venue_categories as vc', 'c.category_id','=', 'vc.category_id')->
                              leftjoin('venues as v', 'vc.venue_id', '=', 'v.venue_id')->where('v.venue_id',$venue->venue_id)->get();

            $one_month_before_date = Carbon::now()->subMonths(1)->toDateString();
            $query = "(SELECT COUNT(*) FROM bookings WHERE booking_date > $one_month_before_date and venue_id = $venue->venue_id)";
            $category = DB::table('bookings')->selectRaw("CASE WHEN ($query) > 15 THEN 'GOLD' WHEN ($query) > 10 THEN 'SILVER' WHEN ($query) > 5 THEN 'BRONZE' ELSE 'OTHER' END AS category")->groupBy('venue_id')->value('category');
            
            $data = new Venue;
            $data->venue_id = $venue->venue_id;
            $data->venue_name = $venue->venue_name;
            $data->venue_category = $venue_category;
            $data->category = $category;
            $venues_array[] = $data;
        }
        return response()->json($venues_array, $this->successStatus);
    }

    public function getAllCategories() {

        $venue_categories = Category::select('category_id','category_name')->get();

        return response()->json($venue_categories, $this->successStatus);
    }
}
