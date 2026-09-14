// Helper global untuk download file (GET/POST) dengan loading state di tombol.
// Dipakai halaman hardware (export Excel & QR PDF). Tanpa dependensi.
window.FileDownload = (() => {
    const SPINNER =
        '<svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">' +
        '<circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>' +
        '<path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>' +
        "</svg>";

    function setLoading(btn, loading, text) {
        if (loading) {
            if (!btn || btn.dataset.loading === "1") return false;
            btn.dataset.loading = "1";
            btn.dataset.origHtml = btn.innerHTML;
            btn.setAttribute("disabled", "disabled");
            btn.classList.add("opacity-75", "cursor-wait");
            btn.innerHTML = SPINNER + "<span>" + text + "</span>";
            return true;
        }
        if (!btn) return;
        btn.dataset.loading = "";
        btn.removeAttribute("disabled");
        btn.classList.remove("opacity-75", "cursor-wait");
        if (btn.dataset.origHtml !== undefined) {
            btn.innerHTML = btn.dataset.origHtml;
        }
    }

    function filenameFrom(res, fallback) {
        const cd = res.headers.get("Content-Disposition") || "";
        const m =
            cd.match(/filename\*=UTF-8''([^;]+)/i) ||
            cd.match(/filename="?([^";]+)"?/i);
        if (!m) return fallback;
        try {
            return decodeURIComponent(m[1]);
        } catch {
            return m[1];
        }
    }

    async function download({ btn, url, method = "GET", body = null, loadingText = "Menyiapkan...", fallbackName = "download" }) {
        if (!setLoading(btn, true, loadingText)) return;
        try {
            const res = await fetch(url, { method, body });
            if (!res.ok) throw new Error("HTTP " + res.status);
            const type = res.headers.get("Content-Type") || "";
            // Session habis / error server biasanya mengembalikan halaman HTML.
            if (type.includes("text/html")) throw new Error("Respon bukan file");
            const blob = await res.blob();
            const objectUrl = URL.createObjectURL(blob);
            const a = document.createElement("a");
            a.href = objectUrl;
            a.download = filenameFrom(res, fallbackName);
            document.body.appendChild(a);
            a.click();
            a.remove();
            setTimeout(() => URL.revokeObjectURL(objectUrl), 5000);
        } catch (e) {
            alert("Gagal mengunduh file. Silakan coba lagi.");
        } finally {
            setLoading(btn, false);
        }
    }

    return { download };
})();
