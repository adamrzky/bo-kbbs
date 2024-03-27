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

        $data = [
            'MPI' => $mpi,
        ];
    
        // Load background image
        $backgroundImage = Image::make('images/gpn.jpg');
        // Resize background image to match canvas size
        $backgroundImage->resize(400, 600);
    
        // Create canvas
        $canvas = Image::canvas(400, 600, '#ffff');
    
        // Insert background image to canvas
        $canvas->insert($backgroundImage, 'top-left', 0, 0);
    
        Log::channel('apilog')->info('REQ SEND API : ' . json_encode($data));
    
        try {
    
            $customApiBaseUrl = env('API_URL');
            
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->post(
                $customApiBaseUrl . '/v1/api/aquerier/create/qr', $data);
    
            Log::channel('apilog')->info('RESP SEND API : ' . json_encode($response->json()));
    
            $res = $response->json();
            if ($res['RC'] == '0000') {

                $qris = $res['MPO']['QRIS'];
                $detailQris = $this->parsingQrCodeASPI($qris);

                $nmid = $res['MPO']['NMID'];
                $merchantName = $detailQris['59'];


    
                $qrcode = base64_encode(
                    QrCode::format('png')
                        ->size(250)
                        ->generate($qris)
                );
    
                //qrispng
                $wmQris = Image::make('images/qris.png');
                $wmQris->resize(200, 70);
    
                // Insert QR code onto canvas
                // $canvas->insert($wmQris, 'top', 10, 10);
    
                // Generate QR code and insert onto canvas
                $qrcodeImage = Image::make($qrcode);
                $canvas->insert($qrcodeImage, 'center', 0, 20);
    
               // Insert Merchant Name text onto canvas
                $paddingTop = 110; // Padding dari atas canvas
                $textHeight = 12; // Tinggi teks dalam font
                $yCoordinate = $paddingTop + $textHeight; // Koordinat y yang dihitung
                $canvas->text($merchantName, $canvas->width() / 2, $yCoordinate, function ($font) {
                    $font->file(storage_path('font/font3.ttf'));
                    $font->size(15);
                    $font->align('center');
                });
    
               // Insert NMID text onto canvas
                $paddingTop2 = 140; // Padding dari atas canvas
                $textHeight2 = 12; // Tinggi teks dalam font
                $yCoordinate2 = $paddingTop2 + $textHeight2; // Koordinat y yang dihitung
                $canvas->text('NMID : ' . $nmid, $canvas->width() / 2, $yCoordinate2, function ($font) {
                    $font->file(storage_path('font/font3.ttf'));
                    $font->size(15);
                    $font->align('center');
                });


    
                // Save the canvas as PNG
                $canvas->save('images/hasil.png');
    
                // Encode the canvas as base64 if needed
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
