import "./bootstrap";
import "flowbite";

import { DataTable } from "simple-datatables";

let tablePermohonanBaru = document.querySelector("#tablePermohonanBaru");
let tablePermohonanSelesai = document.querySelector("#tablePermohonanSelesai");

if (tablePermohonanBaru) {
    tablePermohonanBaru = new DataTable("#tablePermohonanBaru", {
        labels: {
            placeholder: "Cari...",
            perPage: "{select} baris per halaman",
            noRows: "Data tidak ditemukan",
            info: "Menampilkan {start} sampai {end} dari {rows} baris",
        },
        sortable: true,
        columns: [
            {
                select: 0,
                type: "string",
                sortable: true,
            },
        ],
    });
}

if (tablePermohonanSelesai) {
    tablePermohonanSelesai = new DataTable("#tablePermohonanSelesai");
}

const dataPermohonanBaru = [];
const dataPermohonanSelesai = [];
const getData = async (tablePermohonanBaru, tablePermohonanSelesai) => {
    const response = await fetch("/api/customer-support");
    const data = await response.json();
    const newData = [];
    data.data.map((permohonan) => {
        if (permohonan.status === "Waiting") {
            const date = new Date(permohonan.created_at);
            //format date to dd-mm-yyyy hh:mm:ss
            const formattedDate = `${date.getDate()}-${date.getMonth()}-${date.getFullYear()} ${date.getHours()}:${date.getMinutes()}:${date.getSeconds()}`;
            newData.push([
                permohonan.no_ticket,
                formattedDate,
                permohonan.nama_pelapor,
                permohonan.no_wa_pelapor,
                permohonan.keperluan,
                permohonan.status,
                "aw",
            ]);
        }
    });

    dataPermohonanBaru.push(newData);

    tablePermohonanBaru.rows.remove();
    tablePermohonanBaru.insert({ data: newData });
};

getData(tablePermohonanBaru, tablePermohonanSelesai);

// setInterval(() => {
//     getData(tablePermohonanBaru, tablePermohonanSelesai);
// }, 5000);
