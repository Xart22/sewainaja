<?php

namespace App\Http\Controllers;

use App\Imports\HardwareImport;
use App\Models\Hardware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
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
                'hardwares' => Hardware::all()
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
            'hw_serial_number' => 'required|unique:hardware',
            'hardware_image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $file = $request->file('hardware_image');
        $filename = time() . '_' . $file->getClientOriginalName();
        Storage::disk('public')->putFileAs('images', $file, $filename);


        Hardware::create([
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
        $hardware = Hardware::find($id);
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
                'hardware' => Hardware::find($id)
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
            'hw_serial_number' => 'required|unique:hardware,hw_serial_number,' . $id,
        ]);

        $hardware = Hardware::find($id);

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
        Hardware::destroy($id);

        return redirect()->route('master-data.hardware.index')
            ->with('success', 'Hardware Information deleted successfully.');
    }

    public function copyHardware(Request $request)
    {
        $hardware = Hardware::find($request->hardware_id);
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
        $data = Hardware::selectRaw('MIN(id) as id, hw_name, hw_type, hw_brand, hw_image')
            ->groupBy('hw_name', 'hw_type', 'hw_brand', 'hw_image')
            ->get();

        foreach ($data as $key => $value) {
            $value->hw_image = asset($value->hw_image);
        }

        return response()->json($data);
    }


    public function qrCode(string $id)
    {
        // $hardware = Hardware::findOrFail($id);


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


    public function import(Request $request)
    {
        try {
            $data = Excel::toCollection(new HardwareImport, $request->file('hw_file'));
            $image = $request->file('hw_image');
            if ($image) {
                $filename = time() . '_' . $image->getClientOriginalName();
                Storage::disk('public')->putFileAs('images', $image, $filename);
            }
            DB::beginTransaction();
            foreach ($data[0] as $row) {
                // Validate each row before importing
                if (isset($row['hw_name'], $row['hw_type'], $row['hw_brand'], $row['hw_model'], $row['hw_serial_number'])) {
                    Hardware::create([
                        'hw_name' => $row['hw_name'],
                        'hw_type' => $row['hw_type'],
                        'hw_brand' => $row['hw_brand'],
                        'hw_model' => $row['hw_model'],
                        'hw_serial_number' => $row['hw_serial_number'],
                        'hw_relocation' => $row['hw_relocation'] ?? null,
                        'hw_technology' => $row['hw_technology'] ?? null,
                        'hw_bw_color' => $row['hw_bw_color'] ?? null,
                        'hw_description' => $row['hw_description'] ?? null,
                        'customer_id' => $request->customer_id ?? null,
                        'used_status' => $request->customer_id == null ? 0 : 1,
                        'hw_image' => isset($filename) ? Storage::url('images/' . $filename) : null,
                    ]);
                }
            }
            DB::commit();
            //save
            return redirect()->route('master-data.hardware.index')
                ->with('success', 'Hardware data imported successfully.');
        } catch (\Throwable $th) {
            DB::rollBack();
            //delete the image if exists
            if (isset($filename)) {
                Storage::delete('public/images/' . $filename);
            }

            return redirect()->route('master-data.hardware.index')
                ->with('error', 'Failed to import hardware data. ' . $th->getMessage());
        }
    }
}
