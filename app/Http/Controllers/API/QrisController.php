<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Intervention\Image\ImageManager;
use Image;

class QrisController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // return view('qris.index');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Log::channel('apilog')->info('==============================');
        Log::channel('apilog')->info('REQ : ' . json_encode($request->all()));
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

        // switch ($request->qrType) {
        //     case '1':
        //         $data = [
        //             "MPI" => [
        //                 "MERCHANT_ID" => $request->param['MPI']['MERCHANT_ID'],
        //                 "AMOUNT" => $request->param['MPI']['AMOUNT']
        //             ]
        //         ];
        //         break;
        //     case '2':
        //         $data = [
        //             "MPI" => [
        //                 "MERCHANT_ID" => $request->param['MPI']['MERCHANT_ID'],
        //                 "AMOUNT" => $request->param['MPI']['AMOUNT']
        //             ]
        //         ];
        //         break;
        //     case '3':
        //         $data = [
        //             "MPI" => [
        //                 "MERCHANT_ID" => $request->param['MPI']['MERCHANT_ID'],
        //                 "AMOUNT" => $request->param['MPI']['AMOUNT']
        //             ]
        //         ];
        //         break;
        //     case '4':
        //         $data = [
        //             "MPI" => [
        //                 "MERCHANT_ID" => $request->param['MPI']['MERCHANT_ID'],
        //                 "AMOUNT" => $request->param['MPI']['AMOUNT']
        //             ]
        //         ];
        //         break;
        //     case '5':
        //         $data = [
        //             "MPI" => [
        //                 "MERCHANT_ID" => $request->param['MPI']['MERCHANT_ID'],
        //                 "AMOUNT" => $request->param['MPI']['AMOUNT']
        //             ]
        //         ];
        //         break;
        //     case '6':
        //         $data = [
        //             "MPI" => [
        //                 "MERCHANT_ID" => $request->param['MPI']['MERCHANT_ID'],
        //                 "AMOUNT" => $request->param['MPI']['AMOUNT']
        //             ]
        //         ];
        //         break;
        //     case '7':
        //         $data = [
        //             "MPI" => [
        //                 "MERCHANT_ID" => $request->param['MPI']['MERCHANT_ID'],
        //                 "AMOUNT" => $request->param['MPI']['AMOUNT'],
        //                 "TIP_INDICATOR" => $request->param['MPI']['TIP_INDICATOR']
        //             ]
        //         ];
        //         break;
        //     case '8':
        //         $data = [
        //             "MPI" => [
        //                 "MERCHANT_ID" => $request->param['MPI']['MERCHANT_ID'],
        //                 "AMOUNT" => $request->param['MPI']['AMOUNT'],
        //                 "FEE_AMOUNT" => $request->param['MPI']['FEE_AMOUNT']
        //             ]
        //         ];
        //         break;
        //     case '9':
        //         $data = [
        //             "MPI" => [
        //                 "MERCHANT_ID" => $request->param['MPI']['MERCHANT_ID'],
        //                 "AMOUNT" => $request->param['MPI']['AMOUNT'],
        //                 "FEE_AMOUNT_PERCENTAGE" => $request->param['MPI']['FEE_AMOUNT_PERCENTAGE']
        //             ]
        //         ];
        //         break;
        //     default:
        //         $data = [];
        //         break;
        // }

        Log::channel('apilog')->info('REQ SEND API : ' . json_encode($data));

        try {
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->post('http://192.168.26.26:9800/v1/api/aquerier/create/qr', $data);

            Log::channel('apilog')->info('RESP SEND API : ' . json_encode($response->json()));

            $res = $response->json();
            if ($res['RC'] == '0000') {
                $qris = $res['MPO']['QRIS'];
                $nmid = $res['MPO']['NMID'];
                // $qrcode = QrCode::size(400)->generate($qris);
                $qrcode = base64_encode(
                    QrCode::format('png')
                        ->size(200)
                        ->generate($qris),
                );

                //qrispng
                $wmQris = Image::make('images/qris.png');
                $wmQris->resize(200, 70);

                //getPngQRCode
                // $wmQrcode = Image::make($qrcode);
                // $wmQrcode->resize(100, 50);

                //canvas
                $canvas = Image::canvas(300, 350, '#ffff');

                //insertToCanvas
                $canvas->insert($wmQris, 'top', 10, 10);
                $canvas->insert($qrcode, 'center', 0, 20);
                $canvas->text('NMID : ' . $nmid, 50, 330, function ($font) {
                    $font->file(storage_path('font/font3.ttf'));
                    $font->size(12);
                });

                $canvas->save('images/hasil.png');

                $base64 = base64_encode($canvas);
                $detail = $this->parsingQrCodeASPI($qris);
                $res['MPO']['DETAIL'] = $detail;
                $res['MPO']['QR'] = $base64;

                Log::channel('apilog')->info('RESP : ' . json_encode($res));
            } else {
                $res = $response->json();
                Log::channel('apilog')->info('RESP : ' . json_encode($res));
            }
        } catch (\Throwable $th) {
            Log::channel('apilog')->info('RESP SEND API : ' . $th->getMessage());
            $res = [
                'RC' => '0005',
                'RM' => $th->getMessage(),
            ];
            Log::channel('apilog')->info('RESP : ' . json_encode($res));
        }

        return response()->json($res);
    }

    public function detailQris()
    {
        $qris = request()->qris;
        $detail = $this->parsingQrCodeASPI($qris);
        return response()->json($detail);
    }

    private function parsingQrCodeASPI($qris, $pIsNested = true)
    {
        while (strlen($qris) > 0) {
            //Get Data ID
            $tID = substr($qris, 0, 2);
            $tIDKey = intval($tID);
            $qris = substr($qris, 2);

            //Get Data Length
            $tLengthData = substr($qris, 0, 2);
            $qris = substr($qris, 2);

            //Get Data Value
            $tLengthDataInt = intval($tLengthData);
            $tValue = substr($qris, 0, $tLengthDataInt);
            $qris = substr($qris, $tLengthDataInt);

            $additional = ['26', '27', '28', '29', '30', '31', '32', '33', '34', '35', '36', '37', '38', '39', '40', '41', '42', '43', '44', '45', '51', '62'];
            if (in_array($tIDKey, $additional) && $pIsNested) {
                $tResult[$tIDKey] = $this->parsingQrCodeASPI($tValue, false);
            } else {
                $tResult[$tIDKey] = $tValue;
            }
        }

        return $tResult;
    }
}
