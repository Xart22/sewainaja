<?php

namespace App\Console\Commands;

use App\Models\CustomerSupport;
use Illuminate\Console\Command;

class RemainderNotif extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:remainder-notif';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Sending email to user for processed order');
        $status_cso = 'Waiting';
        $status_teknisi = ['Waiting', 'On Progress'];
        $data = CustomerSupport::where('status', 'processed')->get();
    }
}
