<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProductService
{
    public function createProductWithBatch(array $data): Product
    {
        return DB::transaction(function () use ($data) {
            $imagePath = isset($data['preview_image']) 
                ? $data['preview_image']->store('previews', 'public') 
                : null;

            $product = Product::create([
                'name'               => $data['name'],
                'type'               => $data['type'],
                'size'               => $data['size'],
                'preview_image_path' => $imagePath,
            ]);

            $this->addBatch($product, $data);

            return $product;
        });
    }

    public function addBatch(Product $product, array $data)
    {
        $invoicePath = isset($data['invoice_pdf']) 
            ? $data['invoice_pdf']->store('invoices', 'public') 
            : null;

        return $product->batches()->create([
            'batch_number'     => $data['batch_number'] ?? null,
            'initial_quantity' => $data['quantity'],
            'current_quantity' => $data['quantity'],
            'expiration_date'  => $data['expiration_date'],
            'invoice_pdf_path' => $invoicePath,
        ]);
    }

    public function updateProduct(Product $product, array $data): bool
    {
        if (isset($data['preview_image'])) {
            if ($product->preview_image_path) {
                Storage::disk('public')->delete($product->preview_image_path);
            }
            $data['preview_image_path'] = $data['preview_image']->store('previews', 'public');
        }

        return $product->update($data);
    }

    public function deleteProduct(Product $product): ?bool
    {
        return DB::transaction(function () use ($product) {
            if ($product->preview_image_path) {
                Storage::disk('public')->delete($product->preview_image_path);
            }

            foreach ($product->batches as $batch) {
                if ($batch->invoice_pdf_path) {
                    Storage::disk('public')->delete($batch->invoice_pdf_path);
                }
            }

            return $product->delete();
        });
    }

    public function getPaginatedList(string $search = '', int $perPage = 10)
    {
        return Product::query()
            ->with('batches') 
            ->when($search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%")
                      ->orWhere('type', 'like', "%{$search}%")
                      ->orWhere('size', 'like', "%{$search}%");
            })
            ->orderBy('name', 'asc')
            ->paginate($perPage);
    }
}