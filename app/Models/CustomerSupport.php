<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class CustomerSupport extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
    ];



    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id')->with('hardware');
    }

    public function cso()
    {
        return $this->belongsTo(User::class, 'responded_by');
    }

    public function teknisi()
    {
        return $this->belongsTo(User::class, 'teknisi_id');
    }

    public function logs()
    {
        return $this->hasMany(CustomerSupportLog::class, 'customer_support_id')->with('user');
    }
}
