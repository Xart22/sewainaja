<?php

namespace App\Http\Controllers;

use App\Models\CustomerSupport;
use Illuminate\Http\Request;

class CustomerSupportDataController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($start_date, $end_date)
    {

        $start_date = date('Y-m-d', strtotime($start_date));
        $end_date = date('Y-m-d', strtotime($end_date));

        $data = CustomerSupport::whereBetween('created_at', [$start_date . ' 00:00:00', $end_date . ' 23:59:59'])->with(['customer', 'teknisi', 'cso', 'logs'])->get();
        return view('customer-support.data.index', [
            'data' => $data,
            'start_date' => $start_date,
            'end_date' => $end_date
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
