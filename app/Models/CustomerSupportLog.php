<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerSupportLog extends Model
{
    use HasFactory;
    protected $guarded = [];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
    ];
    public function customerSupport()
    {
        return $this->belongsTo(CustomerSupport::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
