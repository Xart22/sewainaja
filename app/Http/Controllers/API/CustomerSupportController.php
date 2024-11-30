<?php

namespace App\Http\Controllers\API;

use App\Events\RequestSupport;
use App\Http\Controllers\Controller;
use App\Models\CustomerSupport;
use App\Models\CustomerSupportLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\FcmService;
use DateTime;

class CustomerSupportController extends Controller
{
    protected $fcmService;

    public function __construct(FcmService $fcmService)
    {
        $this->fcmService = $fcmService;
    }

    public function getData()
    {

        $newRequest = CustomerSupport::where('created_at', '>=', date('Y-m-01'))->where('status_cso', '!=', 'Done')->with(['cso', 'customer', 'teknisi', 'logs'])->orderBy('created_at', 'desc')->get();
        $countNewRequest = $newRequest->count();
        $requestDone = CustomerSupport::where('created_at', '>=', date('Y-m-01'))->where('status_cso', 'Done')->with(['cso', 'customer', 'teknisi', 'logs'])->get();


        return response()->json([
            'data' => $newRequest,
            'count' => $countNewRequest,
            'done' => $requestDone
        ]);
    }

    public function getDataCso()
    {

        $data = CustomerSupport::where('created_at', '>=', date('Y-m-01'))->where('status_cso', '!=', 'Done')->with(['cso', 'customer', 'teknisi', 'logs'])->orderBy('created_at', 'desc')->get();

        return response()->json([
            'data' => $data,
        ]);
    }

    public function getTeknisi()
    {
        $data = User::where('role', 'Teknisi')->where('fcm_token', '!=', null)->get();

        return response()->json([
            'data' => $data,
        ]);
    }

    public function getDataTeknisi()
    {
        $data = CustomerSupport::where('teknisi_id', Auth::user()->id)->with(['cso', 'customer', 'teknisi', 'logs'])->get();

        return response()->json([
            'data' => $data,
        ]);
    }

    public function getDataTeknisiByDate($start, $end)
    {
        $data = CustomerSupport::where('teknisi_id', Auth::user()->id)->whereBetween('created_at', [$start, $end])->with(['cso', 'customer', 'teknisi', 'logs'])->where('status_teknisi', 'Done')->get();

        return response()->json([
            'data' => $data,
        ]);
    }


