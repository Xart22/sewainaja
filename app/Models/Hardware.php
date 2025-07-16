<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hardware extends Model
{
    use Hasfactory;

    protected $guarded = [];
    protected $table = 'hardware';


    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }
}
