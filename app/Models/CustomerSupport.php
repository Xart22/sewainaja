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

    const STATUS_WAITING_FOR_TECHNICIAN = 'Waiting';
    const STATUS_ACCEPTED_BY_TECHNICIAN = 'Accepted';
    const STATUS_REJECTED_BY_TECHNICIAN = 'Rejected';
    const STATUS_TECHNICIAN_ON_THE_WAY = 'On The Way';
    const STATUS_TECHNICIAN_ARRIVED = 'Arrived';
    const STATUS_TECHNICIAN_REPAIRING = 'Repairing';
    const STATUS_TECHNICIAN_DONE = 'Done';



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
        return $this->belongsTo(User::class, 'teknisi_id');
    }

    public function logs()
    {
        return $this->hasMany(CustomerSupportLog::class, 'customer_support_id')->with('user');
    }
}
