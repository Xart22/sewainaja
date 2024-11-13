import { DataTable } from "simple-datatables";

const dataTable = [
    document.querySelector("#tablePermohonanBaru"),
    document.querySelector("#tablePermohonanSelesai"),
];

dataTable.forEach((table) => {
    if (table) {
        new DataTable(table);
    }
});
