<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\CustomerSupport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerSupportController extends Controller
{
    public function getData()
    {
        $status = [
            'Waiting',
            'Responded',
            'Waiting for Technician',
            'On The Way',
            'Repairing',
            'Process Waiting Close by Customer',
        ];
        $newRequest = CustomerSupport::where('created_at', '>=', date('Y-m-01'))->whereIN('status', $status)->with('cso')->orderBy('created_at', 'desc')->get();
        $countNewRequest = $newRequest->count();
        $requestDone = CustomerSupport::where('created_at', '>=', date('Y-m-01'))->where('status', 'Done')->with('cso')->get();


        return response()->json([
            'data' => $newRequest,
            'count' => $countNewRequest,
            'done' => $requestDone
        ]);
    }
}
