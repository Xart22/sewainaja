(() => {
    const init = () => {
        const table = document.querySelector("table[data-bulk-select]");
        if (!table || table.dataset.bulkReady) {
            return;
        }
        table.dataset.bulkReady = "1";

        const selected = new Set(); // id terpilih lintas halaman
        const counter = document.getElementById("bulk-selected-count");
        const toolbar = document.getElementById("bulk-toolbar");
        const actions = toolbar ? toolbar.querySelectorAll("button[data-bulk-action]") : [];

        const syncUi = () => {
            if (counter) {
                counter.textContent = String(selected.size);
            }
            if (toolbar) {
                toolbar.classList.toggle("hidden", selected.size === 0);
            }
            actions.forEach((btn) => {
                btn.disabled = selected.size === 0;
            });
        };

        // sinkronkan checkbox baris yang terlihat (halaman aktif) + state select-all
        const refreshVisibleCheckboxes = () => {
            table.querySelectorAll("tbody .row-select").forEach((cb) => {
                cb.checked = selected.has(cb.value);
            });
            const sa = table.querySelector(".select-all");
            if (sa) {
                const visible = Array.from(table.querySelectorAll("tbody .row-select"));
                const visibleChecked = visible.filter((cb) => cb.checked).length;
                sa.checked = visible.length > 0 && visibleChecked === visible.length;
                sa.indeterminate = visibleChecked > 0 && visibleChecked < visible.length;
            }
        };

        // native checkbox men-toggle sendiri; cukup dengarkan "change" (bubble).
        // DataTable sortir pada kolom checkbox sudah dimatikan (sortable:false),
        // jadi tidak ada <button class="datatable-sorter"> yang memakan klik.
        table.addEventListener("change", (e) => {
            const cb = e.target.closest(".row-select");
            const sa = e.target.closest(".select-all");
            if (cb) {
                if (cb.checked) {
                    selected.add(cb.value);
                } else {
                    selected.delete(cb.value);
                }
                refreshVisibleCheckboxes();
                syncUi();
                return;
            }
            if (sa) {
                table.querySelectorAll("tbody .row-select").forEach((rowCb) => {
                    if (sa.checked) {
                        selected.add(rowCb.value);
                    } else {
                        selected.delete(rowCb.value);
                    }
                });
                refreshVisibleCheckboxes();
                syncUi();
            }
        });

        // form submit: inject hidden ids[]
        const injectIds = (form) => {
            form.querySelectorAll('input[name="ids[]"]').forEach((el) => el.remove());
            selected.forEach((id) => {
                const input = document.createElement("input");
                input.type = "hidden";
                input.name = "ids[]";
                input.value = id;
                form.appendChild(input);
            });
        };

        actions.forEach((btn) => {
            btn.addEventListener("click", () => {
                const form = document.getElementById(btn.dataset.bulkForm);
                if (!form || selected.size === 0) {
                    return;
                }
                if (btn.dataset.bulkConfirm && !confirm(btn.dataset.bulkConfirm)) {
                    return;
                }
                injectIds(form);
                form.submit();
            });
        });

        // DataTable render ulang tbody saat paging/cari -> sinkron checkbox
        const observer = new MutationObserver(() => refreshVisibleCheckboxes());
        observer.observe(table.querySelector("tbody"), { childList: true, subtree: true });

        syncUi();
    };

    if (document.readyState === "loading") {
        document.addEventListener("DOMContentLoaded", init);
    } else {
        init();
    }
})();
