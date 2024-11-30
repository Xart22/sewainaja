@extends('layouts.app')


@section('title')
Hardware
@endsection

@section('header')
@vite(['resources/js/hardware.js'])

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


<div class="mt-14">
    <h1 class="text-3xl font-bold text-gray-800 dark:text-gray-200">Hardware</h1>

    <div class="bg-white rounded-lg shadow-lg dark:bg-gray-800  p-5 mt-5">

        <table id="tableHardware" class="table-auto w-full">
            <thead>
                <tr>
                    <th>
                        <span class="flex items-center">
                            No
                            <svg class="w-4 h-4 ms-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24"
                                height="24" fill="none" viewBox="0 0 24 24">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2" d="m8 15 4 4 4-4m0-6-4-4-4 4" />
                            </svg>
                        </span>
                    </th>
                    <th data-type="date" data-format="YYYY/DD/MM">
                        <span class="flex items-center">
                            Hardware Name
                            <svg class="w-4 h-4 ms-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24"
                                height="24" fill="none" viewBox="0 0 24 24">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2" d="m8 15 4 4 4-4m0-6-4-4-4 4" />
                            </svg>
                        </span>
                    </th>
                    <th>
                        <span class="flex items-center">
                            Hardware Type
                            <svg class="w-4 h-4 ms-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24"
                                height="24" fill="none" viewBox="0 0 24 24">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2" d="m8 15 4 4 4-4m0-6-4-4-4 4" />
                            </svg>
                        </span>
                    </th>
                    <th>
                        <span class="flex items-center">
                            Hardware Brand
                            <svg class="w-4 h-4 ms-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24"
                                height="24" fill="none" viewBox="0 0 24 24">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2" d="m8 15 4 4 4-4m0-6-4-4-4 4" />
                            </svg>
                        </span>
                    </th>
                    <th>
                        <span class="flex items-center">
                            Hardware Model
                            <svg class="w-4 h-4 ms-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24"
                                height="24" fill="none" viewBox="0 0 24 24">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2" d="m8 15 4 4 4-4m0-6-4-4-4 4" />
                            </svg>
                        </span>
                    </th>
                    <th>
                        <span class="flex items-center">
                            Hardware Serial Number
                            <svg class="w-4 h-4 ms-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24"
                                height="24" fill="none" viewBox="0 0 24 24">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2" d="m8 15 4 4 4-4m0-6-4-4-4 4" />
                            </svg>
                        </span>
                    </th>



                    <th>
                        <span class="flex items-center">
                            Hardware Status
                            <svg class="w-4 h-4 ms-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24"
                                height="24" fill="none" viewBox="0 0 24 24">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2" d="m8 15 4 4 4-4m0-6-4-4-4 4" />
                            </svg>
                        </span>
                    </th>
                    <th>
                        <span class="flex items-center">
                            Hardware Location
                            <svg class="w-4 h-4 ms-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24"
                                height="24" fill="none" viewBox="0 0 24 24">
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
            <tbody>
                @foreach ($hardwares as $hardware)

                <tr>
                    <td>{{$loop->iteration}}</td>
                    <td>{{$hardware->hw_name}}</td>
                    <td>{{$hardware->hw_type}}</td>
                    <td>{{$hardware->hw_brand}}</td>
                    <td>{{$hardware->hw_model}}</td>
                    <td>{{$hardware->hw_serial_number}}</td>

                    <td class="{{$hardware->used_status == 1 ? 'text-green-500' : 'text-red-600'}}">
                        {{$hardware->used_status
                        == 1 ? 'Used' : 'Unused'}}</td>
                    <td>{{@$hardware->customer->name}}</td>
                    <td>
                        <div class="flex justify-start space-x-2">
                            <a href="{{route('master-data.hardware.show', $hardware->id)}}"
                                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-full">
                                Detail
                            </a>
                            <a href="{{route('master-data.hardware.edit', $hardware->id)}}"
                                class="bg-yellow-300 hover:bg-yellow-400 text-white font-bold py-2 px-4 rounded-full">
                                Edit
                            </a>
                            <form action="{{route('master-data.hardware.destroy', $hardware->id)}}" method="post">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-full">
                                    Delete
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach

            </tbody>
        </table>
    </div>

</div>

<div id="default-modal" tabindex="-1" aria-hidden="true"
    class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
    <div class="relative p-4 w-full max-w-2xl max-h-full">
        <!-- Modal content -->
        <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
            <!-- Modal header -->
            <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                    Copy Hardware
                </h3>
                <button type="button"
                    class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
                    data-modal-hide="default-modal">
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                    </svg>
                    <span class="sr-only">Close modal</span>
                </button>
            </div>
            <!-- Modal body -->
            <div class="p-4 md:p-5 space-y-4">

                <form action="{{route('master-data.hardware.copy')}}" method="post">
                    @csrf
                    <div>
                        <label class="text-gray-700 dark:text-gray-200" for="hardware_name">Hardware Name</label>
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
                    <div>
                        <label class="text-gray-700 dark:text-gray-200" for="hw_serial_number">Hardware Serial
                            Number</label>
                        <input name="hw_serial_number" id="hw_serial_number" type="text" required
                            value="{{ old('hw_serial_number') }}"
                            class="block w-full mt-2 text-gray-700 bg-white border border-gray-300 rounded-md dark:bg-gray-800 dark:text-gray-300 focus:border-blue-500 focus:outline-none focus:ring">

                    </div>
                    <button type="submit"
                        class=" mt-5 text-white bg-[#2943D1] hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800 w-full">
                        Create Hardware
                    </button>
                </form>

            </div>

        </div>
    </div>
</div>




@endsection