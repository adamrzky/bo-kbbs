@extends('adminlte::page')

@section('title', 'Detail Merchant')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Merchant Details</h1>
                </div>
            </div>
        </div>
    </section>

    <div class="row">
        {{-- Card untuk data $merchant --}}
        <div class="col-md-12">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Data Merchant</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        @foreach ($merchant->toArray() as $key => $value)
                        <div class="form-group col-6">
                            <label>{{ $key }}</label>
                            <input type="text" class="form-control" value="{{ $value }}" readonly>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        {{-- Card untuk data $merchant_details --}}
        <div class="col-md-12">
            <div class="card card-secondary">
                <div class="card-header">
                    <h3 class="card-title">Merchant Details</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        @foreach ($merchant_detail->toArray() as $key => $value)
                        <div class="form-group col-6">
                            <label>{{ $key }}</label>
                            <input type="text" class="form-control" value="{{ $value }}" readonly>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        {{-- Card untuk data $merchant_domestic --}}
        <div class="col-md-12">
            <div class="card card-info">
                <div class="card-header">
                    <h3 class="card-title">Merchant Domestic</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        @foreach ($merchant_domestic->toArray() as $key => $value)
                        <div class="form-group col-6">
                            <label>{{ $key }}</label>
                            <input type="text" class="form-control" value="{{ $value }}" readonly>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection