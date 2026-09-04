<?php

namespace App\Exports;

use App\Models\Customer;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class CustomerExport implements FromCollection, WithHeadings, WithMapping
{
    public function __construct(private ?array $ids = null)
    {
    }

    public function collection()
    {
        return Customer::with(['latestContract', 'hardware'])
            ->when($this->ids, fn ($q) => $q->whereIn('id', $this->ids))
            ->orderBy('name')
            ->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Group Name',
            'Name',
            'Email',
            'Phone Number',
            'Address',
            'Latitude',
            'Longitude',
            'PIC Process',
            'PIC Process Phone Number',
            'PIC Installation',
            'PIC Installation Phone Number',
            'PIC Financial',
            'PIC Financial Phone Number',
            'Contract Start',
            'Contract End',
            'Is Active',
            'Notes',
            'Hardware Count',
            'Created At',
            'Updated At',
        ];
    }

    public function map($customer): array
    {
        return [
            $customer->id,
            $customer->group_name,
            $customer->name,
            $customer->email,
            $customer->phone_number,
            $customer->address,
            $customer->latitude,
            $customer->longitude,
            $customer->pic_process,
            $customer->pic_process_phone_number,
            $customer->pic_installation,
            $customer->pic_installation_phone_number,
            $customer->pic_financial,
            $customer->pic_financial_phone_number,
            optional($customer->contract_start)?->format('Y-m-d H:i:s') ?? $customer->contract_start,
            optional($customer->expired_at)?->format('Y-m-d H:i:s') ?? $customer->expired_at,
            $customer->is_active ? 'Active' : 'Inactive',
            $customer->notes,
            $customer->hardware->count(),
            optional($customer->created_at)?->format('Y-m-d H:i:s'),
            optional($customer->updated_at)?->format('Y-m-d H:i:s'),
        ];
    }
}
