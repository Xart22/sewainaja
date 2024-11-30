import "./bootstrap";
import "flowbite";

import { DataTable } from "simple-datatables";

document.addEventListener("DOMContentLoaded", function () {
    let tablePermohonanBaru = document.querySelector("#tablePermohonanBaru");
    let tablePermohonanSelesai = document.querySelector(
        "#tablePermohonanSelesai"
    );

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
            paging: true,
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
            paging: true,
        });
    }

    const getData = async (tablePermohonanBaru, tablePermohonanSelesai) => {
        const count = document.querySelector("#countNotif").innerHTML;
        const countNotif = document.querySelector("#countNotif");
        const notifSound = document.querySelector("#notifSound");
        const response = await fetch("/api/customer-support");
        const data = await response.json();
        const newData = [];
        const dataDone = [];
        const title = document.querySelector("#title");
        title.innerHTML = `Permohonan Baru (${data.count})`;
        data.data.map((permohonan) => {
            const action = `
                    <div class="flex justify-start space-x-2">
                        ${
                            permohonan.status_cso === "Responded"
                                ? `<button onclick='tes(${JSON.stringify(
                                      permohonan
                                  )})' class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-full" type="button">
                                Detail
                            </button>`
                                : ""
                        }
                            <a href="/customer-support/send/${
                                permohonan.id
                            }" target="_blank"
                                class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-full">
                                WhatsApp
                            </a>
                            
                    </div>

        `;
            const statusCso =
                permohonan.status_cso === "Responded"
                    ? `<span class="bg-green-500 text-white font-bold py-1 p-3 rounded-full">${permohonan.status_cso}</span>`
                    : `<span class="bg-red-500 text-white font-bold py-1 px-2 rounded-full">${permohonan.status_cso}</span>`;
            let statusteknisi = "";
            if (permohonan.status_teknisi !== null) {
                if (permohonan.status_teknisi === "Waiting") {
                    statusteknisi = `<span class="bg-yellow-500 text-white font-bold py-1 p-3 rounded-full">${permohonan.status_teknisi}</span>`;
                }
                if (permohonan.status_teknisi === "Accepted") {
                    statusteknisi = `<span class="bg-green-500 text-white font-bold py-1 p-3 rounded-full">${permohonan.status_teknisi}</span>`;
                }
                if (permohonan.status_teknisi === "On The Way") {
                    statusteknisi = `<span class="bg-blue-500 text-white font-bold py-1 p-3 rounded-full">${permohonan.status_teknisi}</span>`;
                }

                if (permohonan.status_teknisi === "Arrived") {
                    statusteknisi = `<span class="bg-black text-white font-bold py-1 p-3 rounded-full">${permohonan.status_teknisi}</span>`;
                }

                if (permohonan.status_teknisi === "Done") {
                    statusteknisi = `<span class="bg-green-500 text-white font-bold py-1 p-3 rounded-full">${permohonan.status_teknisi}</span>`;
                }
                if (permohonan.status_teknisi === "Working") {
                    statusteknisi = `<span class="bg-blue-500 text-white font-bold py-1 p-3 rounded-full">${permohonan.status_teknisi}</span>`;
                }
            }

            newData.push([
                permohonan.no_ticket,
                permohonan.created_at,
                permohonan.nama_pelapor,
                permohonan.no_wa_pelapor,
                permohonan.keperluan,
                statusCso,
                permohonan.cso ? permohonan.cso.name : "",
                permohonan.teknisi ? permohonan.teknisi.name : "",
                statusteknisi,
                permohonan.status_process ?? "",
                action,
            ]);
        });
        countNotif.innerHTML = data.count;

        if (count == "") {
        } else if (count < data.count) {
            notifSound.play();
        }

        data.done.map((permohonan) => {
            const action = `
                    <div class="flex justify-start space-x-2">
                       <button onclick='tes(${JSON.stringify(
                           permohonan
                       )})' class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-full" type="button">
                                Detail
                            </button>   
                    </div>

        `;
            const statusCso =
                permohonan.status_cso === "Responded"
                    ? `<span class="bg-green-500 text-white font-bold py-1 p-3 rounded-full">${permohonan.status_cso}</span>`
                    : `<span class="bg-red-500 text-white font-bold py-1 px-2 rounded-full">${permohonan.status_cso}</span>`;
            let statusteknisi = "";
            if (permohonan.status_teknisi !== null) {
                if (permohonan.status_teknisi === "Waiting") {
                    statusteknisi = `<span class="bg-yellow-500 text-white font-bold py-1 p-3 rounded-full">${permohonan.status_teknisi}</span>`;
                }
                if (permohonan.status_teknisi === "Accepted") {
                    statusteknisi = `<span class="bg-green-500 text-white font-bold py-1 p-3 rounded-full">${permohonan.status_teknisi}</span>`;
                }
                if (permohonan.status_teknisi === "On The Way") {
                    statusteknisi = `<span class="bg-blue-500 text-white font-bold py-1 p-3 rounded-full">${permohonan.status_teknisi}</span>`;
                }

                if (permohonan.status_teknisi === "Arrived") {
                    statusteknisi = `<span class="bg-black text-white font-bold py-1 p-3 rounded-full">${permohonan.status_teknisi}</span>`;
                }

                if (permohonan.status_teknisi === "Done") {
                    statusteknisi = `<span class="bg-green-500 text-white font-bold py-1 p-3 rounded-full">${permohonan.status_teknisi}</span>`;
                }
                if (permohonan.status_teknisi === "Working") {
                    statusteknisi = `<span class="bg-blue-500 text-white font-bold py-1 p-3 rounded-full">${permohonan.status_teknisi}</span>`;
                }
            }

            dataDone.push([
                permohonan.no_ticket,
                permohonan.created_at,
                permohonan.nama_pelapor,
                permohonan.no_wa_pelapor,
                permohonan.keperluan,
                statusCso,
                permohonan.cso ? permohonan.cso.name : "",
                permohonan.teknisi ? permohonan.teknisi.name : "",
                statusteknisi,
                permohonan.status_process ?? "",
                action,
            ]);
        });

        if (tablePermohonanBaru && tablePermohonanSelesai) {
            tablePermohonanBaru.data.data = [];

            tablePermohonanBaru.insert({ data: newData });
            tablePermohonanSelesai.data.data = [];
            tablePermohonanSelesai.insert({ data: dataDone });
        }
    };

    getData(tablePermohonanBaru, tablePermohonanSelesai);

    const notifContainer = document.getElementById("notification-container");
    window.Echo.channel("support-request").listen("RequestSupport", (event) => {
        if (event.status === "Baru") {
            const templateNewData = `
            <div id="${event.customerSupport.no_ticket}"
                  class="flex items-center w-full max-w-xs p-4 mb-4 text-gray-500 bg-white rounded-lg shadow dark:text-gray-400 dark:bg-gray-800"
                  role="alert">
                  <div
                      class="inline-flex items-center justify-center flex-shrink-0 w-8 h-8 text-green-500 bg-green-100 rounded-lg dark:bg-green-800 dark:text-green-200">
                      <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                          viewBox="0 0 20 20">
                          <path
                              d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5Zm3.707 8.207-4 4a1 1 0 0 1-1.414 0l-2-2a1 1 0 0 1 1.414-1.414L9 10.586l3.293-3.293a1 1 0 0 1 1.414 1.414Z" />
                      </svg>
                      <span class="sr-only">Check icon</span>
                  </div>
                  <div class="ms-3 text-sm font-normal">Permohonan baru telah masuk. <br> NO TIKET: ${event.customerSupport.no_ticket}</div>
                  <button type="button"
                      class="ms-auto -mx-1.5 -my-1.5 bg-white text-gray-400 hover:text-gray-900 rounded-lg focus:ring-2 focus:ring-gray-300 p-1.5 hover:bg-gray-100 inline-flex items-center justify-center h-8 w-8 dark:text-gray-500 dark:hover:text-white dark:bg-gray-800 dark:hover:bg-gray-700"
                      data-dismiss-target="#${event.customerSupport.no_ticket}" aria-label="Close">
                      <span class="sr-only">Close</span>
                      <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                          viewBox="0 0 14 14">
                          <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                      </svg>
                  </button>
              </div>`;
            notifContainer.innerHTML = templateNewData;
        } else {
            const templateNewData = `
            <div id="${event.customerSupport.no_ticket}"
                  class="flex items-center w-full max-w-xs p-4 mb-4 text-gray-500 bg-white rounded-lg shadow dark:text-gray-400 dark:bg-gray-800"
                  role="alert">
                  <div
                      class="inline-flex items-center justify-center flex-shrink-0 w-8 h-8 text-green-500 bg-green-100 rounded-lg dark:bg-green-800 dark:text-green-200">
                      <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                          viewBox="0 0 20 20">
                          <path
                              d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5Zm3.707 8.207-4 4a1 1 0 0 1-1.414 0l-2-2a1 1 0 0 1 1.414-1.414L9 10.586l3.293-3.293a1 1 0 0 1 1.414 1.414Z" />
                      </svg>
                      <span class="sr-only">Check icon</span>
                  </div>
                  <div class="ms-3 text-sm font-normal">Status permohonan telah berubah. <br> NO TIKET: ${event.customerSupport.no_ticket}</div>
                  <button type="button"
                      class="ms-auto -mx-1.5 -my-1.5 bg-white text-gray-400 hover:text-gray-900 rounded-lg focus:ring-2 focus:ring-gray-300 p-1.5 hover:bg-gray-100 inline-flex items-center justify-center h-8 w-8 dark:text-gray-500 dark:hover:text-white dark:bg-gray-800 dark:hover:bg-gray-700"
                      data-dismiss-target="#${event.customerSupport.no_ticket}" aria-label="Close">
                      <span class="sr-only">Close</span>
                      <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                          viewBox="0 0 14 14">
                          <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                      </svg>
                  </button>
              </div>`;
            notifContainer.innerHTML = templateNewData;
            const notifSound = document.querySelector("#notifSound");
            notifSound.play();
        }
        getData(tablePermohonanBaru, tablePermohonanSelesai);
    });
});
