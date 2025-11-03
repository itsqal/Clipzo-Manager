<?php

namespace App\Livewire\Admin;

use Livewire\Attributes\On;
use Livewire\Component;
use App\Models\Transaction;

class TransactionSummary extends Component
{
    public array $selectedServices = [];
    public array $selectedProducts = []; 
    public bool $mode = false;
    public float $total = 0.0;
    public ?string $paymentMethod = null;

    #[On('modeToggled')]
    public function updateMode(bool $mode)
    {
        $this->mode = $mode;
        if (!$mode) {
            $this->selectedServices = [];
            $this->selectedProducts = [];
            $this->paymentMethod = null;
            $this->calculateTotal();
        }
    }

    #[On('serviceSelectionUpdated')]
    public function updateSelectedServices(array $services)
    {
        $this->selectedServices = $services;
        $this->calculateTotal();
    }

    #[On('productSelectionUpdated')]
    public function updateSelectedProducts(array $products)
    {
        $this->selectedProducts = $products;
        $this->calculateTotal();
    }
    
    public function setPaymentMethod(string $method)
    {
        if ($this->total > 0) {
            $this->paymentMethod = $method;
        }
    }

    public function incrementQuantity($id, $type)
    {
        if ($type === 'service') {
            foreach ($this->selectedServices as &$service) {
                if ($service['id'] == $id) {
                    $service['quantity'] = ($service['quantity'] ?? 1) + 1;
                    break;
                }
            }
        } else {
            foreach ($this->selectedProducts as &$product) {
                if ($product['id'] == $id) {
                    $product['quantity'] = ($product['quantity'] ?? 1) + 1;
                    break;
                }
            }
        }
        $this->calculateTotal();
    }

    public function decrementQuantity($id, $type)
    {
        if ($type === 'service') {
            foreach ($this->selectedServices as $key => &$service) {
                if ($service['id'] == $id) {
                    if (($service['quantity'] ?? 1) > 1) {
                        $service['quantity']--;
                    }
                    break;
                }
            }
        } else {
            foreach ($this->selectedProducts as $key => &$product) {
                if ($product['id'] == $id) {
                    if (($product['quantity'] ?? 1) > 1) {
                        $product['quantity']--;
                    }
                    break;
                }
            }
        }
        $this->calculateTotal();
    }

    public function calculateTotal()
    {
        $serviceTotal = 0;
        foreach ($this->selectedServices as $service) {
            $serviceTotal += ($service['price'] * ($service['quantity'] ?? 1));
        }
        $productTotal = 0;
        foreach ($this->selectedProducts as $product) {
            $productTotal += ($product['price'] * ($product['quantity'] ?? 1));
        }

        $this->total = $serviceTotal + $productTotal;
        
        if ($this->total === 0.0) {
            $this->paymentMethod = null;
        }
    }
    
    public function finishTransaction()
    {
        if ($this->total > 0 && $this->paymentMethod) {

            $itemNames = collect($this->selectedServices)
                    ->merge($this->selectedProducts)
                    ->pluck('name')
                    ->join(", ");

            // Create the transaction
            $transaction = Transaction::create([
                'user_id' => auth()->id(),
                'payment_method' => $this->paymentMethod,
                'item' => $itemNames,
                'branch_location' => auth()->user()?->branch_location,
                'transaction_type' => 
                    (count($this->selectedServices) > 0 && count($this->selectedProducts) > 0) ? 'both' :
                    (count($this->selectedServices) > 0 ? 'service' : 'product'),
                'total_amount' => $this->total,
            ]);

            // // Save each service item
            foreach ($this->selectedServices as $service) {
                $transaction->items()->create([
                    'service_id' => $service['id'],
                    'item_type' => 'service',
                    'name' => $service['name'],
                    'price' => $service['price'],
                    'quantity' => $service['quantity'] ?? 1,
                    'subtotal' => ($service['price'] * ($service['quantity'] ?? 1)),
                ]);
            }

            // // Save each product item
            foreach ($this->selectedProducts as $product) {
                $transaction->items()->create([
                    'product_id' => $product['id'],
                    'item_type' => 'product',
                    'name' => $product['name'],
                    'price' => $product['price'],
                    'quantity' => $product['quantity'] ?? 1,
                    'subtotal' => ($product['price'] * ($product['quantity'] ?? 1)),
                ]);
            }

            $this->dispatch('print-transaction', [
                'items' => collect($this->selectedServices)
                    ->merge($this->selectedProducts)
                    ->map(fn($i) => [
                        'name' => $i['name'],
                        'price' => $i['price'],
                        'quantity' => $i['quantity'] ?? 1,
                        'subtotal' => $i['price'] * ($i['quantity'] ?? 1),
                    ])
                    ->values(),
                'total' => $this->total,
                'payment_method' => $this->paymentMethod,
                'branch' => auth()->user()?->branch_location,
                'time' => now()->format('d/m/Y H:i'),
            ]);

            $this->dispatch('flash-message', [
                'message' => 'Transaksi berhasil disimpan!',
                'type' => 'create'
            ]);

            $this->selectedServices = [];
            $this->selectedProducts = [];
            $this->paymentMethod = null;
            $this->total = 0.0;

            $this->dispatch('transactionFinished'); 
        }
    }

    public function render()
    {
        return view('livewire.admin.transaction-summary');
    }
}