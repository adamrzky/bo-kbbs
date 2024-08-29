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

    <div class="col-md-12">
        <div class="card card-success">
            <div class="card-header">
                <h3 class="card-title">QR Code</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="form-group col-12 text-center">
                        @if ($imageExists)
                            <button type="button" class="btn btn-primary" onclick="toggleQrCode()">Tampilkan QR Code</button>
                            <a href="{{ asset($imagePath) }}" class="btn btn-success" download>Download QR Code</a>
                            <div id="qrCodeContainer" style="display:none;"> 
                                <img src="{{ asset($imagePath) }}" alt="QR Code" style="max-width: 300px;">
                            </div>
                        @else
                            <p>QR Code tidak tersedia.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

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

<script>
    function toggleQrCode() {
        var qrCodeContainer = document.getElementById('qrCodeContainer');
        if (qrCodeContainer.style.display === 'none') {
            qrCodeContainer.style.display = 'block';
        } else {
            qrCodeContainer.style.display = 'none';
        }
    }
    </script>