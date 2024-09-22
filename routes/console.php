<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Models\User;
use App\Jobs\UpdateUserBatchJob;
use Illuminate\Foundation\Bus\Dispatchable;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');


class Kernel extends ConsoleKernel
{
    use Dispatchable;
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        $schedule->call(function () {
            $usersToUpdate = User::where('has_changes', true)->get();
            $batches = $usersToUpdate->chunk(1000);
            foreach ($batches as $batch) {
                $this->dispatch(new UpdateUserBatchJob($batch));
            }
        })->everyTenMinutes();
    }
}