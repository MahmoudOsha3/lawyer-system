<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'phone',
        'phone2',
        'email',
        'national_id',
        'passport_number',
        'job',
        'company',
        'address',
        'notes',
        'type',
        'status'
    ];

    public function cases()
    {
        return $this->hasMany(Cases::class);
    }
}
