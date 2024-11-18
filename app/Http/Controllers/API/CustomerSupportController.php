<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\CustomerSupport;
use Illuminate\Http\Request;

class CustomerSupportController extends Controller
{
    public function getData()
    {
        $dataThisMonth = CustomerSupport::whereMonth('created_at', date('m'))->get();


        return response()->json([
            'data' => $dataThisMonth,
        ]);
    }
}
