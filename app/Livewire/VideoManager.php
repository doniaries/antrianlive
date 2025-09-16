<?php

namespace App\Livewire;

use App\Models\Video;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;

class VideoManager extends Component
{
    use WithPagination, WithFileUploads;

    protected $listeners = [
        'videoUpdated' => '$refresh',
        'videoStored' => 'handleVideoStored',
        'videoDeleted' => 'handleVideoDeleted'
    ];

    public $url = '';
    public $type = 'youtube'; // youtube atau file
    public $is_active = true;
    public $video_file;
    public $editId = null;
    public $isOpen = false;

    protected $rules = [
        'type' => 'required|in:youtube,file',
    ];

    protected function rules()
    {
        $rules = [
            'type' => 'required|in:youtube,file',
            'is_active' => 'boolean',
        ];

        if ($this->type === 'youtube') {
            $rules['url'] = 'required|url|max:500';
        } else {
            if (!$this->editId) {
                $rules['video_file'] = 'required|file|mimes:mp4,mkv,avi,mov|max:51200'; // 50MB
            } else {
                $rules['video_file'] = 'nullable|file|mimes:mp4,mkv,avi,mov|max:51200';
            }
        }

        return $rules;
    }

    public function render()
    {
        try {
            $videos = Video::latest()->paginate(10);
            return view('livewire.video-manager', [
                'videos' => $videos->through(function ($video) {
                    // Ensure all required fields have default values
                    return (object)[
                        'id' => $video->id ?? null,
                        'url' => $video->url ?? '',
                        'type' => $video->type ?? 'youtube',
                        'is_active' => $video->is_active ?? true,
                        'created_at' => $video->created_at ?? now(),
                        'updated_at' => $video->updated_at ?? now()
                    ];
                })
            ]);
        } catch (\Exception $e) {
            \Log::error('Error loading videos: ' . $e->getMessage());
            session()->flash('error', 'Gagal memuat video. Silakan coba lagi atau hubungi admin.');
            return view('livewire.video-manager', ['videos' => collect()]);
        }
    }

    public function create()
    {
        $this->resetInputFields();
        $this->openModal();
    }

    public function store()
    {
        $this->validate();

        try {
            $data = [
                'type' => $this->type,
                'is_active' => true, // Always set as active when created/updated
            ];

            if ($this->type === 'youtube') {
                // Clean and validate YouTube URL
                $data['url'] = Video::cleanYoutubeUrl($this->url);
                
                // If we have an existing file video, delete the file
                if ($this->editId) {
                    $oldVideo = Video::find($this->editId);
                    if ($oldVideo && $oldVideo->type === 'file' && $oldVideo->url) {
                        Storage::disk('public')->delete($oldVideo->url);
                    }
                }
            } else {
                if ($this->video_file) {
                    // Delete old video if exists
                    if ($this->editId) {
                        $oldVideo = Video::find($this->editId);
                        if ($oldVideo && $oldVideo->type === 'file' && $oldVideo->url) {
                            Storage::disk('public')->delete($oldVideo->url);
                        }
                    }

                    $path = $this->video_file->store('videos', 'public');
                    $data['url'] = $path;
                } elseif (!$this->editId) {
                    throw new \Exception('File video harus diunggah');
                }
            }

            if ($this->editId) {
                $video = Video::findOrFail($this->editId);
                $video->update($data);
                $message = 'Video berhasil diperbarui';
            } else {
                $video = Video::create($data);
                $message = 'Video berhasil ditambahkan';
            }

            session()->flash('message', $message);

            $this->closeModal();
            $this->resetInputFields();
            
            // Emit event for Livewire to refresh the component
            $this->emit('videoStored');
            $this->dispatch('refreshVideos');
        } catch (\Exception $e) {
            \Log::error('Error saving video: ' . $e->getMessage());
            session()->flash('error', 'Gagal menyimpan video: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $video = Video::findOrFail($id);
        $this->editId = $id;
        $this->type = $video->type;
        $this->url = $video->type === 'youtube' ? $video->url : '';
        $this->is_active = $video->is_active;
        $this->openModal();
    }

    public function delete($id)
    {
        try {
            $video = Video::findOrFail($id);
            
            // Hapus file fisik jika tipe file
            if ($video->type === 'file' && $video->url) {
                $filePath = storage_path('app/public/' . $video->url);
                if (file_exists($filePath)) {
                    unlink($filePath);
                }
            }
            
            $video->delete();
            session()->flash('message', 'Video berhasil dihapus.');
            
            // Emit event for Livewire to refresh the component
            $this->emit('videoDeleted');
            $this->dispatch('refreshVideos');
            
        } catch (\Exception $e) {
            \Log::error('Error deleting video: ' . $e->getMessage());
            session()->flash('error', 'Gagal menghapus video: ' . $e->getMessage());
        }
    }

    private function resetInputFields()
    {
        $this->url = '';
        $this->type = 'youtube';
        $this->video_file = null;
        $this->editId = null;
    }

    public function openModal()
    {
        $this->isOpen = true;
    }

    public function closeModal()
    {
        $this->isOpen = false;
        $this->resetInputFields();
    }

    public function handleVideoStored()
    {
        $this->resetPage();
    }

    public function handleVideoDeleted()
    {
        $this->resetPage();
    }
}
