<?php

namespace App\Livewire;

use App\Models\User;
use App\Models\Service;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserManager extends Component
{
    use WithPagination;

    public $name = '';
    public $email = '';
    public $password = '';
    public $role = 'petugas';
    public $selectedServices = [];
    public $userId = null;
    public $search = '';
    public $showModal = false;
    public $isEditMode = false;

    protected $rules = [
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|string|min:8',
        'role' => 'required|in:superadmin,petugas',
        'selectedServices' => 'array',
    ];

    protected $rulesUpdate = [
        'name' => 'required|string|max:255',
        'email' => 'required|email',
        'role' => 'required|in:superadmin,petugas',
        'selectedServices' => 'array',
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function openModal()
    {
        $this->resetInputFields();
        $this->isEditMode = false;
        $this->showModal = true;
    }

    public function edit($userId)
    {
        $this->resetValidation();
        $user = User::findOrFail($userId);
        $this->userId = $userId;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->role = $user->role;
        $this->selectedServices = $user->services->pluck('id')->toArray();
        $this->password = ''; // Password tidak ditampilkan untuk update
        $this->isEditMode = true;
        $this->showModal = true;
    }

    public function resetInputFields()
    {
        $this->reset(['name', 'email', 'password', 'role', 'selectedServices', 'userId']);
        $this->role = 'petugas';
        $this->selectedServices = [];
        $this->isEditMode = false;
        $this->resetValidation();
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->reset(['name', 'email', 'password', 'role', 'selectedServices', 'userId']);
    }

    public function save()
    {
        if ($this->userId) {
            $this->updateUser();
        } else {
            $this->createUser();
        }
    }

    public function createUser()
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:8',
            'role' => 'required|in:superadmin,petugas',
        ];

        if ($this->role === 'petugas') {
            $rules['selectedServices'] = 'required|array|min:1';
            $rules['selectedServices.*'] = 'exists:services,id';
        }

        $this->validate($rules);

        $user = User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => Hash::make($this->password),
            'role' => $this->role,
        ]);

        if ($this->role === 'petugas') {
            $user->services()->sync($this->selectedServices);
        }

        session()->flash('message', 'User berhasil ditambahkan!');
        $this->closeModal();
    }

    public function updateUser()
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $this->userId,
            'role' => 'required|in:superadmin,petugas',
        ];

        if ($this->role === 'petugas') {
            $rules['selectedServices'] = 'required|array|min:1';
            $rules['selectedServices.*'] = 'exists:services,id';
        }

        $this->validate($rules);

        $user = User::findOrFail($this->userId);
        $user->update([
            'name' => $this->name,
            'email' => $this->email,
            'role' => $this->role,
        ]);

        if ($this->role === 'petugas') {
            $user->services()->sync($this->selectedServices);
        } else {
            $user->services()->detach();
        }

        session()->flash('message', 'User berhasil diperbarui!');
        $this->closeModal();
    }

    public function deleteUser($userId)
    {
        $user = User::findOrFail($userId);
        
        // Cegah menghapus diri sendiri
        if ($user->id === auth()->id()) {
            session()->flash('error', 'Tidak dapat menghapus akun sendiri!');
            return;
        }

        $user->delete();
        session()->flash('message', 'User berhasil dihapus!');
    }

    public function render()
    {
        $users = User::query()
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('email', 'like', '%' . $this->search . '%');
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $services = Service::all();

        return view('livewire.user-manager', [
            'users' => $users,
            'services' => $services,
        ])->layout('components.layouts.app');
    }
}
