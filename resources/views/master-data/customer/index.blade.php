@extends('layouts.app')


@section('title')
Customer
@endsection

@section('header')
@vite(['resources/js/customer.js', 'resources/js/bulk-select.js'])

@endsection



@section('content')


<div class="mt-14">
    <div class="flex flex-wrap items-center justify-between gap-3">
        <h1 class="text-3xl font-bold text-gray-800 dark:text-gray-200">Customer</h1>
        <a href="{{ route('master-data.customer.export') }}"
            class="inline-flex items-center justify-center rounded-lg bg-emerald-600 px-5 py-2.5 text-sm font-medium text-white hover:bg-emerald-700 focus:outline-none focus:ring-4 focus:ring-emerald-300 dark:bg-emerald-500 dark:hover:bg-emerald-600 dark:focus:ring-emerald-800">
            Export Excel
        </a>
    </div>

    <div id="bulk-toolbar" class="hidden mt-4 flex flex-wrap items-center gap-2 rounded-lg bg-indigo-50 dark:bg-indigo-900/30 border border-indigo-200 dark:border-indigo-800 px-4 py-3">
        <span class="text-sm font-semibold text-indigo-800 dark:text-indigo-200"><span id="bulk-selected-count">0</span> item dipilih</span>
        <div class="flex flex-wrap gap-2 ms-auto">
            <button type="button" data-bulk-action data-bulk-form="bulk-export-form"
                class="rounded-lg bg-emerald-600 px-4 py-2 text-sm font-medium text-white hover:bg-emerald-700">
                Export Excel Terpilih
            </button>
            <button type="button" data-bulk-action data-bulk-form="bulk-destroy-form" data-bulk-confirm="Hapus data customer terpilih?"
                class="rounded-lg bg-red-600 px-4 py-2 text-sm font-medium text-white hover:bg-red-700">
                Hapus Terpilih
            </button>
        </div>
    </div>
    <form id="bulk-export-form" method="POST" action="{{ route('master-data.customer.export-selected') }}" class="hidden">@csrf</form>
    <form id="bulk-destroy-form" method="POST" action="{{ route('master-data.customer.destroy-bulk') }}" class="hidden">@csrf @method('DELETE')</form>

    <div class="bg-white rounded-lg shadow-lg dark:bg-gray-800  p-5 mt-5">

        <table id="tableCustomer" class="table-auto w-full" data-bulk-select>
            <thead>
                <tr>
                    <th class="w-10">
                        <input type="checkbox" class="select-all rounded border-gray-300">
                    </th>
                    <th>
                        <span class="flex items-center">
                            No
                            <svg class="w-4 h-4 ms-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24"
                                height="24" fill="none" viewBox="0 0 24 24">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2" d="m8 15 4 4 4-4m0-6-4-4-4 4" />
                            </svg>
                        </span>
                    </th>
                    <th data-type="date">
                        <span class="flex items-center">
                            Group Name
                            <svg class="w-4 h-4 ms-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24"
                                height="24" fill="none" viewBox="0 0 24 24">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2" d="m8 15 4 4 4-4m0-6-4-4-4 4" />
                            </svg>
                        </span>
                    </th>
                    <th>
                        <span class="flex items-center">
                            Customer Name
                            <svg class="w-4 h-4 ms-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24"
                                height="24" fill="none" viewBox="0 0 24 24">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2" d="m8 15 4 4 4-4m0-6-4-4-4 4" />
                            </svg>
                        </span>
                    </th>
                    <th>
                        <span class="flex items-center">
                            Customer Email
                            <svg class="w-4 h-4 ms-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24"
                                height="24" fill="none" viewBox="0 0 24 24">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2" d="m8 15 4 4 4-4m0-6-4-4-4 4" />
                            </svg>
                        </span>
                    </th>
                    <th>
                        <span class="flex items-center">
                            Customer phone number
                            <svg class="w-4 h-4 ms-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24"
                                height="24" fill="none" viewBox="0 0 24 24">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2" d="m8 15 4 4 4-4m0-6-4-4-4 4" />
                            </svg>
                        </span>
                    </th>

                    <th>
                        <span class="flex items-center">
                            Customer Address
                            <svg class="w-4 h-4 ms-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24"
                                height="24" fill="none" viewBox="0 0 24 24">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2" d="m8 15 4 4 4-4m0-6-4-4-4 4" />
                            </svg>
                        </span>
                    </th>
                    <th>
                        <span class="flex items-center">
                            Contract Information
                        </span>
                    </th>

                    <th>
                        <span class="flex items-center">
                            Aksi
                        </span>
                    </th>
                </tr>
            </thead>
            <tbody>

                @foreach ($customers as $customer)
                <tr>
                    <td class="text-center">
                        <input type="checkbox" class="row-select rounded border-gray-300" value="{{ $customer->id }}">
                    </td>
                    <td>{{$loop->iteration}}</td>
                    <td>{{$customer->group_name}}</td>
                    <td>{{$customer->name}}</td>
                    <td>{{$customer->email}}</td>
                    <td>{{$customer->phone_number}}</td>
                    <td class="text-sm">{{$customer->address}}</td>
                    <td>
                        <div class="flex flex-col">
                            <span>Contract Start Date: {{date('d-m-Y', strtotime($customer->contract_start))}}</span>
                            <span>Contract End Date: {{date('d-m-Y', strtotime($customer->expired_at))}}</span>
                            @if (\Carbon\Carbon::now() > $customer->expired_at)
                            <span class="text-red-500">Expired</span>
                            @else
                            <span>Contract Remaining: {{ round(\Carbon\Carbon::now()->diffInDays($customer->expired_at,
                                false))}}
                                Days
                            </span>
                            @endif

                        </div>
                    </td>

                    <td>
                        <div class="flex justify-start space-x-2">
                            <a href="{{route('master-data.customer.show', $customer->id)}}"
                                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-full">
                                Detail
                            </a>
                            <a href="{{route('master-data.customer.edit', $customer->id)}}"
                                class="bg-yellow-300 hover:bg-yellow-400 text-white font-bold py-2 px-4 rounded-full">
                                Edit
                            </a>
                            <form action="{{route('master-data.customer.destroy', $customer->id)}}" method="post">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-full">
                                    Delete
                                </button>
                            </form>
                        </div>

                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

</div>




@endsection