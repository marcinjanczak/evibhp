<?php

namespace App\Services;

use App\Models\Product;
use App\Models\Batch;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\UploadedFile;

class BatchService
{
    public function addBatchToProduct(Product $product, array $data): Batch
    {
        return DB::transaction(function () use ($product, $data) {
            $invoicePath = null;
            if (isset($data['invoice_pdf']) && $data['invoice_pdf'] instanceof UploadedFile) {
                $invoicePath = $data['invoice_pdf']->store('invoices', 'public');
            }

            return $product->batches()->create([
                'batch_number'     => $data['batch_number'] ?? null,
                'size'             => $data['size'], // Teraz rozmiar jest tutaj!
                'initial_quantity' => $data['quantity'],
                'current_quantity' => $data['quantity'],
                'expiration_date'  => $data['expiration_date'],
                'invoice_pdf_path' => $invoicePath,
            ]);
        });
    }
}