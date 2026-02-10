<?php

namespace App\Http\Controllers;

use App\Models\Issue;
use App\Models\Product;
use App\Services\ProductService;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class ProductController extends Controller
{
    protected ProductService $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    public function index(): View
    {
        return view('items.index');
    }

    public function show(Product $item): View
    {
        $item->load('batches');
        
        $rentals = Issue::with('employee')
            ->whereIn('batch_id', $item->batches->pluck('id'))
            ->orderBy('issued_at', 'desc')
            ->get();

        return view('items.show', compact('item', 'rentals'));
    }

    public function edit(Product $item): View
    {
        return view('items.edit', compact('item'));
    }

    public function destroy(Product $item): RedirectResponse
    {
        $this->productService->deleteProduct($item);
        
        return redirect()->route('items.index')->with('success', 'Produkt został usunięty.');
    }
}