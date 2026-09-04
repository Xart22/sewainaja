# Update Aplikasi Mobile Teknisi — Fitur Hold Pekerjaan, Aturan Double Job & Penggantian Teknisi

- **Tanggal:** 2026-09-04
- **Audience:** Tim mobile (aplikasi teknisi)
- **Server:** Backend sudah live. Perubahan API sudah aktif, tidak ada versi API baru — tetap endpoint yang sama.
- **Status deployment backend:** SUDAH DITERAPKAN

---

## 1. Ringkasan Perubahan

| No | Perubahan | Dampak ke Aplikasi Teknisi |
|----|-----------|-----------------------------|
| 1 | Status baru **`On Hold`** (jeda pekerjaan) | WAJIB: tambah tombol/aksi Hold + Resume + input alasan |
| 2 | **`Done`** hanya valid dari status `Working` | WAJIB: cegah kirim Done saat status `On Hold` |
| 3 | Durasi kerja = total pengerjaan dikurangi total waktu hold | WAJIB: tampilkan informasi bahwa waktu hold tidak dihitung |
| 4 | Aturan **double job**: teknisi tidak dapat di-assign tiket baru saat punya pekerjaan aktif (`Waiting / On The Way / Arrived / Working`) | Tidak ada perubahan UI khusus; tugas baru tetap datang via FCM |
| 5 | Aturan **ganti teknisi 5 menit**: CSO dapat mengganti teknisi bila teknisi tidak merespons dalam 5 menit sejak ditugaskan | UI: beri peringatan di job baru bahwa teknisi punya waktu 5 menit untuk menerima/menolak |
| 6 | 4 field baru pada data tiket | WAJIB: pastikan model/parsing tidak error karena field tambahan |

---

## 2. Perubahan Skema / Field Baru

Semua field baru berada di tabel `customer_supports` dan ikut **ter-serialize dalam response JSON** tiap endpoint tiket (`getDataTeknisi`, `getDataTeknisiByDate`, `getData`, `getDataCso`, `tracking`).

| Field | Tipe | Nullable / Default | Deskripsi |
|-------|------|--------------------|-----------|
| `teknisi_assigned_at` | datetime | nullable | Waktu CSO menugaskan teknisi. Basis hitung **aturan ganti teknisi 5 menit**. |
| `hold_reason` | longText | nullable | Alasan hold (diisi teknisi). Terisi hanya saat status `On Hold`. |
| `hold_started_at` | datetime | nullable | Waktu hold dimulai. `null` saat tidak sedang hold. |
| `total_hold_menit` | unsignedInteger | default `0` | Akumulasi total durasi hold (menit). Bertambah tiap kali resume dari hold. |

### Cara baca oleh aplikasi teknisi

- **Sedang di-hold:** `status_teknisi == "On Hold"` → `hold_reason` terisi, `hold_started_at` terisi.
- **Pernah di-hold (riwayat):** `total_hold_menit > 0`.
- Format datetime JSON: string `YYYY-MM-DD HH:MM:SS` (atau `Y-m-d H:i:s` sesuai hasil query Eloquent, contoh: `"2026-09-04 07:12:33"`). Tidak ada casts khusus → **parse sebagai string biasa**, jangan asumsikan format ISO 8601 dengan timezone.

**Catatan:** `waktu_pengerjaan` bertipe STRING (bukan datetime). Saat mulai kerja pertama kali berisi datetime `now()`, setelah `Done` berubah menjadi string durasi `"45 menit"`. Aplikasi hanya menampilkan, tidak perlu memparsing untuk logika.

---

## 3. Status Lifecycle Teknisi (Update)

Status lama tetap ada. Tambahan **`On Hold`** di antara `Working`.

```
                         ┌─────────────────────────────┐
                         ▼                             │
null → Waiting → On The Way → Arrived → Working → On Hold
 │         │                                          │
 │         └── (Cancel / Di Tolak → null + teknisi_id dihapus)
 └── Done (harus dari Working, TIDAK BOLEH dari On Hold langsung)
```

| Status | Dari | Aksi yang dikirim teknisi |
|--------|------|---------------------------|
| `Waiting` | assign CSO (set oleh server, bukan dikirim teknisi) | Teknisi **menerima** = lanjut ke `OTW`; **menolak** = `Di Tolak` + alasan `message` |
| `On The Way` | `OTW` | Menuju lokasi |
| `Arrived` | `Arrived` | Tiba di lokasi |
| `Working` | `Working` (mulai kerja **atau resume dari hold**) | Mulai/`Resume` pengerjaan |
| `On Hold` | `On Hold` (baru) | **Jeda** pekerjaan, wajib isi `hold_reason` |
| `Done` | `Done` | Selesai, wajib isi `work_report` |

### Aturan transisi yang WAJIB dicegah di sisi aplikasi

