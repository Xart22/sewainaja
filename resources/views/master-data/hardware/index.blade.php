@extends('layouts.app')

@section('title', 'Hardware')

@section('header')
@vite(['resources/js/hardware.js', 'resources/js/bulk-select.js'])

<!-- AlpineJS -->
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.3/dist/cdn.min.js"></script>

<!-- Custom Styles -->
<style>
  .top-100 { top: 100%; }
  .bottom-100 { bottom: 100%; }
  .max-h-select { max-height: 300px; }
</style>

<!-- Hardware Select Config -->
<script>
function createSelectConfig(config) {
    return {
        filter: '',
        show: false,
        selected: null,
        focusedOptionIndex: null,
        options: [],
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
            this.show ? this.close() : this.open();
        },
        isOpen() {
            return this.show === true;
        },
        selectedName() {
            return this.selected ? config.displayLabel(this.selected) : this.filter;
        },
        classOption(id, index) {
            const isSelected = this.selected && id == this.selected.id;
            const isFocused = index == this.focusedOptionIndex;

            return {
                'cursor-pointer w-full border-b border-gray-100 hover:bg-blue-50': true,
                'bg-blue-100': isSelected,
                'bg-blue-50': isFocused,
            };
        },
        fetchOptions() {
            fetch(config.fetchUrl)
                .then(res => res.json())
                .then(data => {
                    this.options = Array.isArray(data) ? data : [];
                });
        },
        filteredOptions() {
            const normalizedFilter = (this.filter || '').toLowerCase();

            return this.options.filter(opt =>
                config.searchFields.some(field => String(opt[field] || '').toLowerCase().includes(normalizedFilter))
            );
        },
        onOptionClick(index) {
            this.focusedOptionIndex = index;
            const option = this.filteredOptions()[index];
            if (!option) {
                return;
            }

            const inputs = document.querySelectorAll(`input[name="${config.targetInputName}"]`);
            inputs.forEach(input => {
                input.value = option.id;
            });

            if (typeof config.onOptionSelected === 'function') {
                config.onOptionSelected(option);
            }

            this.selectOption();
        },
        selectOption() {
            if (!this.isOpen()) {
                return;
            }

            this.focusedOptionIndex = this.focusedOptionIndex ?? 0;
            const selected = this.filteredOptions()[this.focusedOptionIndex];
            if (!selected) {
                this.close();
                return;
            }

            if (this.selected && this.selected.id == selected.id) {
                this.filter = '';
                this.selected = null;
            } else {
                this.selected = selected;
                this.filter = this.selectedName();
            }

            this.close();
        },
        focusPrevOption() {
            if (!this.isOpen()) {
                return;
            }

            const maxIndex = this.filteredOptions().length - 1;
            this.focusedOptionIndex = this.focusedOptionIndex > 0 ? this.focusedOptionIndex - 1 : maxIndex;
        },
        focusNextOption() {
            if (!this.isOpen()) {
                this.open();
            }

            const maxIndex = this.filteredOptions().length - 1;
            this.focusedOptionIndex =
                this.focusedOptionIndex == null || this.focusedOptionIndex == maxIndex ? 0 : this.focusedOptionIndex + 1;
        }
    };
}

function selectConfigs() {
    return createSelectConfig({
        fetchUrl: "{{ route('hardware-data') }}",
        targetInputName: 'hardware_id',
        searchFields: ['hw_name', 'hw_type', 'hw_brand'],
        displayLabel: option => `${option.hw_name} ${option.hw_type}`,
    });
}

function selectUserConfigs() {
    return createSelectConfig({
        fetchUrl: "{{ route('customer-data') }}",
        targetInputName: 'customer_id',
        searchFields: ['name', 'group_name'],
        displayLabel: option => `${option.name} ${option.group_name}`,
        onOptionSelected: option => onCustomerSelected(option.id),
    });
}

function getCustomerContractUrl(customerId) {
    return "{{ route('customer-contracts', ['customer' => '__CUSTOMER__']) }}".replace('__CUSTOMER__', customerId);
}

function formatContractLabel(contract) {
    const status = contract.is_active ? 'Active' : 'Inactive';
    return `${contract.contract_start} - ${contract.contract_end} (${status})`;
}

function resetAssignContractOptions() {
    const select = document.getElementById('assign_customer_contract_id');
    const hint = document.getElementById('assign-contract-hint');
    if (!select || !hint) {
        return;
    }

    select.innerHTML = '<option value="">Pilih Kontrak</option>';
    select.value = '';
    select.disabled = true;
    hint.textContent = 'Pilih customer terlebih dahulu.';
}

function onCustomerSelected(customerId) {
    const select = document.getElementById('assign_customer_contract_id');
    const hint = document.getElementById('assign-contract-hint');

    if (!select || !hint || !customerId) {
        return;
    }

    fetch(getCustomerContractUrl(customerId))
        .then(res => res.json())
        .then(contracts => {
            select.innerHTML = '';

            if (!Array.isArray(contracts) || contracts.length === 0) {
                select.disabled = true;
                select.innerHTML = '<option value="">Tidak ada kontrak tersedia</option>';
                hint.textContent = 'Customer ini belum memiliki kontrak.';
                return;
            }

            const placeholder = document.createElement('option');
            placeholder.value = '';
            placeholder.textContent = 'Pilih Kontrak';
            select.appendChild(placeholder);

            contracts.forEach(contract => {
                const option = document.createElement('option');
                option.value = contract.id;
                option.textContent = formatContractLabel(contract);
                select.appendChild(option);
            });

            select.disabled = false;
            hint.textContent = 'Pilih kontrak untuk assignment hardware.';
        })
        .catch(() => {
            select.disabled = true;
            select.innerHTML = '<option value="">Gagal memuat kontrak</option>';
            hint.textContent = 'Terjadi kesalahan saat mengambil data kontrak.';
        });
}

