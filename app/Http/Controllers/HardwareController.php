<?php

namespace App\Http\Controllers;

use App\Models\HardwareInformation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class HardwareController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {


        return view(
            'master-data.hardware.index',
            [
                'hardwares' => HardwareInformation::all()
            ]
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('master-data.hardware.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'hardware_name' => 'required',
            'hardware_type' => 'required',
            'hardware_brand' => 'required',
            'hw_serial_number' => 'required|unique:hardware_information',
            'hardware_image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $file = $request->file('hardware_image');
        $filename = time() . '_' . $file->getClientOriginalName();
        Storage::disk('public')->putFileAs('images', $file, $filename);


        HardwareInformation::create([
            'hw_name' => $request->hardware_name,
            'hw_type' => $request->hardware_type,
            'hw_brand' => $request->hardware_brand,
            'hw_model' => $request->hardware_model,
            'hw_serial_number' => $request->hw_serial_number,
            'hw_technology' => $request->hardware_technology,
            'hw_bw_color' => $request->hardware_bw_color,
            'hw_image' => Storage::url('images/' . $filename),
            'hw_description' => $request->hardware_description,
        ]);

        return redirect()->route('master-data.hardware.index')
            ->with('success', 'Hardware Information created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $hardware = HardwareInformation::find($id);
        $encryptedValue  = Crypt::encrypt($hardware->hw_serial_number);
        $qrCode = QrCode::size(200)->generate(route('customer-online', $encryptedValue));
        return view(
            'master-data.hardware.show',
            [
                'hardware' => $hardware,
                'qrCode' => $qrCode
            ]
        );
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        return view(
            'master-data.hardware.edit',
            [
                'hardware' => HardwareInformation::find($id)
            ]
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'hardware_name' => 'required',
            'hardware_type' => 'required',
            'hardware_brand' => 'required',
            'hw_serial_number' => 'required|unique:hardware_information,hw_serial_number,' . $id,
        ]);

        $hardware = HardwareInformation::find($id);

        $hardware->hw_name = $request->hardware_name;
        $hardware->hw_type = $request->hardware_type;
        $hardware->hw_brand = $request->hardware_brand;
        $hardware->hw_model = $request->hardware_model;
        $hardware->hw_serial_number = $request->hw_serial_number;
        $hardware->hw_technology = $request->hardware_technology;
        $hardware->hw_bw_color = $request->hardware_bw_color;
        $hardware->hw_description = $request->hardware_description;

        if ($request->hasFile('hardware_image')) {
            $file = $request->file('hardware_image');
            $filename = time() . '_' . $file->getClientOriginalName();
            Storage::disk('public')->putFileAs('images', $file, $filename);
            Storage::delete('public/images/' . basename($hardware->hw_image));
            $hardware->hw_image = Storage::url('public/images/' . $filename);
        }

        $hardware->save();

        return redirect()->route('master-data.hardware.index')
            ->with('success', 'Hardware Information updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        HardwareInformation::destroy($id);

        return redirect()->route('master-data.hardware.index')
            ->with('success', 'Hardware Information deleted successfully.');
    }

    public function copyHardware(Request $request)
    {
        $hardware = HardwareInformation::find($request->hardware_id);
        $newHardware = $hardware->replicate();
        $newHardware->hw_serial_number = $request->hw_serial_number;
        $newHardware->customer_id = null;
        $newHardware->used_status = 0;
        $newHardware->save();

        return redirect()->route('master-data.hardware.index')
            ->with('success', 'Hardware Information copied successfully.');
    }


    public function getData()
    {
        // get data from database group by hardware type
        $data = HardwareInformation::selectRaw('MIN(id) as id, hw_name, hw_type, hw_brand, hw_image')
            ->groupBy('hw_name', 'hw_type', 'hw_brand', 'hw_image')
            ->get();

        foreach ($data as $key => $value) {
            $value->hw_image = asset($value->hw_image);
        }

        return response()->json($data);
    }


    public function qrCode(string $id)
    {
        // $hardware = HardwareInformation::findOrFail($id);


        // $qrCode = QrCode::format('png')->size(200)->generate(env('APP_URL') . '/master-data/hardware/' . $hardware->id);


        // $fileName = 'qr_code_' . $hardware->serial_number . '.png';

        // // Kirimkan sebagai file download
        // return response($qrCode)
        //     ->header('Content-Type', 'image/png')
        //     ->header('Content-Disposition', 'attachment; filename="' . $fileName . '"');

        return response()->streamDownload(
            function () {
                echo QrCode::size(200)
                    ->format('png')
                    ->generate('https://harrk.dev');
            },
            'qr-code.png',
            [
                'Content-Type' => 'image/png',
            ]
        );
    }
}
