<?php

namespace App\Jobs;

use App\Models\DigitalProduct;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class ProcessProjectUpload implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $productId;
    public $tempPath;

    /**
     * Create a new job instance.
     */
    public function __construct($productId, $tempPath)
    {
        $this->productId = $productId;
        $this->tempPath = $tempPath;
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        try {
            if (!Storage::disk('local')->exists($this->tempPath)) {
                Log::error("Job failed: Temp file missing at {$this->tempPath}");
                return;
            }

            $fileName = basename($this->tempPath);
            $finalPath = 'digital_products/projects/' . $this->productId . '/' . $fileName;

            Storage::disk('local')->move($this->tempPath, $finalPath);

            $product = DigitalProduct::find($this->productId);
            if ($product) {
                $product->update(['project' => $finalPath, 'status' => 1]);
            }

        } catch (\Exception $e) {
            Log::error('Project Upload Job Error: ' . $e->getMessage());
        }
    }
}
