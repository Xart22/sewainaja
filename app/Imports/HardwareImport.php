<?php

namespace App\Imports;

use App\Models\Hardware;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class HardwareImport implements ToModel, WithHeadingRow

{
    /**
     * @param Collection $collection
     */
    public function collection(Collection $collection)
    {
        //
    }

    public function model(array $row)
    {
        return new Hardware([
            'hw_name' => $row['hw_name'],
            'hw_type' => $row['hw_type'],
            'hw_brand' => $row['hw_brand'],
            'hw_model' => $row['hw_model'],
            'hw_serial_number' => $row['hw_serial_number'],
            'hw_relocation' => $row['hw_relocation'], // Assuming this is a path or URL
            'hw_technology' => $row['hw_technology'], // Assuming this is a path or URL
            'hw_bw_color' => $row['hw_bw_color'], // Assuming this is a path or URL
            'hw_description' => $row['hw_description'],
        ]);
    }
}
