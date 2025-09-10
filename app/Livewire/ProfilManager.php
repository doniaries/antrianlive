<?php

namespace App\Livewire;

use App\Models\Profil;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

class ProfilManager extends Component
{
    use WithFileUploads;

    public $nama_instansi;
    public $alamat;
    public $no_telepon;
    public $email;
    public $logo;
    public $favicon;
    public $existing_logo;
    public $existing_favicon;

    public function mount()
    {
        $profil = Profil::first();
        if ($profil) {
            $this->nama_instansi = $profil->nama_instansi;
            $this->alamat = $profil->alamat;
            $this->no_telepon = $profil->no_telepon;
            $this->email = $profil->email;
            $this->existing_logo = $profil->logo;
            $this->existing_favicon = $profil->favicon;
        }
    }

    public function rules()
    {
        return [
            'nama_instansi' => 'required|string|max:255',
            'alamat' => 'required|string',
            'no_telepon' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'logo' => 'nullable|image|max:1024',
            'favicon' => 'nullable|image|max:512',
        ];
    }

    public function save()
    {
        $this->validate();

        $profil = Profil::first() ?? new Profil();
        
        $profil->nama_instansi = $this->nama_instansi;
        $profil->alamat = $this->alamat;
        $profil->no_telepon = $this->no_telepon;
        $profil->email = $this->email;

        try {
            if ($this->logo) {
                if ($this->existing_logo) {
                    Storage::disk('public')->delete($this->existing_logo);
                }
                $profil->logo = $this->logo->store('logo', 'public');
                $this->existing_logo = $profil->logo;
            }

            if ($this->favicon) {
                if ($this->existing_favicon) {
                    Storage::disk('public')->delete($this->existing_favicon);
                }
                $profil->favicon = $this->favicon->store('favicons', 'public');
                $this->existing_favicon = $profil->favicon;
            }

            $profil->save();
            
            // Reset file inputs
            $this->logo = null;
            $this->favicon = null;

            session()->flash('message', 'Profil berhasil disimpan.');
            
        } catch (\Exception $e) {
            session()->flash('error', 'Error: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.profil-manager');
    }
}