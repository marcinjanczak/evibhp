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
    public $isNewType = false;
    
    public $preview_image;

    public function updatedType($value)
    {
        if ($value === 'NEW') {
            $this->isNewType = true;
            $this->type = '';
        }
    }

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

    public function removeImage(ProductService $service)
    {
        if ($this->product && $this->product->preview_image_path) {
            $service->removeImage($this->product);
            $this->product->preview_image_path = null;
        }
        $this->preview_image = null;
        session()->flash('success', 'Zdjęcie usunięte.');
    }

    public function render()
    {
        $existingTypes = Product::select('type')->distinct()->orderBy('type')->pluck('type');
        
        return view('livewire.products-components.product-form', [
            'existingTypes' => $existingTypes
        ]);
    }
}