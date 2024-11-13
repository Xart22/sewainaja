<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Laravel</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js','resources/js/form-laporan.js'])

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

        <div class="flex flex-row items-s justify-center p-5 bg-[#2943D1] md:w-30 w-50 rounded-3xl h-40 mt-5">
            <div class="informasi-mesin">
                <p class="text-white">Informasi Mesin</p>
                <p class="text-white font-bold">Cannon IR-ADV C3520</p>
                <button class="w-full p-2 mt-3 text-white bg-[#2943D1] rounded-md hover:bg-[#1E2F8C]">Lihat
                    Detail</button>
            </div>
            <img src="{{ asset('assets/img/IR-ADV C3520.png') }}" alt="Whatsapp" class="w-20" />
        </div>

        <form action="" method="POST" class=" mt-5 md:w-1/4 w-full">
            @csrf
            <div class="flex flex-col w-full">
                <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nama</label>
                <input type="text" name="name" id="name" class="w-full p-2 mt-1 border border-gray-300 rounded-md"
                    placeholder="Nama" required>
            </div>
            <div class="flex flex-col w-full mt-3">
                <label for="phone" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nomo Tlp. /
                    WA</label>
                <input type="text" name="phone" id="phone" class="w-full p-2 mt-1 border border-gray-300 rounded-md"
                    placeholder="Nomo Tlp. / WA" required>
            </div>
            <div class="flex flex-col w-full mt-3">
                <label for="keperluan"
                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Keperluan</label>
                <select id="keperluan"
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
                    required></textarea>
            </div>

            <button
                class="block text-white bg-[#2943D1] hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800 w-full mt-5"
                type="button">
                Kirim
            </button>

            <img src="{{ asset('assets/img/flow.png') }}" alt="Whatsapp" class="w-full mt-5" />
        </form>


    </div>
    <div id="popup-modal" tabindex="-1"
        class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
        <div class="relative p-4 w-full max-w-md max-h-full">
            <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                <button type="button"
                    class="absolute top-3 end-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
                    data-modal-hide="popup-modal">
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                    </svg>
                    <span class="sr-only">Close modal</span>
                </button>
                <div class="p-4 md:p-5 text-center">
                    <svg class="mx-auto mb-4 text-gray-400 w-12 h-12 dark:text-gray-200" aria-hidden="true"
                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 11V6m0 8h.01M19 10a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                    <h3 class="mb-5 text-lg font-normal text-gray-500 dark:text-gray-400">Are you sure you want to
                        delete this product?</h3>
                    <button data-modal-hide="popup-modal" type="button"
                        class="text-white bg-red-600 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 dark:focus:ring-red-800 font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center">
                        Yes, I'm sure
                    </button>
                    <button data-modal-hide="popup-modal" type="button"
                        class="py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">No,
                        cancel</button>
                </div>
            </div>
        </div>
    </div>

</body>

</html>