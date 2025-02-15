@extends('layouts.app')
@section('title')
Dashboard
@endsection

@section('header')
@vite(['resources/js/tracking.js'])

<style>
    .top-100 {
        top: 100%
    }

    .bottom-100 {
        bottom: 100%
    }

    .max-h-select {
        max-height: 300px;
    }
</style>
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.3/dist/cdn.min.js"></script>

@if (session('login'))
<script>
    window.onload = () => {
        const welcomeModal = document.getElementById('welcomeModal');
        const modal = new Modal(welcomeModal);
        modal.show();
    }
</script>
@endif
<script>
    function selectConfigs() {
    return {
        filter: '',
        show: false,
        selected: null,
        focusedOptionIndex: null,
        options: null,
        close() { 
          this.show = false;
          this.filter = this.selectedName();
          this.focusedOptionIndex = this.selected ? this.focusedOptionIndex : null;
        },
        open() { 
          this.show = true; 
          this.filter = '';
        },
        toggle() { 
          if (this.show) {
            this.close();
          }
          else {
            this.open()
          }
        },
        isOpen() { return this.show === true },
        selectedName() { return this.selected ? this.selected.name  : this.filter; },
        classOption(id, index) {
          const isSelected = this.selected ? (id == this.selected.id) : false;
          const isFocused = (index == this.focusedOptionIndex);
          return {
            'cursor-pointer w-full border-gray-100 border-b hover:bg-blue-50': true,
            'bg-blue-100': isSelected,
            'bg-blue-50': isFocused
          };
        },
        fetchOptions() {
          fetch('{{ route('get-teknisi') }}')
            .then(response => response.json())
            .then(data => this.options = data.data);
        },
        filteredOptions() {
          return this.options
            ? this.options.filter(option => {
                return (option.name.toLowerCase().indexOf(this.filter) > -1) 
            })
           : {}
        },
        onOptionClick(index) {
          this.focusedOptionIndex = index;
          document.getElementById('user_id').value = this.filteredOptions()[index].id;
          this.selectOption();
        },
        selectOption() {
          if (!this.isOpen()) {
            return;
          }
          this.focusedOptionIndex = this.focusedOptionIndex ?? 0;
          const selected = this.filteredOptions()[this.focusedOptionIndex]
          if (this.selected && this.selected.id == selected.id) {
            this.filter = '';
            this.selected = null;
          }
          else {
            this.selected = selected;
            this.filter = this.selectedName();
          }
          this.close();
        },
        focusPrevOption() {
          if (!this.isOpen()) {
            return;
          }
          const optionsNum = Object.keys(this.filteredOptions()).length - 1;
          if (this.focusedOptionIndex > 0 && this.focusedOptionIndex <= optionsNum) {
            this.focusedOptionIndex--;
          }
          else if (this.focusedOptionIndex == 0) {
            this.focusedOptionIndex = optionsNum;
          }
        },
        focusNextOption() {
          const optionsNum = Object.keys(this.filteredOptions()).length - 1;
          if (!this.isOpen()) {
            this.open();
          }
          if (this.focusedOptionIndex == null || this.focusedOptionIndex == optionsNum) {
            this.focusedOptionIndex = 0;
          }
          else if (this.focusedOptionIndex >= 0 && this.focusedOptionIndex < optionsNum) {
            this.focusedOptionIndex++;
          }
        }
    }
   
}
</script>

@endsection

@section('content')


