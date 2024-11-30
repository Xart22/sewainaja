@extends('layouts.app')


@section('title')
Customer
@endsection

@section('header')
@vite(['resources/js/customer.js'])

@endsection



@section('content')


<div class="mt-14">
    <h1 class="text-3xl font-bold text-gray-800 dark:text-gray-200">Customer</h1>

    <div class="bg-white rounded-lg shadow-lg dark:bg-gray-800  p-5 mt-5">

        <table id="tableCustomer" class="table-auto w-full">
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
                            Group Name
                            <svg class="w-4 h-4 ms-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24"
                                height="24" fill="none" viewBox="0 0 24 24">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2" d="m8 15 4 4 4-4m0-6-4-4-4 4" />
                            </svg>
                        </span>
                    </th>
                    <th>
                        <span class="flex items-center">
                            Customer Name
                            <svg class="w-4 h-4 ms-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24"
                                height="24" fill="none" viewBox="0 0 24 24">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2" d="m8 15 4 4 4-4m0-6-4-4-4 4" />
                            </svg>
                        </span>
                    </th>
                    <th>
                        <span class="flex items-center">
                            Customer Email
                            <svg class="w-4 h-4 ms-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24"
                                height="24" fill="none" viewBox="0 0 24 24">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2" d="m8 15 4 4 4-4m0-6-4-4-4 4" />
                            </svg>
                        </span>
                    </th>
                    <th>
                        <span class="flex items-center">
                            Customer phone number
                            <svg class="w-4 h-4 ms-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24"
                                height="24" fill="none" viewBox="0 0 24 24">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2" d="m8 15 4 4 4-4m0-6-4-4-4 4" />
                            </svg>
                        </span>
                    </th>

                    <th>
                        <span class="flex items-center">
                            Customer Address
                            <svg class="w-4 h-4 ms-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24"
                                height="24" fill="none" viewBox="0 0 24 24">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2" d="m8 15 4 4 4-4m0-6-4-4-4 4" />
                            </svg>
                        </span>
                    </th>
                    <th>
                        <span class="flex items-center">
                            Contract Information
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

                @foreach ($customers as $customer)
                <tr>
                    <td>{{$loop->iteration}}</td>
                    <td>{{$customer->group_name}}</td>
                    <td>{{$customer->name}}</td>
                    <td>{{$customer->email}}</td>
                    <td>{{$customer->phone_number}}</td>
                    <td class="text-sm">{{$customer->address}}</td>
                    <td>
                        <div class="flex flex-col">
                            <span>Contract Start Date: {{date('d-m-Y', strtotime($customer->start_at))}}</span>
                            <span>Contract End Date: {{date('d-m-Y', strtotime($customer->expired_at))}}</span>
                            @if (date('d', strtotime($customer->expired_at)) - date('d') < 0) <span
                                class="text-red-500">Expired</span>
                                @else
                                <span>Contract Remaining: {{ date('d', strtotime($customer->expired_at)) - date('d') }}
                                    Days
                                </span>
                                @endif

                        </div>
                    </td>

                    <td>
                        <div class="flex justify-start space-x-2">
                            <a href="{{route('master-data.customer.show', $customer->id)}}"
                                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-full">
                                Detail
                            </a>
                            <a href="{{route('master-data.customer.edit', $customer->id)}}"
                                class="bg-yellow-300 hover:bg-yellow-400 text-white font-bold py-2 px-4 rounded-full">
                                Edit
                            </a>
                            <form action="{{route('master-data.customer.destroy', $customer->id)}}" method="post">
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




@endsection