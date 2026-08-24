<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\CustomerContract;
use App\Models\Hardware;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

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

        return view('master-data.customer.create', ["hardwares" => Hardware::where('customer_id', null)->get()]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'group_name' => 'required|string|max:255',
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'nullable|email|max:255|unique:customers,email',
            'customer_phone_number' => 'required|string|max:255|unique:customers,phone_number',
            'customer_address' => 'required|string',
            'contract_start_date' => 'required|date',
            'contract_end_date' => 'required|date|after_or_equal:contract_start_date',
        ]);

        try {

            DB::beginTransaction();
            $customer = Customer::create([
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
                'contract_start' => $request->contract_start_date,
                'expired_at' => $request->contract_end_date,
            ]);

            $this->syncContractHistory(
                $customer,
                $request->contract_start_date,
                $request->contract_end_date
            );

            DB::commit();

            return redirect()->route('master-data.customer.index')->with('success', 'Customer created successfully');
        } catch (\Throwable $th) {
            DB::rollBack();

            if ($th instanceof ValidationException) {
                throw $th;
            }

            return redirect()->back()->with('error', 'Failed to create customer: ' . $th->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return view('master-data.customer.show', [
            'customer' => Customer::with(['hardware', 'contracts' => function ($query) {
                $query->orderByDesc('contract_end');
            }])->find($id)
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {

        return view('master-data.customer.edit', ['customer' => Customer::find($id), "hardwares" => Hardware::where('customer_id', null)->get()]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'group_name' => 'required|string|max:255',
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'nullable|email|max:255|unique:customers,email,' . $id,
            'customer_phone_number' => 'required|string|max:255|unique:customers,phone_number,' . $id,
            'customer_address' => 'required|string',
            'contract_start_date' => 'required|date',
            'contract_end_date' => 'required|date|after_or_equal:contract_start_date',
            'new_contract_start_date' => 'nullable|date|required_with:new_contract_end_date',
            'new_contract_end_date' => 'nullable|date|required_with:new_contract_start_date|after_or_equal:new_contract_start_date',
        ]);

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
                'contract_start' => $request->contract_start_date,
                'expired_at' => $request->contract_end_date,
            ]);

            $customer = Customer::findOrFail($id);
            $this->syncContractHistory(
                $customer,
                $request->contract_start_date,
                $request->contract_end_date
            );

            if ($request->filled('new_contract_start_date') && $request->filled('new_contract_end_date')) {
                $this->syncContractHistory(
                    $customer,
                    $request->new_contract_start_date,
                    $request->new_contract_end_date
                );
            }

            DB::commit();

            return redirect()->route('master-data.customer.index')->with('success', 'Customer updated successfully');
        } catch (\Throwable $th) {
            DB::rollBack();

            if ($th instanceof ValidationException) {
                throw $th;
            }

            return redirect()->back()->with('error', 'Failed to update customer ' . $th->getMessage());
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
            Hardware::where('customer_id', $id)->update(['customer_id' => null, 'used_status' => 0]);
            DB::commit();

            return redirect()->route('master-data.customer.index')->with('success', 'Customer deleted successfully');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', 'Failed to delete customer');
        }
    }


    public function getData(Request $request)
    {
        $data = Customer::selectRaw('MIN(id) as id, name, group_name, email, phone_number')
            ->groupBy('name', 'group_name', 'email', 'phone_number')
            ->get();

        return response()->json($data);
    }

    public function getContracts(Customer $customer)
    {
        $now = now();

        $contracts = $customer->contracts()
            ->orderByDesc('contract_end')
            ->get(['id', 'contract_start', 'contract_end'])
            ->map(function ($contract) use ($now) {
                return [
                    'id' => $contract->id,
                    'contract_start' => optional($contract->contract_start)->format('Y-m-d H:i:s'),
                    'contract_end' => optional($contract->contract_end)->format('Y-m-d H:i:s'),
                    'is_active' => $now->between($contract->contract_start, $contract->contract_end),
                ];
            })
            ->values();

        return response()->json($contracts);
    }

    private function syncContractHistory(Customer $customer, $contractStart, $contractEnd): void
    {
        if (!$contractStart || !$contractEnd) {
            return;
        }

        $startAt = Carbon::parse($contractStart);
        $endAt = Carbon::parse($contractEnd);

        $hasOverlappingContract = CustomerContract::where('customer_id', $customer->id)
            ->where(function ($query) use ($startAt, $endAt) {
                $query->where('contract_start', '<=', $endAt)
                    ->where('contract_end', '>=', $startAt);
            })
            ->where(function ($query) use ($startAt, $endAt) {
                $query->where('contract_start', '!=', $startAt)
                    ->orWhere('contract_end', '!=', $endAt);
            })
            ->exists();

        if ($hasOverlappingContract) {
            throw ValidationException::withMessages([
                'contract_end_date' => 'Periode kontrak overlap dengan kontrak customer yang sudah ada.',
            ]);
        }

        CustomerContract::firstOrCreate(
            [
                'customer_id' => $customer->id,
                'contract_start' => $contractStart,
                'contract_end' => $contractEnd,
            ],
            [
                'is_active' => true,
            ]
        );
    }
}
