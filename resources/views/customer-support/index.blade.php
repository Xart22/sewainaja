<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Form Laporan</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css','resources/js/form-laporan.js'])
    <script>
        function isMobileDevice() {
            return /Mobi|Android|iPhone|iPad|iPod|Windows Phone/i.test(navigator.userAgent);
        }
        window.onload = function () {
            console.log('isMobileDevice', isMobileDevice());
            if (!isMobileDevice()) {
                // redirect to 404 page
                window.location.href = '/404';
            }
        };
        // Fungsi untuk memformat dan memvalidasi nomor WA Indonesia
        function formatAndValidateWA() {
            // Ambil nilai input nomor
            let waNumber = document.getElementById('phone').value.trim();

            // Hapus semua karakter selain angka dan tanda +
            waNumber = waNumber.replace(/\D/g, '');

            // Jika nomor dimulai dengan 0, ganti menjadi 62
            if (waNumber.startsWith('0')) {
                waNumber = '62' + waNumber.substring(1);
               
            } else if (waNumber.startsWith('62')) {
                // Jika nomor dimulai dengan 62 tanpa tanda +
                waNumber = waNumber;
            }
            document.getElementById('phone').value = waNumber;
            // Validasi nomor WA Indonesia (harus diawali dengan 8 setelah kode negara)
            if (waNumber.length >= 7) {
                if (waNumber.startsWith('62') && waNumber[2] === '8') {
                    // Menampilkan nomor valid di input field
                    document.getElementById('phone').value = waNumber;
                    document.getElementById('invalid-message').style.display = 'none';
                    document.getElementById('submit').disabled = false;
                } else {
                    // Menyembunyikan pesan valid jika format tidak valid
                    document.getElementById('invalid-message').style.display = 'block';
                    document.getElementById('submit').disabled = true;
                }
            } else {
                // Menyembunyikan pesan jika nomor terlalu pendek
                document.getElementById('invalid-message').style.display = 'none';
                document.getElementById('submit').disabled = false;
            }
        }
    </script>

    @if (session('success'))
    <script>
        document.addEventListener('DOMContentLoaded', function () {
 
    const successModal = document.getElementById('successModal');
    const modal = new Modal(successModal); // Modal bawaan Flowbite
    modal.show(); // Tampilkan modal
  });

    </script>
    @endif


</head>

<body class="font-sans antialiased dark:bg-black dark:text-white/50 ">

    <div class="flex flex-col justify-center p-5 h-1/2 pb-20 bg-origin-padding border border-red-500 md:h-1/3 md:flex-row md:justify-around md:items-center bg-[#2943D1]"
        style="clip-path: polygon(50% 0%, 100% 0, 100% 35%, 100% 100%, 50% 86%, 45% 80%, 40% 82%, 35% 80%, 30% 82%, 26% 80%, 50% 80%,  0 100%, 0% 35%, 0 0);">
        <img src=" {{ asset('assets/logo/sewainaja-white.png') }}" alt="Sewainaja" class="w-36 md:w-72" />
        <div class=" mt-2 text-white">
            <h1 class=" font-bold md:text-center md:text-2xl text-xl">
                Layanan Pengaduan Kendala Online</h1>
            <p class=" text-sm md:text-center  md:text-sm">Sampaikan laporan Anda langsung kepada customer service kami
            </p>
        </div>
    </div>

    <div class="flex flex-col  justify-center p-5 h-1/2 bg-white md:h-1/3 md:space-x-5 md:flex-row">

        <div class="flex flex-row items-s justify-center p-5 bg-[#2943D1] md:w-30 w-50 rounded-3xl h-40 mt-5 space-x-5">
            <div class="informasi-mesin">
                <p class="text-white">Informasi Mesin</p>
                <p class="text-white font-bold">{{ $data->hw_name }}</p>
                <p class="text-white">Informasi Customer</p>
                <p class="text-white">{{ $data->customer->name }}</p>
            </div>
            <img src="{{ $data->hw_image }}" alt="{{$data->hw_name}}" class="h-32" />
        </div>

        <form action="{{route('customer-support.store') }}" method="POST" class=" mt-5 md:w-1/4 w-full">
            @csrf
            <input type="hidden" name="customer_id" value="{{ $data->customer->id }}">
            <div class="flex flex-col w-full">
                <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nama</label>
                <input type="text" name="name" id="name" class="w-full p-2 mt-1 border border-gray-300 rounded-md"
                    placeholder="Nama" required>
            </div>
            <div class="flex flex-col w-full mt-3">
                <label for="phone" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nomor Tlp. /
                    WA</label>
                <input type="text" name="phone" id="phone" class="w-full p-2 mt-1 border border-gray-300 rounded-md"
                    placeholder="Nomor Tlp. / WA" required onchange="formatAndValidateWA()">

                <!-- Pesan validasi -->

                <p id="invalid-message" class="text-red-500 mt-2 hidden">Nomor WhatsApp tidak valid.</p>
            </div>
            <div class="flex flex-col w-full mt-3">
                <label for="keperluan"
                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Keperluan</label>
                <select id="keperluan" required name="keperluan"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                    <option selected>--- Pilih Keperluan ---</option>
                    <option value="Service">Service - Kunjunagan Teknisi</option>
                    <option value="Consumable">Toner - Consumable</option>
                    <option value="Lainnya">Lainnya</option>
                </select>
            </div>
            <div class="flex flex-col w-full mt-3">
                <label for="message" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Deskripsi
                    Keperluan</label>
                <textarea name="message" id="message" class="w-full p-2 mt-1 border border-gray-300 rounded-md" rows="3"
                    placeholder="Deskripsi Keperluan" required></textarea>
            </div>

            <button
                class="block text-white bg-[#2943D1] hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800 w-full mt-5"
                type="submit" id="submit" disabled>
                Kirim
            </button>

            <img src="{{ asset('assets/img/flow.png') }}" alt="Whatsapp" class="w-full mt-5" />
        </form>


    </div>
    <div id="successModal" tabindex="-1" aria-hidden="true"
        class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
        <div class="relative p-4 w-full max-w-2xl max-h-full">
            <!-- Modal content -->
            <div class="relative bg-white rounded-lg shadow dark:bg-gray-700 p-4 md:p-5">
                <!-- Modal body -->
                <div class="flex flex-col items-center">
                    <img src="{{ asset('assets/img/success.png') }}" alt="Whatsapp" class="h-40 mx-auto mt-5" />
                    <h1 class="text-2xl font-bold text-center mt-5">Laporan Anda Berhasil Dikirim</h1>
                </div>

                <h3 class="text-lg font-semibold text-center mt-5">Nomor Tiket: <span class="text-[#2943D1]">{{
                        session('no_ticket') }}</span></h3>

                <p class="text-base text-center mt-2 text-gray-500">
                    Terima kasih telah menghubungi kami. Tim customer service kami akan segera menghubungi Anda
                </p>
                <button type="button" data-modal-hide="successModal"
                    class="mt-3 text-white bg-[#2943D1] hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800 w-full">
                    Tutup
                </button>
            </div>

        </div>

    </div>
</body>

</html>