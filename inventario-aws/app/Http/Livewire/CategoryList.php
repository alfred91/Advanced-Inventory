<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use App\Models\Category;

class CategoryList extends Component
{
    use WithPagination, WithFileUploads;

    public $search = '';
    public $isLoading = false;

    // Propiedades Crear/Editar Categorías
    public $showModal = false;
    public $isEdit = false;
    public $categoryId;
    public $name;
    public $image;
    public $currentImage;

    // Ordenación
    public $sortField = 'id';
    public $sortDirection = 'asc';

    protected $rules = [
        'name' => 'required|string|max:255',
        'image' => 'nullable|image|max:2048',
    ];

    protected $queryString = ['search', 'sortField', 'sortDirection'];

    public function updatingSearch()
    {
        $this->isLoading = true;
        $this->resetPage();
    }

    public function updatedSearch()
    {
        $this->isLoading = false;
    }

    public function reloadCategories()
    {
        $this->isLoading = true;
        $this->resetPage();
    }

    // Modal methods
    public function showCreateModal()
    {
        $this->resetInputFields();
        $this->isEdit = false;
        $this->showModal = true;
    }

    public function showEditModal($id)
    {
        $this->resetInputFields();
        $this->isEdit = true;
        $category = Category::findOrFail($id);
        $this->fillCategoryData($category);
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetInputFields();
    }

    // Category methods
    public function saveCategory()
    {
        $this->validate();

        if ($this->isEdit) {
            $category = Category::findOrFail($this->categoryId);
            $category->update($this->getCategoryData());
            session()->flash('message', 'Categoría actualizada correctamente.');
        } else {
            Category::create($this->getCategoryData());
            session()->flash('message', 'Categoría creada correctamente.');
        }

        $this->showModal = false;
        $this->resetInputFields();
        $this->reloadCategories();
    }

    public function deleteCategory($id)
    {
        $category = Category::findOrFail($id);
        $category->delete();
        session()->flash('message', 'Categoría eliminada correctamente.');
        $this->resetPage();
    }

    // Helper methods
    private function resetInputFields()
    {
        $this->name = '';
        $this->image = null;
        $this->categoryId = null;
        $this->currentImage = null;
    }

    private function fillCategoryData($category)
    {
        $this->categoryId = $category->id;
        $this->name = $category->name;
        $this->currentImage = $category->image;
    }

    private function getCategoryData()
    {
        return [
            'name' => $this->name,
            'image' => $this->image ? $this->image->store('categories', 'public') : $this->currentImage,
        ];
    }

    public function render()
    {
        $query = Category::query();

        if ($this->search) {
            $query->where('name', 'like', '%' . $this->search . '%');
        }

        $query->orderBy($this->sortField, $this->sortDirection);

        $categories = $query->paginate(10);

        return view('livewire.category-list', [
            'categories' => $categories,
        ]);
    }
}