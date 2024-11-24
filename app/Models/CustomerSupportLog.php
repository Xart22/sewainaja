<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerSupportLog extends Model
{
    use HasFactory;
    protected $guarded = [];


    public function customerSupport()
    {
        return $this->belongsTo(CustomerSupport::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
