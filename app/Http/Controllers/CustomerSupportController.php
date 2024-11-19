<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\CustomerSupport;
use App\Models\HardwareInformation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerSupportController extends Controller
{

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


        CustomerSupport::create([
            'no_ticket' => $code,
            'nama_pelapor' => $request->name,
            'no_wa_pelapor' => $request->phone,
            'keperluan' => $request->keperluan,
            'message' => $request->message,
            'customer_id' => $request->customer_id,
        ]);



        return redirect()->back()->with(['success' => 'Tiket berhasil dibuat', 'no_ticket' => $code]);
    }

    public function sendChatWeb($id)
    {

        $data = CustomerSupport::find($id);

        if (!$data) {
            return response()->json([
                'message' => 'Data not found'
            ], 404);
        }
        if ($data->status == 'Waiting') {
            $data->status = 'Responded';
            $data->waktu_respon = now();
            $data->responded_by = Auth::user()->id;
            $data->save();
        }


        $namaPelapor = urlencode($data->nama_pelapor);
        $nomorWA = $data->no_wa_pelapor;
        $nomorTiket = urlencode($data->no_ticket);
        $namaCSO = urlencode(Auth::user()->name);


        $template = "https://api.whatsapp.com/send?phone={$nomorWA}&text=Halo%20*{$namaPelapor}*%2C%0ASaya%20*{$namaCSO}*%2C%20Customer%20Service%20dari%20Disewainaja.co.id%0ATerima%20kasih%20telah%20mengirimkan%20laporan%20kepada%20kami.%20Laporan%20Anda%20telah%20kami%20terima%20dengan%20Nomor%20Tiket%3A%20*{$nomorTiket}*.%0A%0ASaya%20akan%20membantu%20Anda%20menyelesaikan%20kendala%20yang%20Anda%20alami.%20Jika%20ada%20informasi%20tambahan%20yang%20ingin%20disampaikan%2C%20silakan%20balas%20pesan%20ini.%20Kami%20akan%20berusaha%20memberikan%20pelayanan%20terbaik%20untuk%20Anda.%20%F0%9F%98%8A";

        return redirect()->away($template);
    }
}
