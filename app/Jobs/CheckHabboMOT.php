<?php

namespace App\Jobs;

use App\Models\UserHabboLink;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CheckHabboMOT
{
    use SerializesModels;

    public $habboLink;

    public function __construct(UserHabboLink $habboLink = null)
    {
        $this->habboLink = $habboLink;
    }

    public function handle()
    {
        if (!$this->habboLink) {
            Log::error('UserHabboLink instance not found for job.');
            return;
        }

        // Check if 15 minutes have passed since the link was created (first verification attempt)
        if (Carbon::parse($this->habboLink->created_at)->addMinutes(15)->isPast()) {
            Log::info('Link ' . $this->habboLink->id . ' failed verification after 15 minutes');
            $this->habboLink->habbo_origin_status = 'Failed';
            $this->habboLink->save();
            return;
        }

        try {
            // Call the API to check MOT status
            $response = Http::get("https://origins.habbo.com/api/public/users", ['name' => $this->habboLink->habbo_origin_name]);

            if ($response->successful()) {
                $data = $response->json();
                if ($data['motto'] === $this->habboLink->habbo_verification_code) {
                    // Verification successful
                    $this->habboLink->habbo_origin_status = 'Verified';
                    $this->habboLink->verification_attempts = 0;
                    $this->habboLink->save();
                    Log::info('Link ' . $this->habboLink->id . ' verified successfully');
                } else {
                    // Verification failed, increment attempts count
                    $this->habboLink->verification_attempts++;

                    if ($this->habboLink->verification_attempts >= 15) {
                        // Flag as failed after 15 attempts
                        $this->habboLink->habbo_origin_status = 'Failed';
                        Log::info('Link ' . $this->habboLink->id . ' failed verification after 15 attempts');
                    } else {
                        Log::info('Verification failed for link ' . $this->habboLink->id . ', attempt ' . $this->habboLink->verification_attempts);
                    }

                    $this->habboLink->save();
                }
            } else {
                // API request failed
                Log::error('API request failed with status code: ' . $response->status());
            }
        } catch (\Exception $e) {
            // Handle exceptions
            Log::error('Error in CheckHabboMOT job: ' . $e->getMessage());
        }
    }
}