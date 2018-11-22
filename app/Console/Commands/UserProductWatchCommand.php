<?php

namespace App\Console\Commands;

use App\Jobs\ProductAnalyticJob;
use App\UserProductWatch;
use Illuminate\Console\Command;

class UserProductWatchCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user-watch-products';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $watchers = UserProductWatch::where('status', '=', 1)
            ->get();

        foreach ($watchers as $watcher) {
            dispatch(
                new ProductAnalyticJob(
                    $watcher
                )
            );
        }
    }
}
