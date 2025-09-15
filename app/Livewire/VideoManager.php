<?php

namespace App\Livewire;

use App\Models\Video;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;

class VideoManager extends Component
{
    use WithPagination, WithFileUploads;

    public $url = '';
    public $title = '';
    public $type = 'youtube'; // youtube atau file
    public $is_active = true;
    public $video_file;
    public $editId = null;
    public $isOpen = false;

    protected $rules = [
        'title' => 'required|string|max:255',
        'type' => 'required|in:youtube,file',
    ];

    protected function rules()
    {
        $rules = [
            'title' => 'required|string|max:255',
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
        $videos = Video::latest()->paginate(10);
        return view('livewire.video-manager', compact('videos'));
    }

    public function create()
    {
        $this->resetInputFields();
        $this->openModal();
    }

    public function store()
    {
        $this->validate();

        $data = [
            'title' => $this->title,
            'type' => $this->type,
            'is_active' => $this->is_active,
        ];

        if ($this->type === 'youtube') {
            $data['url'] = $this->url;
        } else {
            if ($this->video_file) {
                $path = $this->video_file->store('videos', 'public');
                $data['url'] = $path;
            }
        }

        Video::updateOrCreate(['id' => $this->editId], $data);

        session()->flash('message',
            $this->editId ? 'Video berhasil diperbarui.' : 'Video berhasil ditambahkan.'
        );

        $this->closeModal();
        $this->resetInputFields();
    }

    public function edit($id)
    {
        $video = Video::findOrFail($id);
        $this->editId = $id;
        $this->title = $video->title;
        $this->type = $video->type;
        $this->url = $video->type === 'youtube' ? $video->url : '';
        $this->is_active = $video->is_active;
        $this->openModal();
    }

    public function delete($id)
    {
        $video = Video::find($id);
        if ($video->type === 'file' && $video->url) {
            // Hapus file jika ada
            $filePath = storage_path('app/public/' . $video->url);
            if (file_exists($filePath)) {
                unlink($filePath);
            }
        }
        $video->delete();
        session()->flash('message', 'Video berhasil dihapus.');
    }

    private function resetInputFields()
    {
        $this->title = '';
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
    }
}
