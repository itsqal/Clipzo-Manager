<?php

namespace App\Livewire\SuperAdmin;

use Livewire\Component;
use App\Models\Product;

class ProductCreateForm extends Component
{
    public $name;
    public $description;
    public $price;

    protected $rules = [
        'name' => 'required|string|max:255',
        'description' => 'nullable|string|max:500',
        'price' => 'required|numeric|min:0',
    ];

    protected $messages = [
        'name.required' => 'Nama produk wajib diisi.',
        'price.required' => 'Harga produk wajib diisi.',
        'price.numeric' => 'Harga harus berupa angka.',
    ];

    public function save()
    {
        $this->validate();

        Product::create([
            'name' => $this->name,
            'description' => $this->description,
            'price' => $this->price,
        ]);

        $this->dispatch('flash-message', [
            'message' => 'Data produk berhasil ditambahkan.',
            'type' => 'create'
        ]);

        $this->reset(['name', 'description', 'price']);

        $this->dispatch('product-updated'); 

    }

    public function resetForm()
    {
        $this->reset(['name', 'description', 'price']);
    }

    public function render()
    {
        return view('livewire.super-admin.product-create-form');
    }
}
