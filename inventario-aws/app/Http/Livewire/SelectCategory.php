<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Category;

class SelectCategory extends Component
{
    public function render()
    {
        $categories = Category::all();

        return view('livewire.select-category', [
            'categories' => $categories,
        ]);
    }
}
