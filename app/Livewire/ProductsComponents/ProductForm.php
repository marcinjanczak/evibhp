<?php

namespace App\Livewire\ProductsComponents;

use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Validate;
use Livewire\Attributes\On;
use App\Models\Product;
use App\Services\ProductService;
use Illuminate\Validation\Rule; 

class ProductForm extends Component
{
    use WithFileUploads;

    public ?Product $product = null;

    public $name = '';
    public $type = '';
    
    public $preview_image;

    protected function rules()
    {
        return [
            'name' => [
                'required', 'string', 'max:255',
                Rule::unique('products', 'name')->ignore($this->product?->id),
            ],
            'type' => 'required|string|max:100',
            'preview_image' => 'nullable|image|max:5120',
        ];
    }

    #[On('edit-product')]
    public function setProduct(Product $product)
    {
        $this->product = $product;
        $this->name = $product->name;
        $this->type = $product->type;
        $this->reset('preview_image'); 
        $this->resetValidation();
    }

    #[On('reset-product-form')] 
    public function resetForm()
    {
        $this->reset(); 
        $this->resetValidation();
    }

    public function save(ProductService $service)
    {
        $validated = $this->validate();

        try {
            if ($this->product) {
                $service->updateProduct($this->product, [
                    'name' => $this->name,
                    'type' => $this->type,
                    'preview_image' => $this->preview_image,
                ]);
                $message = 'Produkt zaktualizowany!';
            } else {
                $service->createProduct([
                    'name' => $this->name,
                    'type' => $this->type,
                    'preview_image' => $this->preview_image,
                ]);
                $message = 'Produkt dodany!';
            }

            session()->flash('success', $message);
            
            return $this->redirectRoute('items.index', navigate: true);

        } catch (\Exception $e) {
            $this->addError('base', 'Wystąpił błąd: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.products-components.product-form');
    }
}