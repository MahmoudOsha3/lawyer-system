<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CaseSession extends Model
{
    use HasFactory;

    protected $fillable = [
        'case_id',
        'session_date',
        'session_result',
        'next_session'
    ];

    protected $casts = [
        'session_date' => 'date',
        'next_session' => 'date',
    ];

    public function case()
    {
        return $this->belongsTo(Cases::class, 'case_id');
    }
}



