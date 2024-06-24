<?php

namespace App\Http\Controllers\API\client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;


class TransactionClientController extends Controller
{
    public function index(Request $request)
    {

        $getUserId = Auth::id();
        $userId = $getUserId;

        $query = DB::table('QRIS_TRANSACTION_AQUERIER_MAIN')
        ->join('user_has_merchant', 'QRIS_TRANSACTION_AQUERIER_MAIN.MERCHANT_ID', '=', 'user_has_merchant.MERCHANT_ID')
        ->join('users', 'user_has_merchant.USER_ID', '=', 'users.id')
        ->select('QRIS_TRANSACTION_AQUERIER_MAIN.*');
    
        if ($userId !== null && $userId != 1) {
            $query->where('users.id', $userId);
        }


        $data = $query->get()->toArray();

        return response()->json(['transaction' => $data]);
    }
}