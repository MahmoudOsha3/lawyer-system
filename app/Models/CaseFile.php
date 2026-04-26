<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class CaseFile extends Model
{
    use HasFactory;

    protected $fillable = [
        'case_id',
        'file',
        'title'
    ];

    protected $appends = ['file_type' , 'file_size' , 'download_url'];

    public function getFileTypeAttribute()
    {
        return pathinfo($this->file, PATHINFO_EXTENSION);
    }

        public function getFileSizeAttribute()
    {
        if (Storage::disk('files')->exists($this->file)) {
            $size = Storage::disk('files')->size($this->file); // الحجم بالبايت
            if ($size >= 1024 * 1024) {
                return round($size / (1024 * 1024), 2) . ' MB';
            } elseif ($size >= 1024) {
                return round($size / 1024, 2) . ' KB';
            } else {
                return $size . ' B';
            }
        }
        return 'غير معروف';
    }

    public function getDownloadUrlAttribute()
    {
        return Storage::disk('files')->url($this->file);
    }
}
