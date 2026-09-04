import { DataTable } from "simple-datatables";

const STORAGE_KEY = "dt-customer-state";

// State posisi DataTable (page/search/perPage) disimpan ke sessionStorage agar
// tetap bertahan saat pindah ke halaman detail lalu kembali (back), sehingga
// pagination tidak reset ke halaman 1.
const readState = () => {
    try {
        return JSON.parse(sessionStorage.getItem(STORAGE_KEY) || "null");
    } catch {
        return null;
    }
};

const writeState = (state) => {
    try {
        sessionStorage.setItem(STORAGE_KEY, JSON.stringify(state));
    } catch {
        /* sessionStorage penuh / tak tersedia — abaikan */
    }
};

// Baca posisi dari DOM (input search, tombol pager aktif, select perPage)
const collectState = (wrapper) => {
    const input = wrapper.querySelector(".datatable-input");
    const active = wrapper.querySelector(
        ".datatable-pagination-list-item.datatable-active .datatable-pagination-list-item-link"
    );
    const select = wrapper.querySelector(".datatable-selector");
    return {
        search: input ? input.value : "",
        page: active ? parseInt(active.textContent, 10) || 1 : 1,
        perPage: select ? parseInt(select.value, 10) || 25 : 25,
    };
};

const dataTable = [document.querySelector("#tableCustomer")];

dataTable.forEach((table) => {
    if (table) {
        const instance = new DataTable(table, {
            perPage: 25,
            perPageSelect: [5, 10, 25, 50, 100],
            columns: [
                {
                    select: 0,
                    sortable: false,
                    searchable: false,
                },
                {
                    select: 6,
                    render: function (data, cell, row) {
                        const shortAddress =
                            data[0].data.length > 50
                                ? data[0].data.substring(0, 50) + "..."
                                : data[0].data;

                        return `<span title="${data[0].data}">${shortAddress}</span>`;
                    },
                },
                {
                    select: 7,
                    sortable: false,
                },
            ],
            template: (options, dom) => `
                <a href="/admin/master-data/customer/create"
            class="px-3 py-1 bg-blue-500 text-white rounded-md hover:bg-blue-600">Add Customer</a>
            
            <div class='${options.classes.top} mt-5'>
            ${
                options.paging && options.perPageSelect
                    ? `<div class='${options.classes.dropdown}'>
                    <label>
                        <select class='${options.classes.selector}'></select> ${options.labels.perPage}
                    </label>
                </div>`
                    : ""
            }
            ${
                options.searchable
                    ? `<div class='${options.classes.search}'>
                    <input class='${options.classes.input}' placeholder='${
                          options.labels.placeholder
                      }' type='search' title='${options.labels.searchTitle}'${
                          dom.id ? ` aria-controls="${dom.id}"` : ""
                      }>
                </div>`
                    : ""
            }
        </div>
        <div class='${options.classes.container}'${
                options.scrollY.length
                    ? ` style='height: ${options.scrollY}; overflow-Y: auto;'`
                    : ""
            }></div>
        <div class='${options.classes.bottom}'>
            ${
                options.paging
                    ? `<div class='${options.classes.info}'></div>`
                    : ""
            }
            <nav class='${options.classes.pagination}'></nav>
        </div>`,
        });

        const persistNow = () => writeState(collectState(instance.wrapperDOM || table.parentElement));

        // simpan tiap interaksi yang mengubah posisi daftar
        instance.on("datatable.page", persistNow);
        instance.on("datatable.sort", persistNow);
        instance.on("datatable.search", persistNow);
        instance.on("datatable.perpage", persistNow);

        // pulihkan posisi terakhir setelah tabel siap dirender
        instance.on("datatable.init", () => {
            const saved = readState();
            if (!saved) return;
            const wrapper = instance.wrapperDOM || table.parentElement;
            const input = wrapper.querySelector(".datatable-input");
            const select = wrapper.querySelector(".datatable-selector");

            if (saved.perPage && select && select.value != saved.perPage) {
                select.value = saved.perPage;
            }
            if (saved.search && input) {
                input.value = saved.search;
                instance.search(saved.search);
            }
            if (saved.page > 1) {
                instance.page(saved.page);
            }
        });
    }
});

