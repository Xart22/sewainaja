import { DataTable } from "simple-datatables";

const STORAGE_KEY = "dt-hardware-state";

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

const dataTable = [document.querySelector("#tableHardware")];

dataTable.forEach((table) => {
    if (table) {
        const instance = new DataTable(table, {
            perPage: 25,
            perPageSelect: [5, 10, 25, 50, 100],
            columns: [{ select: 0, sortable: false, searchable: false }],
            template: (options, dom) => `
            <div class='flex space-x-2'>
             <a href="/admin/master-data/hardware/create" class="block text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
            Add Hardware
            </a>
            <button data-modal-target="default-modal" data-modal-toggle="default-modal" class="block text-white bg-green-500 hover:bg-green-600 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800" type="button">
            Copy Hardware
            </button>
            <button data-modal-target="import-modal" data-modal-toggle="import-modal" class="block text-white bg-orange-500 hover:bg-orange-600 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800" type="button">
            Import Hardware
            </button>
            </div>
           
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
