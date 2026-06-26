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
                $this->resizeImage($data['preview_image']);
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
        if (array_key_exists('preview_image', $data)) {
            if ($product->preview_image_path && $data['preview_image'] instanceof UploadedFile) {
                Storage::disk('public')->delete($product->preview_image_path);
            }
            if ($data['preview_image'] instanceof UploadedFile) {
                $this->resizeImage($data['preview_image']);
                $data['preview_image_path'] = $data['preview_image']->store('previews', 'public');
            }
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

    public function getPaginatedList(string $search = '', int $perPage = 10, string $category = '')
    {
        return Product::query()
            ->with('batches') 
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('type', 'like', "%{$search}%");
                });
            })
            ->when($category, function ($query, $category) {
                $query->where('type', $category);
            })
            ->orderBy('name', 'asc')
            ->paginate($perPage);
    }

    public function removeImage(Product $product): bool
    {
        if ($product->preview_image_path) {
            Storage::disk('public')->delete($product->preview_image_path);
            return $product->update(['preview_image_path' => null]);
        }
        return false;
    }

    private function resizeImage(UploadedFile $file, $maxWidth = 800, $maxHeight = 800)
    {
        if (!function_exists('imagecreatefromjpeg')) {
            return;
        }

        $mime = $file->getMimeType();
        $sourcePath = $file->getRealPath();

        switch ($mime) {
            case 'image/jpeg':
                $image = @imagecreatefromjpeg($sourcePath);
                break;
            case 'image/png':
                $image = @imagecreatefrompng($sourcePath);
                break;
            case 'image/gif':
                $image = @imagecreatefromgif($sourcePath);
                break;
            case 'image/webp':
                $image = @imagecreatefromwebp($sourcePath);
                break;
            default:
                return;
        }

        if (!$image) return;

        $width = imagesx($image);
        $height = imagesy($image);

        if ($width <= $maxWidth && $height <= $maxHeight) {
            imagedestroy($image);
            return;
        }

        $ratio = min($maxWidth / $width, $maxHeight / $height);
        $newWidth = (int)($width * $ratio);
        $newHeight = (int)($height * $ratio);

        $newImage = imagecreatetruecolor($newWidth, $newHeight);

        if ($mime == 'image/png' || $mime == 'image/gif' || $mime == 'image/webp') {
            imagecolortransparent($newImage, imagecolorallocatealpha($newImage, 0, 0, 0, 127));
            imagealphablending($newImage, false);
            imagesavealpha($newImage, true);
        }

        imagecopyresampled($newImage, $image, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);

        switch ($mime) {
            case 'image/jpeg':
                imagejpeg($newImage, $sourcePath, 85);
                break;
            case 'image/png':
                imagepng($newImage, $sourcePath, 8);
                break;
            case 'image/gif':
                imagegif($newImage, $sourcePath);
                break;
            case 'image/webp':
                imagewebp($newImage, $sourcePath, 85);
                break;
        }

        imagedestroy($image);
        imagedestroy($newImage);
    }
}