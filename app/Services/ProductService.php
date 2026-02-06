<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class ProductService
{
    public function createProduct(array $data): Product
    {
        return DB::transaction(function () use ($data) {
            $imagePath = null;
            if (isset($data['preview_image']) && $data['preview_image'] instanceof UploadedFile) {
                $imagePath = $data['preview_image']->store('previews', 'public');
            }

            return Product::create([
                'name'               => $data['name'],
                'type'               => $data['type'],
                'preview_image_path' => $imagePath,
            ]);
        });
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
                      ->orWhere('type', 'like', "%{$search}%");
            })
            ->orderBy('name', 'asc')
            ->paginate($perPage);
    }
}