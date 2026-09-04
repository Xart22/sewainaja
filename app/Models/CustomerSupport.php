<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class CustomerSupport extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $appends = ['resolution_time'];


    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id')->with('hardware');
    }

    public function hardware()
    {
        return $this->belongsTo(Hardware::class, 'hw_id');
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

    public function ulasan()
    {
        return $this->hasOne(UlasanCustomer::class, 'customer_support_id');
    }

    /**
     * Waktu resolution: durasi dari tiket dibuat (created_at) sampai selesai dikerjakan
     * (waktu_selesai). Format "X hari, Y jam, Z menit". Tiket belum selesai → null.
     */
    public function getResolutionTimeAttribute()
    {
        // created_at Eloquent-cast (terbawa timezone), waktu_selesai string mentah DB.
        // Hitung sebagai jam-dinding naif (strtotime) agar keduanya konsisten.
        $createdStr = $this->created_at?->format('Y-m-d H:i:s');
        $doneStr = $this->waktu_selesai;
        if (!$createdStr || !$doneStr) {
            return null;
        }
        $minutes = max(0, (int) round((strtotime($doneStr) - strtotime($createdStr)) / 60));
        $days = intdiv($minutes, 1440);
        $hours = intdiv($minutes % 1440, 60);
        $mins = $minutes % 60;
        $parts = [];
        if ($days > 0) $parts[] = $days . ' hari';
        if ($hours > 0) $parts[] = $hours . ' jam';
        $parts[] = $mins . ' menit';
        return implode(', ', $parts);
    }
}
