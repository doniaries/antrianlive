<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;

class ProfilSeeder extends Seeder
{
    /**
     * Clean up existing logo and favicon files
     */
    protected function cleanupExistingFiles(): void
    {
        $storagePath = public_path('storage');
        
        // Delete all files in the logos directory
        $logosPath = "{$storagePath}/logos";
        if (File::isDirectory($logosPath)) {
            File::cleanDirectory($logosPath);
        } else {
            File::makeDirectory($logosPath, 0755, true);
        }
        
        // Delete all files in the favicons directory
        $faviconsPath = "{$storagePath}/favicons";
        if (File::isDirectory($faviconsPath)) {
            File::cleanDirectory($faviconsPath);
        } else {
            File::makeDirectory($faviconsPath, 0755, true);
        }
    }
    
    /**
     * Generate a default logo using FontAwesome
     */
    protected function generateDefaultLogo(): string
    {
        $svg = '<?xml version="1.0" encoding="UTF-8"?>';
        $svg .= '<svg width="200" height="200" xmlns="http://www.w3.org/2000/svg">';
        $svg .= '<rect width="200" height="200" fill="#3b82f6" rx="100"/>';
        $svg .= '<path d="M100 50L120 90H140L110 120L120 170L100 150L80 170L90 120L60 90H80Z" fill="white"/>';
        $svg .= '<text x="100" y="180" font-family="Arial" font-size="20" text-anchor="middle" fill="white">Puskesmas</text>';
        $svg .= '</svg>';
        
        $filename = 'logo-' . Str::random(10) . '.svg';
        $path = "public/logos/{$filename}";
        
        // Ensure the directory exists
        Storage::makeDirectory(dirname($path));
        
        // Save the SVG to storage
        Storage::put($path, $svg);
        
        // Return the public URL
        return "storage/logos/" . basename($path);
    }
    
    /**
     * Generate a default favicon
     */
    protected function generateDefaultFavicon(): string
    {
        $svg = '<?xml version="1.0" encoding="UTF-8"?>';
        $svg .= '<svg width="64" height="64" xmlns="http://www.w3.org/2000/svg">';
        $svg .= '<rect width="64" height="64" fill="#3b82f6" rx="32"/>';
        $svg .= '<path d="M32 20L38 36H44L35 44L38 60L32 54L26 60L29 44L20 36H26Z" fill="white"/>';
        $svg .= '</svg>';
        
        $filename = 'favicon-' . Str::random(10) . '.svg';
        $path = "public/favicons/{$filename}";
        
        // Ensure the directory exists
        Storage::makeDirectory(dirname($path));
        
        // Save the SVG to storage
        Storage::put($path, $svg);
        
        // Return the public URL
        return "storage/favicons/" . basename($path);
    }

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clean up existing files
        $this->cleanupExistingFiles();
        
        // Generate new default logo and favicon
        $logoPath = $this->generateDefaultLogo();
        $faviconPath = $this->generateDefaultFavicon();
        
        // Truncate the table first to avoid duplicates
        DB::table('profils')->truncate();
        
        // Insert default profile data
        DB::table('profils')->insert([
            'nama_instansi' => 'Puskesmas Sijunjung',
            'alamat' => 'Jl. Puskesmas No. 123, Kota Administrasi',
            'no_telepon' => '(021) 12345678',
            'email' => 'info@puskesmas.go.id',
            'logo' => $logoPath,
            'favicon' => $faviconPath,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
    }
}
