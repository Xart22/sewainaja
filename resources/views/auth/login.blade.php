<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,100..1000;1,9..40,100..1000&family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap"
        rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

</head>

<body class="font-inter antialiased dark:bg-black dark:text-white/50 "
    style="background-image: url('{{ asset('assets/img/bg-login.png') }}'); background-size: cover; background-position: center;">

    <div class="min-h-screen flex items-center justify-center p-2">
        <div class="w-full max-w-md bg-white rounded-lg shadow-lg px-12 py-8">
            <img src="{{ asset('assets/logo/sewainaja-blue.png') }}" alt="Logo" class="h-16 w-auto mx-auto mb-8">
            <h1 class="text-2xl font-bold text-gray-800 mb-8">Sign In</h1>
            <form method="POST" action="{{ route('authenticate') }}">
                @csrf

                <div class="mb-4">
                    <label for="nip" class="block text-sm font-medium text-gray-700">NIP</label>
                    <input id="nip" type="text" name="nip" value="{{ old('nip') }}" required
                        class=" mt-1 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                </div>

                <div class="mb-4">
                    <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                    <input id="password" type="password" name="password" required autocomplete="current-password"
                        class="mt-1 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                </div>

                {{-- error --}}
                @if ($errors->any())
                <div class=" mb-4">
                    <div class="text-red-500">
                        {{ $errors->first() }}
                    </div>
                </div>
                @endif


                <div class="flex items center justify-between mt-5">
                    <button type="submit"
                        class="text-white bg-[#2943D1] hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800 w-full">
                        Sign In
                    </button>
                </div>
            </form>
        </div>
    </div>

</body>

</html>