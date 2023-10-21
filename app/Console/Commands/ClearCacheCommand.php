<?php

namespace App\Console\Commands;

use App\Product;
use App\Category;
use Illuminate\Console\Command;

class ClearCacheCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fme:clear-cache';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear site cache';

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
        try {
            $this->call('view:clear');
            $this->call('responsecache:clear');
            $this->call('route:cache');
            $this->call('config:cache');
            $this->info('All cache cleared !');
            Product::flushCache();
            Category::flushCache();
        } catch (\Exception $exception) {
            $this->info($exception->getMessage());
        }
    }
}