function openModal(modalId) {
    const modal = document.getElementById(modalId);
    if (!modal) {
        return;
    }

    modal.classList.remove('hidden');
    modal.classList.add('flex');
    modal.setAttribute('aria-hidden', 'false');
}

function closeModal(modalId) {
    const modal = document.getElementById(modalId);
    if (!modal) {
        return;
    }

    modal.classList.remove('flex');
    modal.classList.add('hidden');
    modal.setAttribute('aria-hidden', 'true');
}

function openAssignModal(el) {
    const modalData = el.getAttribute('data-modal-data');
    const modal = document.getElementById('assign-modal');
    if (!modal) {
        return;
    }

    const input = modal.querySelector('input[name="hardware_id"]');
    if (input) {
        input.value = modalData;
    }

    const customerInput = modal.querySelector('input[name="customer_id"]');
    if (customerInput) {
        customerInput.value = '';
    }

    resetAssignContractOptions();

    openModal('assign-modal');
}
</script>
@endsection

@section('content')
<div class="mt-14">
    <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
        <h1 class="text-3xl font-bold text-gray-800 dark:text-gray-200">Hardware</h1>
        <div class="flex flex-wrap gap-2">
            <a href="{{ route('master-data.hardware.export-qr-pdf') }}"
                class="inline-flex items-center justify-center rounded-lg bg-rose-600 px-5 py-2.5 text-sm font-medium text-white hover:bg-rose-700 focus:outline-none focus:ring-4 focus:ring-rose-300 dark:bg-rose-500 dark:hover:bg-rose-600 dark:focus:ring-rose-800">
                Export QR PDF
            </a>
            <a href="{{ route('master-data.hardware.export') }}"
                class="inline-flex items-center justify-center rounded-lg bg-emerald-600 px-5 py-2.5 text-sm font-medium text-white hover:bg-emerald-700 focus:outline-none focus:ring-4 focus:ring-emerald-300 dark:bg-emerald-500 dark:hover:bg-emerald-600 dark:focus:ring-emerald-800">
                Export Excel
            </a>
        </div>
    </div>

    <div id="bulk-toolbar" class="hidden mt-4 flex flex-wrap items-center gap-2 rounded-lg bg-indigo-50 dark:bg-indigo-900/30 border border-indigo-200 dark:border-indigo-800 px-4 py-3">
        <span class="text-sm font-semibold text-indigo-800 dark:text-indigo-200"><span id="bulk-selected-count">0</span> item dipilih</span>
        <div class="flex flex-wrap gap-2 ms-auto">
            <button type="button" data-bulk-action data-bulk-form="bulk-export-form"
                class="rounded-lg bg-emerald-600 px-4 py-2 text-sm font-medium text-white hover:bg-emerald-700">
                Export Excel Terpilih
            </button>
            <button type="button" data-bulk-action data-bulk-form="bulk-qr-form"
                class="rounded-lg bg-rose-600 px-4 py-2 text-sm font-medium text-white hover:bg-rose-700">
                Export QR PDF Terpilih
            </button>
            <button type="button" data-bulk-action data-bulk-form="bulk-destroy-form" data-bulk-confirm="Hapus data hardware terpilih?"
                class="rounded-lg bg-red-600 px-4 py-2 text-sm font-medium text-white hover:bg-red-700">
                Hapus Terpilih
            </button>
        </div>
    </div>
    <form id="bulk-export-form" method="POST" action="{{ route('master-data.hardware.export-selected') }}" class="hidden">@csrf</form>
    <form id="bulk-qr-form" method="POST" action="{{ route('master-data.hardware.export-qr-pdf-selected') }}" class="hidden">@csrf</form>
    <form id="bulk-destroy-form" method="POST" action="{{ route('master-data.hardware.destroy-bulk') }}" class="hidden">@csrf @method('DELETE')</form>

    <div class="bg-white rounded-lg shadow-lg dark:bg-gray-800  p-5 mt-5">

        <table id="tableHardware" class="table-auto w-full" data-bulk-select>
            <thead>
                <tr>
                
                    <th class="w-10">
                        <input type="checkbox" class="select-all rounded border-gray-300">
                    </th>
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
           
                    <td class="text-center">
                        <input type="checkbox" class="row-select rounded border-gray-300" value="{{ $hardware->id }}">
                    </td>
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
                            @if($hardware->customer_id == null)
                            <button data-modal-data="{{ $hardware->id }}" onclick="openAssignModal(this)"
                                class="block text-white bg-green-500 hover:bg-green-600 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium  text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800 rounded-full btn-assign" type="button">
                                Assign
                             </button>
                            @else
                          <a  href="{{route('master-data.hardware.deassign', $hardware->id)}}"
                                class="block text-white bg-orange-500 hover:bg-orange-600 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium  text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800 rounded-full btn-assign" type="button">
                                Deassign
                          </a>
                            @endif
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

@include('master-data.hardware.partials.copy-modal')
@include('master-data.hardware.partials.import-modal')
@include('master-data.hardware.partials.assign-modal')

@endsection
