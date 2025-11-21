<?php

namespace App\Livewire\SuperAdmin;

use Livewire\Component;
use App\Models\Product;
use Livewire\WithPagination;
use Livewire\Attributes\On; 

class ProductTable extends Component
{
    use WithPagination;
    
    public $product_name;
    public $product_description;
    public $product_price;

    public $selectedProduct;

    protected $rules = [
        'product_name' => 'required|string|max:255',
        'product_price' => 'required|numeric|min:0',
        'product_description' => 'nullable|string|max:500',
    ];

    protected $messages = [
        'product_name.required' => 'Nama produk tidak boleh kosong.',
        'product_price.required' => 'Harga produk tidak boleh kosong.',
        'product_price.numeric' => 'Harga produk harus berupa angka.',
    ];

    #[On('product-updated')]
    public function refreshTable()
    {
        $this->resetPage();
    }


    public function viewDeleteProduct($productId)
    {
        $this->selectedProduct = Product::findOrFail($productId);

        $this->dispatch('open-modal', name: 'delete-product-modal');
    }

    public function viewEditProduct($productId)
    {
        $this->selectedProduct = Product::findOrFail($productId);

        $this->product_name = $this->selectedProduct->name;
        $this->product_price = $this->selectedProduct->price;
        $this->product_description = $this->selectedProduct->description;

        $this->dispatch('open-modal', name: 'edit-product-modal');
    }

    public function getProducts()
    {
        return Product::orderBy('created_at', 'desc')
            ->paginate(5);
    }

    public function updateProduct()
    {
        $this->validate();

        if (!$this->selectedProduct) {
            session()->flash('message', 'Tidak ada produk yang dipilih.');
            return;
        }

        $product = Product::find($this->selectedProduct->id);

        $product->update([
            'name' => $this->product_name,
            'price' => $this->product_price,
            'description' => $this->product_description,
        ]);

        $this->dispatch('flash-message', [
            'message' => 'Data produk berhasil diperbarui.',
            'type' => 'update'
        ]);

        $this->reset(['selectedProduct', 'product_name', 'product_price', 'product_description']);

        $this->dispatch('close-modal', name: 'edit-product-modal');

        $this->dispatch('product-updated');
    }

    public function deleteProduct()
    {
        $product = Product::findOrFail($this->selectedProduct->id);
        $product->delete();

        $this->dispatch('flash-message', [
            'message' => 'Data produk berhasil dihapus.',
            'type' => 'delete'
        ]);

        $this->reset('selectedProduct');

        $this->dispatch('product-updated');
        $this->dispatch('close-modal');
    }
    
    public function render()
    {
        $products = $this->getProducts();

        return view('livewire.super-admin.product-table', [
            'products' => $products,
        ]);
    }
}