1. **`On Hold` hanya dari `Working`.** Jika status bukan `Working`, server balas `422`:
   ```json
   { "message": "Hanya pekerjaan berstatus Working yang bisa di-hold." }
   ```
2. **`Done` hanya dari `Working`.** Jika masih `On Hold`, server balas `422`:
   ```json
   { "message": "Pekerjaan harus dalam status Working terlebih dahulu. Jika sedang On Hold, lanjutkan (resume) pekerjaan dahulu." }
   ```
   → Alur benar: `Working → On Hold → (resume) Working → Done`.
3. **Resume = kirim status `Working`** saat status tiket sedang `On Hold`. Server otomatis:
   - menghitung durasi hold sejak `hold_started_at` → ditambahkan ke `total_hold_menit`,
   - mengosongkan `hold_started_at` dan `hold_reason`,
   - TIDAK mereset `waktu_pengerjaan` (mulai-kerja tetap waktu pertama).
4. **`work_report` wajib** saat `Done` (`required|string`). `hold_reason` wajib saat `On Hold` (`required|string`).

---

## 4. Endpoint yang Berubah / Perlu Penyesuaian

Semua endpoint di bawah dalam grup middleware `auth:sanctum`, kecuali `/get-teknisi` (public).

### 4.1 POST `/api/teknisi/update-status-teknisi/{id}` — perubahan utama

Request (application/x-www-form-urlencoded atau JSON):

| Field | Tipe | Wajib | Berlaku status | Keterangan |
|-------|------|-------|----------------|------------|
| `status` | string | ya | semua | Nilai baru: `OTW`, `Arrived`, `Working`, `On Hold`, `Done`, `Cancel`, `Di Tolak` |
| `work_report` | string | ya (saat `Done`) | `Done` | Laporan pekerjaan |
| `hold_reason` | string | ya (saat `On Hold`) | `On Hold` | Alasan hold, contoh: menunggu sparepart |
| `message` | string | ya (saat `Di Tolak`/`Cancel`) | `Di Tolak`, `Cancel` | Alasan menolak/membatalkan |

Contoh sukses (selalu `200`):
```json
{ "message": "Data updated" }
```

Contoh sukses hold:
```
POST /api/teknisi/update-status-teknisi/123
status=On Hold
hold_reason=Menunggu sparepart toner
```
```json
{ "message": "Data updated" }
```

Error (JSON, gunakan pesan untuk ditampilkan ke user):
| HTTP | Skenario | `message` |
|------|----------|-----------|
| `404` | `{id}` tiket tidak ada | `"Data not found"` |
| `422` | Hold padahal status bukan `Working` | `"Hanya pekerjaan berstatus Working yang bisa di-hold."` |
| `422` | `hold_reason` kosong | pesan validasi Laravel (array `errors`) |
| `422` | Done padahal bukan `Working` | `"Pekerjaan harus dalam status Working terlebih dahulu..."` |
| `422` | `work_report` kosong | pesan validasi Laravel (array `errors`) |
| `500` | gagal internal | `{ "message": "Failed to update status", "error": "..." }` |

### 4.2 GET `/api/teknisi/get-customer-support` & `/api/teknisi/get-customer-support/{start}/{end}`

Response shape tetap:
```json
{ "data": [ ...tiket... ] }
```
Perubahan: tiap tiket kini menyertakan field baru (`teknisi_assigned_at`, `hold_reason`, `hold_started_at`, `total_hold_menit`). Relasi yang sama: `cso`, `customer`, `teknisi`, `logs`, `hardware`.

### 4.3 GET `/api/get-teknisi` (public)

Response kini menyertakan field **`is_busy`** (boolean) per teknisi — tambahan di luar atribut `users` biasa.

```json
{
  "data": [
    { "id": 4, "nip": "E001", "name": "Andri Supriyatno", "role": "Teknisi", "...": "...", "is_busy": false }
  ]
}
```

Makna:
- `is_busy = true` → teknisi punya pekerjaan aktif (`Waiting / On The Way / Arrived / Working`, belum `Done`, belum ditolak).
- `is_busy = false` → tidak ada pekerjaan aktif. Pekerjaan **`On Hold` TIDAK membuat teknisi busy** — teknisi boleh menerima tiket baru.
- Endpoint ini terutama dipakai aplikasi CSO (web) untuk menampilkan badge "Sibuk". Jika aplikasi teknisi tidak memakai endpoint ini, tidak ada perubahan wajib.

### 4.4 POST `/api/customer-support/assign-teknisi` (CSO)

