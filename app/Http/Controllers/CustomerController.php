<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\HardwareInformation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('master-data.customer.index', ['customers' => Customer::all()]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

        return view('master-data.customer.create', ["hardwares" => HardwareInformation::where('customer_id', null)->get()]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            DB::beginTransaction();
            $id = Customer::create([
                'group_name' => $request->group_name,
                'name' => $request->customer_name,
                'email' => $request->customer_email,
                'phone_number' => $request->customer_phone_number,
                'address' => $request->customer_address,
                'pic_process' => $request->customer_pic_process,
                'pic_financial' => $request->customer_pic_financial,
                'pic_installation' => $request->customer_pic_installation,
                'pic_process_phone_number' => $request->customer_pic_process_phone_number,
                'pic_financial_phone_number' => $request->customer_pic_financial_phone_number,
                'pic_installation_phone_number' => $request->customer_pic_installation_phone_number,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'hw_id' => $request->hardware_information,
                'contract_start' => $request->contract_start_date,
                'expired_at' => $request->contract_end_date,
            ])->id;
            HardwareInformation::where('id', $request->hardware_information)->update(['customer_id' => $id, 'used_status' => 1]);
            DB::commit();

            return redirect()->route('master-data.customer.index')->with('success', 'Customer created successfully');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', 'Failed to create customer');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return view('master-data.customer.show', ['customer' => Customer::find($id)]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        return view('master-data.customer.edit', ['customer' => Customer::find($id), "hardwares" => HardwareInformation::where('customer_id', null)->get()]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            DB::beginTransaction();
            Customer::where('id', $id)->update([
                'group_name' => $request->group_name,
                'name' => $request->customer_name,
                'email' => $request->customer_email,
                'phone_number' => $request->customer_phone_number,
                'address' => $request->customer_address,
                'pic_process' => $request->customer_pic_process,
                'pic_financial' => $request->customer_pic_financial,
                'pic_installation' => $request->customer_pic_installation,
                'pic_process_phone_number' => $request->customer_pic_process_phone_number,
                'pic_financial_phone_number' => $request->customer_pic_financial_phone_number,
                'pic_installation_phone_number' => $request->customer_pic_installation_phone_number,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'hw_id' => $request->hardware_information ? $request->hardware_information : Customer::find($id)->hw_id,
                'contract_start' => $request->contract_start_date,
                'expired_at' => $request->contract_end_date,
            ]);

            if ($request->hardware_information) {
                HardwareInformation::where('customer_id', $id)->update(['customer_id' => null, 'used_status' => 0]);
                HardwareInformation::where('id', $request->hardware_information)->update(['customer_id' => $id, 'used_status' => 1]);
            }
            DB::commit();

            return redirect()->route('master-data.customer.index')->with('success', 'Customer updated successfully');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', 'Failed to update customer');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            DB::beginTransaction();
            Customer::where('id', $id)->delete();
            HardwareInformation::where('customer_id', $id)->update(['customer_id' => null, 'used_status' => 0]);
            DB::commit();

            return redirect()->route('master-data.customer.index')->with('success', 'Customer deleted successfully');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', 'Failed to delete customer');
        }
    }
}
