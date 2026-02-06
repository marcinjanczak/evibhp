<?php

namespace App\Livewire\ProductsComponents;

use Livewire\Component;
use Livewire\WithFileUploads; // Niezbędne do plików!
use Livewire\Attributes\Validate;
use Livewire\Attributes\On;
use App\Services\ProductService;

class ProductForm extends Component
{
    use WithFileUploads;

    #[Validate('required|string|max:255')]
    public $name = '';

    #[Validate('required|string|max:100')]
    public $type = '';

    #[Validate('nullable|image|max:5120')] 
    public $preview_image;

    public function save(ProductService $productService)
    {
        $this->validate();

        $data = [
            'name' => $this->name,
            'type' => $this->type,
            'preview_image' => $this->preview_image,
        ];

        try {
            $product = $productService->createProduct($data);
            session()->flash('success', 'Produkt zdefiniowany pomyślnie! Teraz możesz dodać pierwszą partię.');

            return $this->redirectRoute('items.show', ['item' => $product->id], navigate: true);

        } catch (\Exception $e) {
            $this->addError('base', 'Wystąpił błąd podczas zapisu: ' . $e->getMessage());
        }
    }

    #[On('reset-product-form')] 
    public function resetForm()
    {
        $this->reset(); 
        $this->resetValidation(); 
    }

    public function render()
    {
        return view('livewire.products-components.product-form');
    }
}