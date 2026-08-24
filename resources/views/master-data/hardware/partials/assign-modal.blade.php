<div id="assign-modal" tabindex="-1" aria-hidden="true"
    class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
    <div class="relative p-4 w-full max-w-2xl max-h-full">
        <!-- Modal content -->
        <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
            <!-- Modal header -->
            <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                    Assign Hardware to Customer
                </h3>
                <button type="button"
                    class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
                    data-modal-hide="assign-modal" onclick="closeModal('assign-modal')">
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

                <form action="{{route('master-data.hardware.assign')}}" method="post">
                    @csrf
                    <input type="hidden" name="hardware_id" >
                    <div>
                        <label class="text-gray-700 dark:text-gray-200" for="hardware_name">Assign Hardware to
                            Customer</label>
                        <input type="hidden" name="customer_id" id="customer_id">
                        <div class="flex flex-col items-center">
                            <div class="w-full  flex flex-col items-center">
                                <div class="w-full">
                                    <div x-data="selectUserConfigs()" x-init="fetchOptions()"
                                        class="flex flex-col items-center relative">
                                        <div class="w-full">
                                            <div @click.away="close()"
                                                class="my-2 p-1 bg-white flex border border-gray-200 rounded">
                                                <input x-model="filter"
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
                                                                    <span
                                                                        x-text="option.name + ' | ' + option.group_name">
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
                        <label class="text-gray-700 dark:text-gray-200" for="assign_customer_contract_id">
                            Customer Contract
                        </label>
                        <select name="customer_contract_id" id="assign_customer_contract_id" disabled
                            class="block w-full mt-2 text-gray-700 bg-white border border-gray-300 rounded-md dark:bg-gray-800 dark:text-gray-300 focus:border-blue-500 focus:outline-none focus:ring">
                            <option value="">Pilih Kontrak</option>
                        </select>
                        <p id="assign-contract-hint" class="mt-2 text-xs text-gray-500 dark:text-gray-400">
                            Pilih customer terlebih dahulu.
                        </p>
                    </div>
               
                    <button type="submit"
                        class=" mt-5 text-white bg-[#2943D1] hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800 w-full">
                      Save
                    </button>
                </form>

            </div>

        </div>
    </div>
</div>
