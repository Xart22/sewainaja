<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UlasanCustomer extends Model
{
    use HasFactory;
    protected $guarded = [];


    public function customerSupport()
    {
        return $this->belongsTo(CustomerSupport::class);
    }

    public function cso()
    {
        return $this->belongsTo(User::class, 'cso_id');
    }

    public function teknisi()
    {
        return $this->belongsTo(User::class, 'teknisi_id');
    }
}
