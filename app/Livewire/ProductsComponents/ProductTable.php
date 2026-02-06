<?php

namespace App\Livewire\ProductsComponents;

use App\Services\ProductService;
use Livewire\Component;
use Livewire\WithPagination;

class ProductTable extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $search = '';

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function render(ProductService $productService)
    {
        return view('livewire.products-components.product-table', [
            'items' => $productService->getPaginatedList($this->search, 10)
        ]);
    }
}