<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use DB;

class VenueSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();
        $venue_types = ['Football', 'Cricket' , 'Hockey'];
        foreach (range(1, 10) as $index) {
            DB::table('venues')->insert([
                'venue_name' => $faker->company,
                'venue_type' => $faker->randomElement($venue_types),
            ]);
        }
    }
}
