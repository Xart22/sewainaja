import "./bootstrap";
import "flowbite";

import { DataTable } from "simple-datatables";

let tablePermohonanBaru = document.querySelector("#tablePermohonanBaru");
let tablePermohonanSelesai = document.querySelector("#tablePermohonanSelesai");

if (tablePermohonanBaru) {
    tablePermohonanBaru = new DataTable("#tablePermohonanBaru", {
        labels: {
            placeholder: "Cari...",

            noRows: "Data tidak ditemukan",
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
    tablePermohonanSelesai = new DataTable("#tablePermohonanSelesai", {
        labels: {
            placeholder: "Cari...",

            noRows: "Data tidak ditemukan",
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

const getData = async (tablePermohonanBaru, tablePermohonanSelesai) => {
    const count = document.querySelector("#countNotif").innerHTML;
    const countNotif = document.querySelector("#countNotif");
    const notifSound = document.querySelector("#notifSound");
    const response = await fetch("/api/customer-support");
    const data = await response.json();
    const newData = [];
    const title = document.querySelector("#title");
    title.innerHTML = `Permohonan Baru (${data.count})`;
    data.data.map((permohonan) => {
        const date = new Date(permohonan.created_at);
        const formattedDate = `${date.getDate()}-${date.getMonth()}-${date.getFullYear()} ${date.getHours()}:${date.getMinutes()}:${date.getSeconds()}`;
        const action = `
       <div class="flex justify-start space-x-2">
                            <a href=""
                                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-full">
                                Detail
                            </a>
                            <a href="/customer-support/send/${permohonan.id}" target="_blank"
                                class="bg-green-300 hover:bg-green-400 text-white font-bold py-2 px-4 rounded-full">
                                WhatsApp
                            </a>
                            
                        </div>

        `;
        const status =
            permohonan.status === "Responded"
                ? `<p class="bg-green-500 text-white font-bold py-1 p-3 rounded-full">${permohonan.status}</p>`
                : `<span class="bg-red-500 text-white font-bold py-1 px-2 rounded-full">${permohonan.status}</span>`;
        newData.push([
            permohonan.no_ticket,
            formattedDate,
            permohonan.nama_pelapor,
            permohonan.no_wa_pelapor,
            permohonan.keperluan,
            status,
            permohonan.cso ? permohonan.cso.name : "",
            action,
        ]);
    });
    countNotif.innerHTML = data.count;

    if (count < data.count) {
        notifSound.play();
    }
    if (tablePermohonanBaru && tablePermohonanSelesai) {
        tablePermohonanBaru.data.data = [];

        tablePermohonanBaru.insert({ data: newData });
    }
};

getData(tablePermohonanBaru, tablePermohonanSelesai);

setInterval(() => {
    getData(tablePermohonanBaru, tablePermohonanSelesai);
}, 5000);
