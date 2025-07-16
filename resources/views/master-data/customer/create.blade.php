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

                <div class="col-span-2">
                    <label class="text-gray-700 dark:text-gray-200" for="hardware_information">Hardware
                        Information</label>
                    <select name="hardware_information[]" id="multiple-with-conditional-counter-select" multiple=""
                        data-hs-select='{
                        "hasSearch": true,
                "searchPlaceholder": "Search...",
                "searchClasses": "block w-full sm:text-sm border-gray-200 rounded-lg focus:border-blue-500 focus:ring-blue-500 before:absolute before:inset-0 before:z-1 dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 dark:placeholder-neutral-500 py-1.5 sm:py-2 px-3",
                "searchWrapperClasses": "bg-white p-2 -mx-1 sticky top-0 dark:bg-neutral-900",
                    "placeholder": "Select Hardware",
                    "toggleTag": "<button type=\"button\" aria-expanded=\"false\"></button>",
                    "toggleClasses": "hs-select-disabled:pointer-events-none hs-select-disabled:opacity-50 relative py-3 ps-4 pe-9 flex gap-x-2 text-nowrap w-full cursor-pointer bg-white border border-gray-200 rounded-lg text-start text-sm focus:outline-hidden focus:ring-2 focus:ring-blue-500 dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 dark:focus:outline-hidden dark:focus:ring-1 dark:focus:ring-neutral-600",
                    "toggleSeparators": {
                    "betweenItemsAndCounter": "&"
                    },
                    "toggleCountText": "+",
                    "toggleCountTextPlacement": "prefix-no-space",
                    "toggleCountTextMinItems": 2,
                    "toggleCountTextMode": "nItemsAndCount",
                    "dropdownClasses": "mt-2 z-50 w-full max-h-72 p-1 space-y-0.5 bg-white border border-gray-200 rounded-lg overflow-hidden overflow-y-auto [&::-webkit-scrollbar]:w-2 [&::-webkit-scrollbar-thumb]:rounded-full [&::-webkit-scrollbar-track]:bg-gray-100 [&::-webkit-scrollbar-thumb]:bg-gray-300 dark:[&::-webkit-scrollbar-track]:bg-neutral-700 dark:[&::-webkit-scrollbar-thumb]:bg-neutral-500 dark:bg-neutral-900 dark:border-neutral-700",
                    "optionClasses": "py-2 px-4 w-full text-sm text-gray-800 cursor-pointer hover:bg-gray-100 rounded-lg focus:outline-hidden focus:bg-gray-100 dark:bg-neutral-900 dark:hover:bg-neutral-800 dark:text-neutral-200 dark:focus:bg-neutral-800",
                    "optionTemplate": "<div class=\"flex justify-between items-center w-full\"><span data-title></span><span class=\"hidden hs-selected:block\"><svg class=\"shrink-0 size-3.5 text-blue-600 dark:text-blue-500 \" xmlns=\"http://w3.org/2000/svg\" width=\"24\" height=\"24\" viewBox=\"0 0 24 24\" fill=\"none\" stroke=\"currentColor\" stroke-width=\"2\" stroke-linecap=\"round\" stroke-linejoin=\"round\"><polyline points=\"20 6 9 17 4 12\"/></svg></span></div>",
                    "extraMarkup": "<div class=\"absolute top-1/2 end-3 -translate-y-1/2\"><svg class=\"shrink-0 size-3.5 text-gray-500 dark:text-neutral-500 \" xmlns=\"http://www.w3.org/2000/svg\" width=\"24\" height=\"24\" viewBox=\"0 0 24 24\" fill=\"none\" stroke=\"currentColor\" stroke-width=\"2\" stroke-linecap=\"round\" stroke-linejoin=\"round\"><path d=\"m7 15 5 5 5-5\"/><path d=\"m7 9 5-5 5 5\"/></svg></div>"
                }' class="hidden">
                        <option value="">Choose</option>
                        @if ($hardwares->isEmpty())
                        <option value="" disabled>No Hardware Available</option>
                        @else
                        @foreach ($hardwares as $hardware)
                        <option value="{{ $hardware->id }}">{{ $hardware->hw_type }} - {{
                            $hardware->hw_serial_number }}
                        </option>
                        @endforeach
                        @endif
                    </select>
                    <!-- End Select -->
                    <div class="flex flex-wrap gap-2">
                        <button type="button" id="multiple-with-conditional-counter-trigger-clear"
                            class="py-1 px-2 inline-flex items-center gap-x-1 text-sm rounded-lg border border-gray-200 bg-white text-gray-800 hover:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:text-white dark:border-neutral-700 dark:hover:bg-neutral-800">
                            <svg class="shrink-0 size-3.5" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path d="M18 6 6 18"></path>
                                <path d="m6 6 12 12"></path>
                            </svg>
                            Clear
                        </button>
                    </div>
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

@section('scripts')
<script>
    window.addEventListener('load', () => {
    (function() {
      const clearBtn = document.querySelector('#multiple-with-conditional-counter-trigger-clear');

      clearBtn.addEventListener('click', () => {
        const select = HSSelect.getInstance('#multiple-with-conditional-counter-select', true);

        select.element.setValue([]);
      });
    })();
  });
</script>
@endsection