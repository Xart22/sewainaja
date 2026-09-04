<?php

namespace App\Http\Controllers;

use App\Exports\HardwareExport;
use App\Imports\HardwareImport;
use App\Models\CustomerContract;
use App\Models\Hardware;
use Barryvdh\DomPDF\Facade\Pdf;
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
                'hardwares' => Hardware::with(['customer', 'customerContract'])->get()
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
        $filename = $this->sanitizeUploadFilename($file->getClientOriginalName(), $file->getClientOriginalExtension());
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
            $filename = $this->sanitizeUploadFilename($file->getClientOriginalName(), $file->getClientOriginalExtension());
            Storage::disk('public')->putFileAs('images', $file, $filename);
            Storage::delete('public/images/' . basename($hardware->hw_image));
            $hardware->hw_image = Storage::url('images/' . $filename);
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

    public function destroyBulk(Request $request)
    {
        $request->validate(['ids' => 'required|array', 'ids.*' => 'integer']);

        $count = Hardware::whereIn('id', $request->input('ids'))->delete();

        return redirect()->route('master-data.hardware.index')
            ->with('success', $count . ' Hardware deleted successfully.');
    }

    public function copyHardware(Request $request)
    {
        $hardware = Hardware::find($request->hardware_id);
        $newHardware = $hardware->replicate();
        $newHardware->hw_serial_number = $request->hw_serial_number;
        $newHardware->customer_id = null;
        $newHardware->customer_contract_id = null;
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
                $filename = $this->sanitizeUploadFilename($image->getClientOriginalName(), $image->getClientOriginalExtension());
                Storage::disk('public')->putFileAs('images', $image, $filename);
            }
            DB::beginTransaction();
            foreach ($data[0] as $row) {
                // Validate each row before importing
                if (isset($row['hw_name'], $row['hw_type'], $row['hw_brand'], $row['hw_model'], $row['hw_serial_number'])) {
                    $contractId = $this->resolveContractId($request->customer_id);

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
                        'customer_contract_id' => $request->customer_id ? $contractId : null,
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

    public function assign(Request $request)
    {
        $request->validate([
            'hardware_id' => 'required|exists:hardware,id',
            'customer_id' => 'required|exists:customers,id',
            'customer_contract_id' => 'nullable|exists:customer_contracts,id',
        ]);

        if ($request->customer_contract_id) {
            $selectedContract = CustomerContract::where('id', $request->customer_contract_id)
                ->where('customer_id', $request->customer_id)
                ->first();

            if (!$selectedContract) {
                return redirect()->route('master-data.hardware.index')
                    ->with('error', 'Kontrak yang dipilih tidak valid untuk customer tersebut.');
            }
        }

        $contractId = $request->customer_contract_id ?: $this->resolveContractId((int) $request->customer_id);
        if (!$contractId) {
            return redirect()->route('master-data.hardware.index')
                ->with('error', 'Customer tidak memiliki kontrak. Tambahkan kontrak terlebih dahulu.');
        }

        $hardware = Hardware::find($request->hardware_id);
        $hardware->customer_id = $request->customer_id;
        $hardware->customer_contract_id = $contractId;
        $hardware->used_status = 1; // Set to used
        $hardware->save();

        return redirect()->route('master-data.hardware.index')
            ->with('success', 'Hardware assigned to customer successfully.');
    }

    public function destroyAssign(string $id)
    {
        $hardware = Hardware::find($id);
        $hardware->customer_id = null;
        $hardware->customer_contract_id = null;
        $hardware->used_status = 0; // Set to unused
        $hardware->save();

        return redirect()->route('master-data.hardware.index')
            ->with('success', 'Hardware assignment removed successfully.');
    }

    public function export(Request $request)
    {
        $fileName = 'hardware_export_' . now()->format('Ymd_His') . '.xlsx';
        $ids = $request->filled('ids') ? $request->input('ids') : null;

        return Excel::download(new HardwareExport($ids), $fileName);
    }

    public function exportSelected(Request $request)
    {
        $request->validate(['ids' => 'required|array', 'ids.*' => 'integer']);

        return $this->export($request);
    }

    public function exportQrPdf(Request $request)
    {
        $tempDir = storage_path('app/private/qr-temp');
        if (!is_dir($tempDir)) {
            mkdir($tempDir, 0775, true);
        }

        $query = Hardware::whereNotNull('customer_id')->with('customer');
        if ($request->filled('ids')) {
            $query->whereIn('id', $request->input('ids'));
        }

        $hardwares = $query->get()
            ->map(function ($hardware) use ($tempDir) {
                $qrFile = $tempDir . '/' . uniqid('qr_', true) . '.png';
                $this->qrPng(route('customer-online', Crypt::encrypt($hardware->hw_serial_number)), $qrFile, 300);
                $hardware->qr_path = $qrFile;

                return $hardware;
            });

        try {
            $fileName = 'qr_codes_' . now()->format('Ymd_His') . '.pdf';

            return Pdf::loadView('master-data.hardware.pdf-qr-codes', ['hardwares' => $hardwares])
                ->setPaper('a4')
                ->download($fileName);
        } finally {
            foreach ($hardwares as $hardware) {
                @unlink($hardware->qr_path);
            }
        }
    }

    /**
     * Render QR dari matriks BaconQrCode ke PNG via GD (tanpa SVG/imagick).
     * dompdf tidak merender SVG inline, jadi PNG disimpan sementara untuk <img>.
     */
    private function qrPng(string $content, string $destPath, int $size = 300): void
    {
        $qrCode = \BaconQrCode\Encoder\Encoder::encode($content, \BaconQrCode\Common\ErrorCorrectionLevel::M());
        $matrix = $qrCode->getMatrix();
        $n = $matrix->getWidth();
        $margin = 2;
        $scale = (int) floor($size / ($n + $margin * 2));

        $img = imagecreatetruecolor($size, $size);
        $white = imagecolorallocate($img, 255, 255, 255);
        $black = imagecolorallocate($img, 0, 0, 0);
        imagefill($img, 0, 0, $white);

        for ($y = 0; $y < $n; $y++) {
            for ($x = 0; $x < $n; $x++) {
                if ($matrix->get($x, $y)) {
                    imagefilledrectangle(
                        $img,
                        ($x + $margin) * $scale,
                        ($y + $margin) * $scale,
                        ($x + $margin + 1) * $scale - 1,
                        ($y + $margin + 1) * $scale - 1,
                        $black
                    );
                }
            }
        }

        imagepng($img, $destPath);
        imagedestroy($img);
    }

    private function resolveContractId(?int $customerId): ?int
    {
        if (!$customerId) {
            return null;
        }

        $now = now();

        $activeContract = CustomerContract::where('customer_id', $customerId)
            ->where('contract_start', '<=', $now)
            ->where('contract_end', '>=', $now)
            ->orderByDesc('contract_end')
            ->first();

        if ($activeContract) {
            return $activeContract->id;
        }

        $latestContract = CustomerContract::where('customer_id', $customerId)
            ->orderByDesc('contract_end')
            ->orderByDesc('id')
            ->first();

        return $latestContract?->id;
    }

    /**
     * Nama file sanitasi: prefix unik + slug base name, ekstensi tunggal dari $ext param.
     * Mencegah spasi/karakter URL-unsafe & ekstensi ganda (.jpg.jpeg) yang bikin gambar 403/404.
     */
    private function sanitizeUploadFilename(string $originalName, string $ext = '', string $prefix = ''): string
    {
        $prefix = $prefix ?: (string) time();
        $ext = preg_replace('/[^a-z0-9]/', '', strtolower($ext)) ?: 'jpg';
        $originalName = strtolower($originalName);
        $originalName = preg_replace('/(?:\.[a-z0-9]+)+$/', '', $originalName); // buang semua ekstensi (.jpg.jpeg -> kosong)
        $originalName = preg_replace('/[^a-z0-9._-]+/', '-', $originalName); // karakter aneh -> '-'
        $originalName = trim($originalName, '-._');
        $originalName = substr($originalName ?: 'image', 0, 60);

        return $prefix . '_' . $originalName . '.' . $ext;
    }
}
