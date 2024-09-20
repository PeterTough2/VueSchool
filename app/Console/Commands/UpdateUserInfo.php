<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Faker\Factory as Faker;


class UpdateUserInfo extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:user-info';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Updates user first name, last name, and timezone with random values';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $faker = Faker::create();

        $timezones = ['CET', 'CST', 'GMT+1']; // Add more timezones as needed

        User::all()->each(function ($user) use ($faker, $timezones) {
            $firstName = $faker->firstName;
            $lastName = $faker->lastName;
            $name = $firstName." ".$lastName;
            $user->update([
                'name' => $name,
                'timezone' => $timezones[array_rand($timezones)],
            ]);
        });

        $this->info('User information updated successfully.');

        return Command::SUCCESS;
    }
}
