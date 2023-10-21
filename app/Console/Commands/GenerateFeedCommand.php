<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use FME\EloquenceCsvFeed\Handlers\ProductHandler;

class GenerateFeedCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fme:feed {model}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate csv feed for a {model}';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        set_time_limit(0);
        ini_set('memory_limit', '-1');
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle(): void
    {
        $model = $this->argument('model');

        switch ($model) {
            default:
                (new ProductHandler($model))->storeCsv();
                break;
        }
    }
}
