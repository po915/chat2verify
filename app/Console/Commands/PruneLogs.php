<?php

namespace App\Console\Commands;

use App\Models\MessageLog;
use Carbon\Carbon;
use Illuminate\Console\Command;

class PruneLogs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'logs:prune';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Prune the incoming message logs';

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
     * @return int
     */
    public function handle()
    {
        MessageLog::query()
            ->whereDate( 'created_at', '<=', Carbon::now()->subDays(1) )
            ->delete();

        return 0;
    }
}