    public function assignteknisi(Request $request)
    {
        $request->validate([
            'id' => 'required',
            'teknisi_id' => 'required',
        ]);

        $data = CustomerSupport::find($request->id);

        if (!$data) {
            return response()->json([
                'message' => 'Data not found',
            ], 404);
        }

        $teknisi = User::find($request->teknisi_id);
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
            $data->teknisi_id = $request->teknisi_id;
            $data->status_process = 'Waiting Close by Customer';
            $data->save();

            CustomerSupportLog::create(
                [
                    'customer_support_id' => $data->id,
                    'user_id' => Auth::user()->id,
                    'status' => 'Success',
                    'message' => Auth::user()->name . ' assign teknisi ' . $teknisi->name . ' to ticket ' . $data->no_ticket,
                ]
            );

            return response()->json([
                'message' => 'Data updated',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to assign teknisi',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function updateStatusTeknisi(Request $request, $id)
    {
        try {
            $request->validate([
                'status' => 'required',
            ]);

            $data = CustomerSupport::find($id);
            broadcast(new RequestSupport($data, $request->status));
            if (!$data) {
                return response()->json([
                    'message' => 'Data not found',
                ], 404);
            }
            $fcm_token = $data->cso->fcm_token;
            if ($request->status == 'Cancel') {
                if ($fcm_token) {
                    $title = 'Pekerjaan Dibatalkan';
                    $message = 'Pekerjaan dengan nomor tiket ' . $data->no_ticket . ' telah dibatalkan oleh teknisi';
                    $this->fcmService->sendNotification($fcm_token, $title, $message);
                }
                $data->status_teknisi = null;
                $data->teknisi_id = null;
                CustomerSupportLog::create(
                    [
                        'customer_support_id' => $data->id,
                        'user_id' => Auth::user()->id,
                        'status' => 'Failed',
                        'message' => Auth::user()->name . ' Mengcancel pekerjaan ' . $data->no_ticket . ' dengan alasan ' . $request->message,
                    ]
                );
            }
            if ($request->status == 'Di Tolak') {
                if ($fcm_token) {
                    $title = 'Pekerjaan Di Tolak';
                    $message = 'Pekerjaan dengan nomor tiket ' . $data->no_ticket . ' telah ditolak oleh teknisi';
                    $this->fcmService->sendNotification($fcm_token, $title, $message);
                }
                $data->status_teknisi = null;
                $data->teknisi_id = null;
                CustomerSupportLog::create(
                    [
                        'customer_support_id' => $data->id,
                        'user_id' => Auth::user()->id,
                        'status' => 'Failed',
                        'message' => Auth::user()->name . ' Menolak pekerjaan ' . $data->no_ticket . ' dengan alasan ' . $request->message,
                    ]
                );
            }

            if ($request->status == 'OTW') {
                if ($fcm_token) {
                    $title = 'Teknisi Menuju Lokasi';
                    $message = 'Teknisi sedang menuju lokasi pekerjaan dengan nomor tiket ' . $data->no_ticket;
                    $this->fcmService->sendNotification($fcm_token, $title, $message);
                }
                $data->status_teknisi = 'On The Way';
                $data->waktu_respon_teknisi = now();
                CustomerSupportLog::create(
                    [
                        'customer_support_id' => $data->id,
                        'user_id' => Auth::user()->id,
                        'status' => 'Success',
                        'message' => Auth::user()->name . ' Menuju lokasi',
                    ]
                );
            }

            if ($request->status == 'Arrived') {
                if ($fcm_token) {
                    $title = 'Teknisi Tiba di Lokasi';
                    $message = 'Teknisi telah tiba di lokasi pekerjaan dengan nomor tiket ' . $data->no_ticket;
                    $this->fcmService->sendNotification($fcm_token, $title, $message);
                }
                $data->status_teknisi = 'Arrived';
                $data->waktu_tiba = now();
                $waktu_tiba = new DateTime($data->waktu_tiba);
                $waktu_respon_teknisi = new DateTime($data->waktu_respon_teknisi);
                $data->waktu_perjalanan = $waktu_tiba->diff($waktu_respon_teknisi)->format('%i menit');

                CustomerSupportLog::create(
                    [
                        'customer_support_id' => $data->id,
                        'user_id' => Auth::user()->id,
                        'status' => 'Success',
                        'message' => Auth::user()->name . ' Tiba di lokasi',
                    ]
                );
            }

            if ($request->status == 'Working') {
                if ($fcm_token) {
                    $title = 'Teknisi Memulai Pengerjaan';
                    $message = 'Teknisi telah memulai pengerjaan pekerjaan dengan nomor tiket ' . $data->no_ticket;
                    $this->fcmService->sendNotification($fcm_token, $title, $message);
                }

                $data->status_teknisi = 'Working';
                $data->waktu_pengerjaan = now();
                CustomerSupportLog::create(
                    [
                        'customer_support_id' => $data->id,
                        'user_id' => Auth::user()->id,
                        'status' => 'Success',
                        'message' => Auth::user()->name . ' Mulai Pengerjaan',
                    ]
                );
            }

            if ($request->status == 'Done') {
                if ($fcm_token) {
                    $title = 'Teknisi telah menyelesaikan pekerjaan';
                    $message = 'Teknisi telah menyelesaikan pekerjaan dengan nomor tiket ' . $data->no_ticket;
                    $this->fcmService->sendNotification($fcm_token, $title, $message);
                }
                $data->status_teknisi = 'Done';
                $data->waktu_selesai = now();
                $waktu_selesai = new DateTime($data->waktu_selesai);
                $waktu_pengerjaan = new DateTime($data->waktu_pengerjaan);
                $data->waktu_pengerjaan = $waktu_selesai->diff($waktu_pengerjaan)->format('%i menit');
                $data->status_process = 'Waiting Close by Customer';
                $data->save();
                CustomerSupportLog::create(
                    [
                        'customer_support_id' => $data->id,
                        'user_id' => Auth::user()->id,
                        'status' => 'Success',
                        'message' => Auth::user()->name . ' Selesai Pengerjaan',
                    ]
                );
            }

            $data->save();

            return response()->json([
                'message' => 'Data updated',
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Failed to update status',
                'error' => $th->getMessage(),
            ], 500);
        }
    }

    public function teknisiResponse(Request $request)
    {
        $request->validate([
            'id' => 'required',
            'status' => 'required',
        ]);

        $data = CustomerSupport::find($request->id);

        if (!$data) {
            return response()->json([
                'message' => 'Data not found'
            ], 404);
        }

        $data->status_teknisi = $request->status;
        $data->save();

        return response()->json([
            'message' => 'Data updated'
        ]);
    }

    public function sendChatApi($id)
    {
        try {
            $data = CustomerSupport::where('id', $id)->first();

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

            return response()->json([
                'message' => 'Success',
                'link' => $template
            ]);
        } catch (\Throwable $th) {
            dd($th->getMessage());
        }
    }


    public function tracking($id)
    {
        $data = CustomerSupport::where('no_ticket', $id)->with(['customer', 'teknisi'])->first();

        if (!$data) {
            return response()->json([
                'message' => 'Data not found'
            ], 404);
        }

        return response()->json([
            'data' => $data,
        ]);
    }
}
