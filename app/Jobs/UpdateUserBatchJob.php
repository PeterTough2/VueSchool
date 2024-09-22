<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Log;

class UpdateUserBatchJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $batch;

    public function __construct($batch)
    {
        $this->batch = $batch;
    }

    public function handle()
    {
        $rateLimiter = new RateLimiter();

        if (RateLimiter::tooManyAttempts($this->getKey(), 5, 60 * 60)) {
            // Rate limit exceeded
            return $this->release(60); // Retry in 1 minute
        }

        $payload = [
            'batches' => [
                [
                    'subscribers' => $this->batch->map(function ($user) {
                        return [
                            'email' => $user->email,
                            'timezone' => $user->timezone,
                            'name' => $user->name
                        ];
                    })->toArray()
                ]
            ]
        ];

        foreach ($this->batch as $user) {
            Log::info("[{$user->id}] firstname: {$user->name}, timezone: '{$user->timezone}'");
        }

        /*
        $response = Http::post('https://api.example.com/update', $payload);

        if ($response->successful()) {
            // Update user records
        } else {
            // Handle errors
        }
        */
    }

    public function getKey()
    {
        return 'user_batch_update_' . now()->hour;
    }
}