<?php

namespace App\Http\Controllers;

use App\Events\RequestSupport;
use App\Models\CustomerSupport;
use App\Models\CustomerSupportLog;
use App\Models\HardwareInformation;
use App\Models\UlasanCustomer;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Google\Client as GoogleClient;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;
use App\Services\FcmService;

class CustomerSupportController extends Controller
{
    protected $fcmService;

    public function __construct(FcmService $fcmService)
    {
        $this->fcmService = $fcmService;
    }
    public function customerOnline($hashed)
    {
        $id = decrypt($hashed);
        $data = HardwareInformation::where('hw_serial_number', $id)->with('customer')->first();
        if (!$data->customer) {
            return redirect()->route('not-found');
        }
        return view('customer-support.index', ['data' => $data]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'phone' => 'required',
            'keperluan' => 'required',
            'message' => 'required',
            'customer_id' => 'required',
        ]);
        $code = '';
        if ($request->keperluan == 'Service') {
            $code = 'SV-';
        } else if ($request->keperluan == 'Consumable') {
            $code = 'CN-';
        } else if ($request->keperluan == 'Lainnya') {
            $code = 'LN-';
        }

        $countThisMonth = CustomerSupport::where('created_at', '>=', date('Y-m-01'))->where('keperluan', $request->keperluan)->count();
        $countThisMonth++;
        $countThisMonth = str_pad($countThisMonth, 4, '0', STR_PAD_LEFT);
        $code .= date('ymd') . $countThisMonth;


        $data =  CustomerSupport::create([
            'no_ticket' => $code,
            'nama_pelapor' => $request->name,
            'no_wa_pelapor' => $request->phone,
            'keperluan' => $request->keperluan,
            'message' => $request->message,
            'customer_id' => $request->customer_id,
        ]);


        $cso = User::where('role', 'CSO')->where('fcm_token', '!=', null)->get();
        $title = 'Tiket Baru Diterima dengan Nomor Tiket ' . $data->no_ticket;
        $message = 'Anda mendapatkan tiket baru dengan nomor tiket ' . $data->no_ticket . '. Silahkan cek aplikasi Anda untuk melihat detail tiket.';
        foreach ($cso as $item) {
            $response = $this->fcmService->sendNotification($item->fcm_token, $title, $message);

            if (isset($response['error'])) {
                return response()->json([
                    'message' => 'Failed to send notification',
                    'error' => $response['error'],
                ], 500);
            }
        }
        broadcast(new RequestSupport($data, 'Waiting'));

