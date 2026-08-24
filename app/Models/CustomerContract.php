<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerContract extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'contract_start' => 'datetime',
        'contract_end' => 'datetime',
        'is_active' => 'boolean',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function hardwares()
    {
        return $this->hasMany(Hardware::class, 'customer_contract_id');
    }
}
