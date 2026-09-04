<?php

namespace App\Console\Commands;

use App\Models\CustomerSupport;
use App\Models\CustomerSupportLog;
use Illuminate\Console\Command;

class RemainderNotif extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:auto-close-tickets {--days=7 : Jumlah hari tiket menggantung di "Waiting Close by Customer" sebelum ditutup otomatis}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Auto-close tiket yang sudah selesai dikerjakan tapi tidak dikonfirmasi customer dalam X hari';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $days = (int) $this->option('days');
        $cutoff = now()->subDays($days);

        // tiket yang pekerjaannya selesai (menunggu konfirmasi customer) tetapi sudah
        // lewat batas waktu tanpa aksi close dari customer. Hanya tiket yang benar-benar
        // punya laporan kegiatan (work_report) yang ditutup otomatis — tiket lama tanpa
        // laporan (belum pernah dikerjakan / teknisi reject) TIDAK di-auto-close.
        $tickets = CustomerSupport::where('status_cso', '!=', 'Done')
            ->where('status_process', 'Waiting Close by Customer')
            ->whereNotNull('work_report')
            ->where('updated_at', '<=', $cutoff)
            ->get();

        if ($tickets->isEmpty()) {
            $this->info("Tidak ada tiket menggantung yang perlu ditutup otomatis.");
            return 0;
        }

        foreach ($tickets as $data) {
            $data->status_cso = 'Done';
            $data->status_process = 'Closed';
            $data->save();

            CustomerSupportLog::create([
                'customer_support_id' => $data->id,
                'user_id' => null,
                'status' => 'Info',
                'message' => 'Tiket ditutup otomatis oleh sistem karena tidak dikonfirmasi customer dalam ' . $days . ' hari.',
            ]);

            $this->line('Auto-close tiket ' . $data->no_ticket . ' (id ' . $data->id . ')');
        }

        $this->info($tickets->count() . ' tiket ditutup otomatis.');
        return 0;
    }
}
