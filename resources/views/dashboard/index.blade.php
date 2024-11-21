@extends('layouts.app')
@section('title')
Dashboard
@endsection

@section('header')
@vite(['resources/js/Dashboard.js'])

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
        selectedName() { return this.selected ? this.selected.hw_name + ' ' + this.selected.hw_type : this.filter; },
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
          fetch('{{ route('hardware-data') }}')
            .then(response => response.json())
            .then(data => this.options = data);
        },
        filteredOptions() {
          return this.options
            ? this.options.filter(option => {
                return (option.hw_name.toLowerCase().indexOf(this.filter) > -1) 
                  || (option.hw_type.toLowerCase().indexOf(this.filter) > -1)
                  || (option.hw_brand.toLowerCase().indexOf(this.filter) > -1)
            })
           : {}
        },
        onOptionClick(index) {
          this.focusedOptionIndex = index;
          document.getElementById('hardware_id').value = this.filteredOptions()[index].id;
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
                                    ID TIKET
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
                                    ID TIKET
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
        <div class="relative p-4 w-full md:w-1/2 mx-auto">
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
                    </div>


                    <div class="bg-white w-full hidden" id="assignTechnician">
                        <div>
                            <label class="text-gray-700 dark:text-gray-200 font-semibold" for="hardware_name">Tugaskan
                                Teknisi</label>
                            <input type="hidden" name="hardware_id" id="hardware_id">
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
                                                                <div class="w-6 flex flex-col items-center">
                                                                    <div
                                                                        class="flex relative w-5 h-5  justify-center items-center m-1 mr-2  mt-1 rounded-full ">
                                                                        <img class="rounded-full" alt="A"
                                                                            x-bind:src="option.hw_image">
                                                                    </div>
                                                                </div>
                                                                <div class="w-full items-center flex">
                                                                    <div class="mx-2 -mt-1">
                                                                        <span
                                                                            x-text="option.hw_name + ' | ' + option.hw_type">
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

                        </div>
                    </div>
                </div>


            </div>

        </div>
    </div>
    <script>
        const tes =async (data) => {
        const prosesModal = document.getElementById('prosesModal');
        const modal = new Modal(prosesModal); 
        modal.show();

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

        if(data.status_teknisi == '' || data.status_teknisi == null){
            document.getElementById('assignTechnician').classList.remove('hidden');
            document.getElementById('detailTeknisi').classList.add('hidden');
            
        }else{
       
        if(data.status_teknisi == 'Waiting' || data.status_teknisi == 'Accepted' || data.status_teknisi == 'On The Way' || data.status_teknisi == 'Arrived' || data.status_teknisi == 'Repairing' || data.status_teknisi == 'Done'){

            document.getElementById('assignTechnician').classList.add('hidden');
            document.getElementById('detailTeknisi').classList.remove('hidden');
            document.getElementById('namaTeknisi').innerText = data.technician.name;
            document.getElementById('noWaTeknisi').innerText = data.technician.phone_number ? data.technician.phone_number : '-';
           await getALocationAddress(data.technician.latitude,data.technician.longitude);
        }
    }
    };

    const getALocationAddress = (lat,long)=>{
        fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${long}&zoom=18&addressdetails=1`)
        .then(response => response.json())
        .then(data => {
            document.getElementById('lokasiTeknisi').innerText = data.display_name;
        });
    }

    const formatDate = (date) => {
        const d = new Date(date);
        return `${d.getFullYear()}-${d.getMonth() + 1}-${d.getDate()}`;
    };
    </script>
</div>




@endsection