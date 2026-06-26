<?php

namespace App\Livewire\ProductsComponents;

use App\Services\ProductService;
use App\Models\Product;
use Livewire\Component;
use Livewire\WithPagination;

class ProductTable extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $search = '';
    public $category = '';

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedCategory()
    {
        $this->resetPage();
    }

    public function render(ProductService $productService)
    {
        return view('livewire.products-components.product-table', [
            'items' => $productService->getPaginatedList($this->search, 10, $this->category),
            'categories' => Product::select('type')->distinct()->pluck('type')->filter()->values()
        ]);
    }

    public function delete($id, ProductService $productService)
    {
        $product = Product::findOrFail($id);
        
        try {
            $productService->deleteProduct($product);
            session()->flash('success', 'Przedmiot został pomyślnie usunięty.');
        } catch (\Exception $e) {
            session()->flash('error', 'Wystąpił błąd podczas usuwania przedmiotu.');
        }
    }
}