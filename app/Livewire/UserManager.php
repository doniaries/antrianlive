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
    public $selectedService = '';
    public $userId = null;
    public $search = '';
    public $showModal = false;
    public $isEditMode = false;

    protected function rules()
    {
        $rules = [
            'name' => 'required|string|max:25',
            'email' => $this->isEditMode 
                ? 'required|email|unique:users,email,' . $this->userId 
                : 'required|email|unique:users,email',
            'role' => 'required|in:superadmin,petugas',
        ];

        // Tambahkan password rules
        if ($this->isEditMode) {
            $rules['password'] = 'nullable|string|min:8';
        } else {
            $rules['password'] = 'required|string|min:8';
        }

        // Tambahkan selectedService hanya jika role adalah petugas
        if ($this->role === 'petugas') {
            $rules['selectedService'] = 'required|exists:services,id';
        }

        return $rules;
    }

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
        $this->selectedService = $user->services->first()->id ?? '';
        $this->password = ''; // Password tidak ditampilkan untuk update
        $this->isEditMode = true;
        $this->showModal = true;
    }

    public function resetInputFields()
    {
        $this->reset(['name', 'email', 'password', 'role', 'selectedService', 'userId']);
        $this->role = 'petugas';
        $this->selectedService = '';
        $this->isEditMode = false;
        $this->resetValidation();
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->reset(['name', 'email', 'password', 'role', 'selectedService', 'userId']);
    }

    protected function messages()
    {
        return [
            'name.required' => 'Nama lengkap wajib diisi.',
            'name.string' => 'Nama lengkap harus berupa teks.',
            'name.max' => 'Nama lengkap maksimal 25 karakter.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah digunakan oleh user lain.',
            'password.required' => 'Password wajib diisi.',
            'password.string' => 'Password harus berupa teks.',
            'password.min' => 'Password minimal 8 karakter.',
            'password.nullable' => 'Kosongkan password jika tidak ingin mengubahnya.',
            'role.required' => 'Role wajib dipilih.',
            'role.in' => 'Role tidak valid.',
            'selectedService.required' => 'Layanan wajib dipilih untuk role petugas.',
            'selectedService.exists' => 'Layanan tidak valid.',
        ];
    }

    public function store()
    {
        $this->validate();

        $data = [
            'name' => $this->name,
            'email' => $this->email,
            'role' => $this->role,
        ];

        if (!$this->isEditMode || $this->password) {
            $data['password'] = Hash::make($this->password);
        }

        if ($this->isEditMode) {
            $user = User::findOrFail($this->userId);
            $user->update($data);
            
            if ($this->role === 'petugas') {
                $user->services()->sync([$this->selectedService]);
            } else {
                $user->services()->detach();
            }
            
            session()->flash('message', 'User berhasil diperbarui!');
        } else {
            $user = User::create($data);
            
            if ($this->role === 'petugas') {
                $user->services()->sync([$this->selectedService]);
            }
            
            session()->flash('message', 'User berhasil ditambahkan!');
        }

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

    public function resetPassword($userId)
    {
        $user = User::findOrFail($userId);
        
        // Cegah reset password untuk akun sendiri
        if ($user->id === auth()->id()) {
            session()->flash('error', 'Tidak dapat reset password akun sendiri!');
            return;
        }

        // Generate password baru
        $newPassword = 'password123'; // Password default
        $user->update([
            'password' => bcrypt($newPassword)
        ]);

        session()->flash('message', 'Password user ' . $user->name . ' berhasil direset menjadi: ' . $newPassword);
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
