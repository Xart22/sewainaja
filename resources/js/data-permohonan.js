import { DataTable } from "simple-datatables";

document.addEventListener("DOMContentLoaded", function () {
    let tableData = document.querySelector("#tableData");

    const dataSet = data.map((item) => {
        const action = `
        <div class="flex justify-start space-x-2">
           <button onclick='detail(${JSON.stringify(
               item
           )})' class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-full" type="button">
                    Detail
                </button>   
        </div>

`;
        return [
            item.no_ticket,
            item.created_at,
            item.nama_pelapor,
            item.no_wa_pelapor,
            item.keperluan,
            item.status_cso,
            item.cso ? item.cso.name : "-",
            item.teknisi ? item.teknisi.name : "-",
            item.status_teknisi,
            item.status_process,
            item.created_at,
            item.waktu_respon_cso,
            item.waktu_respon_teknisi,
            item.waktu_perjalanan,
            item.waktu_tiba,
            item.waktu_pengerjaan,
            item.waktu_selesai,
            action,
        ];
    });
    if (tableData) {
        tableData = new DataTable("#tableData");
    }
    tableData.insert({ data: dataSet });
    const filterDate = () => {
        let dateFrom = document.getElementById("dateFrom").value;
        let dateTo = document.getElementById("dateTo").value;
        window.location.href = `/admin/data-permohonan/${dateFrom}/${dateTo}`;
    };
    const start = document.getElementById("start_date");
    const end = document.getElementById("end_date");

    const dateFrom = document.getElementById("dateFrom");
    const dateTo = document.getElementById("dateTo");
    //set default value
    dateFrom.value = start.value;
    dateTo.value = end.value;
    const btnFilter = document.getElementById("btnFilter");

    if (btnFilter) {
        btnFilter.addEventListener("click", filterDate);
    }

    const exportData = () => {
        window.location.href = `/admin/data-permohonan/export`;
    };
});
