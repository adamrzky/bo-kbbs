<?php


namespace App\Exports;

use App\Models\Merchant;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class MerchantsExport implements FromCollection, WithHeadings, WithMapping
{
    private $rowNumber = 0;
    public function collection()
    {
        // Menambahkan 'details' dan 'domestic' pada pemanggilan
        return Merchant::with(['details', 'domestic'])->get();
    }

    public function headings(): array
    {
        return [
            'No.',
            'Nama Merchant (max 50)',
            'Nama Merchant (max 25)',
            'MPAN',
            'MID',
            'Kota',
            'Kodepos',
            'Kriteria',
            'MCC',
            'Jml Terminal',
            'Tipe Merchant',
            'NPWP**',
            'KTP**',
            'Tipe QR',
            'NMID',
            'Tanggal Create',
            'No Rekening',
            'Kategori',
            'Merchant Domestik'
        ];
    }

    public function map($merchant): array
    {

        $this->rowNumber++;
        return [
            $this->rowNumber,
            $merchant->MERCHANT_NAME, // Nama Merchant (max 50)
            substr($merchant->MERCHANT_NAME, 0, 25), // Nama Merchant (max 25)
            $merchant->details ? $merchant->details->MPAN : '', // MPAN
            $merchant->details ? $merchant->details->MID : '', // MID
            $merchant->MERCHANT_CITY, // Kota
            $merchant->POSTAL_CODE, // Kodepos
            $merchant->details ? $merchant->details->CRITERIA : '', // Kriteria
            $merchant->domestic ? $merchant->domestic->MCC : '', // MCC
            '1', // Jml Terminal - Asumsikan ini perlu diisi secara manual atau dengan data lain
            $merchant->MERCHANT_TYPE_2, // Tipe Merchant
            $merchant->NPWP, // NPWP**
            $merchant->KTP, // KTP**
            $merchant->TYPE_QR, // Tipe QR
            $merchant->domestic ? $merchant->domestic->NMID : '', // NMID
            $merchant->CREATED_AT, // Tanggal Create
            $merchant->ACCOUNT_NUMBER, // No Rekening
            '', // Kategori - Asumsikan ini perlu diisi secara manual atau dengan data lain
            $merchant->domestic ? 'Ya' : 'Tidak' // Merchant Domestik
        ];
    }
    
}
