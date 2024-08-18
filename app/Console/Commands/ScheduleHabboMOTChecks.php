<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\UserHabboLink;
use App\Jobs\CheckHabboMOT;
use Illuminate\Support\Facades\Log;

class ScheduleHabboMOTChecks extends Command
{
    protected $signature = 'schedule:habbo-mot-checks';

    protected $description = 'Schedule MOT checks for all pending UserHabboLink records';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $pendingLinks = UserHabboLink::where('habbo_origin_status', 'Pending')->get();

        // Log::info('Found ' . $pendingLinks->count() . ' pending links');

        foreach ($pendingLinks as $link) {
            Log::info('Executing job for link: ' . $link->id);
            (new CheckHabboMOT($link))->handle();
        }

        $this->info('Completed MOT checks for all pending links.');
    }
}