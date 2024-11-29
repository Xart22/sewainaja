<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Terima Kasih</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css'])
</head>

<body class="font-sans antialiased dark:bg-black dark:text-white/50 ">
    <div class="flex flex-col items-center">
        <img src="{{ asset('assets/img/success.png') }}" alt="Whatsapp" class="h-40 mx-auto mt-5" />
        <h1 class="text-2xl font-bold text-center mt-5">Laporan Anda Berhasil Ditutup</h1>
    </div>

    <h3 class="text-lg font-semibold text-center mt-5">Nomor Tiket: <span class="text-[#2943D1]">{{
            session('no_ticket') }}</span></h3>

    <p class="text-base text-center mt-2 text-gray-500">
        Terima kasih telah menghubungi kami. Laporan Anda telah ditutup.
    </p>
</body>

</html>