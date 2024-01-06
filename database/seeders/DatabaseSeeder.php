<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use App\Models\Venue_Slot;
use App\Models\Venue;
use App\Models\Slot;
use App\Models\Category;
use App\Models\User;
use App\Models\Venue_Category;
use DB;
use Log;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        $faker = Faker::create();
        $venue_types = ['Football', 'Cricket' , 'Hockey'];
        foreach ($venue_types as $venue) {
            Category::create([
                'category_name' => $venue
            ]);
        }

        foreach (range(1, 10) as $index) {
            Venue::create([
                'venue_name' => $faker->company,
            ]);
        }

        $venues = Venue::all();
        $categories = Category::all();
        foreach ($venues as $venue) {
            foreach($categories as $category) {
                Venue_Category::create([
                    'venue_id' => $venue->venue_id,
                    'category_id' => $category->category_id
                ]);
            }
        }

        foreach (range(1, 10) as $index) {
            User::create([
                'name' => $faker->name,
                'email' => $faker->unique()->safeEmail,
                'phone_number' => $faker->phoneNumber,
                'password' => bcrypt('password') # password = "password"
            ]);
        }

        $slots = ['6am','7am','8am','6pm','7pm','8pm','9pm','10pm'];
        foreach(range(0,7) as $index) {
            Slot::create([
                'slot' => $slots[$index],
            ]);
        }

        $venues = Venue::all();
        foreach($venues as $venue) {
            $slots = Slot::all();
            foreach($slots as $slot) {
                Venue_Slot::create([
                    'venue_id' => $venue->venue_id,
                    'slot_id' => $slot->slot_id,
                ]);
            }
        }
    }
}
