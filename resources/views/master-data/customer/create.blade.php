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
    <h1 class="text-3xl font-bold text-gray-800 dark:text-gray-200">Create Customoer</h1>
    <form action="{{ route('master-data.customer.store') }}" method="POST">
        @csrf
        <div class="bg-white rounded-lg shadow-lg dark:bg-gray-800 p-5 mt-5 ">
            <div class="grid grid-cols-1 gap-4 mt-4 md:grid-cols-2">
                <div>
                    <label class="text-gray-700 dark:text-gray-200" for="group_name">Group Name</label>
                    <input name="group_name" id="group_name" type="text" value="{{ old('group_name') }}" required
                        class="block w-full mt-2 text-gray-700 bg-white border border-gray-300 rounded-md dark:bg-gray-800 dark:text-gray-300 focus:border-blue-500 focus:outline-none focus:ring">

                </div>

                <div>
                    <label class="text-gray-700 dark:text-gray-200" for="customer_name">Customer Name</label>
                    <input name="customer_name" id="customer_name" type="text" value="{{ old('customer_name') }}"
                        required
                        class="block w-full mt-2 text-gray-700 bg-white border border-gray-300 rounded-md dark:bg-gray-800 dark:text-gray-300 focus:border-blue-500 focus:outline-none focus:ring">
                </div>

                <div>
                    <label class="text-gray-700 dark:text-gray-200" for="customer_email">Customer Email</label>
                    <input name="customer_email" id="customer_email" type="email" value="{{ old('customer_email') }}"
                        class="block w-full mt-2 text-gray-700 bg-white border border-gray-300 rounded-md dark:bg-gray-800 dark:text-gray-300 focus:border-blue-500 focus:outline-none focus:ring">
                </div>

                <div>
                    <label class="text-gray-700 dark:text-gray-200" for="customer_phone_number">Customer
                        Phone</label>
                    <input name="customer_phone_number" id="customer_phone_number" type="text" required
                        value="{{ old('customer_phone_number') }}"
                        class="block w-full mt-2 text-gray-700 bg-white border border-gray-300 rounded-md dark:bg-gray-800 dark:text-gray-300 focus:border-blue-500 focus:outline-none focus:ring number">
                </div>

                <div class="col-span-2">
                    <label class="text-gray-700 dark:text-gray-200" for="customer_address">Customer Address</label>
                    <input name="customer_address" id="customer_address" type="text" required
                        value="{{ old('customer_address') }}"
                        class="block w-full mt-2 text-gray-700 bg-white border border-gray-300 rounded-md dark:bg-gray-800 dark:text-gray-300 focus:border-blue-500 focus:outline-none focus:ring">
                </div>

                <div>
                    <label class="text-gray-700 dark:text-gray-200" for="latitude">Latitude</label>
                    <input name="latitude" id="latitude" type="text" required value="{{ old('latitude') }}"
                        class="block w-full mt-2 text-gray-700 bg-white border border-gray-300 rounded-md dark:bg-gray-800 dark:text-gray-300 focus:border-blue-500 focus:outline-none focus:ring">
                </div>

                <div>
                    <label class="text-gray-700 dark:text-gray-200" for="longitude">Longitude</label>
                    <input name="longitude" id="longitude" type="text" required value="{{ old('longitude') }}"
                        class="block w-full mt-2 text-gray-700 bg-white border border-gray-300 rounded-md dark:bg-gray-800 dark:text-gray-300 focus:border-blue-500 focus:outline-none focus:ring">
                </div>


                <div>
                    <label class="text-gray-700 dark:text-gray-200" for="customer_pic_process">Customer PIC
                        Process</label>
                    <input name="customer_pic_process" id="customer_pic_process" type="text"
                        value="{{ old('customer_pic_process') }}"
                        class="block w-full mt-2 text-gray-700 bg-white border border-gray-300 rounded-md dark:bg-gray-800 dark:text-gray-300 focus:border-blue-500 focus:outline-none focus:ring">
                </div>

                <div>
                    <label class="text-gray-700 dark:text-gray-200" for="customer_pic_process_phone_number">Customer
                        PIC
                        Process Phone Number</label>
                    <input name="customer_pic_process_phone_number" id="customer_pic_process_phone_number" type="text"
                        value="{{ old('customer_pic_process_phone_number') }}"
                        class="block w-full mt-2 text-gray-700 bg-white border border-gray-300 rounded-md dark:bg-gray-800 dark:text-gray-300 focus:border-blue-500 focus:outline-none focus:ring number">
                </div>

                <div>
                    <label class="text-gray-700 dark:text-gray-200" for="customer_pic_installation">Customer PIC
                        Installation </label>
                    <input name="customer_pic_installation" id="customer_pic_installation" type="text"
                        value="{{ old('customer_pic_installation') }}"
                        class="block w-full mt-2 text-gray-700 bg-white border border-gray-300 rounded-md dark:bg-gray-800 dark:text-gray-300 focus:border-blue-500 focus:outline-none focus:ring">
                </div>

                <div>
                    <label class="text-gray-700 dark:text-gray-200"
                        for="customer_pic_installation_phone_number">Customer
                        PIC Installation Phone Number</label>
                    <input name="customer_pic_installation_phone_number" id="customer_pic_installation_phone_number"
                        type="text" value="{{ old('customer_pic_installation_phone_number') }}"
                        class="block w-full mt-2 text-gray-700 bg-white border border-gray-300 rounded-md dark:bg-gray-800 dark:text-gray-300 focus:border-blue-500 focus:outline-none focus:ring number">
                </div>

                <div>
                    <label class="text-gray-700 dark:text-gray-200" for="customer_pic_financial">Customer PIC
                        Financial
                    </label>
                    <input name="customer_pic_financial" id="customer_pic_financial" type="text"
                        value="{{ old('customer_pic_financial') }}"
                        class="block w-full mt-2 text-gray-700 bg-white border border-gray-300 rounded-md dark:bg-gray-800 dark:text-gray-300 focus:border-blue-500 focus:outline-none focus:ring">
                </div>

                <div>
                    <label class="text-gray-700 dark:text-gray-200" for="customer_pic_financial_phone_number">Customer
                        PIC
                        Financial Phone Number</label>
                    <input name="customer_pic_financial_phone_number" id="customer_pic_financial_phone_number"
                        type="text" value="{{ old('customer_pic_financial_phone_number') }}"
                        class="block w-full mt-2 text-gray-700 bg-white border border-gray-300 rounded-md dark:bg-gray-800 dark:text-gray-300 focus:border-blue-500 focus:outline-none focus:ring number">
                </div>
            </div>


            <div>
                <label class="text-gray-700 dark:text-gray-200" for="contract_start_date">Contract Start
                    Date</label>
                <input name="contract_start_date" id="contract_start_date" type="date" required
                    value="{{ old('contract_start_date') }}"
                    class="block w-full mt-2 text-gray-700 bg-white border border-gray-300 rounded-md dark:bg-gray-800 dark:text-gray-300 focus:border-blue-500 focus:outline-none focus:ring">
            </div>

            <div>
                <label class="text-gray-700 dark:text-gray-200" for="contract_end_date">Contract End
                    Date</label>
                <input name="contract_end_date" id="contract_end_date" type="date" required
                    value="{{ old('contract_end_date') }}"
                    class="block w-full mt-2 text-gray-700 bg-white border border-gray-300 rounded-md dark:bg-gray-800 dark:text-gray-300 focus:border-blue-500 focus:outline-none focus:ring">
            </div>
        </div>



        <div id="map" class="w-full h-[450px] mt-5"></div>
        <button type="submit"
            class=" mt-5 text-white bg-[#2943D1] hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800 w-full">
            Create Customer
        </button>
</div>

</form>
</div>

@endsection
