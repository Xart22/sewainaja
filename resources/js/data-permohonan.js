import { DataTable } from "simple-datatables";

document.addEventListener("DOMContentLoaded", () => {
    let tableData = document.querySelector("#tableData");

    if (tableData) {
        tableData = new DataTable("#tableData", {
            searchable: true,
            fixedHeight: true,
            template: (options, dom) => `
        <input type="date" class="border border-gray-300 rounded-md p-1" id="dateFrom" placeholder="From">
        <input type="date" class="border border-gray-300 rounded-md p-1" id="dateTo" placeholder="To">
        <a class="px-3 py-1 bg-blue-500 text-white rounded-md hover:bg-blue-600" id="btnFilter">Filter</a>
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
        </div>`,
        });

        // Tambahkan event listener untuk input tanggal
        document
            .getElementById("dateFrom")
            .addEventListener("change", filterDate);
        document
            .getElementById("dateTo")
            .addEventListener("change", filterDate);
    }

    // Fungsi filterDate
});
const filterDate = () => {
    let dateFrom = document.getElementById("dateFrom").value;
    let dateTo = document.getElementById("dateTo").value;

    console.log(dateFrom, dateTo); // Debugging: Tampilkan nilai input tanggal di konsol
};
