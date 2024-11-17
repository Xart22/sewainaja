import { DataTable } from "simple-datatables";

const dataTable = [document.querySelector("#tableCustomer")];

dataTable.forEach((table) => {
    if (table) {
        new DataTable(table, {
            columns: [
                {
                    select: 5,
                    render: function (data, cell, row) {
                        const shortAddress =
                            data[0].data.length > 50
                                ? data[0].data.substring(0, 50) + "..."
                                : data;
                        return `<span title="${data[0].data}">${shortAddress}</span>`;
                    },
                },
                {
                    select: 6,
                    sortable: false,
                },
            ],
            template: (options, dom) => `
                <a href="/master-data/customer/create"
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
