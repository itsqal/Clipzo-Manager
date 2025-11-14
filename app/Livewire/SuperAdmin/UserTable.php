<?php

namespace App\Livewire\SuperAdmin;

use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use App\Models\User;

class UserTable extends Component
{
    public $selectedUser; 
    public $username;
    public $branch_location;
    public $address;
    public $password;

    protected $listeners = ['userUpdated' => '$refresh'];

    protected $rules = [
        'username' => 'required|string|max:255',
        'password' => 'nullable|string|min:6',
        'branch_location' => 'nullable|string|max:255',
        'address' => 'nullable|string|max:500',
    ];

    protected $messages = [
        'username.required' => 'Username tidak boleh kosong.',
        'password.min' => 'Password akun minimal 6 karakter',
        'branch_location.required' => 'Lokasi cabang admin tidak boleh kosong.'
    ];

    public function viewOnDeleteUser($userId)
    {
        $this->selectedUser = User::findOrFail($userId);
        $this->dispatch('open-modal', name: 'delete-user-modal');
    }

    public function viewOnEditUser($userId)
    {
        $this->selectedUser = User::findOrFail($userId);

        $this->username = $this->selectedUser->username;
        $this->branch_location = $this->selectedUser->branch_location;
        $this->address = $this->selectedUser->address;
        $this->password = null;

        $this->dispatch('open-modal', name: 'edit-user-modal');
    }

    public function deleteUser()
    {
        $user = User::findOrFail($this->selectedUser->id);
        $user->delete();

        $this->reset('selectedUser');

        $this->dispatch('flash-message', [
            'message' => 'Akun admin berhasil dihapus.',
            'type' => 'delete'
        ]);

        $this->dispatch('userUpdated');
        $this->dispatch('close-modal');
    }

    public function updateUser()
    {
        $this->validate();

        if (!$this->selectedUser) return;

        $data = [
            'username' => $this->username,
            'branch_location' => $this->branch_location,
            'address' => $this->address,
        ];

        if ($this->password) {
            $data['password'] = Hash::make($this->password);
        }

        $this->selectedUser->update($data);

        $this->dispatch('flash-message', [
            'message' => 'Data akun admin berhasil diperbarui.',
            'type' => 'update'
        ]);

        $this->reset(['selectedUser', 'username', 'password', 'branch_location', 'address']);

        $this->dispatch('userUpdated');
        $this->dispatch('close-modal', name: 'edit-user-modal');
    }

    public function getUsers()
    {
        return User::where('role', 2)
            ->orderBy('created_at', 'desc')
            ->paginate(5);
    }

    public function render()
    {
        $users = $this->getUsers();
        return view('livewire.super-admin.user-table', [
            'users' => $users,
        ]);
    }
}