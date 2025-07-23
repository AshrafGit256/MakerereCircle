<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ScheduleLoop extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'schedule:loop';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Runs the command schedule:run every minute';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info("🚀 Laravel Schedule Loop Started");

        while (true) {
            $this->call('schedule:run');
            sleep(60); // wait 60 seconds
        }
    }
}
