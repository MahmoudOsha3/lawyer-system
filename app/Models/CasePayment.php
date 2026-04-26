<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CasePayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'case_id',
        'amount',
        'payment_date',
        'notes'
    ];

    protected $casts = [
        'payment_date' => 'date',
    ];

    public function case()
    {
        return $this->belongsTo(Cases::class , 'case_id') ;
    }
}
