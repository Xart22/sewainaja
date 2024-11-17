@extends('layouts.app')


@section('title')
Customer
@endsection

@section('header')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
    integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
    integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
@vite(['resources/js/customer.js'])

@endsection

@section('content')
<div class="mt-14">
    <h1 class="text-3xl font-bold text-gray-800 dark:text-gray-200">Detail Customoer</h1>


    <div class="bg-white rounded-lg shadow-lg dark:bg-gray-800 p-5 mt-5 ">
        <div class="grid grid-cols-1 gap-4 mt-4 md:grid-cols-2">
            <div>
                <label class="text-gray-700 dark:text-gray-200" for="group_name">Group Name</label>
                <input name="group_name" id="group_name" type="text" value="{{ $customer->group_name }}" readonly
                    class="block w-full mt-2 text-gray-700 bg-white border border-gray-300 rounded-md dark:bg-gray-800 dark:text-gray-300 focus:border-blue-500 focus:outline-none focus:ring">

            </div>

            <div>
                <label class="text-gray-700 dark:text-gray-200" for="customer_name">Customer Name</label>
                <input name="customer_name" id="customer_name" type="text" value="{{ $customer->name }}" readonly
                    class="block w-full mt-2 text-gray-700 bg-white border border-gray-300 rounded-md dark:bg-gray-800 dark:text-gray-300 focus:border-blue-500 focus:outline-none focus:ring">
            </div>

            <div>
                <label class="text-gray-700 dark:text-gray-200" for="customer_email">Customer Email</label>
                <input name="customer_email" id="customer_email" type="email" value="{{ $customer->email }}"
                    class="block w-full mt-2 text-gray-700 bg-white border border-gray-300 rounded-md dark:bg-gray-800 dark:text-gray-300 focus:border-blue-500 focus:outline-none focus:ring">
            </div>

            <div>
                <label class="text-gray-700 dark:text-gray-200" for="customer_phone_number">Customer
                    Phone</label>
                <input name="customer_phone_number" id="customer_phone_number" type="text" readonly
                    value="{{ $customer->phone_number }}"
                    class="block w-full mt-2 text-gray-700 bg-white border border-gray-300 rounded-md dark:bg-gray-800 dark:text-gray-300 focus:border-blue-500 focus:outline-none focus:ring number">
            </div>

            <div class="col-span-2">
                <label class="text-gray-700 dark:text-gray-200" for="customer_address">Customer Address</label>
                <input name="customer_address" id="customer_address" type="text" readonly
                    value="{{ $customer->address }}"
                    class="block w-full mt-2 text-gray-700 bg-white border border-gray-300 rounded-md dark:bg-gray-800 dark:text-gray-300 focus:border-blue-500 focus:outline-none focus:ring">
            </div>

            <div>
                <label class="text-gray-700 dark:text-gray-200" for="latitude">Latitude</label>
                <input name="latitude" id="latitude" type="text" readonly value="{{ $customer->latitude }}"
                    class="block w-full mt-2 text-gray-700 bg-white border border-gray-300 rounded-md dark:bg-gray-800 dark:text-gray-300 focus:border-blue-500 focus:outline-none focus:ring">
            </div>

            <div>
                <label class="text-gray-700 dark:text-gray-200" for="longitude">Longitude</label>
                <input name="longitude" id="longitude" type="text" readonly value="{{ $customer->longitude }}"
                    class="block w-full mt-2 text-gray-700 bg-white border border-gray-300 rounded-md dark:bg-gray-800 dark:text-gray-300 focus:border-blue-500 focus:outline-none focus:ring">
            </div>


            <div>
                <label class="text-gray-700 dark:text-gray-200" for="customer_pic_process">Customer PIC
                    Process</label>
                <input name="customer_pic_process" id="customer_pic_process" type="text"
                    value="{{ $customer->pic_process }}" readonly
                    class="block w-full mt-2 text-gray-700 bg-white border border-gray-300 rounded-md dark:bg-gray-800 dark:text-gray-300 focus:border-blue-500 focus:outline-none focus:ring">
            </div>

            <div>
                <label class="text-gray-700 dark:text-gray-200" for="customer_pic_process_phone_number">Customer
                    PIC
                    Process Phone Number</label>
                <input name="customer_pic_process_phone_number" id="customer_pic_process_phone_number" type="text"
                    value="{{ $customer->pic_process_phone_number }}" readonly
                    class="block w-full mt-2 text-gray-700 bg-white border border-gray-300 rounded-md dark:bg-gray-800 dark:text-gray-300 focus:border-blue-500 focus:outline-none focus:ring number">
            </div>

            <div>
                <label class="text-gray-700 dark:text-gray-200" for="customer_pic_installation">Customer PIC
                    Installation </label>
                <input name="customer_pic_installation" id="customer_pic_installation" type="text"
                    value="{{ $customer->pic_installation }}" readonly
                    class="block w-full mt-2 text-gray-700 bg-white border border-gray-300 rounded-md dark:bg-gray-800 dark:text-gray-300 focus:border-blue-500 focus:outline-none focus:ring">
            </div>

            <div>
                <label class="text-gray-700 dark:text-gray-200" for="customer_pic_installation_phone_number">Customer
                    PIC Installation Phone Number</label>
                <input name="customer_pic_installation_phone_number" id="customer_pic_installation_phone_number"
                    type="text" value="{{ $customer->pic_installation_phone_number }}" readonly
                    class="block w-full mt-2 text-gray-700 bg-white border border-gray-300 rounded-md dark:bg-gray-800 dark:text-gray-300 focus:border-blue-500 focus:outline-none focus:ring number">
            </div>

            <div>
                <label class="text-gray-700 dark:text-gray-200" for="customer_pic_financial">Customer PIC
                    Financial
                </label>
                <input name="customer_pic_financial" id="customer_pic_financial" type="text"
                    value="{{ $customer->pic_financial }}" readonly
                    class="block w-full mt-2 text-gray-700 bg-white border border-gray-300 rounded-md dark:bg-gray-800 dark:text-gray-300 focus:border-blue-500 focus:outline-none focus:ring">
            </div>

            <div>
                <label class="text-gray-700 dark:text-gray-200" for="customer_pic_financial_phone_number">Customer
                    PIC
                    Financial Phone Number</label>
                <input name="customer_pic_financial_phone_number" id="customer_pic_financial_phone_number" type="text"
                    value="{{ $customer->pic_financial_phone_number }}" readonly
                    class="block w-full mt-2 text-gray-700 bg-white border border-gray-300 rounded-md dark:bg-gray-800 dark:text-gray-300 focus:border-blue-500 focus:outline-none focus:ring number">
            </div>

            <div>
                <label class="text-gray-700 dark:text-gray-200" for="contract_start_date">Contract Start
                    Date</label>
                <input name="contract_start_date" id="contract_start_date" type="date" readonly
                    value="{{ $date = date('Y-m-d', strtotime($customer->contract_start)) }}"
                    class="block w-full mt-2 text-gray-700 bg-white border border-gray-300 rounded-md dark:bg-gray-800 dark:text-gray-300 focus:border-blue-500 focus:outline-none focus:ring">
            </div>

            <div>
                <label class="text-gray-700 dark:text-gray-200" for="contract_end_date">Contract End
                    Date</label>
                <input name="contract_end_date" id="contract_end_date" type="date" readonly
                    value="{{ $date = date('Y-m-d', strtotime($customer->expired_at)) }}"
                    class="block w-full mt-2 text-gray-700 bg-white border border-gray-300 rounded-md dark:bg-gray-800 dark:text-gray-300 focus:border-blue-500 focus:outline-none focus:ring">
            </div>
        </div>
        <div id="map" class="w-full h-[450px] mt-5"></div>
        <div class="mt-3 flex  space-x-2">
            <a href="{{ route('master-data.customer.edit', $customer->id) }}"
                class="mt-3 text-white bg-[#F59E0B] hover:bg-yellow-800 focus:ring-4 focus:outline-none focus:ring-yellow-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-yellow-600 dark:hover:bg-yellow-700 dark:focus:ring-yellow-800">
                Edit
            </a>
            <a href="{{ route('master-data.customer.index') }}"
                class="mt-3 text-white bg-black hover:bg-gray-800 focus:ring-4 focus:outline-none focus:ring-gray-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-gray-600 dark:hover:bg-gray-700 dark:focus:ring-gray-800">
                Back
            </a>

        </div>
    </div>


</div>

@endsection