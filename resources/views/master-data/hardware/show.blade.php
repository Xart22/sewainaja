@extends('layouts.app')


@section('title')
Hardware
@endsection

@section('header')
@vite(['resources/js/hardware.js'])
<script>
    function downloadQRCode(hw_name, hw_sn) {
    const div = document.getElementById('qrCode');
    const qrCode = div.querySelector('svg');
    const svgData = new XMLSerializer().serializeToString(qrCode);

    const canvas = document.createElement("canvas");
    const svgSize = qrCode.viewBox.baseVal.width;
    canvas.width = svgSize;
    canvas.height = svgSize;
    const ctx = canvas.getContext("2d");
    const img = new Image();
    img.src = "data:image/svg+xml;base64," + btoa(svgData);
    img.onload = function () {
        ctx.drawImage(img, 0, 0, svgSize, svgSize);
        const pngFile = canvas.toDataURL("image/png");
        const downloadLink = document.createElement("a");
        downloadLink.href = pngFile;
        downloadLink.download = `${hw_name}-${hw_sn}.png`;
        downloadLink.click();
    };
    }

</script>
@endsection



@section('content')


<div class="mt-14">
    <h1 class="text-3xl font-bold text-gray-800 dark:text-gray-200">Detail Hardware</h1>

    <div class="bg-white rounded-lg shadow-lg dark:bg-gray-800 p-5 mt-5 ">
        <div class="grid grid-cols-1 gap-4 mt-4 md:grid-cols-2">
            <div>
                <label class="text-gray-700 dark:text-gray-200" for="hardware_name">Hardware Name</label>
                <input name="hardware_name" id="hardware_name" type="text" disabled value="{{ $hardware->hw_name }}"
                    class="block w-full mt-2 text-gray-700 bg-white border border-gray-300 rounded-md dark:bg-gray-800 dark:text-gray-300 focus:border-blue-500 focus:outline-none focus:ring">

            </div>
            <div>
                <label class="text-gray-700 dark:text-gray-200" for="hardware_type">Hardware Type</label>
                <input name="hardware_type" id="hardware_type" type="text" disabled value="{{ $hardware->hw_type }}"
                    class="block w-full mt-2 text-gray-700 bg-white border border-gray-300 rounded-md dark:bg-gray-800 dark:text-gray-300 focus:border-blue-500 focus:outline-none focus:ring">
            </div>
            <div>
                <label class="text-gray-700 dark:text-gray-200" for="hardware_brand">Hardware Brand</label>
                <input name="hardware_brand" id="hardware_brand" type="text" disabled value="{{ $hardware->hw_brand }}"
                    class="block w-full mt-2 text-gray-700 bg-white border border-gray-300 rounded-md dark:bg-gray-800 dark:text-gray-300 focus:border-blue-500 focus:outline-none focus:ring">
            </div>
            <div>
                <label class="text-gray-700 dark:text-gray-200" for="hardware_model">Hardware Model</label>
                <input name="hardware_model" id="hardware_model" type="text" value="{{ $hardware->hw_model }}" disabled
                    class="block w-full mt-2 text-gray-700 bg-white border border-gray-300 rounded-md dark:bg-gray-800 dark:text-gray-300 focus:border-blue-500 focus:outline-none focus:ring">
            </div>

            <div>
                <label class="text-gray-700 dark:text-gray-200" for="hardware_techonology">Hardware
                    Technology</label>
                <input name="hardware_techonology" id="hardware_techonology" type="text" disabled
                    value="{{ $hardware->hw_technology }}"
                    class="block w-full mt-2 text-gray-700 bg-white border border-gray-300 rounded-md dark:bg-gray-800 dark:text-gray-300 focus:border-blue-500 focus:outline-none focus:ring">
            </div>
            <div>
                <label class="text-gray-700 dark:text-gray-200" for="hardware_bw_color">Hardware B/W Color</label>
                <input name="hardware_bw_color" id="hardware_bw_color" type="text" disabled
                    value="{{ $hardware->hw_bw_color }}"
                    class="block w-full mt-2 text-gray-700 bg-white border border-gray-300 rounded-md dark:bg-gray-800 dark:text-gray-300 focus:border-blue-500 focus:outline-none focus:ring">
            </div>
        </div>
        <div class="mt-4">
            <label class="text-gray-700 dark:text-gray-200" for="hw_serial_number">Hardware Serial
                Number</label>
            <input name="hw_serial_number" id="hw_serial_number" type="text" disabled
                value="{{ $hardware->hw_serial_number }}"
                class="block w-full mt-2 text-gray-700 bg-white border border-gray-300 rounded-md dark:bg-gray-800 dark:text-gray-300 focus:border-blue-500 focus:outline-none focus:ring ">
        </div>
        <div class="mt-4 flex justify-around items-center flex-col md:flex-row">
            <img src=" {{ $hardware->hw_image }}" alt="{{ $hardware->hw_name }}" class="h-52 object-cover">
            <div class="flex flex-col items-center" id="qrCode">
                QR Code
                {{$qrCode}}

            </div>


        </div>
        <div class="mt-4">
            <label class="text-gray-700 dark:text-gray-200" for="hardware_description">Hardware Description</label>
            <textarea name="hardware_description" id="hardware_description" type="text" rows="4" disabled
                class="block w-full mt-2 text-gray-700 bg-white border border-gray-300 rounded-md dark:bg-gray-800 dark:text-gray-300 focus:border-blue-500 focus:outline-none focus:ring">{{ $hardware->hw_description }}</textarea>
        </div>
        <div class="mt-3 flex  space-x-2">
            <button type="button"
                onclick="downloadQRCode('{{ $hardware->hw_name }}','{{ $hardware->hw_serial_number }}')"
                class="mt-3 text-white bg-[#2943D1] hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                Donwload QR Code
            </button>
            <a href="{{ route('master-data.hardware.edit', $hardware->id) }}"
                class="mt-3 text-white bg-[#F59E0B] hover:bg-yellow-800 focus:ring-4 focus:outline-none focus:ring-yellow-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-yellow-600 dark:hover:bg-yellow-700 dark:focus:ring-yellow-800">
                Edit
            </a>
            <a href="{{ route('master-data.hardware.index') }}"
                class="mt-3 text-white bg-black hover:bg-gray-800 focus:ring-4 focus:outline-none focus:ring-gray-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-gray-600 dark:hover:bg-gray-700 dark:focus:ring-gray-800">
                Back
            </a>

        </div>

    </div>

    @if($hardware->customer != null)
    <h1 class="text-3xl font-bold text-gray-800 dark:text-gray-200 mt-4">Customer Data</h1>

    <div class="bg-white rounded-lg shadow-lg dark:bg-gray-800 p-5 mt-5 ">
        <div class="grid grid-cols-1 gap-4 mt-4 md:grid-cols-2">

            <div>
                <label class="text-gray-700 dark:text-gray-200" for="group_name">Group Name</label>
                <input name="group_name" id="group_name" type="text" disabled
                    value="{{ $hardware->customer->group_name }}"
                    class="block w-full mt-2 text-gray-700 bg-white border border-gray-300 rounded-md dark:bg-gray-800 dark:text-gray-300 focus:border-blue-500 focus:outline-none focus:ring">

            </div>
            <div>
                <label class="text-gray-700 dark:text-gray-200" for="customer_name">Customer Name</label>
                <input name="customer_name" id="customer_name" type="text" disabled
                    value="{{ $hardware->customer->name }}"
                    class="block w-full mt-2 text-gray-700 bg-white border border-gray-300 rounded-md dark:bg-gray-800 dark:text-gray-300 focus:border-blue-500 focus:outline-none focus:ring">
            </div>
            <div>
                <label class="text-gray-700 dark:text-gray-200" for="customer_email">Customer Email</label>
                <input name="customer_email" id="customer_email" type="text" disabled
                    value="{{ $hardware->customer->email }}"
                    class="block w-full mt-2 text-gray-700 bg-white border border-gray-300 rounded-md dark:bg-gray-800 dark:text-gray-300 focus:border-blue-500 focus:outline-none focus:ring">
            </div>
            <div>
                <label class="text-gray-700 dark:text-gray-200" for="customer_phone_number">Customer Phone</label>
                <input name="customer_phone_number" id="customer_phone_number" type="text" disabled
                    value="{{ $hardware->customer->phone_number }}"
                    class="block w-full mt-2 text-gray-700 bg-white border border-gray-300 rounded-md dark:bg-gray-800 dark:text-gray-300 focus:border-blue-500 focus:outline-none focus:ring">
            </div>
            <div class="col-span-2">
                <label class="text-gray-700 dark:text-gray-200" for="customer_address">Customer Address</label>
                <input name="customer_address" id="customer_address" type="text" disabled
                    value="{{ $hardware->customer->address }}"
                    class="block w-full mt-2 text-gray-700 bg-white border border-gray-300 rounded-md dark:bg-gray-800 dark:text-gray-300 focus:border-blue-500 focus:outline-none focus:ring">
            </div>

            <div>
                <label class="text-gray-700 dark:text-gray-200" for="customer_pic_process">Customer PIC Process
                </label>
                <input name="customer_pic_process" id="customer_pic_process" type="text" disabled
                    value="{{ $hardware->customer->pic_process }}"
                    class="block w-full mt-2 text-gray-700 bg-white border border-gray-300 rounded-md dark:bg-gray-800 dark:text-gray-300 focus:border-blue-500 focus:outline-none focus:ring">
            </div>

            <div>
                <label class="text-gray-700 dark:text-gray-200" for="customer_pic_process_phone_number">Customer PIC
                    Process Phone Number</label>
                <input name="customer_pic_process_phone_number" id="customer_pic_process_phone_number" type="text"
                    disabled value="{{ $hardware->customer->pic_process_phone_number }}"
                    class="block w-full mt-2 text-gray-700 bg-white border border-gray-300 rounded-md dark:bg-gray-800 dark:text-gray-300 focus:border-blue-500 focus:outline-none focus:ring">
            </div>

            <div>
                <label class="text-gray-700 dark:text-gray-200" for="customer_pic_installation">Customer PIC
                    Installation </label>
                <input name="customer_pic_installation" id="customer_pic_installation" type="text" disabled
                    value="{{ $hardware->customer->pic_installation }}"
                    class="block w-full mt-2 text-gray-700 bg-white border border-gray-300 rounded-md dark:bg-gray-800 dark:text-gray-300 focus:border-blue-500 focus:outline-none focus:ring">
            </div>

            <div>
                <label class="text-gray-700 dark:text-gray-200" for="customer_pic_installation_phone_number">Customer
                    PIC Installation Phone Number</label>
                <input name="customer_pic_installation_phone_number" id="customer_pic_installation_phone_number"
                    type="text" disabled value="{{ $hardware->customer->pic_installation_phone_number }}"
                    class="block w-full mt-2 text-gray-700 bg-white border border-gray-300 rounded-md dark:bg-gray-800 dark:text-gray-300 focus:border-blue-500 focus:outline-none focus:ring">
            </div>

            <div>
                <label class="text-gray-700 dark:text-gray-200" for="customer_pic_financial">Customer PIC Financial
                </label>
                <input name="customer_pic_financial" id="customer_pic_financial" type="text" disabled
                    value="{{ $hardware->customer->pic_financial }}"
                    class="block w-full mt-2 text-gray-700 bg-white border border-gray-300 rounded-md dark:bg-gray-800 dark:text-gray-300 focus:border-blue-500 focus:outline-none focus:ring">
            </div>

            <div>
                <label class="text-gray-700 dark:text-gray-200" for="customer_pic_financial_phone_number">Customer PIC
                    Financial Phone Number</label>
                <input name="customer_pic_financial_phone_number" id="customer_pic_financial_phone_number" type="text"
                    disabled value="{{ $hardware->customer->pic_financial_phone_number }}"
                    class="block w-full mt-2 text-gray-700 bg-white border border-gray-300 rounded-md dark:bg-gray-800 dark:text-gray-300 focus:border-blue-500 focus:outline-none focus:ring">
            </div>


        </div>
    </div>
    @endif
</div>




@endsection