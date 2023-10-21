<?php

namespace App\Console\Commands;

use App\Product;
use App\UserFile;
use Illuminate\Support\Arr;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use App\Repositories\ProductRepository;
use Illuminate\Support\Facades\Artisan;

class UpdateProductsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fme:update-products {filename}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update products from "{file.csv}" should bet on /app/public/csv/';

    /**
     * Product repository
     *
     * @var ProductRepository
     */
    protected $productRepository;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(ProductRepository $productRepository)
    {
        parent::__construct();
        $this->productRepository = $productRepository;
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle(): void
    {
        set_time_limit(0);
        ini_set('memory_limit', '-1');
        
        $filename = $this->argument('filename');

        $csvArray = readCsvFile($filename);
        $totalRows = count($csvArray);
        $notFound = 0;
        $found = [];

        $data = [
            'name' => $filename,
            'current_row' => 0,
            'total_rows' => $totalRows,
            'user_id' => auth()->check() ? auth()->user()->id : null,
            'file_type' => 'boh',
            'file_errors' => '[]'
        ];

        try {
            $userFile = UserFile::where($data)->orderBy('id', 'desc')->firstOrFail();
        } catch (\Exception $exception) {
            $userFile = UserFile::create($data);
        }

        $userFile->update([
            'processed' => 2
        ]);

        $progressBar = $this->output->createProgressBar($totalRows);
        $progressBar->start();

        foreach ($csvArray as $index => $row) {
            if ($index === 0) {
                continue;
            }

            try {
                $found[] = $row[11];
                $product = Product::where('vendor_code', $row[11])->firstOrFail();
                $this->productRepository->updateProductFromBoh($product, $row);
                $userFile->increment('current_row');
            } catch (\Exception $exception) {
                $notFound++;
                logger($exception->getMessage());
            }

            $progressBar->advance();
        }

        $updated = Product::whereNotIn('vendor_code', Arr::wrap($found))->update([
            'quantity' => 0
        ]);

        $progressBar->finish();

        Artisan::call('fme:clear-cache');

        $this->info(PHP_EOL);
        $this->info('total not found: '. $notFound);
        $this->info('total products updated: '. $updated);

        $userFile->update(['processed' => true]);
    }
}
