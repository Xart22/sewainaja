import { DataTable } from "simple-datatables";

const dataTable = [document.querySelector("#tableHardware")];

dataTable.forEach((table) => {
    if (table) {
        new DataTable(table, {
            template: (options, dom) => `
            <div class='flex space-x-2'>
             <a href="/master-data/hardware/create" class="block text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
            Add Hardware
            </a>
            <button data-modal-target="default-modal" data-modal-toggle="default-modal" class="block text-white bg-green-500 hover:bg-green-600 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800" type="button">
            Copy Hardware
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
    }
});