        return redirect()->route('customer-support.submission')->with(['success' => 'Tiket berhasil dibuat', 'no_ticket' => $code]);
    }

    public function sendChatWeb($id)
    {

        $data = CustomerSupport::find($id);

        if (!$data) {
            return response()->json([
                'message' => 'Data not found'
            ], 404);
        }
        $namaPelapor = urlencode($data->nama_pelapor);
        $nomorWA = $data->no_wa_pelapor;
        $nomorTiket = urlencode($data->no_ticket);
        $namaCSO = urlencode(Auth::user()->name);

        if ($data->status_cso == "Responded") {
            $template = "https://api.whatsapp.com/send?phone={$nomorWA}";
        }
        if ($data->status_teknisi == "Done") {
            broadcast(new RequestSupport($data, 'Waiting'));
            $linkCloseTiket = urlencode('https://disewainaja.co.id/close-ticket/' . $data->no_ticket);
            $template = "https://api.whatsapp.com/send?phone={$nomorWA}&text=Halo%20*{$namaPelapor}*%2C%0APerbaikan%20terkait%20laporan%20Anda%20dengan%20Nomor%20Tiket%3A%20*{$nomorTiket}*%20telah%20selesai%20kami%20kerjakan.%20%F0%9F%8F%A1%0A%0AKami%20mohon%20kesediaannya%20untuk%20melakukan%20pengecekan%20dan%20meng-close%20tiket%20jika%20masalah%20telah%20terselesaikan.%20%F0%9F%98%8A%0A%0ASilakan%20klik%20link%20berikut%20untuk%20meng-close%20tiket%3A%0A{$linkCloseTiket}%0A%0AJika%20masih%20ada%20kendala%2C%20jangan%20ragu%20untuk%20menghubungi%20kami%20kembali.%20Terima%20kasih%20telah%20mempercayai%20Disewainaja.co.id.%20%F0%9F%99%8F";
        }
        if ($data->status_cso == "Waiting") {
            broadcast(new RequestSupport($data, 'Waiting'));
            $template = "https://api.whatsapp.com/send?phone={$nomorWA}&text=Halo%20*{$namaPelapor}*%2C%0ASaya%20*{$namaCSO}*%2C%20Customer%20Service%20dari%20Disewainaja.co.id%0ATerima%20kasih%20telah%20mengirimkan%20laporan%20kepada%20kami.%20Laporan%20Anda%20telah%20kami%20terima%20dengan%20Nomor%20Tiket%3A%20*{$nomorTiket}*.%0A%0ASaya%20akan%20membantu%20Anda%20menyelesaikan%20kendala%20yang%20Anda%20alami.%20Jika%20ada%20informasi%20tambahan%20yang%20ingin%20disampaikan%2C%20silakan%20balas%20pesan%20ini.%20Kami%20akan%20berusaha%20memberikan%20pelayanan%20terbaik%20untuk%20Anda.%20%F0%9F%98%8A";
            $data->status_cso = 'Responded';
            $data->waktu_respon_cso = now();
            $data->responded_by = Auth::user()->id;
            $data->save();
        }
        CustomerSupportLog::create([
            'customer_support_id' => $data->id,
            'user_id' => Auth::user()->id,
            'status' => 'Success',
            'message' => Auth::user()->name . ' send chat to ' . $data->nama_pelapor . ' with ticket ' . $data->no_ticket,
        ]);

        return redirect()->away($template);
    }

    public function assignteknisi(Request $request)
    {
        $request->validate([
            'ticket_id' => 'required',
            'user_id' => 'required',
        ]);

        $data = CustomerSupport::find($request->ticket_id);

        if (!$data) {
            return response()->json([
                'message' => 'Data not found',
            ], 404);
        }

        $teknisi = User::find($request->user_id);
        $fcm_token = $teknisi->fcm_token;

        $title = 'Pekerjaan Baru Diterima dengan Nomor Tiket ' . $data->no_ticket;
        $message = 'Anda mendapatkan pekerjaan baru dengan nomor tiket ' . $data->no_ticket . '. Silahkan cek aplikasi Anda untuk melihat detail pekerjaan.';

        try {
            $response = $this->fcmService->sendNotification($fcm_token, $title, $message);

            if (isset($response['error'])) {
                return response()->json([
                    'message' => 'Failed to send notification',
                    'error' => $response['error'],
                ], 500);
            }
            $data->status_teknisi = 'Waiting';
            $data->teknisi_id = $request->user_id;
            $data->save();

            CustomerSupportLog::create([
                'customer_support_id' => $data->id,
                'user_id' => Auth::user()->id,
                'status' => 'Success',
                'message' => Auth::user()->name . ' assign teknisi ' . $teknisi->name . ' to ticket ' . $data->no_ticket,
            ]);

            return redirect()->back()->with(['success' => 'Teknisi berhasil diassign']);
        } catch (\Exception $e) {
            dd($e);
            return  redirect()->back()->with(['error' => 'Gagal mengirim notifikasi']);
        }
    }

    public function closeTicketView($id)
    {

        if (Auth::check()) {
            return redirect()->route('not-found');
        }

        $data = CustomerSupport::where('no_ticket', $id)->first();
        if (!$data) {
            return redirect()->route('not-found');
        }


        if ($data->status_cso != 'Responded') {
            return redirect()->route('not-found');
        }
        if ($data->teknisi_id != null) {
            if ($data->status_teknisi != 'Done') {
                return redirect()->route('not-found');
            }
        }
        return view('customer-support.close', ['data' => $data]);
    }

    public function closeTicket(Request $request)
    {
        $data = CustomerSupport::find($request->id);

        if (!$data) {
            return redirect()->route('not-found');
        }

        $data->status_cso = 'Done';
        $data->status_teknisi = 'Done';
        $data->status_process = 'Closed';
        $data->save();

        UlasanCustomer::create([
            'customer_support_id' => $data->id,
            'cso_id' => $data->responded_by,
            'rating_cso' => $request->rating_cso,
            'ulasan_cso' => $request->ulasan_cso,
            'teknisi_id' => $data->teknisi_id,
            'rating_teknisi' => $request->rating_teknisi,
            'ulasan_teknisi' => $request->ulasan_teknisi,
        ]);

        CustomerSupportLog::create([
            'customer_support_id' => $data->id,
            'status' => 'Success',
            'message' => 'No Ticket : ' . $data->no_ticket . ' closed by customer dengan IP ' . $request->ip(),
        ]);
        broadcast(new RequestSupport($data, 'Closed'));
        return redirect()->route('customer-support.closed')->with('no_ticket', $data->no_ticket);
    }

    public function closed()
    {

        return view('customer-support.finish');
    }

    public function submission()
    {
        return view('customer-support.done');
    }

    function tracking()
    {
        return view('dashboard.tracking');
    }

    public function tes()
    {
        broadcast(new RequestSupport(CustomerSupport::find(1), 'Baru'));
    }
}
