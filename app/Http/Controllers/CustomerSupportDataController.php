<?php

namespace App\Http\Controllers;

use App\Models\CustomerSupport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class CustomerSupportDataController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($start_date, $end_date)
    {

        $start_date = date('Y-m-d', strtotime($start_date));
        $end_date = date('Y-m-d', strtotime($end_date));

        $data = CustomerSupport::whereBetween('created_at', [$start_date . ' 00:00:00', $end_date . ' 23:59:59'])->with(['customer', 'teknisi', 'cso', 'logs'])->get();
        return view('customer-support.data.index', [
            'data' => $data,
            'start_date' => $start_date,
            'end_date' => $end_date
        ]);
    }


    public function export($start_date, $end_date)
    {
        $data = CustomerSupport::whereBetween('created_at', [$start_date . ' 00:00:00', $end_date . ' 23:59:59'])->with(['customer', 'teknisi', 'cso', 'logs'])->get();
        $tempFile = $this->writeFile($data);
        return response()->download($tempFile, 'export_' . $start_date . '-' . $end_date . '.xlsx')->deleteFileAfterSend(true);
    }

    private function writeFile($data)
    {

        $template = IOFactory::load(Storage::disk('local')->path('template/export.xlsx'));
        $sheet = $template->getActiveSheet();
        $row = 2;
        $sheet->getStyle('A1:V1')->getFont()->setBold(true);
        // border
        $sheet->getStyle('A1:V1')->getBorders()->getAllBorders()->setBorderStyle('thin');
        foreach ($data as $item) {
            $sheet->setCellValue('A' . $row, $item->no_ticket);
            $sheet->setCellValue('B' . $row, $item->created_at);
            $sheet->setCellValue('C' . $row, $item->nama_pelapor);
            $sheet->setCellValue('D' . $row, $item->no_wa_pelapor);
            $sheet->setCellValue('E' . $row, $item->keperluan);
            $sheet->setCellValue('F' . $row, $item->message);
            $sheet->setCellValue('G' . $row, $item->customer->name);
            $sheet->setCellValue('H' . $row, $item->customer->group_name);
            $sheet->setCellValue('I' . $row, $item->customer->hardware->hw_name);
            $sheet->setCellValue('J' . $row, $item->customer->hardware->hw_brand);
            $sheet->setCellValue('K' . $row, $item->customer->hardware->hw_serial_number);
            $sheet->setCellValue('L' . $row, $item->status_cso);
            $sheet->setCellValue('M' . $row, $item->cso->name);
            $sheet->setCellValue('N' . $row, $item->teknisi->name);
            $sheet->setCellValue('O' . $row, $item->status_teknisi);
            $sheet->setCellValue('P' . $row, $item->status_process);
            $sheet->setCellValue('Q' . $row, $item->waktu_respon_cso);
            $sheet->setCellValue('R' . $row, $item->waktu_respon_teknisi);
            $sheet->setCellValue('S' . $row, $item->waktu_perjalanan);
            $sheet->setCellValue('T' . $row, $item->waktu_tiba);
            $sheet->setCellValue('U' . $row, $item->waktu_pengerjaan);
            $sheet->setCellValue('V' . $row, $item->waktu_selesai);
            // border
            $sheet->getStyle('A' . $row . ':V' . $row)->getBorders()->getAllBorders()->setBorderStyle('thin');
            $row++;
            // merge cell from A to V and write Logs
            $sheet->mergeCells('A' . $row . ':C' . $row);
            $sheet->setCellValue('A' . $row, 'Logs');
            $sheet->getStyle('A' . $row)->getFont()->setBold(true)->setSize(14)->getColor()->setRGB('FF0000');
            $sheet->getStyle('A' . $row)->getAlignment()->setHorizontal('center');
            $row++;
            // write header logs
            $sheet->setCellValue('A' . $row, 'User');
            $sheet->setCellValue('B' . $row, 'Message');
            $sheet->setCellValue('C' . $row, 'Date Time');
            $row++;
            // write logs
            foreach ($item->logs as $log) {
                $sheet->setCellValue('A' . $row, $log->user->name);
                $sheet->setCellValue('B' . $row, $log->message);
                $sheet->setCellValue('C' . $row, $log->created_at);
                $row++;
            }
            $row++;
            $sheet->getStyle('A1:C' . $row)->getBorders()->getAllBorders()->setBorderStyle('thin');
        }
        //border only outside
        $sheet->getStyle('A1:V' . $row)->getBorders()->getOutline()->setBorderStyle('thin');
        $writer = IOFactory::createWriter($template, 'Xlsx');
        $tempFile = tempnam(sys_get_temp_dir(), 'laravel_excel');
        $writer->save($tempFile);
        return $tempFile;
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
