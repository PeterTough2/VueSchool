<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        for ($i = 0; $i < 20; $i++) {
            $timezones = ['CET', 'CST', 'GMT+1'];
            $randomTimezone = $timezones[array_rand($timezones)];
            $randomPassword = rand(100000, 999999);

            User::create([
                'name' => $faker->name,
                'email' => $faker->unique()->safeEmail,
                'email_verified_at' => now(),
                'password' => Hash::make($randomPassword),
                'timezone' => $randomTimezone,
                'remember_token' => Str::random(10),
            ]);
        }
    }
}