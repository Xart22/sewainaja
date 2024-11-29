@extends('layouts.app')


@section('title')
Detail User
@endsection

@section('header')
@endsection

@section('content')
<div class="mt-14">
    <h1 class="text-3xl font-bold text-gray-800 dark:text-gray-200">Detail User</h1>

    <div class="bg-white rounded-lg shadow-lg dark:bg-gray-800 p-5 mt-5 ">
        <div class="grid grid-cols-1 gap-4 mt-4 md:grid-cols-3">
            <div>
                <label class="text-gray-700 dark:text-gray-200" for="nip">NIP</label>
                <input name="nip" id="nip" type="text" value="{{ $user->nip }}" readonly
                    class="block w-full mt-2 text-gray-700 bg-white border border-gray-300 rounded-md dark:bg-gray-800 dark:text-gray-300 focus:border-blue-500 focus:outline-none focus:ring">
            </div>

            <div>
                <label class="text-gray-700 dark:text-gray-200" for="name">Name</label>
                <input name="name" id="name" type="text" value="{{ $user->name }}" readonly
                    class="block w-full mt-2 text-gray-700 bg-white border border-gray-300 rounded-md dark:bg-gray-800 dark:text-gray-300 focus:border-blue-500 focus:outline-none focus:ring">
            </div>


            <div>
                <label class="text-gray-700 dark:text-gray-200" for="phone_number">Phone Number</label>
                <input name="phone_number" id="phone_number" type="text" readonly value="{{ $user->phone_number }}"
                    class="block w-full mt-2 text-gray-700 bg-white border border-gray-300 rounded-md dark:bg-gray-800 dark:text-gray-300 focus:border-blue-500 focus:outline-none focus:ring number">
            </div>


            <div class="col-span-3">
                <label class="text-gray-700 dark:text-gray-200" for="role">Role</label>
                <select name="role" id="role" disabled
                    class="block w-full mt-2 text-gray-700 bg-white border border-gray-300 rounded-md dark:bg-gray-800 dark:text-gray-300 focus:border-blue-500 focus:outline-none focus:ring">

                    <option @if($user->role == 'Admin') selected @endif value="Admin">Admin</option>
                    <option @if($user->role == 'CSO') selected @endif value="CSO">CSO</option>
                    <option @if($user->role == 'Teknisi') selected @endif value="Teknisi">Teknisi</option>


                </select>
            </div>
        </div>
        <div class="mt-3 flex  space-x-2">
            <a href="{{ route('manage-user.edit', $user->id) }}"
                class="mt-3 text-white bg-[#F59E0B] hover:bg-yellow-800 focus:ring-4 focus:outline-none focus:ring-yellow-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-yellow-600 dark:hover:bg-yellow-700 dark:focus:ring-yellow-800">
                Edit
            </a>
            <a href="{{ route('manage-user.index') }}"
                class="mt-3 text-white bg-black hover:bg-gray-800 focus:ring-4 focus:outline-none focus:ring-gray-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-gray-600 dark:hover:bg-gray-700 dark:focus:ring-gray-800">
                Back
            </a>

        </div>


    </div>

    @endsection