document.addEventListener("DOMContentLoaded", function () {
    const getAllNameNumber = document.querySelectorAll(".number");

    getAllNameNumber.forEach((element) => {
        element.addEventListener("input", function (e) {
            const regex = /[^0-9]/g;
            e.target.value = e.target.value.replace(regex, "");
        });
    });

    const map = L.map("map").setView([-6.2088, 106.8456], 13);

    L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
        attribution: "© OpenStreetMap contributors",
    }).addTo(map);

    let marker;

    document.getElementById("latitude").oninput = function (e) {
        if (marker) {
            map.removeLayer(marker);
        }

        if (e.target.value && document.getElementById("longitude").value) {
            marker = L.marker([
                e.target.value,
                document.getElementById("longitude").value,
            ]).addTo(map);

            fetch(
                `https://nominatim.openstreetmap.org/reverse?lat=${
                    e.target.value
                }&lon=${document.getElementById("longitude").value}&format=json`
            )
                .then((response) => response.json())
                .then((data) => {
                    document.getElementById("customer_address").value =
                        data.display_name || "";
                })
                .catch((error) => console.error("Error:", error));

            map.setView([
                e.target.value,
                document.getElementById("longitude").value,
            ]);
        }
    };

    document.getElementById("longitude").oninput = function (e) {
        if (marker) {
            map.removeLayer(marker);
        }

        if (e.target.value && document.getElementById("latitude").value) {
            marker = L.marker([
                document.getElementById("latitude").value,
                e.target.value,
            ]).addTo(map);

            fetch(
                `https://nominatim.openstreetmap.org/reverse?lat=${
                    document.getElementById("latitude").value
                }&lon=${e.target.value}&format=json`
            )
                .then((response) => response.json())
                .then((data) => {
                    document.getElementById("customer_address").value =
                        data.display_name || "";
                })
                .catch((error) => console.error("Error:", error));

            map.setView([
                document.getElementById("latitude").value,
                e.target.value,
            ]);
        }
    };

    if (
        document.getElementById("latitude").value &&
        document.getElementById("longitude").value
    ) {
        marker = L.marker([
            document.getElementById("latitude").value,
            document.getElementById("longitude").value,
        ]).addTo(map);
        map.setView([
            document.getElementById("latitude").value,
            document.getElementById("longitude").value,
        ]);
        // get url path
        const url = new URL(window.location.href);
        if (url.pathname.includes("edit")) {
            map.on("click", function (e) {
                const lat = e.latlng.lat;
                const lng = e.latlng.lng;

                if (marker) {
                    map.removeLayer(marker);
                }

                marker = L.marker([lat, lng]).addTo(map);

                fetch(
                    `https://nominatim.openstreetmap.org/reverse?lat=${lat}&lon=${lng}&format=json`
                )
                    .then((response) => response.json())
                    .then((data) => {
                        marker
                            .bindPopup(
                                data.display_name || "Alamat tidak ditemukan"
                            )
                            .openPopup();

                        document.getElementById("latitude").value = lat;
                        document.getElementById("longitude").value = lng;

                        document.getElementById("customer_address").value =
                            data.display_name || "";
                    })
                    .catch((error) => console.error("Error:", error));
            });
        }
    } else {
        map.on("click", function (e) {
            const lat = e.latlng.lat;
            const lng = e.latlng.lng;

            if (marker) {
                map.removeLayer(marker);
            }

            marker = L.marker([lat, lng]).addTo(map);

            fetch(
                `https://nominatim.openstreetmap.org/reverse?lat=${lat}&lon=${lng}&format=json`
            )
                .then((response) => response.json())
                .then((data) => {
                    console.log(data);
                    // Tampilkan alamat di marker popup
                    marker
                        .bindPopup(
                            data.display_name || "Alamat tidak ditemukan"
                        )
                        .openPopup();

                    document.getElementById("latitude").value = lat;
                    document.getElementById("longitude").value = lng;

                    document.getElementById("customer_address").value =
                        data.display_name || "";
                })
                .catch((error) => console.error("Error:", error));
        });
    }
});
