<?php

namespace App\Livewire\ProductsComponents;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Product;

class ProductIssuesList extends Component
{
    use WithPagination;

    public Product $product;
    public $searchEmployee = '';
    public $statusFilter = ''; // 'active', 'returned', or empty for all

    protected $paginationTheme = 'bootstrap';

    public function updatingSearchEmployee()
    {
        $this->resetPage();
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = $this->product->issues()->with(['employee', 'batch']);

        if (!empty($this->searchEmployee)) {
            $query->whereHas('employee', function($q) {
                $q->where('first_name', 'like', '%' . $this->searchEmployee . '%')
                  ->orWhere('last_name', 'like', '%' . $this->searchEmployee . '%');
            });
        }

        if ($this->statusFilter === 'active') {
            $query->whereNull('returned_at');
        } elseif ($this->statusFilter === 'returned') {
            $query->whereNotNull('returned_at');
        }

        return view('livewire.products-components.product-issues-list', [
            'issues' => $query->orderByDesc('issued_at')->paginate(10)
        ]);
    }
}