<div class="mt-14  space-y-4">
    <h1 class="text-3xl font-bold text-gray-800 dark:text-gray-200">Dashboard</h1>


    <div class="mx-auto bg-white rounded-lg shadow-lg dark:bg-gray-800  ">
        <div class="mb-4 border-b border-gray-200 dark:border-gray-700">
            <ul class="flex flex-wrap -mb-px text-sm font-medium text-center w-full" id="default-tab"
                data-tabs-toggle="#default-tab-content" role="tablist">
                <li class="flex-1" role="presentation">
                    <button
                        class="inline-block w-full p-4 border-b-2 rounded-t-lg text-lg font-semibold text-gray-800 dark:text-gray-100 hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300"
                        id="permohonanBaru-tab" data-tabs-target="#permohonanBaru" type="button" role="tab"
                        aria-controls="permohonanBaru" aria-selected="false">Permohonan Baru</button>
                </li>
                <li class="flex-1" role="presentation">
                    <button
                        class="inline-block w-full p-4 border-b-2 rounded-t-lg text-lg font-semibold text-gray-800 dark:text-gray-100 hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300"
                        id="permohonanSelesai-tab" data-tabs-target="#permohonanSelesai" type="button" role="tab"
                        aria-controls="permohonanSelesai" aria-selected="false">Permohonan Selesai</button>
                </li>
            </ul>
        </div>
        <div id="default-tab-content">
            <div class="hidden p-4 rounded-lg bg-gray-50 dark:bg-gray-800" id="permohonanBaru" role="tabpanel"
                aria-labelledby="permohonanBaru-tab">
                <table id="tablePermohonanBaru" class="table-auto w-full">
                    <thead>
                        <tr>

                            <th data-type="date" data-format="YYYY/DD/MM">
                                <span class="flex items-center">
                                    NO TIKET
                                    <svg class="w-4 h-4 ms-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                        width="24" height="24" fill="none" viewBox="0 0 24 24">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-width="2" d="m8 15 4 4 4-4m0-6-4-4-4 4" />
                                    </svg>
                                </span>
                            </th>
                            <th>
                                <span class="flex items-center">
                                    Tanggal Permohonan
                                    <svg class="w-4 h-4 ms-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                        width="24" height="24" fill="none" viewBox="0 0 24 24">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-width="2" d="m8 15 4 4 4-4m0-6-4-4-4 4" />
                                    </svg>
                                </span>
                            </th>
                            <th>
                                <span class="flex items-center">
                                    Nama Pemohon
                                    <svg class="w-4 h-4 ms-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                        width="24" height="24" fill="none" viewBox="0 0 24 24">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-width="2" d="m8 15 4 4 4-4m0-6-4-4-4 4" />
                                    </svg>
                                </span>
                            </th>
                            <th>
                                <span class="flex items-center">
                                    Nomor Whatsapp
                                    <svg class="w-4 h-4 ms-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                        width="24" height="24" fill="none" viewBox="0 0 24 24">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-width="2" d="m8 15 4 4 4-4m0-6-4-4-4 4" />
                                    </svg>
                                </span>
                            </th>
                            <th>
                                <span class="flex items-center">
                                    Keperluan
                                    <svg class="w-4 h-4 ms-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                        width="24" height="24" fill="none" viewBox="0 0 24 24">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-width="2" d="m8 15 4 4 4-4m0-6-4-4-4 4" />
                                    </svg>
                                </span>
                            </th>
                            <th>
                                <span class="flex items-center">
                                    Status CSO
                                    <svg class="w-4 h-4 ms-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                        width="24" height="24" fill="none" viewBox="0 0 24 24">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-width="2" d="m8 15 4 4 4-4m0-6-4-4-4 4" />
                                    </svg>
                                </span>
                            </th>
                            <th>
                                <span class="flex items-center">
                                    CSO
                                    <svg class="w-4 h-4 ms-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                        width="24" height="24" fill="none" viewBox="0 0 24 24">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-width="2" d="m8 15 4 4 4-4m0-6-4-4-4 4" />
                                    </svg>
                                </span>
                            </th>
                            <th>
                                <span class="flex items-center">
                                    TEKNISI
                                    <svg class="w-4 h-4 ms-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                        width="24" height="24" fill="none" viewBox="0 0 24 24">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-width="2" d="m8 15 4 4 4-4m0-6-4-4-4 4" />
                                    </svg>
                                </span>
                            </th>

                            <th>
                                <span class="flex items-center">
                                    Status Teknisi
                                    <svg class="w-4 h-4 ms-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                        width="24" height="24" fill="none" viewBox="0 0 24 24">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-width="2" d="m8 15 4 4 4-4m0-6-4-4-4 4" />
                                    </svg>
                                </span>
                            </th>
                            <th>
                                <span class="flex items-center">
                                    Status Customer
                                    <svg class="w-4 h-4 ms-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                        width="24" height="24" fill="none" viewBox="0 0 24 24">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-width="2" d="m8 15 4 4 4-4m0-6-4-4-4 4" />
                                    </svg>
                                </span>
                            </th>

                            <th>
                                <span class="flex items-center">
                                    Aksi
                                </span>
                            </th>
                        </tr>
                    </thead>

                </table>
            </div>
            <div class="hidden p-4 rounded-lg bg-gray-50 dark:bg-gray-800" id="permohonanSelesai" role="tabpanel"
                aria-labelledby="permohonanSelesai-tab">
                <table id="tablePermohonanSelesai" class="table-auto w-full">
                    <thead>
                        <tr>

                            <th data-type="date" data-format="YYYY/DD/MM">
                                <span class="flex items-center">
                                    NO TIKET
                                    <svg class="w-4 h-4 ms-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                        width="24" height="24" fill="none" viewBox="0 0 24 24">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-width="2" d="m8 15 4 4 4-4m0-6-4-4-4 4" />
                                    </svg>
                                </span>
                            </th>
                            <th>
                                <span class="flex items-center">
                                    Tanggal Permohonan
                                    <svg class="w-4 h-4 ms-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                        width="24" height="24" fill="none" viewBox="0 0 24 24">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-width="2" d="m8 15 4 4 4-4m0-6-4-4-4 4" />
                                    </svg>
                                </span>
                            </th>
                            <th>
                                <span class="flex items-center">
                                    Nama Pemohon
                                    <svg class="w-4 h-4 ms-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                        width="24" height="24" fill="none" viewBox="0 0 24 24">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-width="2" d="m8 15 4 4 4-4m0-6-4-4-4 4" />
                                    </svg>
                                </span>
                            </th>
                            <th>
                                <span class="flex items-center">
                                    Nomor Whatsapp
                                    <svg class="w-4 h-4 ms-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                        width="24" height="24" fill="none" viewBox="0 0 24 24">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-width="2" d="m8 15 4 4 4-4m0-6-4-4-4 4" />
                                    </svg>
                                </span>
                            </th>
                            <th>
                                <span class="flex items-center">
                                    Keperluan
                                    <svg class="w-4 h-4 ms-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                        width="24" height="24" fill="none" viewBox="0 0 24 24">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-width="2" d="m8 15 4 4 4-4m0-6-4-4-4 4" />
                                    </svg>
                                </span>
                            </th>
                            <th>
                                <span class="flex items-center">
                                    Status CSO
                                    <svg class="w-4 h-4 ms-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                        width="24" height="24" fill="none" viewBox="0 0 24 24">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-width="2" d="m8 15 4 4 4-4m0-6-4-4-4 4" />
                                    </svg>
                                </span>
                            </th>
                            <th>
                                <span class="flex items-center">
                                    CSO
                                    <svg class="w-4 h-4 ms-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                        width="24" height="24" fill="none" viewBox="0 0 24 24">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-width="2" d="m8 15 4 4 4-4m0-6-4-4-4 4" />
                                    </svg>
                                </span>
                            </th>
                            <th>
                                <span class="flex items-center">
                                    TEKNISI
                                    <svg class="w-4 h-4 ms-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                        width="24" height="24" fill="none" viewBox="0 0 24 24">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-width="2" d="m8 15 4 4 4-4m0-6-4-4-4 4" />
                                    </svg>
                                </span>
                            </th>

                            <th>
                                <span class="flex items-center">
                                    Status Teknisi
                                    <svg class="w-4 h-4 ms-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                        width="24" height="24" fill="none" viewBox="0 0 24 24">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-width="2" d="m8 15 4 4 4-4m0-6-4-4-4 4" />
                                    </svg>
                                </span>
                            </th>
                            <th>
                                <span class="flex items-center">
                                    Status Customer
                                    <svg class="w-4 h-4 ms-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                        width="24" height="24" fill="none" viewBox="0 0 24 24">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-width="2" d="m8 15 4 4 4-4m0-6-4-4-4 4" />
                                    </svg>
                                </span>
                            </th>

                            <th>
                                <span class="flex items-center">
                                    Aksi
                                </span>
                            </th>
                        </tr>
                    </thead>

                </table>
            </div>
        </div>
    </div>

    <div id="prosesModal" tabindex="-1" aria-hidden="true"
        class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
        <div class="relative p-4 w-full max-w-4xl max-h-full">
            <!-- Modal content -->
            <div class="relative bg-white rounded-lg shadow dark:bg-gray-700 p-4 md:p-5">
                <!-- Modal body -->
                <div class="flex flex-row justify-around">

                    <div class="bg-[#F9FAFB] w-full">
                        <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-200">Detail Permohonan</h1>
                        <p class="text-lg font-bold">ID TICKET</p>
                        <p class="text-base text-gray-600" id="idTicket"></p>
                        <p class="text-lg font-bold">Nama Pemohon</p>
                        <p class="text-base text-gray-600" id="namaPemohon"></p>
                        <p class="text-lg font-bold">Nomor Whatsapp</p>
                        <p class="text-base text-gray-600" id="noWa"></p>

                        <p class="text-lg font-bold">Keperluan</p>
                        <p class="text-base text-gray-600" id="keperluan"></p>
                        <p class="text-lg font-bold">Deskripsi Keperluan</p>
                        <p class="text-base text-gray-600" id="deskripsiKeperluan"></p>

                    </div>
                    <div class="bg-[#F9FAFB] w-full">
                        <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-200">Detail Customer</h1>
                        <p class="text-lg font-bold">Nama Customer</p>
                        <p class="text-base text-gray-600" id="namaCustomer"></p>
                        <p class="text-lg font-bold">Group Customer</p>
                        <p class="text-base text-gray-600" id="groupCustomer"></p>
                        <p class="text-lg font-bold">Alamat</p>
                        <p class="text-base text-gray-600" id="alamat"></p>
                        <p class="text-lg font-bold">PIC Process</p>
                        <p class="text-base text-gray-600" id="picProcess"></p>
                        <p class="text-lg font-bold">PIC Process Phone Number</p>
                        <p class="text-base text-gray-600" id="picProcessPhoneNumber"></p>
                        <p class="text-lg font-bold">PIC Installation</p>
                        <p class="text-base text-gray-600" id="picInstallation"></p>
                        <p class="text-lg font-bold">PIC Installation Phone Number</p>
                        <p class="text-base text-gray-600" id="picInstallationPhoneNumber"></p>
                        <p class="text-lg font-bold">PIC Financial</p>
                        <p class="text-base text-gray-600" id="picFinancial"></p>
                        <p class="text-lg font-bold">PIC Financial Phone Number</p>
                        <p class="text-base text-gray-600" id="picFinancialPhoneNumber"></p>
                    </div>
                    <div class="bg-[#F9FAFB] w-full">
                        <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-200">Detail Hardware</h1>
                        <p class="text-lg font-bold">Hardware Name</p>
                        <p class="text-base text-gray-600" id="hardwareName"></p>
                        <p class="text-lg font-bold">Hardware Type</p>
                        <p class="text-base text-gray-600" id="hardwareType"></p>
                        <p class="text-lg font-bold">Hardware Brand</p>
                        <p class="text-base text-gray-600" id="hardwareBrand"></p>
                        <p class="text-lg font-bold">Hardware Serial Number</p>
                        <p class="text-base text-gray-600" id="hardwareSerialNumber"></p>
                        <p class="text-lg font-bold">Hardware Image</p>
                        <img id="hardwareImage" class="h-32 w-32" src="" alt="">
                        <p class="text-lg font-bold">Contract Start Date</p>
                        <p class="text-base text-gray-600" id="contractStartDate"></p>
                        <p class="text-lg font-bold">Contract End Date</p>
                        <p class="text-base text-gray-600" id="contractEndDate"></p>
                    </div>

                    <div class="bg-[#F9FAFB] w-full hidden" id="detailTeknisi">
                        <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-200">Detail Teknisi</h1>
                        <p class="text-lg font-bold">Nama Teknisi</p>
                        <p class="text-base text-gray-600" id="namaTeknisi"></p>
                        <p class="text-lg font-bold">Nomor Whatsapp</p>
                        <p class="text-base text-gray-600" id="noWaTeknisi"></p>
                        <p class="text-lg font-bold">Lokasi Teknisi</p>
                        <p class="text-base text-gray-600" id="lokasiTeknisi"></p>
                        <button type="button" data-modal-target="trackModal" data-modal-toggle="trackModal"
                            id="trackTeknisi"
                            class="text-white mt-3 bg-[#2943D1] hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800 w-full">
                            Track Teknisi
                        </button>
                    </div>


                    <div class="bg-white w-full hidden" id="assignteknisi">
                        <form action="{{ route('assign-teknisi-web') }}" method="POST">
                            @csrf
                            <label class="text-gray-700 dark:text-gray-200 font-semibold" for="hardware_name">Tugaskan
                                Teknisi</label>
                            <input type="hidden" name="user_id" id="user_id">
                            <input type="hidden" name="ticket_id" id="ticket_id">
                            <div class="flex flex-col items-center">
                                <div class="w-full  flex flex-col items-center">
                                    <div class="w-full">
                                        <div x-data="selectConfigs()" x-init="fetchOptions()"
                                            class="flex flex-col items-center relative">
                                            <div class="w-full">
                                                <div @click.away="close()"
                                                    class="my-2 p-1 bg-white flex border border-gray-200 rounded">
                                                    <input x-model="filter" required
                                                        x-transition:leave="transition ease-in duration-100"
                                                        x-transition:leave-start="opacity-100"
                                                        x-transition:leave-end="opacity-0" @mousedown="open()"
                                                        @keydown.enter.stop.prevent="selectOption()"
                                                        @keydown.arrow-up.prevent="focusPrevOption()"
                                                        @keydown.arrow-down.prevent="focusNextOption()"
                                                        class="p-1 px-2 appearance-none outline-none w-full text-gray-800">
                                                    <div
                                                        class="text-gray-300 w-8 py-1 pl-2 pr-1 border-l flex items-center border-gray-200">
                                                        <button @click="toggle()" type="button"
                                                            class="cursor-pointer w-6 h-6 text-gray-600 outline-none focus:outline-none">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="100%"
                                                                height="100%" fill="none" viewBox="0 0 24 24"
                                                                stroke="currentColor" stroke-width="2"
                                                                stroke-linecap="round" stroke-linejoin="round">
                                                                <polyline x-show="!isOpen()" points="18 15 12 20 6 15">
                                                                </polyline>
                                                                <polyline x-show="isOpen()" points="18 15 12 9 6 15">
                                                                </polyline>
                                                            </svg>

                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                            <div x-show="isOpen()"
                                                class="absolute shadow bg-white top-100 z-40 w-full lef-0 rounded max-h-select overflow-y-auto svelte-5uyqqj">
                                                <div class="flex flex-col w-full">
                                                    <template x-for="(option, index) in filteredOptions()" :key="index">
                                                        <div @click="onOptionClick(index)"
                                                            :class="classOption(option.id, index)"
                                                            :aria-selected="focusedOptionIndex === index">
                                                            <div
                                                                class="flex w-full items-center p-2 pl-2 border-transparent border-l-2 relative hover:border-teal-100">

                                                                <div class="w-full items-center flex">
                                                                    <div class="mx-2 -mt-1">
                                                                        <span x-text="option.name">
                                                                        </span>

                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </template>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <button type="submit"
                                class=" mt-5 text-white bg-[#2943D1] hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800 w-full">
                                Tugaskan Teknisi
                            </button>
                        </form>
                    </div>
                </div>
                <div class="bg-[#F9FAFB]">
                    <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-200">Logs</h1>
                    <div class="overflow-y-auto h-96">
                        <table class="table-auto w-full border">
                            <thead>
                                <tr>
                                    <th>
                                        User
                                    </th>

                                    <th>
                                        Message
                                    </th>
                                    <th>
                                        Date Time
                                    </th>

                                </tr>
                            </thead>
                            <tbody id="logs" class="text-center">
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div id="welcomeModal" tabindex="-1" aria-hidden="true"
        class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto h-[calc(100%-1rem)] max-h-full">
        <div class="relative w-full max-w-md max-h-full">
            <div class="relative bg-white rounded-lg shadow dark:bg-gray-700 p-3">
                <div class="p-6 space-y-6">
                    <p class="text-base leading-relaxed text-gray-500 dark:text-gray-400">
                        Welcome back, {{ Auth::user()->name }}!
                    </p>
                </div>
                <button data-modal-hide="welcomeModal" type="button"
                    class="text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-500 dark:hover:bg-blue-600 dark:focus:ring-blue-800 w-full">Tutup</button>
            </div>
        </div>
    </div>

    <div id="trackModal" tabindex="-1"
        class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] max-h-full">
        <div class="relative w-full max-w-7xl max-h-full">
            <!-- Modal content -->
            <div class="relative bg-white rounded-lg shadow dark:bg-gray-700 p-3">

                <!-- Modal body -->
                <iframe id="map" class="w-full h-96" src="{{route('tracking')}}" frameborder="0"></iframe>
                <!-- Modal footer -->
                <button data-modal-hide="trackModal" type="button"
                    class="text-white w-full mt-2 bg-black hover:bg-gray-800 focus:ring-4 focus:outline-none focus:ring-gray-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-gray-600 dark:hover:bg-gray-700 dark:focus:ring-gray-800">
                    Close</button>
            </div>
        </div>
    </div>


    <script>
        const tes =async (data) => {
        const prosesModal = document.getElementById('prosesModal');
        const modal = new Modal(prosesModal); 
        modal.show();
        document.getElementById('ticket_id').value = data.id;
        document.getElementById('idTicket').innerText = data.no_ticket;
        document.getElementById('namaPemohon').innerText = data.nama_pelapor;
        document.getElementById('noWa').innerText = data.no_wa_pelapor;
        document.getElementById('alamat').innerText = data.customer.address;
        document.getElementById('keperluan').innerText = data.keperluan;
        document.getElementById('deskripsiKeperluan').innerText = data.message;
        document.getElementById('namaCustomer').innerText = data.customer.name;
        document.getElementById('groupCustomer').innerText = data.customer.group_name;
        document.getElementById('picProcess').innerText = data.customer.pic_process;
        document.getElementById('picProcessPhoneNumber').innerText = data.customer.pic_process_phone_number;
        document.getElementById('picInstallation').innerText = data.customer.pic_installation;
        document.getElementById('picInstallationPhoneNumber').innerText = data.customer.pic_installation_phone_number;
        document.getElementById('picFinancial').innerText = data.customer.pic_financial;
        document.getElementById('picFinancialPhoneNumber').innerText = data.customer.pic_financial_phone_number;
        
        document.getElementById('contractStartDate').innerText =formatDate(data.customer.contract_start);
        document.getElementById('contractEndDate').innerText = formatDate(data.customer.expired_at);
        document.getElementById('hardwareName').innerText = data.customer.hardware.hw_name;
        document.getElementById('hardwareType').innerText = data.customer.hardware.hw_type;
        document.getElementById('hardwareBrand').innerText = data.customer.hardware.hw_brand;
        document.getElementById('hardwareSerialNumber').innerText = data.customer.hardware.hw_serial_number;
        document.getElementById('hardwareImage').src = data.customer.hardware.hw_image;
        document.getElementById('logs').innerHTML = '';
        data.logs.forEach((log)=>{
                const tr = document.createElement('tr');
                const tdUser = document.createElement('td');
            
                const tdMessage = document.createElement('td');
                const tdDateTime = document.createElement('td');
                tdUser.innerText = log.user ? log.user.name : '-';

                tdMessage.innerText = log.message;
                tdDateTime.innerText = formatDate(log.created_at);
                tr.appendChild(tdUser);
     
                tr.appendChild(tdMessage);
                tr.appendChild(tdDateTime);
                document.getElementById('logs').appendChild(tr);
            });

        if(data.status_teknisi == '' || data.status_teknisi == null){
            document.getElementById('assignteknisi').classList.remove('hidden');
            document.getElementById('detailTeknisi').classList.add('hidden');
            
        }else{
       
        if(data.status_teknisi == 'Waiting' || data.status_teknisi == 'Accepted' || data.status_teknisi == 'On The Way' || data.status_teknisi == 'Arrived' || data.status_teknisi == 'Repairing' || data.status_teknisi == 'Done'){
            document.getElementById('trackTeknisi').classList.add('hidden');
            document.getElementById('assignteknisi').classList.add('hidden');
            document.getElementById('detailTeknisi').classList.remove('hidden');
            document.getElementById('namaTeknisi').innerText = data.teknisi.name;
            document.getElementById('noWaTeknisi').innerText = data.teknisi.phone_number ?? '-';
            document.getElementById('user_id').value = data.teknisi.id;
         
           await getALocationAddress(data.teknisi.latitude,data.teknisi.longitude);  
        }
        if(data.status_teknisi == 'On The Way'){
            document.getElementById('trackTeknisi').classList.remove('hidden');
            document.getElementById('map').src = `{{route('tracking')}}?id=${data.no_ticket}`;
        }
        
    }
    };

    const getALocationAddress = (lat,long)=>{
        fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${long}&zoom=18&addressdetails=1`)
        .then(response => response.json())
        .then(data => {
            document.getElementById('lokasiTeknisi').innerText = data.display_name;
        });
    };
    const formatDate = (date) => {
        const d = new Date(date);
        return `${d.getFullYear()}-${d.getMonth() + 1}-${d.getDate()} ${d.getHours()}:${d.getMinutes()}:${d.getSeconds()}`;
    };
    </script>
</div>




@endsection