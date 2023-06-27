<?php

namespace App\Http\Controllers;

use App\Models\Merchant;
use App\Models\Mcc;
use App\Models\MerchantDetails;
use App\Models\MerchantDomestic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Str;
use App\Traits\Common;
use Illuminate\Support\Facades\Http;

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
        $merchant = Merchant::latest()->paginate(5);

        return view('merchant.index', compact('merchant'))->with('i', (request()->input('page', 1) - 1) * 5);
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

        return view('merchant.create', compact('mcc', 'criteria', 'prov'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd($request);
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
        ]);

        $cek = $this->cekNorek($request->norek);
        if ($cek['rc'] != '0000') {
            return back()->withErrors(['msg' => 'Merchant created failed. (Invalid Account Number [No Rekening])']);
        }

        try {
            $date = date('Y-m-d H:i:s');
            $nmid = 'ID' . genID(13);
            $nns = '93600521';
            $domain = 'ID.CO.QRIS.WWW';
            $data_domestic = [
                'REVERSE_DOMAIN' => $domain,
                'NMID' => $nmid,
                'MCC' => $request->mcc,
                'CRITERIA' => $request->criteria,
            ];
            $id_domestic = MerchantDomestic::create($data_domestic)->id;

            $data_merchant = [
                'CREATED_AT' => $date,
                'UPDATED_AT' => '',
                'TERMINAL_LABEL' => 'K19',
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
                'STATUS' => '1',
                'NMID' => $nmid,
                'ACCOUNT_NUMBER' => $request->norek,
            ];

            $merchant_id = Merchant::create($data_merchant)->ID;

            $data_detail = [
                'MERCHANT_ID' => $merchant_id,
                'DOMAIN' => $domain,
                'TAG' => '26',
                'MPAN' => $nns . $request->norek,
                'MID' => $nmid,
                'CRITERIA' => $request->criteria,
            ];
            $merchant_detail = MerchantDetails::create($data_detail);

            $param = [
                'MERCHANT_DOMESTIC' => $data_domestic,
                'MERCHANT' => $data_merchant,
                'MERCHANT_DETAIL' => $data_detail,
            ];

           
            
            // $res = Http::timeout(10)->withBasicAuth('username', 'password')->post('http://192.168.26.26:10002/merchant.php?cmd=add', $param);

            return redirect()
                ->route('merchant.index')
                ->with(['msg' => 'Merchant created successfully.']);
        } catch (\Throwable $th) {
            return back()->withErrors(['msg' => 'Merchant created failed. (' . $th->getMessage() . ')']);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Merchant  $merchant
     * @return \Illuminate\Http\Response
     */
    public function show(Merchant $merchant, $id)
    {
        $id = Crypt::decrypt($id);
        
        $mcc = Mcc::orderBy('DESC_MCC')
            ->get()
            ->toArray();
        $criteria = getCriteria();
        $merchant = Merchant::where('id', $id)->first();
        return view('merchant.show', compact('mcc','criteria','merchant'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Merchant  $merchant
     * @return \Illuminate\Http\Response
     */
    // public function edit(Merchant $merchant)
    // {
    //     return view('merchants.edit',compact('merchant'));
    // }
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
    public function update(Request $request, Merchant $merchant)
    {
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
        
        // $res = Http::timeout(10)->withBasicAuth('username', 'password')->post('http://192.168.26.26:10002/merchant.php?cmd=edit', $param);

        return redirect()
            ->route('merchant.index')
            ->with('success', 'Merchant updated successfully');
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
}
