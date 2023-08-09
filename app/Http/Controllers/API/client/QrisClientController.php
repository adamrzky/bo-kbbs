<?php

namespace App\Http\Controllers\API\client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

use App\Models\Merchant;
use App\Models\Mcc;
use App\Models\MerchantDetails;
use App\Models\MerchantDomestic;
use App\Models\UserMerchant;

class QrisClientController extends Controller
{
public function get(Request $request)
    {
        // Log::channel('apilog')->info('==============================');
        // Log::channel('apilog')->info('REQ : ' . json_encode($request->all()));
        $mpi = [];

        foreach ($request->param['MPI'] as $key => $value) {
            if (!is_null($value)) {
                $mpi[$key] = $value;
            }
        }

        //   dd($mpi);

        $data = [
            'MPI' => $mpi,
        ];


        // Log::channel('apilog')->info('REQ SEND API : ' . json_encode($data));

        try {

            $customApiBaseUrl = env('API_URL');
            

            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->post(
            $customApiBaseUrl . '/v1/api/aquerier/create/qr', $data);

            

            // Log::channel('apilog')->info('RESP SEND API : ' . json_encode($response->json()));

            $res = $response->json();

        } catch (\Throwable $th) {
            // Log::channel('apilog')->info('RESP SEND API : ' . $th->getMessage());
            $res = [
                'RC' => '0005',
                'RM' => $th->getMessage(),
            ];
            // Log::channel('apilog')->info('RESP : ' . json_encode($res));
        }

        return response()->json($res);
    }
}