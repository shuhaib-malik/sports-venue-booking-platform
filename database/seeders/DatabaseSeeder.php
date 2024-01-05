<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use DB;

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

        foreach (range(1, 10) as $index) {
            DB::table('venues')->insert([
                'venue_name' => $faker->company,
                'venue_type' => $faker->randomElement($venue_types),
            ]);
        }

        foreach (range(1, 10) as $index) {
            DB::table('users')->insert([
                'name' => $faker->name,
                'email' => $faker->unique()->safeEmail,
                'phone_number' => $faker->phoneNumber,
                'password' => bcrypt('password') # pssword = "password"
            ]);
        }
    }
}
