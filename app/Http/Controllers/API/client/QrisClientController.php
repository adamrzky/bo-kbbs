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
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->post('http://192.168.26.26:9800/v1/api/aquerier/create/qr', $data);

            // Log::channel('apilog')->info('RESP SEND API : ' . json_encode($response->json()));

            $res = $response->json();
            // if ($res['RC'] == '0000') {
            //     $qris = $res['MPO']['QRIS'];
            //     $nmid = $res['MPO']['NMID'];
            //     // $qrcode = QrCode::size(400)->generate($qris);
            //     $qrcode = base64_encode(
            //         QrCode::format('png')
            //             ->size(200)
            //             ->generate($qris),
            //     );

            //     //qrispng
            //     $wmQris = Image::make('images/qris.png');
            //     $wmQris->resize(200, 70);

            //     //getPngQRCode
            //     // $wmQrcode = Image::make($qrcode);
            //     // $wmQrcode->resize(100, 50);

            //     //canvas
            //     $canvas = Image::canvas(300, 350, '#ffff');

            //     //insertToCanvas
            //     $canvas->insert($wmQris, 'top', 10, 10);
            //     $canvas->insert($qrcode, 'center', 0, 20);
            //     $canvas->text('NMID : ' . $nmid, 50, 330, function ($font) {
            //         $font->file(storage_path('font/font3.ttf'));
            //         $font->size(12);
            //     });

            //     $canvas->save('images/hasil.png');

            //     $base64 = base64_encode($canvas);
            //     $detail = $this->parsingQrCodeASPI($qris);
            //     $res['MPO']['DETAIL'] = $detail;
            //     $res['MPO']['QR'] = $base64;

            //     Log::channel('apilog')->info('RESP : ' . json_encode($res));
            // } else {
            //     $res = $response->json();
            //     Log::channel('apilog')->info('RESP : ' . json_encode($res));
            // }
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