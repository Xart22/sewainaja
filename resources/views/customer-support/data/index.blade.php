@extends('layouts.app')


@section('title')
Data Permohonan
@endsection

@section('header')
@vite(['resources/js/data-permohonan.js'])


<script>
    const data = @json($data);
document.addEventListener('DOMContentLoaded', function () {
    const btnexport = document.getElementById('btnexport');
    btnexport.addEventListener('click', async () => {
        const dateFrom = document.getElementById('dateFrom').value;
        const dateTo = document.getElementById('dateTo').value;
        const url = `/admin/data-permohonan-export/${dateFrom}/${dateTo}`;
        window.open(url, '_blank').focus();
        
    });
});
</script>
@endsection



@section('content')


<div class="mt-14">
    <h1 class="text-3xl font-bold text-gray-800 dark:text-gray-200">Data Permohonan</h1>

    <input type="hidden" id="start_date" value="{{ $start_date }}">
    <input type="hidden" id="end_date" value="{{ $end_date }}">
    <input type="date" class="border border-gray-300 rounded-md p-1" id="dateFrom" placeholder="From">
    <input type="date" class="border border-gray-300 rounded-md p-1" id="dateTo" placeholder="To">
    <button class="px-3 py-1 bg-blue-500 text-white rounded-md hover:bg-blue-600" id="btnFilter">Filter</button>
    <button class="px-3 py-1 bg-green-500 text-white rounded-md hover:bg-green-600" id="btnexport">Export</button>
    <div class="bg-white rounded-lg shadow-lg dark:bg-gray-800  p-5 mt-5">

        <table id="tableData" class="table-auto w-full">
            <thead>
                <tr>

                    <th data-type="date" data-format="YYYY/DD/MM">
                        <span class="flex items-center">
                            NO TIKET
                            <svg class="w-4 h-4 ms-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24"
                                height="24" fill="none" viewBox="0 0 24 24">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2" d="m8 15 4 4 4-4m0-6-4-4-4 4" />
                            </svg>
                        </span>
                    </th>
                    <th>
                        <span class="flex items-center">
                            Tanggal Permohonan
                            <svg class="w-4 h-4 ms-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24"
                                height="24" fill="none" viewBox="0 0 24 24">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2" d="m8 15 4 4 4-4m0-6-4-4-4 4" />
                            </svg>
                        </span>
                    </th>
                    <th>
                        <span class="flex items-center">
                            Nama Pemohon
                            <svg class="w-4 h-4 ms-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24"
                                height="24" fill="none" viewBox="0 0 24 24">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2" d="m8 15 4 4 4-4m0-6-4-4-4 4" />
                            </svg>
                        </span>
                    </th>
                    <th>
                        <span class="flex items-center">
                            Nomor Whatsapp
                            <svg class="w-4 h-4 ms-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24"
                                height="24" fill="none" viewBox="0 0 24 24">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2" d="m8 15 4 4 4-4m0-6-4-4-4 4" />
                            </svg>
                        </span>
                    </th>
                    <th>
                        <span class="flex items-center">
                            Keperluan
                            <svg class="w-4 h-4 ms-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24"
                                height="24" fill="none" viewBox="0 0 24 24">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2" d="m8 15 4 4 4-4m0-6-4-4-4 4" />
                            </svg>
                        </span>
                    </th>
                    <th>
                        <span class="flex items-center">
                            Status CSO
                            <svg class="w-4 h-4 ms-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24"
                                height="24" fill="none" viewBox="0 0 24 24">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2" d="m8 15 4 4 4-4m0-6-4-4-4 4" />
                            </svg>
                        </span>
                    </th>
                    <th>
                        <span class="flex items-center">
                            CSO
                            <svg class="w-4 h-4 ms-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24"
                                height="24" fill="none" viewBox="0 0 24 24">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2" d="m8 15 4 4 4-4m0-6-4-4-4 4" />
                            </svg>
                        </span>
                    </th>
                    <th>
                        <span class="flex items-center">
                            TEKNISI
                            <svg class="w-4 h-4 ms-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24"
                                height="24" fill="none" viewBox="0 0 24 24">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2" d="m8 15 4 4 4-4m0-6-4-4-4 4" />
                            </svg>
                        </span>
                    </th>

                    <th>
                        <span class="flex items-center">
                            Status Teknisi
                            <svg class="w-4 h-4 ms-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24"
                                height="24" fill="none" viewBox="0 0 24 24">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2" d="m8 15 4 4 4-4m0-6-4-4-4 4" />
                            </svg>
                        </span>
                    </th>
                    <th>
                        <span class="flex items-center">
                            Status Customer
                            <svg class="w-4 h-4 ms-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24"
                                height="24" fill="none" viewBox="0 0 24 24">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2" d="m8 15 4 4 4-4m0-6-4-4-4 4" />
                            </svg>
                        </span>
                    </th>
                    <th>
                        Created At
                    </th>
                    <th>
                        Respon CSO
                    </th>
                    <th>
                        Respon Teknisi
                    </th>
                    <th>

                        Waktu Perjalanan
                    </th>
                    <th>
                        Waktu Tiba
                    </th>
                    <th>
                        Waktu Pengerjaan
                    </th>
                    <th>
                        Waktu Selesai
                    </th>
                </tr>
            </thead>
            <tbody>



            </tbody>
        </table>
    </div>

</div>
@endsection