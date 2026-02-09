<?php

namespace App\Http\Controllers;

use App\Models\Position;
use Illuminate\View\View;

class PositionController extends Controller
{

    public function index(): View
    {
        return view('positions.index');
    }

    public function show(Position $position): View
    {
        $position->load(['employees', 'products.batches']); 

        return view('positions.show', compact('position'));
    }
}