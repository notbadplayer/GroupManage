<?php

namespace Database\Seeders;

use Faker\Factory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $faker = Factory::create();

    	// foreach (range(1,200) as $index) {
        //     DB::table('users')->insert([
        //         'name' => $faker->name,
        //         'surname' => $faker->word,
        //         'email' => $faker->email,
        //         'phone' => $faker->phoneNumber,
        //         'password' => $faker->word,
        //     ]);
        // }
    }
}
