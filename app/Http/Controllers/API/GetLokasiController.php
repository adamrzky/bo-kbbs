<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\Controller;

class GetLokasiController extends Controller
{
    public function getLokasi(Request $request, $provinsi)
    {
        $url = 'http://103.182.72.16:10002/api.php?negara=ID&prov=' . $provinsi;
        
        $response = Http::get($url);
        
        // Check if the request was successful
        if ($response->successful()) {
            $data = $response->json();
            // Process the data or return it as a response
            return response()->json($data);
        } else {
            // Handle error response
            return response()->json(['error' => 'Failed to retrieve data'], 500);
        }
    }
}
