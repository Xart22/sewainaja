import { DataTable } from "simple-datatables";

// Format datetime DB (ISO `YYYY-MM-DDTHH:MM:SS` atau `YYYY-MM-DD HH:MM:SS`) ke
// `DD/MM/YYYY HH:MM`. Nilai non-tanggal (mis. "45 menit") ditampilkan apa adanya.
const fmtDateTime = (v) => {
    if (v === null || v === undefined || v === '') return '-';
    const s = String(v).replace('T', ' ').trim();
    // nilai ber-zona (contoh "2026-08-13 08:33:30+00:00" / "...Z") = UTC, ubah ke zona lokal
    if (/Z$|[+-]\d{2}:\d{2}$/.test(s)) {
        const d = new Date(s);
        if (!isNaN(d)) {
            const p = (n) => String(n).padStart(2, '0');
            return p(d.getDate()) + '/' + p(d.getMonth() + 1) + '/' + d.getFullYear() + ' ' + p(d.getHours()) + ':' + p(d.getMinutes());
        }
    }
    const m = s.match(/^(\d{4})-(\d{2})-(\d{2})[ ](\d{2}):(\d{2})/);
    if (!m) return s;
    return m[3] + '/' + m[2] + '/' + m[1] + ' ' + m[4] + ':' + m[5];
};

const statusBadgeCell = (status) => {
    if (!status) return '-';
    const map = {
        'Waiting':    'bg-yellow-100 text-yellow-700',
        'Responded':  'bg-blue-100 text-blue-700',
        'Accepted':   'bg-green-100 text-green-700',
        'On The Way': 'bg-indigo-100 text-indigo-700',
        'Arrived':    'bg-purple-100 text-purple-700',
        'Working':    'bg-orange-100 text-orange-700',
        'On Hold':    'bg-amber-100 text-amber-700',
        'Repairing':  'bg-orange-100 text-orange-700',
        'Done':       'bg-green-600 text-white',
        'Open':       'bg-red-100 text-red-700',
        'Closed':     'bg-gray-700 text-white',
    };
    const cls = map[status] ?? 'bg-gray-100 text-gray-700';
    return `<span class="inline-block px-2 py-0.5 rounded-full text-xs font-semibold ${cls}">${status}</span>`;
};

const nullOrDash = (val) => (val !== null && val !== undefined && val !== '') ? val : '-';

document.addEventListener("DOMContentLoaded", function () {
    const tableEl = document.querySelector("#tableData");
    if (!tableEl) return;

    const dataSet = data.map((item) => {
        const action = `<div class="flex justify-start">
            <button onclick='detail(${JSON.stringify(item).replace(/'/g, "&#39;")})' 
                class="bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold py-1.5 px-3 rounded-lg" type="button">
                Detail
            </button>
        </div>`;

        return [
            nullOrDash(item.no_ticket),
            fmtDateTime(item.created_at),
            nullOrDash(item.nama_pelapor),
            nullOrDash(item.no_wa_pelapor),
            nullOrDash(item.keperluan),
            statusBadgeCell(item.status_cso),
            item.cso ? item.cso.name : '-',
            item.teknisi ? item.teknisi.name : '-',
            statusBadgeCell(item.status_teknisi),
            statusBadgeCell(item.status_process),
            fmtDateTime(item.created_at),
            fmtDateTime(item.waktu_respon_cso),
            fmtDateTime(item.waktu_respon_teknisi),
            nullOrDash(item.waktu_perjalanan),
            fmtDateTime(item.waktu_tiba),
            nullOrDash(item.waktu_pengerjaan),
            fmtDateTime(item.waktu_selesai),
            nullOrDash(item.resolution_time),
            action,
        ];
    });

    const table = new DataTable("#tableData");
    table.insert({ data: dataSet });

    // Set default date values from hidden inputs
    const start   = document.getElementById("start_date");
    const end     = document.getElementById("end_date");
    const dateFrom = document.getElementById("dateFrom");
    const dateTo   = document.getElementById("dateTo");
    if (start && dateFrom) dateFrom.value = start.value;
    if (end && dateTo)     dateTo.value   = end.value;

    // Filter handler
    const filterDate = () => {
        const from = dateFrom.value;
        const to   = dateTo.value;
        if (!from || !to) {
            alert('Pilih rentang tanggal terlebih dahulu.');
            return;
        }
        window.location.href = `/admin/data-permohonan/${from}/${to}`;
    };

    const btnFilter = document.getElementById("btnFilter");
    if (btnFilter) {
        btnFilter.addEventListener("click", filterDate);
    }
});
