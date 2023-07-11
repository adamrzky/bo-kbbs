<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GetLokasiController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });
Route::post('/detail_qris', 'API\QrisController@detailQris')->name('detail_qris');
// Route::get('/getLokasi', 'API\GetLokasiController@getLokasi')->name('getLokasi');
Route::get('/getLokasi/{provinsi}', 'API\GetLokasiController@getLokasi')->name('getLokasi');

Route::group(['middleware' => ['cors']], function () {
    //
    Route::post('/register', 'API\AuthController@register');
    Route::post('/gettoken', 'API\AuthController@getToken')->name('gettoken');

    Route::group(['middleware' => ['check.auth']], function () {
        Route::get('/product', 'API\ProductController@index')->name('api.product');
        Route::post('/qris', 'API\QrisController@store')->name('api.qris');
        Route::post('/refund', 'API\RefundController@store')->name('api.refund');
        Route::get('/tranmain', 'API\TranmainController@get')->name('api.tranmainget');
    });

});
