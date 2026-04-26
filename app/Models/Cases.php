<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cases extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'case_number',
        'case_type',
        'court',
        'opponent',
        'start_date',
        'next_session',
        'case_price',
        'description',
        'status'
    ];

    protected $casts = [
        'start_date' => 'date',
        'next_session' => 'date',
    ];

    public function totalPayments()
    {
        return $this->payments()->sum('amount');
    }

    public function remainingAmount()
    {
        return $this->case_price - $this->totalPayments();
    }

    public function profit()
    {
        return $this->totalPayments() - $this->expenses()->sum('amount');
    }

    // Relationship

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function payments() {
        return $this->hasMany(CasePayment::class, 'case_id');
    }

    public function expenses()
    {
        return $this->hasMany(CaseExpense::class , 'case_id');
    }

    public function sessions()
    {
        return $this->hasMany(CaseSession::class , 'case_id');
    }

    public function files() {
        return $this->hasMany(CaseFile::class, 'case_id');
    }

    // public function legalCase() {
    //     return $this->belongsTo(Cases::class, 'case_id');
    // }
}
