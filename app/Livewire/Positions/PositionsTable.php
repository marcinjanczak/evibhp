<?php

namespace App\Livewire\Positions;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Position;

class PositionsTable extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $search = '';

    public function render()
    {
        $positions = Position::query()
            ->withCount(['employees', 'products']) 
            ->when($this->search, fn($q) => $q->where('name', 'like', "%{$this->search}%"))
            ->orderBy('name')
            ->paginate(10);

        return view('livewire.positions.positions-table', [
            'positions' => $positions
        ]);
    }
}