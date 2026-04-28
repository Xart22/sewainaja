<?php

namespace App\Exports;

use App\Models\Hardware;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithDrawings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class HardwareExport implements FromCollection, WithHeadings, WithMapping, WithDrawings, WithEvents
{
    private ?Collection $rows = null;

    public function collection()
    {
        return $this->rows();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Hardware Name',
            'Hardware Type',
            'Hardware Brand',
            'Hardware Model',
            'Hardware Serial Number',
            'Hardware Technology',
            'Hardware B/W Color',
            'Hardware Description',
            'Hardware Image',
            'Used Status',
            'Customer Name',
            'Customer Phone Number',
            'Customer Address',
            'Contract Start',
            'Contract End',
            'Created At',
            'Updated At',
        ];
    }

    public function map($hardware): array
    {
        $contractStart = $hardware->customerContract?->contract_start ?? $hardware->customer?->contract_start;
        $contractEnd = $hardware->customerContract?->contract_end ?? $hardware->customer?->expired_at;

        return [
            $hardware->id,
            $hardware->hw_name,
            $hardware->hw_type,
            $hardware->hw_brand,
            $hardware->hw_model,
            $hardware->hw_serial_number,
            $hardware->hw_technology,
            $hardware->hw_bw_color,
            $hardware->hw_description,
            '',
            (int) $hardware->used_status === 1 ? 'Used' : 'Unused',
            optional($hardware->customer)->name,
            optional($hardware->customer)->phone_number,
            optional($hardware->customer)->address,
            optional($contractStart)?->format('Y-m-d H:i:s') ?? $contractStart,
            optional($contractEnd)?->format('Y-m-d H:i:s') ?? $contractEnd,
            optional($hardware->created_at)?->format('Y-m-d H:i:s'),
            optional($hardware->updated_at)?->format('Y-m-d H:i:s'),
        ];
    }

    public function drawings()
    {
        $drawings = [];

        foreach ($this->rows() as $index => $hardware) {
            $imagePath = $this->resolveImagePath($hardware->hw_image);
            if (!$imagePath) {
                continue;
            }

            $drawing = new Drawing();
            $drawing->setName('Hardware Image ' . $hardware->id);
            $drawing->setDescription('Hardware Image ' . $hardware->id);
            $drawing->setPath($imagePath);
            $drawing->setHeight(72);
            $drawing->setCoordinates('J' . ($index + 2));

            $drawings[] = $drawing;
        }

        return $drawings;
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $event->sheet->getDelegate()->getColumnDimension('J')->setWidth(18);

                $lastRow = $this->rows()->count() + 1;
                for ($row = 2; $row <= $lastRow; $row++) {
                    $event->sheet->getDelegate()->getRowDimension($row)->setRowHeight(58);
                }
            },
        ];
    }

    private function rows(): Collection
    {
        if ($this->rows === null) {
            $this->rows = Hardware::with(['customer', 'customerContract'])->orderBy('id')->get();
        }

        return $this->rows;
    }

    private function resolveImagePath(?string $image): ?string
    {
        if (!$image) {
            return null;
        }

        $path = parse_url($image, PHP_URL_PATH) ?: $image;

        if (str_starts_with($path, '/storage/')) {
            $publicPath = public_path(ltrim($path, '/'));
            if (is_file($publicPath)) {
                return $publicPath;
            }

            $storagePath = storage_path('app/public/' . ltrim(str_replace('/storage/', '', $path), '/'));
            if (is_file($storagePath)) {
                return $storagePath;
            }
        }

        if (str_starts_with($path, 'storage/')) {
            $publicPath = public_path($path);
            if (is_file($publicPath)) {
                return $publicPath;
            }
        }

        if (is_file($path)) {
            return $path;
        }

        return null;
    }
}
