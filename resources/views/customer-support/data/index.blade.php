@extends('layouts.app')

@section('title')
Data Permohonan
@endsection

@section('header')
@vite(['resources/js/data-permohonan.js'])

<script>
    const data = @json($data);
    document.addEventListener('DOMContentLoaded', function () {
        document.getElementById('btnexport').addEventListener('click', () => {
            const dateFrom = document.getElementById('dateFrom').value;
            const dateTo   = document.getElementById('dateTo').value;
            if (!dateFrom || !dateTo) {
                alert('Pilih rentang tanggal terlebih dahulu.');
                return;
            }
            window.open(`/admin/data-permohonan-export/${dateFrom}/${dateTo}`, '_blank').focus();
        });
    });
</script>
@endsection

@section('content')

<div class="mt-14 space-y-4">
    <h1 class="text-3xl font-bold text-gray-800 dark:text-gray-200">Data Permohonan</h1>

    <input type="hidden" id="start_date" value="{{ $start_date }}">
    <input type="hidden" id="end_date"   value="{{ $end_date }}">

    {{-- Filter card --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow border border-gray-100 dark:border-gray-700 p-4 flex flex-wrap items-end gap-3">
        <div>
            <label class="block text-xs font-semibold text-gray-600 dark:text-gray-300 mb-1" for="dateFrom">Dari Tanggal</label>
            <input type="date" id="dateFrom"
                class="border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 text-sm dark:bg-gray-700 dark:text-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-400">
        </div>
        <div>
            <label class="block text-xs font-semibold text-gray-600 dark:text-gray-300 mb-1" for="dateTo">Sampai Tanggal</label>
            <input type="date" id="dateTo"
                class="border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 text-sm dark:bg-gray-700 dark:text-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-400">
        </div>
        <button id="btnFilter"
            class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm font-semibold shadow">
            Filter
        </button>
        <button id="btnexport"
            class="px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 text-sm font-semibold shadow flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M7 10l5 5 5-5M12 15V3"/>
            </svg>
            Export Excel
        </button>
    </div>

    {{-- Table card --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow border border-gray-100 dark:border-gray-700 p-5">
        <table id="tableData" class="table-auto w-full">
            <thead>
                <tr>
                    <th><span class="flex items-center">No Tiket</span></th>
                    <th><span class="flex items-center">Tanggal</span></th>
                    <th><span class="flex items-center">Nama Pemohon</span></th>
                    <th><span class="flex items-center">No. WA</span></th>
                    <th><span class="flex items-center">Keperluan</span></th>
                    <th><span class="flex items-center">Status CSO</span></th>
                    <th><span class="flex items-center">CSO</span></th>
                    <th><span class="flex items-center">Teknisi</span></th>
                    <th><span class="flex items-center">Status Teknisi</span></th>
                    <th><span class="flex items-center">Status Proses</span></th>
                    <th><span class="flex items-center">Dibuat</span></th>
                    <th><span class="flex items-center">Respon CSO</span></th>
                    <th><span class="flex items-center">Respon Teknisi</span></th>
                    <th><span class="flex items-center">Perjalanan</span></th>
                    <th><span class="flex items-center">Tiba</span></th>
                    <th><span class="flex items-center">Pengerjaan</span></th>
                    <th><span class="flex items-center">Selesai</span></th>
                    <th><span class="flex items-center">Aksi</span></th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>

{{-- Detail Modal --}}
<div id="detail" tabindex="-1"
    class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] max-h-full bg-gray-900/40 backdrop-blur-sm">
    <div class="relative w-full max-w-5xl max-h-full mx-auto">
        <div class="relative bg-white rounded-2xl shadow-2xl border border-gray-100 dark:bg-gray-700 dark:border-gray-600 p-5">

            {{-- Header --}}
            <div class="flex items-center justify-between border-b border-gray-200 dark:border-gray-600 pb-3 mb-4">
                <div>
                    <h2 class="text-xl font-bold text-gray-800 dark:text-gray-100">Detail Permohonan</h2>
                    <p class="text-sm text-gray-500 dark:text-gray-400" id="d-ticket-no"></p>
                </div>
                <button data-modal-hide="detail" type="button"
                    class="inline-flex items-center justify-center rounded-lg border border-gray-300 px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 dark:border-gray-500 dark:text-gray-200 dark:hover:bg-gray-600">
                    Tutup
                </button>
            </div>

            {{-- Info cards --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">

                {{-- Permohonan --}}
                <div class="rounded-xl border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-800 p-4">
                    <h3 class="font-bold text-gray-700 dark:text-gray-200 mb-3 border-b border-gray-200 dark:border-gray-600 pb-1">Permohonan</h3>
                    <dl class="space-y-2 text-sm">
                        <div><dt class="font-medium text-gray-500 dark:text-gray-400 text-xs uppercase tracking-wide">Nama Pemohon</dt><dd class="text-gray-800 dark:text-gray-200 mt-0.5" id="d-nama-pemohon"></dd></div>
                        <div><dt class="font-medium text-gray-500 dark:text-gray-400 text-xs uppercase tracking-wide">No. WhatsApp</dt><dd class="text-gray-800 dark:text-gray-200 mt-0.5" id="d-no-wa"></dd></div>
                        <div><dt class="font-medium text-gray-500 dark:text-gray-400 text-xs uppercase tracking-wide">Keperluan</dt><dd class="text-gray-800 dark:text-gray-200 mt-0.5" id="d-keperluan"></dd></div>
                        <div><dt class="font-medium text-gray-500 dark:text-gray-400 text-xs uppercase tracking-wide">Deskripsi</dt><dd class="text-gray-800 dark:text-gray-200 mt-0.5 break-words" id="d-deskripsi"></dd></div>
                        <div><dt class="font-medium text-gray-500 dark:text-gray-400 text-xs uppercase tracking-wide">Status CSO</dt><dd class="mt-0.5" id="d-status-cso"></dd></div>
                        <div><dt class="font-medium text-gray-500 dark:text-gray-400 text-xs uppercase tracking-wide">Status Teknisi</dt><dd class="mt-0.5" id="d-status-teknisi"></dd></div>
                        <div><dt class="font-medium text-gray-500 dark:text-gray-400 text-xs uppercase tracking-wide">Status Proses</dt><dd class="mt-0.5" id="d-status-process"></dd></div>
                    </dl>
                </div>

                {{-- Customer & Hardware --}}
                <div class="rounded-xl border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-800 p-4">
                    <h3 class="font-bold text-gray-700 dark:text-gray-200 mb-3 border-b border-gray-200 dark:border-gray-600 pb-1">Customer & Hardware</h3>
                    <dl class="space-y-2 text-sm">
                        <div><dt class="font-medium text-gray-500 dark:text-gray-400 text-xs uppercase tracking-wide">Nama Customer</dt><dd class="text-gray-800 dark:text-gray-200 mt-0.5" id="d-customer-name"></dd></div>
                        <div><dt class="font-medium text-gray-500 dark:text-gray-400 text-xs uppercase tracking-wide">Group</dt><dd class="text-gray-800 dark:text-gray-200 mt-0.5" id="d-customer-group"></dd></div>
                        <div><dt class="font-medium text-gray-500 dark:text-gray-400 text-xs uppercase tracking-wide">Alamat</dt><dd class="text-gray-800 dark:text-gray-200 mt-0.5 break-words" id="d-customer-address"></dd></div>
                        <div><dt class="font-medium text-gray-500 dark:text-gray-400 text-xs uppercase tracking-wide">Hardware</dt><dd class="text-gray-800 dark:text-gray-200 mt-0.5" id="d-hw-name"></dd></div>
                        <div><dt class="font-medium text-gray-500 dark:text-gray-400 text-xs uppercase tracking-wide">Type</dt><dd class="text-gray-800 dark:text-gray-200 mt-0.5" id="d-hw-type"></dd></div>
                        <div><dt class="font-medium text-gray-500 dark:text-gray-400 text-xs uppercase tracking-wide">Brand</dt><dd class="text-gray-800 dark:text-gray-200 mt-0.5" id="d-hw-brand"></dd></div>
                        <div><dt class="font-medium text-gray-500 dark:text-gray-400 text-xs uppercase tracking-wide">Serial Number</dt><dd class="text-gray-800 dark:text-gray-200 mt-0.5 break-all" id="d-hw-sn"></dd></div>
                    </dl>
                </div>

                {{-- Timeline --}}
                <div class="rounded-xl border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-800 p-4">
                    <h3 class="font-bold text-gray-700 dark:text-gray-200 mb-3 border-b border-gray-200 dark:border-gray-600 pb-1">Timeline & Penanganan</h3>
                    <dl class="space-y-2 text-sm">
                        <div><dt class="font-medium text-gray-500 dark:text-gray-400 text-xs uppercase tracking-wide">CSO</dt><dd class="text-gray-800 dark:text-gray-200 mt-0.5" id="d-cso-name"></dd></div>
                        <div><dt class="font-medium text-gray-500 dark:text-gray-400 text-xs uppercase tracking-wide">Teknisi</dt><dd class="text-gray-800 dark:text-gray-200 mt-0.5" id="d-teknisi-name"></dd></div>
                        <div><dt class="font-medium text-gray-500 dark:text-gray-400 text-xs uppercase tracking-wide">No. WA Teknisi</dt><dd class="text-gray-800 dark:text-gray-200 mt-0.5" id="d-teknisi-wa"></dd></div>
                        <div><dt class="font-medium text-gray-500 dark:text-gray-400 text-xs uppercase tracking-wide">Tiket Dibuat</dt><dd class="text-gray-800 dark:text-gray-200 mt-0.5" id="d-created-at"></dd></div>
                        <div><dt class="font-medium text-gray-500 dark:text-gray-400 text-xs uppercase tracking-wide">Respon CSO</dt><dd class="text-gray-800 dark:text-gray-200 mt-0.5" id="d-respon-cso"></dd></div>
                        <div><dt class="font-medium text-gray-500 dark:text-gray-400 text-xs uppercase tracking-wide">Respon Teknisi</dt><dd class="text-gray-800 dark:text-gray-200 mt-0.5" id="d-respon-teknisi"></dd></div>
                        <div><dt class="font-medium text-gray-500 dark:text-gray-400 text-xs uppercase tracking-wide">Waktu Perjalanan</dt><dd class="text-gray-800 dark:text-gray-200 mt-0.5" id="d-perjalanan"></dd></div>
                        <div><dt class="font-medium text-gray-500 dark:text-gray-400 text-xs uppercase tracking-wide">Waktu Tiba</dt><dd class="text-gray-800 dark:text-gray-200 mt-0.5" id="d-tiba"></dd></div>
                        <div><dt class="font-medium text-gray-500 dark:text-gray-400 text-xs uppercase tracking-wide">Waktu Pengerjaan</dt><dd class="text-gray-800 dark:text-gray-200 mt-0.5" id="d-pengerjaan"></dd></div>
                        <div><dt class="font-medium text-gray-500 dark:text-gray-400 text-xs uppercase tracking-wide">Waktu Selesai</dt><dd class="text-gray-800 dark:text-gray-200 mt-0.5" id="d-selesai"></dd></div>
                    </dl>
                </div>
            </div>

            {{-- Ulasan --}}
            <div class="rounded-xl border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-800 p-4 mb-4" id="d-ulasan-card">
                <h3 class="font-bold text-gray-700 dark:text-gray-200 mb-3 border-b border-gray-200 dark:border-gray-600 pb-1">Ulasan Customer</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                    <div>
                        <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-2">CSO</p>
                        <dl class="space-y-2">
                            <div><dt class="font-medium text-gray-500 dark:text-gray-400 text-xs">Rating</dt><dd class="mt-0.5" id="d-rating-cso"></dd></div>
                            <div><dt class="font-medium text-gray-500 dark:text-gray-400 text-xs">Ulasan</dt><dd class="text-gray-800 dark:text-gray-200 mt-0.5 break-words" id="d-ulasan-cso"></dd></div>
                        </dl>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-2">Teknisi</p>
                        <dl class="space-y-2">
                            <div><dt class="font-medium text-gray-500 dark:text-gray-400 text-xs">Rating</dt><dd class="mt-0.5" id="d-rating-teknisi"></dd></div>
                            <div><dt class="font-medium text-gray-500 dark:text-gray-400 text-xs">Ulasan</dt><dd class="text-gray-800 dark:text-gray-200 mt-0.5 break-words" id="d-ulasan-teknisi"></dd></div>
                        </dl>
                    </div>
                    <div class="md:col-span-2">
                        <dt class="font-medium text-gray-500 dark:text-gray-400 text-xs uppercase tracking-wide">Ulasan Sistem</dt>
                        <dd class="text-gray-800 dark:text-gray-200 mt-0.5 break-words" id="d-ulasan-system"></dd>
                    </div>
                </div>
            </div>

            {{-- Logs --}}
            <div class="rounded-xl border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-800 p-4">
                <h3 class="font-bold text-gray-700 dark:text-gray-200 mb-3 border-b border-gray-200 dark:border-gray-600 pb-1">Logs Aktivitas</h3>
                <div class="overflow-y-auto max-h-56 rounded-lg border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-900">
                    <table class="table-auto w-full text-sm">
                        <thead>
                            <tr class="bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200 sticky top-0">
                                <th class="py-2 px-3 text-left font-semibold">User</th>
                                <th class="py-2 px-3 text-left font-semibold">Pesan</th>
                                <th class="py-2 px-3 text-left font-semibold whitespace-nowrap">Waktu</th>
                            </tr>
                        </thead>
                        <tbody id="d-logs" class="divide-y divide-gray-100 dark:divide-gray-700"></tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</div>

<script>
    const statusBadge = (status) => {
        if (!status) return '<span class="text-gray-400 text-xs">-</span>';
        const map = {
            'Waiting':    'bg-yellow-100 text-yellow-700',
            'Responded':  'bg-blue-100 text-blue-700',
            'Accepted':   'bg-green-100 text-green-700',
            'On The Way': 'bg-indigo-100 text-indigo-700',
            'Arrived':    'bg-purple-100 text-purple-700',
            'Working':    'bg-orange-100 text-orange-700',
            'Repairing':  'bg-orange-100 text-orange-700',
            'Done':       'bg-green-600 text-white',
            'Open':       'bg-red-100 text-red-700',
            'Closed':     'bg-gray-700 text-white',
        };
        const cls = map[status] ?? 'bg-gray-100 text-gray-700';
        return `<span class="inline-block px-2 py-0.5 rounded-full text-xs font-semibold ${cls}">${status}</span>`;
    };

    const fmt = (val) => (val !== null && val !== undefined && val !== '') ? val : '-';

    const ratingStars = (rating) => {
        if (rating === null || rating === undefined) return '<span class="text-gray-400 text-xs">-</span>';
        const n = parseInt(rating);
        const stars = '★'.repeat(Math.min(Math.max(n, 0), 5)) + '☆'.repeat(Math.max(5 - n, 0));
        const color = n >= 4 ? 'text-yellow-400' : n >= 3 ? 'text-orange-400' : 'text-red-400';
        return `<span class="${color} text-base font-bold">${stars}</span> <span class="text-gray-600 dark:text-gray-300 text-xs">(${n}/5)</span>`;
    };

    const detail = (item) => {
        const modal = new Modal(document.getElementById('detail'));
        modal.show();

        document.getElementById('d-ticket-no').innerText       = `No. Tiket: ${fmt(item.no_ticket)}`;
        document.getElementById('d-nama-pemohon').innerText    = fmt(item.nama_pelapor);
        document.getElementById('d-no-wa').innerText           = fmt(item.no_wa_pelapor);
        document.getElementById('d-keperluan').innerText       = fmt(item.keperluan);
        document.getElementById('d-deskripsi').innerText       = fmt(item.message);
        document.getElementById('d-status-cso').innerHTML      = statusBadge(item.status_cso);
        document.getElementById('d-status-teknisi').innerHTML  = statusBadge(item.status_teknisi);
        document.getElementById('d-status-process').innerHTML  = statusBadge(item.status_process);

        document.getElementById('d-customer-name').innerText    = fmt(item.customer?.name);
        document.getElementById('d-customer-group').innerText   = fmt(item.customer?.group_name);
        document.getElementById('d-customer-address').innerText = fmt(item.customer?.address);
        document.getElementById('d-hw-name').innerText          = fmt(item.hardware?.hw_name);
        document.getElementById('d-hw-type').innerText          = fmt(item.hardware?.hw_type);
        document.getElementById('d-hw-brand').innerText         = fmt(item.hardware?.hw_brand);
        document.getElementById('d-hw-sn').innerText            = fmt(item.hardware?.hw_serial_number);

        document.getElementById('d-cso-name').innerText        = fmt(item.cso?.name);
        document.getElementById('d-teknisi-name').innerText    = fmt(item.teknisi?.name);
        document.getElementById('d-teknisi-wa').innerText      = fmt(item.teknisi?.phone_number);
        document.getElementById('d-created-at').innerText      = fmt(item.created_at);
        document.getElementById('d-respon-cso').innerText      = fmt(item.waktu_respon_cso);
        document.getElementById('d-respon-teknisi').innerText  = fmt(item.waktu_respon_teknisi);
        document.getElementById('d-perjalanan').innerText      = fmt(item.waktu_perjalanan);
        document.getElementById('d-tiba').innerText            = fmt(item.waktu_tiba);
        document.getElementById('d-pengerjaan').innerText      = fmt(item.waktu_pengerjaan);
        document.getElementById('d-selesai').innerText         = fmt(item.waktu_selesai);

        // Ulasan
        const ulasanCard = document.getElementById('d-ulasan-card');
        if (item.ulasan) {
            ulasanCard.classList.remove('hidden');
            document.getElementById('d-rating-cso').innerHTML    = ratingStars(item.ulasan.rating_cso);
            document.getElementById('d-ulasan-cso').innerText    = fmt(item.ulasan.ulasan_cso);
            document.getElementById('d-rating-teknisi').innerHTML = ratingStars(item.ulasan.rating_teknisi);
            document.getElementById('d-ulasan-teknisi').innerText = fmt(item.ulasan.ulasan_teknisi);
        } else {
            ulasanCard.classList.add('hidden');
        }

        const logsBody = document.getElementById('d-logs');
        logsBody.innerHTML = '';
        (item.logs ?? []).forEach(log => {
            const tr = document.createElement('tr');
            [log.user?.name ?? '-', log.message, log.created_at].forEach(text => {
                const td = document.createElement('td');
                td.className = 'py-2 px-3 text-gray-700 dark:text-gray-300 align-top';
                td.innerText = text ?? '-';
                tr.appendChild(td);
            });
            logsBody.appendChild(tr);
        });
    };
</script>
@endsection