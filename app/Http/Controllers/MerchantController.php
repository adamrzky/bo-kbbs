<?php

namespace App\Http\Controllers;

use App\Models\Merchant;
use App\Models\Mcc;
use App\Models\MerchantDetails;
use App\Models\MerchantDomestic;
use App\Models\UserMerchant;
use App\Models\Cabang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Str;
use App\Traits\Common;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;



class MerchantController extends Controller
{
    use Common;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
        $this->middleware('permission:merchant-list|merchant-create|merchant-edit|merchant-delete', ['only' => ['index', 'show']]);
        $this->middleware('permission:merchant-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:merchant-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:merchant-delete', ['only' => ['destroy']]);
    }

    public function index()
    {

        $getUserId = Auth::id();
        $userId = $getUserId;

        $query = DB::table('QRIS_MERCHANT')
            ->join('user_has_merchant', 'QRIS_MERCHANT.ID', '=', 'user_has_merchant.MERCHANT_ID')
            ->join('users', 'user_has_merchant.USER_ID', '=', 'users.id')
            ->select('QRIS_MERCHANT.*');

        if ($userId != 1) {
            $query->where('users.id', $userId);
        }

        $merchants = $query->paginate(5); // Specify the number of items per page (e.g., 5)

        return view('merchant.index', ['merchants' => $merchants]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $mcc = Mcc::orderBy('DESC_MCC')
            ->get()
            ->toArray();
        $criteria = getCriteria();
        $prov = getWilayah();
        $cabangs = Cabang::all();

        return view('merchant.create', compact('mcc', 'criteria', 'prov', 'cabangs'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        if ($request['merchantType']) {

            // dd($request);

            try {
                $data_merchant = [

                    'CODE_MCC' => $request->code,
                    'DESC_MCC' => $request->desc,

                ];

                $mcc = Mcc::create($data_merchant);

                return redirect()
                    ->route('merchant.categories')
                    ->with(['msg' => 'MCC created successfully.']);
            } catch (\Throwable $th) {
                return back()->withErrors(['msg' => 'Merchant created failed. (' . $th->getMessage() . ')']);
            }
        } else {

            request()->validate([
                'norek' => 'required|numeric',
                'merchant' => 'required',
                'mcc' => 'required',
                'criteria' => 'required',
                'prov' => 'required',
                'city' => 'required',
                'address' => 'required',
                'postalcode' => 'required',
                'fee' => 'required',
                'mid' => 'required',
            ]);

            $cek = $this->cekNorek($request->norek);
            if ($cek['rc'] != '0000') {
                return back()->withErrors(['msg' => 'Merchant created failed. (Invalid Account Number [No Rekening])']);
            }

            // dd($request);

            try {
                $date = date('Y-m-d H:i:s');
                $nmid = 'ID' . genID(13);
                $nns = '93600521';
                $domain = 'ID.CO.QRIS.WWW';
                $data_domestic = [
                    'REVERSE_DOMAIN' => $domain,
                    'NMID' => '',
                    'MCC' => $request->mcc,
                    'CRITERIA' => $request->criteria,
                ];
                $id_domestic = MerchantDomestic::create($data_domestic)->id;

                $data_merchant = [
                    'CREATED_AT' => $date,
                    'UPDATED_AT' => '',
                    'TERMINAL_LABEL' => 'A01',
                    'MERCHANT_COUNTRY' => 'ID',
                    'QRIS_MERCHANT_DOMESTIC_ID' => $id_domestic,
                    'TYPE_QR' => 'STATIS',
                    'MERCHANT_NAME' => $request->merchant,
                    'MERCHANT_CITY' => $request->city,
                    'POSTAL_CODE' => $request->postalcode,
                    'MERCHANT_CURRENCY_CODE' => '360',
                    'MERCHANT_TYPE' => $request->mcc,
                    'MERCHANT_EXP' => '900',
                    'MERCHANT_CODE' => genID(5, true),
                    'MERCHANT_ADDRESS' => $request->address,
                    'STATUS' => '0',
                    'NMID' => $request->nmid,
                    'ACCOUNT_NUMBER' => $request->norek,
                    'KTP' => $request->ktp,
                    'NPWP' => $request->npwp,
                ];

                $merchants = Merchant::create($data_merchant);

                $merchant_id = $merchants->ID;

                $data_user_has_merchant = [

                    'USER_ID' => Auth::id(),
                    'MERCHANT_ID' =>  $merchant_id,
                ];


                $user_has_merchant = UserMerchant::create($data_user_has_merchant);

                $data_detail = [
                    'MERCHANT_ID' => $merchant_id,
                    'DOMAIN' => $domain,
                    'TAG' => '26',
                    'MPAN' => $request->mpan,
                    'MID' => $request->mid,
                    'CRITERIA' => $request->criteria,
                ];
                $merchant_detail = MerchantDetails::create($data_detail);



                $param = [
                    'MERCHANT_DOMESTIC' => $data_domestic,
                    'MERCHANT' => $data_merchant,
                    'MERCHANT_DETAIL' => $data_detail,
                ];



                // $res = Http::timeout(10)->withBasicAuth('username', 'password')->post('http://192.168.26.26:10002/merchant.php?cmd=add', $param);

                if (isset($merchants)) {
                    $spreadsheet = new Spreadsheet();
                    $sheet = $spreadsheet->getActiveSheet();

                    // Setup titles
                    $sheet->setCellValue('A1', 'FORM PENDAFTARAN');
                    $sheet->setCellValue('A2', 'NATIONAL MERCHANT REPOSITORY QRIS');
                    $sheet->mergeCells('A1:O1'); // Merging title
                    $sheet->mergeCells('A2:O2'); // Merging subtitle

                    // Applying styles to the merged headers
                    $sheet->getStyle('A1:O2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                    $sheet->getStyle('A1:O2')->getFont()->setBold(true);

                    // Header row for "Mandatory"
                    $sheet->setCellValue('B3', 'Mandatory');
                    $sheet->mergeCells('B3:O3');
                    $sheet->getStyle('B3:O3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                    $sheet->getStyle('B3:O3')->getFont()->setBold(true);
                    $sheet->getStyle('B3')->getFill()
                        ->setFillType(Fill::FILL_SOLID)
                        ->getStartColor()->setARGB('FFFF00'); // Yellow color for mandatory

                    // Headers for columns
                    $headers = [
                        'A3' => 'No.', 'B4' => 'NMID', 'C4' => 'Nama Merchant (max 50)', 'D4' => 'Nama Merchant (max 25)',
                        'E4' => 'MPAN', 'F4' => 'MID', 'G4' => 'Kota', 'H4' => 'Kodepos', 'I4' => 'Kriteria',
                        'J4' => 'MCC', 'K4' => 'Jml Terminal', 'L4' => 'Tipe Merchant', 'M4' => 'NPWP',
                        'N4' => 'KTP', 'O4' => 'Tipe QR'
                    ];

                    foreach ($headers as $cell => $value) {
                        $sheet->getStyle($cell, $value)->getFill()
                            ->setFillType(Fill::FILL_SOLID)
                            ->getStartColor()->setARGB('FFFF00'); // Yellow color for mandatory
                        $sheet->setCellValue($cell, $value);
                        // Apply border and styling for all headers
                        $sheet->getStyle($cell)->applyFromArray([
                            'borders' => [
                                'outline' => [
                                    'borderStyle' => Border::BORDER_MEDIUM,
                                    'color' => ['argb' => '000000'],
                                ],
                            ],
                        ]);
                    }

                    // Adding number data spanning rows 3-4
                    $sheet->mergeCells('A3:A4');
                    $sheet->setCellValue('A3', 'NO');

                    // Adding actual data at row 5
                    $data = [
                        'A5' => '1', 
                        'B5' => $request->nmid, 
                        'C5' => $request->merchant, 
                        'D5' => strlen($request->merchant) > 25 ? substr($request->merchant, 0, 25) : $request->merchant,
                        'E5' => "'" . $request->mpan . "'",  // Menambahkan tanda kutip pada MPAN
                        'F5' => $request->mid, 
                        'G5' => $request->city, 
                        'H5' => $request->postalcode, 
                        'I5' => $request->criteria,
                        'J5' => $request->mcc, 
                        'K5' => '1', 
                        'L5' => $request->merchantType, 
                        'M5' => "'" . $request->npwp . "'",  // Menambahkan tanda kutip pada NPWP
                        'N5' => "'" . $request->ktp . "'",   // Menambahkan tanda kutip pada KTP
                        'O5' => $request->qrType
                    ];

                    foreach ($data as $cell => $value) {
                        $sheet->setCellValue($cell, $value);
                        // Apply border to each data cell
                        $sheet->getStyle($cell)->applyFromArray([
                            'borders' => [
                                'outline' => [
                                    'borderStyle' => Border::BORDER_THIN,
                                    'color' => ['argb' => '000000'],
                                ],
                            ],
                        ]);
                    }

                    $appEnv = getenv('APP_ENV');
                    $dateNow = date('Ymd');
                    $baseDir = '/home/share/test/KBBS_OUT/';
                    $baseDir2 = '/home/adam/test/KBBS_OUT/';
                    $folderName = $dateNow;
                    $storagePathProd = $baseDir . $folderName;
                    $storagePathDev = $baseDir2 . $folderName;

                    switch ($appEnv) {
                        case 'prod':
                            $storagePath = $storagePathProd;
                            break;
                        case 'dev':
                            $storagePath = $storagePathDev;
                            break;
                        case 'local':
                            $storagePath = null;
                            break;
                        default:
                            die('Invalid environment.');
                    }
                    
                    function getNextBatchNumber($storagePath, $dateNow)
                    {
                        $batchNumber = 0; // Mulai dari 0 untuk mengecek apakah ada file sama sekali
                        while (file_exists($storagePath . '/QRIS_NMR_93600521_' . $dateNow . ($batchNumber ? '_batch' . $batchNumber : '') . '.xlsx')) {
                            $batchNumber++;
                        }
                        return $batchNumber;
                    }
                    
                    if ($appEnv === 'local') {
                        // Logika download file untuk environment local
                        $batchNumber = getNextBatchNumber($storagePath, $dateNow); // Dapatkan batch number yang sesuai
                        $filename = 'QRIS_NMR_93600521_' . $dateNow . ($batchNumber ? '_batch' . $batchNumber : '') . '.xlsx';
                        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                        header('Content-Disposition: attachment; filename="' . $filename . '"');
                        $writer = new Xlsx($spreadsheet);
                        $writer->save('php://output');
                        exit;
                    } else {
                        // Mengecek dan membuat direktori jika belum ada
                        if (!file_exists($storagePath)) {
                            if (!mkdir($storagePath, 0775, true)) {
                                // Jika pembuatan direktori gagal, catat error dan kirim response error
                                error_log("Failed to create directory at $storagePath");
                                return response()->json(['error' => 'Failed to create directory'], 500);
                            }
                        }
                    
                        // Simpan file ke disk untuk environment prod dan dev
                        $batchNumber = getNextBatchNumber($storagePath, $dateNow);
                        $filename = 'QRIS_NMR_93600521_' . $dateNow . ($batchNumber ? '_batch' . $batchNumber : '') . '.xlsx';
                        $path = $storagePath . '/' . $filename;
                        $writer = new Xlsx($spreadsheet);
                        $writer->save($path);
                    }
                    
                }

                return redirect()
                    ->route('merchant.index')
                    ->with(['msg' => 'Merchant created successfully.']);
            } catch (\Throwable $th) {
                return back()->withErrors(['msg' => 'Merchant created failed. (' . $th->getMessage() . ')']);
            }
        }
    }


    public function show(Merchant $merchant, $id)
    {
        $id = Crypt::decrypt($id);

        $mcc = Mcc::orderBy('DESC_MCC')
            ->get()
            ->toArray();
        $criteria = getCriteria();
        $merchant = Merchant::where('id', $id)->first();
        return view('merchant.show', compact('mcc', 'criteria', 'merchant'));
    }


    public function edit(Merchant $merchant, $id)
    {
        $id = Crypt::decrypt($id);
        $merchant = Merchant::where('id', $id)->first();
        return view('merchant.edit', compact('merchant'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Merchant  $merchant
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Merchant $merchant, Mcc $mcc)
    {

        // dd($request);


        if ($request->has('merchantType')) {
            try {
                // Validasi data yang diperlukan
                $data_merchant = $request->validate([
                    'ID' => 'required|integer',
                    'CODE_MCC' => 'required|string|max:4',
                    'DESC_MCC' => 'required|string|max:255',
                ]);

                // Ambil objek MCC yang akan di-update
                $mcc = Mcc::find($data_merchant['ID']);

                // Periksa apakah objek MCC ditemukan
                if ($mcc) {
                    // Update data MCC dengan data yang sudah divalidasi
                    $mcc->update($data_merchant);

                    // Redirect ke route 'merchant.categories' dengan pesan sukses
                    return redirect()
                        ->route('merchant.categories')
                        ->with('msg', 'Merchant updated successfully.');
                } else {
                    return back()->withErrors(['msg' => 'Merchant not found.']);
                }
            } catch (\Throwable $th) {
                // Kembali ke halaman sebelumnya dengan pesan error
                return back()->withErrors(['msg' => 'Merchant update failed. (' . $th->getMessage() . ')']);
            }
        } else {

            $data_merchant = [
                'ID' => 'required',
                'TERMINAL_LABEL' => 'required',
                'MERCHANT_COUNTRY' => 'required',
                'QRIS_MERCHANT_DOMESTIC_ID' => 'required',
                'TYPE_QR' => 'required',
                'MERCHANT_NAME' => 'required',
                'MERCHANT_CITY' => 'required',
                'POSTAL_CODE' => 'required',
                'MERCHANT_CURRENCY_CODE' => 'required',
                'MERCHANT_TYPE' => 'required',
                'ACCOUNT_NUMBER' => 'required',
                'STATUS' => 'required',
                'MERCHANT_CODE' => 'required',
                'MERCHANT_ADDRESS' => 'required',
            ];

            // dd($request);
            $validatedData = $request->validate($data_merchant);
            // $merchant = Merchant::findOrFail($id);
            $merchant->update($validatedData);

            // $merchant->update($request->all());


            // $data_detail = [
            //     'MERCHANT_ID' => $merchant_id,
            //     'DOMAIN' => $domain,
            //     'TAG' => '26',
            //     'MPAN' => $nns . $request->norek,
            //     'MID' => $nmid,
            //     'CRITERIA' => $request->criteria,
            // ];
            // $merchant_detail = MerchantDetails::create($data_detail);

            $date = date('Y-m-d H:i:s');
            $nmid = 'ID' . genID(13);
            $nns = '93600521';
            $domain = 'ID.CO.QRIS.WWW';


            $val_merchant = [
                'ID' => $request->merchant->ID,
                'UPDATED_AT' => $date,
                'TERMINAL_LABEL' => $request->merchant->TERMINAL_LABEL,
                'MERCHANT_COUNTRY' => 'ID',
                // 'QRIS_MERCHANT_DOMESTIC' => '',
                'TYPE_QR' => $request->merchant->TYPE_QR,
                'MERCHANT_NAME' => $request->merchant->MERCHANT_NAME,
                'MERCHANT_CITY' => $request->merchant->MERCHANT_CITY,
                'POSTAL_CODE' => $request->merchant->POSTAL_CODE,
                'MERCHANT_CURRENCY_CODE' => $request->merchant->MERCHANT_CURRENCY_CODE,
                'MERCHANT_TYPE' => $request->merchant->MERCHANT_TYPE,
                'MERCHANT_EXP' => $request->merchant->MERCHANT_EXP,
                'MERCHANT_CODE' => '900',
                'MERCHANT_ADDRESS' => $request->merchant->MERCHANT_ADDRESS,
                'STATUS' => $request->merchant->STATUS,
                'NMID' => $request->merchant->NMID,
                'ACCOUNT_NUMBER' => $request->merchant->ACCOUNT_NUMBER,
            ];

            $param = [

                'MERCHANT' => $val_merchant

            ];
            // json_encode($param);
            // dd(json_encode($param));

            // dd($request->merchant);

            $res = Http::timeout(10)->withBasicAuth('username', 'password')->post('http://192.168.26.26:10002/merchant.php?cmd=edit', $param);

            return redirect()
                ->route('merchant.index')
                ->with('success', 'Merchant updated successfully');
        }
    }


    public function generateMid(Request $request)
    {
        $nns = '93600521';
        $kodeCabang = str_pad($request->kodeCabang, 2, '0', STR_PAD_LEFT);
        $kodeLokasi = str_pad($request->kodeLokasi, 2, '0', STR_PAD_LEFT);

        // Mengambil ID terakhir dan menambahkannya dengan 1 untuk sequence baru
        $lastMerchant = Merchant::orderBy('ID', 'desc')->first();
        $sequence = $lastMerchant ? $lastMerchant['ID'] + 1 : 1;

        $mid = $kodeCabang . $kodeLokasi . str_pad($sequence, 5, '0', STR_PAD_LEFT);

        return response()->json([
            'success' => true,
            'mid' => $mid
        ]);
    }

    public function rekening(Request $request)
    {
        $norek = $request->input('norek');
        try {
            // Asumsikan fungsi cekNorek mengembalikan array dengan 'rc' dan mungkin lebih
            $hasilCek = $this->cekNorek($norek);

            // dd($hasilCek);

            if ($hasilCek['rc'] != '0000') {
                return response()->json([
                    'error' => 'Nomor Rekening tidak valid',
                ]);
            }

            return response()->json([
                'norek' => $hasilCek['norek'],
                'name' => $hasilCek['name'],
                'balance' => number_format($hasilCek['balance'], 0, ',', '.'),
                'rc' => $hasilCek['rc']
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'error' => 'Terjadi kesalahan dalam verifikasi nomor rekening',
            ]);
        }
    }




    public function saldo(Request $request)
    {
        $id = Crypt::decrypt($request->id);
        $merchant = Merchant::where('id', $id)->first();
        try {
            $cek = $this->cekNorek($merchant->ACCOUNT_NUMBER);
            if ($cek['rc'] != '0000') {
                return response()->json([
                    'error' => ' (Invalid Account Number [No Rekening]) ',
                ]);
            } else {
                return response()->json([
                    'norek' => $cek['norek'],
                    'name' => $cek['name'],
                    'balance' => number_format($cek['balance'], 0, ',', '.'),
                ]);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'error' => ' (Invalid Account Number [No Rekening]) ',
            ]);
        }
    }

    public function mutasi(Request $request)
    {
        $id = Crypt::decrypt($request->id);
        $merchant = Merchant::where('id', $id)->first();
        try {
            $cek = $this->cekMutasi($merchant->ACCOUNT_NUMBER);
            if ($cek['rc'] != '0000') {
                return response()->json([
                    'error' => ' (Invalid Account Number [No Rekening]) ',
                ]);
            } else {
                return response()->json(json_decode($cek['json'], true));
            }
        } catch (\Throwable $th) {
            return response()->json([
                'error' => ' (Invalid Account Number) ',
            ]);
        }
    }

    public function categories()
    {

        $mcc = Mcc::orderBy('DESC_MCC')
            ->get();
        // ->toArray();



        // $merchants = $mcc->paginate(5); // Specify the number of items per page (e.g., 5)

        return view('merchant.categories', ['mcc' => $mcc]);
    }

    public function categoriesCreate()
    {
        // $mcc = Mcc::orderBy('DESC_MCC')
        //     ->get()
        //     ->toArray();
        // $criteria = getCriteria();
        // $prov = getWilayah();

        return view('merchant.categoriesCreate');
    }

    public function categoriesEdit($ID)
    {

        // dd($ID);
        $id = Crypt::decrypt($ID);
        $mcc = Mcc::where('id', $id)->first();

        // dd($mcc);
        return view('merchant.categoriesEdit', compact('mcc'));
    }
}
