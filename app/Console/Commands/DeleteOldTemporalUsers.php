<?php

namespace App\Console\Commands;

use App\Models\TemporalUser;
use Illuminate\Console\Command;

class DeleteOldTemporalUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'temporal-user:delete-old';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $cutoffDate = now()->subDays(14);

        $usersToDelete = TemporalUser::where('created_at', '<', $cutoffDate)
        ->doesntHave('threads')
        ->doesntHave('comments')
        ->get();

        $deletedCount = $usersToDelete->count();
        $usersToDelete->each->delete();

        $this->info("Deleted $deletedCount temporal_user(s) older than 14 days and without threads or comments.");
    }
}
