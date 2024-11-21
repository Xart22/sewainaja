<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\CustomerSupport;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Google\Client as GoogleClient;
use Illuminate\Support\Facades\Http;

class CustomerSupportController extends Controller
{
    public function getData()
    {

        $newRequest = CustomerSupport::where('created_at', '>=', date('Y-m-01'))->where('status_cso', '!=', 'Done')->with(['cso', 'customer', 'technician', 'logs'])->orderBy('created_at', 'desc')->get();
        $countNewRequest = $newRequest->count();
        $requestDone = CustomerSupport::where('created_at', '>=', date('Y-m-01'))->where('status_cso', 'Done')->with(['cso', 'customer', 'technician', 'logs'])->get();


        return response()->json([
            'data' => $newRequest,
            'count' => $countNewRequest,
            'done' => $requestDone
        ]);
    }

    public function getDataTeknisi()
    {
        $data = CustomerSupport::where('teknisi_id', Auth::user()->id)->with(['cso', 'customer', 'technician', 'logs'])->get();

        return response()->json([
            'data' => $data,
        ]);
    }


    public function assignTechnician(Request $request)
    {
        $request->validate([
            'id' => 'required',
            'teknisi_id' => 'required',
        ]);

        $data = CustomerSupport::find($request->id);

        if (!$data) {
            return response()->json([
                'message' => 'Data not found',
            ], 404);
        }

        $teknisi = User::find($request->teknisi_id);
        $fcm_token = $teknisi->fcm_token;
        $fcm_project_id = env('FCM_PROJECT_ID', 'disewainaja');

        $title = 'Pekerjaan Baru Diterima dengan Nomor Tiket ' . $data->no_ticket;
        $message = 'Anda mendapatkan pekerjaan baru dengan nomor tiket ' . $data->no_ticket . '. Silahkan cek aplikasi Anda untuk melihat detail pekerjaan.';

        try {
            $access_token = $this->getAccessToken();

            $headers = [
                "Authorization" => "Bearer $access_token",
                "Content-Type" => "application/json",
            ];

            $dataMessage = [
                'message' => [
                    'token' => $fcm_token,
                    'notification' => [
                        'title' => $title,
                        'body' => $message,
                    ],
                ],
            ];

            $response = Http::withHeaders($headers)
                ->post('https://fcm.googleapis.com/v1/projects/' . $fcm_project_id . '/messages:send', $dataMessage);

            if ($response->failed()) {
                return response()->json([
                    'message' => 'Failed to send notification',
                    'error' => $response->json(),
                ], $response->status());
            }

            $data->status_teknisi = 'Waiting';
            $data->teknisi_id = $request->teknisi_id;
            $data->save();

            return response()->json([
                'message' => 'Data updated',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to assign technician',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function technicianResponse(Request $request)
    {
        $request->validate([
            'id' => 'required',
            'status' => 'required',
        ]);

        $data = CustomerSupport::find($request->id);

        if (!$data) {
            return response()->json([
                'message' => 'Data not found'
            ], 404);
        }

        $data->status_teknisi = $request->status;
        $data->save();

        return response()->json([
            'message' => 'Data updated'
        ]);
    }



    private function getAccessToken()
    {
        $credentialsFilePath = Storage::disk('local')->path('firebase/disewainaja-firebase-adminsdk-i4pdh-7fd46b763a.json');

        if (!file_exists($credentialsFilePath)) {
            throw new \Exception('Firebase credentials file not found.');
        }

        $client = new GoogleClient();
        $client->setAuthConfig($credentialsFilePath);
        $client->addScope('https://www.googleapis.com/auth/firebase.messaging');
        $client->useApplicationDefaultCredentials();

        $token = $client->fetchAccessTokenWithAssertion();

        if (isset($token['error'])) {
            throw new \Exception('Error fetching access token: ' . $token['error']);
        }

        return $token['access_token'];
    }
}
