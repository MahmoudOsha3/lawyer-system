<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CaseExpense extends Model
{
    use HasFactory;

    protected $fillable = [
        'case_id',
        'title',
        'amount',
        'expense_date',
        'notes',
        'status'
    ];

    protected $casts = [
        'expense_date' => 'date',
    ];

    public function case()
    {
        return $this->belongsTo(Cases::class , 'case_id') ;
    }
}
