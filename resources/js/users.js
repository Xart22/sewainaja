import { DataTable } from "simple-datatables";

const dataTable = [document.querySelector("#tableUser")];

dataTable.forEach((table) => {
    if (table) {
        new DataTable(table, {
            template: (options, dom) => `
                <a href="/admin/manage-user/create"
            class="px-3 py-1 bg-blue-500 text-white rounded-md hover:bg-blue-600">Add User</a>
            
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
