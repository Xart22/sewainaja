<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerSupport extends Model
{
    use HasFactory;
    protected $guarded = [];

    // Status
    const STATUS_WAITING = 'Waiting';
    const STATUS_RESPONDED = 'Responded';
    const STATUS_WAITING_TECHNICIAN = 'Waiting for Technician';
    const STATUS_OTW = 'On The Way';
    const STATUS_REPAIRING = 'Repairing';
    const STATUS_PROCESS_WAITING_CLOSE = 'Process Waiting Close by Customer';
    const STATUS_DONE = 'Done';

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id')->with('hardware');
    }

    public function cso()
    {
        return $this->belongsTo(User::class, 'responded_by');
    }

    public function technician()
    {
        return $this->belongsTo(User::class, 'tekhnisi_id');
    }
}
