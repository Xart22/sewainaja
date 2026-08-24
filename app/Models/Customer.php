<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function hardware()
    {
        return $this->hasMany(Hardware::class, 'customer_id', 'id');
    }

    public function contracts()
    {
        return $this->hasMany(CustomerContract::class, 'customer_id', 'id');
    }

    public function latestContract()
    {
        return $this->hasOne(CustomerContract::class, 'customer_id', 'id')->latestOfMany('contract_end');
    }
}
