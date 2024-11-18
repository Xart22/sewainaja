<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\CustomerSupport;
use App\Models\HardwareInformation;
use Illuminate\Http\Request;

class CustomerSupportController extends Controller
{

    public function customerOnline($hashed)
    {
        $id = decrypt($hashed);
        $data = HardwareInformation::where('hw_serial_number', $id)->with('customer')->first();
        if (!$data->customer) {
            //404
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
}
