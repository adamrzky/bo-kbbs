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
                <h2>Edit Merchant </h2>
            </div>
            <div class="pull-right">

            </div>
        </div>
    </div>


    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Whoops!</strong> There were some problems with your input.<br><br>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif



    <form action="{{ route('merchant.update', $merchant->ID) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">

                    <strong>MERCHANT_ID:</strong>
                     <input type="text" name="ID" value="{{ $merchant->ID }}" class="form-control col-3" readonly>
                    <strong>Merchant City:</strong>
                    <input name="MERCHANT_CITY" value="{{ $merchant->MERCHANT_CITY }}" class="form-control col-3">
                    <strong>Name:</strong>
                    <input type="text" name="MERCHANT_NAME" value="{{ $merchant->MERCHANT_NAME }}"
                        class="form-control col-3">
                    <strong>Kode Pos:</strong>
                    <input type="text" name="POSTAL_CODE" value="{{ $merchant->POSTAL_CODE }}"
                        class="form-control col-3">
                    <strong>Merchant Curency:</strong>
                    <input type="text" name="MERCHANT_CURRENCY_CODE" value="{{ $merchant->MERCHANT_CURRENCY_CODE }}"
                        class="form-control col-3">
                    <strong>Terminal Label:</strong>
                    <input type="text" name="TERMINAL_LABEL" value="{{ $merchant->TERMINAL_LABEL }}"
                        class="form-control col-3">
                    <strong>MERCHANT_CODE:</strong>
                    <input type="text" name="MERCHANT_CODE" value="{{ $merchant->MERCHANT_CODE }}"
                        class="form-control col-3">
                    <strong>MERCHANT_COUNTRY:</strong>
                    <input type="text" name="MERCHANT_COUNTRY" value="{{ $merchant->MERCHANT_COUNTRY }}"
                        class="form-control col-3">
                    <strong>QRIS_MERCHANT_DOMESTIC_ID:</strong>
                    <input type="text" name="QRIS_MERCHANT_DOMESTIC_ID"
                        value="{{ $merchant->QRIS_MERCHANT_DOMESTIC_ID }}" class="form-control col-3">
                    <strong>TYPE_QR:</strong>
                    <input type="text" name="TYPE_QR" value="{{ $merchant->TYPE_QR }}" class="form-control col-3">
                    <strong>MERCHANT_TYPE:</strong>
                    <input type="text" name="MERCHANT_TYPE" value="{{ $merchant->MERCHANT_TYPE }}"
                        class="form-control col-3">
                    <strong>REKENING_NUMBER:</strong>
                    <input type="text" name="ACCOUNT_NUMBER" value="{{ $merchant->ACCOUNT_NUMBER }}"
                        class="form-control col-3">
                    {{-- <strong>CATEGORY:</strong>
                     <input type="text" name="CATEGORY" value="{{ $merchant->CATEGORY }}" class="form-control col-3"         
                    <strong>CRITERIA:</strong>
                     <input type="text" name="CRITERIA" value="{{ $merchant->CRITERIA }}" class="form-control col-3" > --}}
                    <strong>STATUS:</strong>
                    <input type="number" name="STATUS" value="{{ $merchant->STATUS }}" class="form-control col-3">
                    <strong>MERCHANT_ADDRESS:</strong>
                    <input type="text" name="MERCHANT_ADDRESS" value="{{ $merchant->MERCHANT_ADDRESS }}"
                        class="form-control col-3">
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                    <a class="btn btn-primary" href="{{ route('merchant.index') }}"> Cancel </a>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>

            </div>

        </div>


    </form>



@endsection
