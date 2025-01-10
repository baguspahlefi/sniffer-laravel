<?php
// app/Models/GalleryImage.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GalleryImage extends Model
{
    use HasFactory;

    protected $table = 'gallery_images';

    protected $fillable = [
        'filename',
        'file_path',
        'original_name',
        'mime_type',
        'file_size'
    ];

    // Add accessor for full URL if needed
    public function getFullUrlAttribute()
    {
        return asset('storage/' . $this->file_path);
    }
}