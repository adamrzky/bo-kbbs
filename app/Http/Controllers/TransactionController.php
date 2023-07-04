<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use DataTables;
use Illuminate\Support\Facades\Http;
use App\Models\Merchant;
use App\Models\MerchantDetails;
use App\Models\Nns;
// use Yajra\DataTables\Facades\DataTables;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
         $this->middleware('permission:transaction-list|transaction-create|transaction-edit|transaction-delete', ['only' => ['index','show']]);
         $this->middleware('permission:transaction-create', ['only' => ['create','store']]);
         $this->middleware('permission:transaction-edit', ['only' => ['edit','update']]);
         $this->middleware('permission:transaction-delete', ['only' => ['destroy']]);
         $this->middleware('permission:transaction-broadcast', ['only' => ['broadcast']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    // public function index(TransactionDataTable $dataTable)
    // {
    //     return $dataTable->render('transaction.index');
    // }

    public function index()
    {
        // $data = Http::get('http://192.168.26.26:10002/tm.php')->json();
        
        // foreach ($data as $key => $value) {
          
        //     $merchant = Merchant::where('ID',$value['MERCHANT_ID'])->first();
        //     if ($merchant != '') {
        //         // dd($merchant);
        //         $data[$key]['MERCHANT'] = $merchant->toArray();
        //     } else {
        //         $data[$key]['MERCHANT'] = $merchant;
        //     }

            
           
        // }
       
        // dd($data);
        return view('transactions.index');
    }

    public function data(Request $request)
    {
        if ($request->ajax()) {

            $data = Transaction::get()->toArray();
       

        // $data = Http::get('http://192.168.26.26:10002/tm.php')->json();
        
        // foreach ($data as $key => $value) {
          
        //     $merchant = Merchant::where('ID',$value['MERCHANT_ID'])->first();
        //     if ($merchant != '') {
        //         // dd($merchant);
        //         $data[$key]['MERCHANT'] = $merchant->toArray();
        //     } else {
        //         $data[$key]['MERCHANT'] = $merchant;
        //     }

            
           
        // }
       
      

        // dd($data);
        return DataTables::of($data)
    
        ->toJson();

        
    }
}
    public function data2(Request $request)
    {

        
        $searchValue = $request['search']['value']; // Search value
        $searchByAmount = $request['searchByAmount'];
        $searchByStatus = $request['searchByStatus'];
        ## Search 
            $searchQuery = " ";
            if($searchByAmount != ''){
               $searchQuery .= " and (emp_name like '%".$searchByAmount."%' ) ";
            }
            if($searchByStatus != ''){
               $searchQuery .= " and (gender='".$searchByStatus."') ";
            }
            if($searchValue != ''){
               $searchQuery .= " and (emp_name like '%".$searchValue."%' or 
                  email like '%".$searchValue."%' or 
                  city like'%".$searchValue."%' ) ";
            }




    }

    public function detail($id)
    {
        $idString = strval($id);
        // $datas = Http::get('http://192.168.26.26:10002/tm.php')->json();
        $datas = Transaction::get()->toArray();
        
        // $dataDecode = json_decode($datas);
        
        foreach ($datas as $key => $value) {
            // dd($value);
            
            $merchant = Merchant::where('ID',$value['MERCHANT_ID'])->first();
            if ($merchant != '') {
                // dd($merchant);
                $datas[$key]['MERCHANT'] = $merchant->toArray();
            } else {
                $datas[$key]['MERCHANT'] = $merchant;
            }
            
            $nns = Nns::where('NNS',$value['ISSUING_INSTITUTION_NAME'])->first();
            // dd($nns->toArray());
            if ($nns != '') {
                // dd($nns);
                // dd($datas[$key]['NNS']['NAME']);
                $datas[$key]['NNS'] = $nns['NAME'];
            } else {
                $datas[$key]['NNS'] = $nns;
            }

            $mpan = MerchantDetails::where('MERCHANT_ID',$value['MERCHANT_ID'])->first();
            if ($mpan != '') {
                // dd($merchant);
                $datas[$key]['MPAN'] = $mpan['MPAN'];
            } else {
                $datas[$key]['MPAN'] = $mpan;
            }
            
        }

        
      
      
       
        foreach ($datas as $data) {
        
            if($data['ID'] == $idString )
            {

            return response()->json([
       
                'MERCHANT_ACC_NUMBER'           =>   $data['MERCHANT_ACC_NUMBER'],
                'TIP_INDICATOR'                 =>   $data['TIP_INDICATOR'],
                'TRANSFER_REFF'                 =>   $data['TRANSFER_REFF'],
                'AMOUNT_TIP_PERCENTAGE'         =>   $data['AMOUNT_TIP_PERCENTAGE'],
                'DESCRIPTION'                   =>   $data['DESCRIPTION'],
                'QRIS'                          =>   $data['QRIS'],
                'TRANSACTION_ID'                =>   $data['TRANSACTION_ID'],
                'STATUS_TRANSFER'               =>   $data['STATUS_TRANSFER'],
                'STATUS'                        =>   $data['STATUS'],
                'AMOUNT'                        =>   $data['AMOUNT'],
                'EXPIRE_DATE_TIME'              =>   $data['EXPIRE_DATE_TIME'],
                'RETRIEVAL_REFERENCE_NUMBER'    =>   $data['RETRIEVAL_REFERENCE_NUMBER'],
                'CREATED_AT'                    =>   $data['CREATED_AT'],
                'TRANSACTION_TYPE'              =>   $data['TRANSACTION_TYPE'],
                'ID'                            =>   $data['ID'],
                'FEE_AMOUNT'                    =>   $data['FEE_AMOUNT'],
                'AMOUNT_REFUND'                 =>   $data['AMOUNT_REFUND'],
                'INVOICE_NUMBER'                =>   $data['INVOICE_NUMBER'],
                'POSTAL_CODE'                   =>   $data['POSTAL_CODE'],
                'UPDATED_AT'                    =>   $data['UPDATED_AT'],
                'TRANSFER_STATUS'               =>   $data['TRANSFER_STATUS'],
                'MERCHANT_ID'                   =>   $data['MERCHANT_ID'],
                'MERCHANT'                      =>   $data['MERCHANT'],
                'AQUERIER_TYPE'                  =>   $data['AQUERIER_TYPE'],
                'MID'                              =>   $data['MID'],
                'RRN_REFUND'                     =>   $data['RRN_REFUND'],
                'BIT3_RESPONSE'                  =>   $data['BIT3_RESPONSE'],
                'RC_FUND'                        =>   $data['RC_FUND'],
                'ACQUIRING_INSTITUTION_NAME'    =>   $data['ACQUIRING_INSTITUTION_NAME'],
                'ISSUING_INSTITUTION_NAME'      =>   $data['ISSUING_INSTITUTION_NAME'],
                'ISSUING_CUSTOMER_NAME'         =>   $data['ISSUING_CUSTOMER_NAME'],
                'CUSTOMER_PAN'                  =>   $data['CUSTOMER_PAN'],
                'NNS'                  =>   $data['NNS'],
                'bit_12'                  =>   $data['bit_12'],
                'BIT_2'                  =>   $data['BIT_2'],
                'CURRENT_AMOUNT_REFUND'                  =>   $data['CURRENT_AMOUNT_REFUND'],
                'MPAN'                  =>   $data['MPAN'],

         ]);
        }}
        
    }
}

