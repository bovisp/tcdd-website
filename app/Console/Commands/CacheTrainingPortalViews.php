<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Classes\Reports\TrainingPortal;
use App\Classes\Reports\Fiscal\TrainingPortalFiscalYears;

class CacheTrainingPortalViews extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cache:tp-views';

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
     * @return int
     */
    public function handle()
    {
        cache()->forget('tp-fiscalyears');
        cache()->forget('metric-type');
        cache()->forget('tp-views');

        cache()->forever('tp-fiscalyears', (new TrainingPortalFiscalYears)->get());
        cache()->forever('metric-type', 'training_portal');
        cache()->forever('tp-views', (new TrainingPortal)->generate());

        // $this->info(json_encode((new TrainingPortalFiscalYears)->get()));
    }
}
