<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Video extends Model
{
    protected $fillable = [
        'url',
        'type',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    protected $attributes = [
        'type' => 'youtube',
        'is_active' => true,
    ];

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($video) {
            // Clean up URL if it's a YouTube URL
            if ($video->type === 'youtube' && $video->url) {
                $video->url = self::cleanYoutubeUrl($video->url);
            }
        });
    }

    public static function cleanYoutubeUrl($url)
    {
        if (empty($url)) return $url;
        
        // Extract video ID from various YouTube URL formats
        $pattern = '/(?:youtube\.com\/(?:[^\/\n\s]+\/\S+\/|(?:v|e(?:mbed)?)\/|\S*?[?&]v=)|youtu\.be\/)([a-zA-Z0-9_-]{11})/';
        
        if (preg_match($pattern, $url, $matches)) {
            return $matches[1]; // Return just the video ID
        }
        
        return $url;
    }

    public function getVideoUrlAttribute()
    {
        if (empty($this->url)) return null;
        
        if ($this->type === 'youtube') {
            return 'https://www.youtube.com/embed/' . $this->url . '?autoplay=1&mute=1&loop=1&playlist=' . $this->url;
        }
        
        return $this->url;
    }
}
