<?php

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

function genID($len = 13, $alfanumeric = false)
{
    $digits = '';
    if ($alfanumeric) {
        // $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $permitted_chars = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';

        $digits = substr(str_shuffle($permitted_chars), 0, $len);
    } else {
        for ($i = 0; $i < $len; $i++) {
            $digits .= rand(0, 9);
        }
    }
    return $digits;
}

function getCriteria()
{
    $criteria = [
        [
            'id' => 'UMI',
            'desc' => 'Usaha Mikro'
        ],
        [
            'id' => 'UKE',
            'desc' => 'Usaha Kecil'
        ],
        [
            'id' => 'UME',
            'desc' => 'Usaha Menengah'
        ],
        [
            'id' => 'UBE',
            'desc' => 'Usaha Besar'
        ]
    ];

    return $criteria;
}

function getQrtype()
{
    $qrType = [
        [
            'id' => '1',
            'desc' => 'QRIS Static'
        ],
        [
            'id' => '2',
            'desc' => 'QRIS Dinamis'
        ],
        [
            'id' => '3',
            'desc' => 'QRIS Dinamic With Amount'
        ],
        [
            'id' => '4',
            'desc' => 'QRIS Static With Multiple Acquirer'
        ],
        [
            'id' => '5',
            'desc' => 'QRIS Dinamic Without Amount'
        ],
        [
            'id' => '6',
            'desc' => 'QRIS Dinamic With Amount'
        ],
        [
            'id' => '7',
            'desc' => 'QR Static With Tip'
        ],
        [
            'id' => '8',
            'desc' => 'QR Static With Tip'
        ],
        [
            'id' => '9',
            'desc' => 'QR Static With % Tip '
        ]
    ];

    return $qrType;
}

function getWilayah($prov = '', $negara = 'ID')
{
    $url = 'http://192.168.26.26:10002/api.php?negara=' . $negara . '&prov=' . $prov;
    $response = Http::get($url);

    return $response->json();
}

function traceLog($pMessage)
{
    Log::debug($pMessage);
    Log::channel('daily')->debug($pMessage);
}

function sendAPI($pUrl, $pData)
{
    $tStringData = json_encode($pData);
    traceLog("API Request to {$pUrl} : {$tStringData}");

    $tMethod = 'POST';
    $requestBody = str_replace(array(" ", "\n", "\t", "\r"), array("", "", "", ""), $tStringData);
    $tCurl = curl_init($pUrl);
    curl_setopt($tCurl, CURLOPT_CUSTOMREQUEST, $tMethod);
    curl_setopt($tCurl, CURLOPT_POSTFIELDS, $tStringData);
    curl_setopt($tCurl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt(
        $tCurl,
        CURLOPT_HTTPHEADER,
        array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen($tStringData),
        )
    );
    sleep(0.5);
    $tResult = curl_exec($tCurl);
    curl_close($tCurl);

    traceLog("API Response from {$pUrl} : {$tResult}");

    return json_decode($tResult, true);
}

function sendSocket($address, $port, $out)
{
    traceLog("[sendSocket] start with address {$address}, port {$port}, and out {$out}");

    $timeout = 1000;
    $s = '';
    $bTimeout = 0;

    $fp = fsockopen($address, $port, $errno, $errstr, $timeout);

    if (!$fp) {
        $bTimeout = 999;
    } else {
        //$n = fwrite($fp, GetLengthByte(strlen($out)), 2); //byte order
        $n = fwrite($fp, $out, strlen($out));
        $n = fwrite($fp, chr(-1));
        @stream_set_timeout($fp, $timeout);

        $c = '';
        $bDone = false;
        $bHead = false;
        $lenCount = 0;
        $i = 0;
        while ((!feof($fp)) && ($bTimeout == 0) && (!$bDone)) {
            $info = @stream_get_meta_data($fp);
            if ($info['timed_out']) {
                $bTimeout = 1;
            }

            if ($bTimeout == 0) {
                $c = fread($fp, 1);
                if ($c != chr(-1)) {
                    $s .= $c;
                } else {
                    $bDone = true;
                }
            } // end of !$bTimeout
        }

        fclose($fp);
    }
    $sResp = $s;

    traceLog("[sendSocket] end with result {$sResp}");
    return $sResp;
}
