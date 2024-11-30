@extends('layouts.app')


@section('title')
Edit User
@endsection

@section('header')
@endsection

@section('content')
<div class="mt-14">
    <h1 class="text-3xl font-bold text-gray-800 dark:text-gray-200">Edit User</h1>
    <form action="{{ route('manage-user.update', $user->id) }}" method="POST">
        @method('PUT')
        @csrf
        <div class="bg-white rounded-lg shadow-lg dark:bg-gray-800 p-5 mt-5 ">
            <div class="grid grid-cols-1 gap-4 mt-4 md:grid-cols-2">
                <div>
                    <label class="text-gray-700 dark:text-gray-200" for="nip">NIP</label>
                    <input name="nip" id="nip" type="text" value="{{ old('nip', $user->nip) }}" required
                        class="block w-full mt-2 text-gray-700 bg-white border border-gray-300 rounded-md dark:bg-gray-800 dark:text-gray-300 focus:border-blue-500 focus:outline-none focus:ring">
                </div>

                <div>
                    <label class="text-gray-700 dark:text-gray-200" for="name">Name</label>
                    <input name="name" id="name" type="text" value="{{ old('name', $user->name) }}" required
                        class="block w-full mt-2 text-gray-700 bg-white border border-gray-300 rounded-md dark:bg-gray-800 dark:text-gray-300 focus:border-blue-500 focus:outline-none focus:ring">
                </div>


                <div>
                    <label class="text-gray-700 dark:text-gray-200" for="phone_number">Phone Number</label>
                    <input name="phone_number" id="phone_number" type="text" required
                        value="{{ old('phone_number', $user->phone_number) }}"
                        class="block w-full mt-2 text-gray-700 bg-white border border-gray-300 rounded-md dark:bg-gray-800 dark:text-gray-300 focus:border-blue-500 focus:outline-none focus:ring number">
                </div>
                <div>
                    <label class="text-gray-700 dark:text-gray-200" for="password">Password</label>
                    <input name="password" id="password" type="password" value="{{ old('password') }}"
                        class="block w-full mt-2 text-gray-700 bg-white border border-gray-300 rounded-md dark:bg-gray-800 dark:text-gray-300 focus:border-blue-500 focus:outline-none focus:ring number">
                </div>

                <div class="col-span-2">
                    <label class="text-gray-700 dark:text-gray-200" for="role">Role</label>
                    <select name="role" id="role" required
                        class="block w-full mt-2 text-gray-700 bg-white border border-gray-300 rounded-md dark:bg-gray-800 dark:text-gray-300 focus:border-blue-500 focus:outline-none focus:ring">

                        <option value="">Select Role</option>
                        <option value="Admin" @if($user->role == 'Admin') selected @endif>Admin</option>
                        <option value="CSO" @if($user->role == 'CSO') selected @endif>CSO</option>
                        <option value="Teknisi" @if($user->role == 'Teknisi') selected @endif>Teknisi</option>

                    </select>
                </div>
            </div>
            <button type="submit"
                class=" mt-5 text-white bg-[#2943D1] hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800 w-full">
                Update User
            </button>

    </form>
</div>

@endsection