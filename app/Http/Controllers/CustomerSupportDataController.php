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

        $data = CustomerSupport::whereBetween('created_at', [$start_date . ' 00:00:00', $end_date . ' 23:59:59'])->with(['customer', 'teknisi', 'cso', 'logs','hardware','ulasan'])->get();
        return view('customer-support.data.index', [
            'data' => $data,
            'start_date' => $start_date,
            'end_date' => $end_date
        ]);
    }


    public function export($start_date, $end_date)
    {
        $data = CustomerSupport::whereBetween('created_at', [$start_date . ' 00:00:00', $end_date . ' 23:59:59'])->with(['customer', 'teknisi', 'cso', 'logs','hardware','ulasan'])->get();
        $tempFile = $this->writeFile($data);
        return response()->download($tempFile, 'export_' . $start_date . '-' . $end_date . '.xlsx')->deleteFileAfterSend(true);
    }

    public function exportNoLogs($start_date, $end_date)
    {
        $data = CustomerSupport::whereBetween('created_at', [$start_date . ' 00:00:00', $end_date . ' 23:59:59'])->with(['customer', 'teknisi', 'cso', 'hardware','ulasan'])->get();
        $tempFile = $this->writeFile($data, false);
        return response()->download($tempFile, 'export_' . $start_date . '-' . $end_date . '_tanpa_logs.xlsx')->deleteFileAfterSend(true);
    }

    private function writeFile($data, $withLogs = true)
    {

        $template = IOFactory::load(Storage::disk('local')->path('template/export.xlsx'));
        $sheet = $template->getActiveSheet();
        $row = 2;
        $sheet->getStyle('A1:AB1')->getFont()->setBold(true);
        // border
        $sheet->getStyle('A1:AB1')->getBorders()->getAllBorders()->setBorderStyle('thin');
        // set ulasan column headers
        $sheet->setCellValue('W1', 'Rating CSO');
        $sheet->setCellValue('X1', 'Ulasan CSO');
        $sheet->setCellValue('Y1', 'Rating Teknisi');
        $sheet->setCellValue('Z1', 'Ulasan Teknisi');
        $sheet->setCellValue('AA1', 'Laporan Kegiatan');
        $sheet->setCellValue('AB1', 'Waktu Resolution');
        $sheet->getStyle('AB')->getAlignment()->setWrapText(true);
        // Force integer format for rating columns so Excel doesn't interpret as date
        $sheet->getStyle('W')->getNumberFormat()->setFormatCode('0');
        $sheet->getStyle('Y')->getNumberFormat()->setFormatCode('0');
        foreach ($data as $item) {
            $sheet->setCellValue('A' . $row, $item->no_ticket);
            $sheet->setCellValue('B' . $row, $this->fmtCell($item->created_at));
            $sheet->setCellValue('C' . $row, $item->nama_pelapor);
            $sheet->setCellValue('D' . $row, $item->no_wa_pelapor);
            $sheet->setCellValue('E' . $row, $item->keperluan);
            $sheet->setCellValue('F' . $row, $item->message);
            $sheet->setCellValue('G' . $row, $item->customer?->name ?? '-');
            $sheet->setCellValue('H' . $row, $item->customer?->group_name ?? '-');
            $sheet->setCellValue('I' . $row, $item->hardware?->hw_name ?? '-');
            $sheet->setCellValue('J' . $row, $item->hardware?->hw_brand ?? '-');
            $sheet->setCellValue('K' . $row, $item->hardware?->hw_serial_number ?? '-');
            $sheet->setCellValue('L' . $row, $item->status_cso ?? '-');
            $sheet->setCellValue('M' . $row, $item->cso?->name ?? '-');
            $sheet->setCellValue('N' . $row, $item->teknisi?->name ?? '-');
            $sheet->setCellValue('O' . $row, $item->status_teknisi ?? '-');
            $sheet->setCellValue('P' . $row, $item->status_process ?? '-');
            $sheet->setCellValue('Q' . $row, $this->fmtCell($item->waktu_respon_cso));
            $sheet->setCellValue('R' . $row, $this->fmtCell($item->waktu_respon_teknisi));
            $sheet->setCellValue('S' . $row, $item->waktu_perjalanan ?? '-');
            $sheet->setCellValue('T' . $row, $this->fmtCell($item->waktu_tiba));
            $sheet->setCellValue('U' . $row, $item->waktu_pengerjaan ?? '-');
            $sheet->setCellValue('V' . $row, $this->fmtCell($item->waktu_selesai));
            $sheet->setCellValue('W' . $row, $item->ulasan?->rating_cso ?? '-');
            $sheet->setCellValue('X' . $row, $item->ulasan?->ulasan_cso ?? '-');
            $sheet->setCellValue('Y' . $row, $item->ulasan?->rating_teknisi ?? '-');
            $sheet->setCellValue('Z' . $row, $item->ulasan?->ulasan_teknisi ?? '-');
            $sheet->setCellValue('AA' . $row, $item->work_report ?? '-');
            $sheet->setCellValue('AB' . $row, $item->resolution_time ?? '-');
            // border
            $sheet->getStyle('A' . $row . ':AB' . $row)->getBorders()->getAllBorders()->setBorderStyle('thin');
            $row++;

            if ($withLogs) {
                // merge cell from A to C and write Logs per tiket
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
                    $sheet->setCellValue('A' . $row, $log->user?->name ?? '-');
                    $sheet->setCellValue('B' . $row, $log->message);
                    $sheet->setCellValue('C' . $row, $this->fmtCell($log->created_at));
                    $row++;
                }
                $row++;
                $sheet->getStyle('A1:C' . $row)->getBorders()->getAllBorders()->setBorderStyle('thin');
            }
        }
        //border only outside
        $sheet->getStyle('A1:AB' . $row)->getBorders()->getOutline()->setBorderStyle('thin');
        $writer = IOFactory::createWriter($template, 'Xlsx');
        $tempFile = tempnam(sys_get_temp_dir(), 'laravel_excel');
        $writer->save($tempFile);
        return $tempFile;
    }

    /**
     * Normalisasi datetime DB (ISO / string) ke `DD/MM/YYYY HH:MM` agar konsisten di Excel.
     * Nilai non-tanggal (mis. "45 menit") dikembalikan apa adanya.
     */
    private function fmtCell($v)
    {
        if ($v === null || $v === '') {
            return '-';
        }
        if (preg_match('/^(\d{4})-(\d{2})-(\d{2})[T ](\d{2}):(\d{2})/', (string) $v, $m)) {
            return $m[3] . '/' . $m[2] . '/' . $m[1] . ' ' . $m[4] . ':' . $m[5];
        }
        return (string) $v;
    }
}
