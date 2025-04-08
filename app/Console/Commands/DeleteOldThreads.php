<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Thread;
use Carbon\Carbon;

class DeleteOldThreads extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'threads:delete-old';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete threads older than 7 days';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $cutoffDate = now()->subDays(7);

        $deleted = Thread::where('created_at', '<', $cutoffDate)->delete();

        $this->info("Deleted $deleted thread(s) older than 7 days.");
    }

}
