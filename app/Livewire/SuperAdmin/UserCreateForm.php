<?php

namespace App\Livewire\SuperAdmin;

use Livewire\Component;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserCreateForm extends Component
{
    public $username;
    public $password;
    public $password_confirmation;
    public $branch_location;
    public $address;

    protected $rules = [
        'username' => 'required|string|min:4|unique:users,username',
        'password' => 'required|string|min:6|confirmed',
        'branch_location' => 'required|string|max:255',
        'address' => 'nullable|string|max:500',
    ];

    protected $messages = [
        'username.required' => 'Username tidak boleh kosong.',
        'password.confirmed' => 'Password konfirmasi tidak cocok.',
        'password.min' => 'Password akun minimal 6 karakter',
        'branch_location.required' => 'Lokasi cabang admin tidak boleh kosong.'
    ];

    public function create()
    {
        $this->validate();

        User::create([
            'username' => $this->username,
            'password' => Hash::make($this->password),
            'branch_location' => $this->branch_location,
            'address' => $this->address,
        ]);

        $this->dispatch('flash-message', [
            'message' => 'Data akun admin berhasil ditambahkan.',
            'type' => 'create'
        ]);

        $this->resetForm();
        $this->dispatch('userUpdated');
    }

    public function resetForm()
    {
        $this->reset(['username', 'password', 'password_confirmation', 'branch_location', 'address']);
    }

    public function render()
    {
        return view('livewire.super-admin.user-create-form');
    }
}