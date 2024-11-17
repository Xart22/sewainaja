@extends('layouts.app')


@section('title')
Hardware
@endsection

@section('header')
@vite(['resources/js/hardware.js'])

@endsection



@section('content')


<div class="mt-14">
    <h1 class="text-3xl font-bold text-gray-800 dark:text-gray-200">Create Hardware</h1>


    <div class="bg-white rounded-lg shadow-lg dark:bg-gray-800 p-5 mt-5 ">

        <form action="{{ route('master-data.hardware.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="grid grid-cols-1 gap-4 mt-4 md:grid-cols-2">
                <div>
                    <label class="text-gray-700 dark:text-gray-200" for="hardware_name">Hardware Name</label>
                    <input name="hardware_name" id="hardware_name" type="text" required
                        value="{{ old('hardware_name') }}"
                        class="block w-full mt-2 text-gray-700 bg-white border border-gray-300 rounded-md dark:bg-gray-800 dark:text-gray-300 focus:border-blue-500 focus:outline-none focus:ring">

                </div>
                <div>
                    <label class="text-gray-700 dark:text-gray-200" for="hardware_type">Hardware Type</label>
                    <input name="hardware_type" id="hardware_type" type="text" required
                        value="{{ old('hardware_type') }}"
                        class="block w-full mt-2 text-gray-700 bg-white border border-gray-300 rounded-md dark:bg-gray-800 dark:text-gray-300 focus:border-blue-500 focus:outline-none focus:ring">
                </div>
                <div>
                    <label class="text-gray-700 dark:text-gray-200" for="hardware_brand">Hardware Brand</label>
                    <input name="hardware_brand" id="hardware_brand" type="text" required
                        value="{{ old('hardware_brand') }}"
                        class="block w-full mt-2 text-gray-700 bg-white border border-gray-300 rounded-md dark:bg-gray-800 dark:text-gray-300 focus:border-blue-500 focus:outline-none focus:ring">
                </div>
                <div>
                    <label class="text-gray-700 dark:text-gray-200" for="hardware_model">Hardware Model</label>
                    <input name="hardware_model" id="hardware_model" type="text" value="{{ old('hardware_model') }}"
                        class="block w-full mt-2 text-gray-700 bg-white border border-gray-300 rounded-md dark:bg-gray-800 dark:text-gray-300 focus:border-blue-500 focus:outline-none focus:ring">
                </div>

                <div>
                    <label class="text-gray-700 dark:text-gray-200" for="hardware_technology">Hardware
                        Technology</label>
                    <input name="hardware_technology" id="hardware_technology" type="text"
                        value="{{ old('hardware_technology') }}"
                        class="block w-full mt-2 text-gray-700 bg-white border border-gray-300 rounded-md dark:bg-gray-800 dark:text-gray-300 focus:border-blue-500 focus:outline-none focus:ring">
                </div>
                <div>
                    <label class="text-gray-700 dark:text-gray-200" for="hardware_bw_color">Hardware B/W Color</label>
                    <input name="hardware_bw_color" id="hardware_bw_color" type="text"
                        value="{{ old('hardware_bw_color') }}"
                        class="block w-full mt-2 text-gray-700 bg-white border border-gray-300 rounded-md dark:bg-gray-800 dark:text-gray-300 focus:border-blue-500 focus:outline-none focus:ring">
                </div>
                <div>
                    <label class="text-gray-700 dark:text-gray-200" for="hw_serial_number">Hardware Serial
                        Number</label>
                    <input name="hw_serial_number" id="hw_serial_number" type="text" required
                        value="{{ old('hw_serial_number') }}" class="block w-full mt-2 text-gray-700 bg-white border border-gray-300 rounded-md dark:bg-gray-800 dark:text-gray-300 focus:border-blue-500 focus:outline-none focus:ring
                        @error('hw_serial_number') border-red-500 @enderror">
                    @error('hw_serial_number')
                    <span class="text-red-500 mt-2">Serial Number Sudah Ada</span>
                    @enderror

                </div>
                <div>
                    <label class="text-gray-700 dark:text-gray-200" for="hardware_image">Hardware Image</label>
                    <input name="hardware_image" id="hardware_image" type="file" accept="image/*"
                        value="{{ old('hardware_image') }}"
                        class="block w-full mt-2 text-gray-700 bg-white border border-gray-300 rounded-md dark:bg-gray-800 dark:text-gray-300 focus:border-blue-500 focus:outline-none focus:ring"
                        required>
                    @error('hardware_image')
                    <span class="text-red-500 mt-2">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            <div class="mt-5">
                <label class="text-gray-700 dark:text-gray-200" for="hardware_description">Hardware
                    Description</label>
                <textarea name="hardware_description" id="hardware_description" type="text" rows="4"
                    class="block w-full mt-2 text-gray-700 bg-white border border-gray-300 rounded-md dark:bg-gray-800 dark:text-gray-300 focus:border-blue-500 focus:outline-none focus:ring">{{ old('hardware_description') }}</textarea>
            </div>

            <button type="submit"
                class=" mt-5 text-white bg-[#2943D1] hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800 w-full">
                Create Hardware
            </button>



        </form>



    </div>

</div>




@endsection