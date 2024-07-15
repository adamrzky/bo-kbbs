@extends('adminlte::page')

@section('title', 'Dashboard')


@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
    <script>
        console.log('Hi!');
    </script>
@stop


@section('content')

    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2> Detail Merchant </h2>
            </div>
            <br>
        </div>
    </div>

    <!-- {{-- {{ dd($merchant->ID) }} --}} -->
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Name:</strong>
                {{ $merchant->MERCHANT_NAME }}
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>MPAN:</strong>
                {{ $merchant_detail->MPAN }}
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>MID:</strong>
                {{ $merchant_detail->MID }}
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Merchant City:</strong>
                {{ $merchant->MERCHANT_CITY }}
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Kode Pos:</strong>
                {{ $merchant->POSTAL_CODE }}
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Merchant Curency:</strong>
                {{ $merchant->MERCHANT_CURRENCY_CODE }}
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong> TERMINAL_LABEL:</strong>
                {{ $merchant->TERMINAL_LABEL }}
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>MERCHANT_COUNTRY:</strong>
                {{ $merchant->MERCHANT_COUNTRY }}
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>QRIS_MERCHANT_DOMESTIC_ID:</strong>
                {{ $merchant->QRIS_MERCHANT_DOMESTIC_ID }}
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>TYPE_QR:</strong>
                {{ $merchant->TYPE_QR }}
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>MERCHANT_TYPE:</strong>
                @if ($merchant->MERCHANT_TYPE == $mcc[0]['CODE_MCC'])
                    {{ $merchant->MERCHANT_TYPE }} -> {{ $mcc[0]['DESC_MCC'] }}
                @else
                    {{ $merchant->MERCHANT_TYPE }}
                @endif
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>MERCHANT_ID:</strong>
                {{ $merchant->ID }}
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>REKENING_NUMBER:</strong>
                {{ $merchant->ACCOUNT_NUMBER }}
            </div>
        </div>
        <!-- <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>CATEGORY:</strong>
                    {{ $merchant->CATEGORY }}
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>CRITERIA:</strong>
                    {{ $merchant->CRITERIA }}
                </div>
            </div> -->
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>STATUS:</strong>
                    @if ($merchant->STATUS == 1)
                        Aktif
                    @else
                        Tidak Aktif
                    @endif
                </div>
            </div>
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>MERCHANT_ADDRESS:</strong>
                {{ $merchant->MERCHANT_ADDRESS }}
            </div>
        </div>
        <div class="pull-right">
            <a class="btn btn-primary" href="{{ route('merchant.index') }}"> Back</a>
        </div>
    </div>
@endsection