Tidak dipakai aplikasi teknisi, tapi logika berdampak pada FCM:
- Server menolak assign (`422`, message: `"Teknisi sedang memiliki pekerjaan aktif. Selesaikan atau hold pekerjaan tersebut terlebih dahulu."`) bila teknisi punya pekerjaan aktif. Artinya **teknisi tidak akan menerima notifikasi pekerjaan baru** selama masih ada pekerjaan aktif non-hold.
- Teknisi yang pekerjaannya sedang **`On Hold` tetap bisa menerima notifikasi pekerjaan baru**.

---

## 5. Notifikasi FCM (Perubahan Pesan)

Semua dikirim ke `fcm_token` CSO terkait (bukan ke teknisi), sebagai notifikasi progres ke CSO. Tidak ada payload/data tambahan. Judul/body:

| Status | Title | Body |
|--------|-------|------|
| `Working` (mulai) | `Teknisi Memulai Pengerjaan` | `Teknisi telah memulai pengerjaan pekerjaan dengan nomor tiket {no_ticket}` |
| `Working` (resume) | `Pekerjaan Dilanjutkan` | `Teknisi melanjutkan pengerjaan pekerjaan dengan nomor tiket {no_ticket}` |
| `On Hold` | `Pekerjaan Di-Hold` | `Pekerjaan dengan nomor tiket {no_ticket} di-hold oleh teknisi. Alasan: {hold_reason}` |
| `Done` | `Teknisi telah menyelesaikan pekerjaan` | `Teknisi telah menyelesaikan pekerjaan dengan nomor tiket {no_ticket}` |

Jika aplikasi teknisi memfilter FCM berdasarkan `title`, tambahkan dua nilai baru di atas (`Pekerjaan Dilanjutkan`, `Pekerjaan Di-Hold`).

---

## 6. Checklist Implementasi Aplikasi Teknisi

- [ ] Tambah aksi **Hold** di detail pekerjaan (hanya muncul saat status `Working`): tampilkan input/textarea alasan (min. 1 karakter), kirim `POST /api/teknisi/update-status-teknisi/{id}` dengan `status=On Hold`, `hold_reason=...`.
- [ ] Saat status tiket `On Hold`, tampilkan banner/info: **alasan hold** (`hold_reason`) dan **mulai hold** (`hold_started_at`).
- [ ] Saat status `On Hold`, tampilkan aksi **Resume/Lanjutkan** → kirim `status=Working` (tanpa `work_report`). Setelah sukses, kembali ke `Working`.
- [ ] Sembunyikan tombol **Selesai (Done)** saat status `On Hold`; tampilkan kembali setelah resume ke `Working`.
- [ ] Jika status selain `Working` mencoba kirim Hold → tampilkan pesan server apa adanya (jangan parsing sendiri).
- [ ] Pastikan parsing objek tiket toleran terhadap 4 field baru (jangan gunakan deserializer ketat yang error pada field tak dikenal).
- [ ] (Opsional) Tampilkan info `total_hold_menit` atau catatan bahwa waktu hold tidak dihitung dalam durasi pengerjaan.
- [ ] (Opsional) Di kartu pekerjaan baru (status `Waiting`), tampilkan indikasi waktu tunggu respons 5 menit sejak `teknisi_assigned_at` — setelah itu CSO berhak mengganti teknisi.
- [ ] Uji alur lengkap: `Working → On Hold (isi alasan) → Working (resume) → Done (isi work_report)` lalu verifikasi di server `total_hold_menit > 0` dan `waktu_pengerjaan` = durasi minus hold (format `"X menit"`).

---

## 7. Contoh Skenario End-to-End (Regresi)

1. CSO assign teknisi A ke tiket 1 → tiket 1 `Waiting`, FCM masuk ke teknisi A.
2. Teknisi A tidak respons > 5 menit → CSO ganti ke teknisi B (server mengizinkan). Teknisi A tidak dapat akses tiket 1 lagi (`teknisi_id` berpindah ke B).
3. Teknisi B terima → `OTW` → `Arrived` → `Working`.
4. Tidak ada sparepart → teknisi B **Hold** (alasan: "menunggu sparepart"). CSO melihat status `On Hold` + alasan. Tiket 1 tidak membuat teknisi B `is_busy`.
5. CSO assign tiket 2 ke teknisi B → berhasil (karena tiket 1 `On Hold`, bukan pekerjaan aktif).
6. Sparepart datang → teknisi B **Resume** tiket 1 (`Working`). `total_hold_menit` bertambah.
7. Teknisi B selesai tiket 1 → `Done` + `work_report`. Durasi tampil dikurangi waktu hold.
8. Teknisi B selesai tiket 2 dengan alur sama. Kedua tiket masuk "Menunggu Konfirmasi Customer".

---

## 8. Pertanyaan / Kontak

Untuk detail teknis backend atau data uji, hubungi pengembang backend (repo: `sewainaja`, modul API di [`app/Http/Controllers/API/CustomerSupportController.php`](../app/Http/Controllers/API/CustomerSupportController.php)).
