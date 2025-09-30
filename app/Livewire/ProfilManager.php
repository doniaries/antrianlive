<?php

namespace App\Livewire;

use App\Models\Profil;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class ProfilManager extends Component
{
    use WithFileUploads;

    public $nama_instansi;
    public $alamat;
    public $no_telepon;
    public $email;
    public $logo;
    public $existing_logo;
    public $existing_favicon;

    protected $rules = [
        'nama_instansi' => 'required|string|max:255',
        'alamat' => 'required|string',
        'no_telepon' => 'required|string|max:20',
        'email' => 'required|email|max:255',
        'logo' => 'nullable|image|max:2048', // Maksimal 2MB
    ];

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

    public function save()
    {
        $this->validate();

        $profil = Profil::first() ?? new Profil();
        
        $profil->nama_instansi = $this->nama_instansi;
        $profil->alamat = $this->alamat;
        $profil->no_telepon = $this->no_telepon;
        $profil->email = $this->email;

        try {
            // Hapus file lama jika ada
            if ($this->logo) {
                // Hapus logo lama
                if ($this->existing_logo) {
                    Storage::disk('public')->delete($this->existing_logo);
                }
                
                // Hapus favicon lama
                if ($this->existing_favicon) {
                    Storage::disk('public')->delete($this->existing_favicon);
                }
                
                // Simpan logo baru
                $logoPath = $this->logo->store('logo', 'public');
                $profil->logo = $logoPath;
                $this->existing_logo = $logoPath;
                
                // Buat dan simpan favicon dari logo
                $this->generateFavicon($logoPath, $profil);
            }

            $profil->save();
            
            // Reset file input
            $this->reset(['logo']);
            
            // Dispatch browser event untuk notifikasi
            $this->dispatch('profile-saved', message: 'Profil berhasil disimpan.');
            
        } catch (\Exception $e) {
            $this->dispatch('profile-error', message: 'Error: ' . $e->getMessage());
        }
    }
    
    protected function generateFavicon($logoPath, &$profil)
    {
        try {
            // Dapatkan path lengkap ke file logo
            $fullLogoPath = storage_path('app/public/' . $logoPath);
            
            // Buat direktori favicon jika belum ada
            $faviconDir = 'favicon';
            if (!Storage::disk('public')->exists($faviconDir)) {
                Storage::disk('public')->makeDirectory($faviconDir);
            }
            
            // Generate nama file favicon
            $faviconName = 'favicon-' . time() . '.png';
            $faviconPath = $faviconDir . '/' . $faviconName;
            $fullFaviconPath = storage_path('app/public/' . $faviconPath);
            
            // Buat instance ImageManager dengan driver GD
            $manager = new ImageManager(new Driver());
            
            // Buat favicon 64x64 dari logo
            $manager->read($fullLogoPath)
                ->cover(64, 64) // Ukuran favicon standar
                ->save($fullFaviconPath, 90); // Kualitas 90%
            
            // Simpan path favicon ke model
            $profil->favicon = $faviconPath;
            $this->existing_favicon = $faviconPath;
            
        } catch (\Exception $e) {
            // Jika gagal membuat favicon, gunakan logo sebagai fallback
            $profil->favicon = $logoPath;
            $this->existing_favicon = $logoPath;
        }
    }

    public function render()
    {
        return view('livewire.profil-manager');
    }
}