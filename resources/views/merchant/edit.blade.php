@extends('adminlte::page')

@section('title', 'Edit Merchant')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Edit Merchant Details</h1>
                </div>
            </div>
        </div>
    </section>

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
            <div class="col-md-12">
                <div class="card card-default">
                    <div class="card-body">
                        <div class="row">
                            <div class="form-group col-6">
                                <label>NMID</label>
                                <input type="text" class="form-control" name="NMID"
                                    value="{{ $merchant_detail->NMID }}" readonly>
                            </div>

                            <div class="form-group col-6">
                                <label>Nama Merchant (max 50)</label>
                                <input type="text" class="form-control" name="MERCHANT_NAME"
                                    value="{{ $merchant->MERCHANT_NAME }}" required>
                            </div>

                            <div class="form-group col-6">
                                <label>MPAN</label>
                                <input type="text" class="form-control" name="MPAN"
                                    value="{{ $merchant_detail->MPAN }}" readonly>
                            </div>

                            <div class="form-group col-6">
                                <label>MID</label>
                                <input type="text" class="form-control" name="MID"
                                    value="{{ $merchant_detail->MID }}" readonly>
                            </div>

                            <div class="form-group col-6">
                                <label>Kota</label>
                                <input type="text" class="form-control" name="MERCHANT_CITY"
                                    value="{{ $merchant->MERCHANT_CITY }}">
                            </div>

                            <div class="form-group col-6">
                                <label>Kodepos</label>
                                <input type="text" class="form-control" name="POSTAL_CODE"
                                    value="{{ $merchant->POSTAL_CODE }}">
                            </div>

                            <div class="form-group col-6">
                                <label>Kriteria Merchant</label>
                                <select class="form-control" name="criteria" id="criteria" required>
                                    @foreach ($criteria as $option)
                                        <option value="{{ $option['id'] }}" {{ $merchant_detail->CRITERIA == $option['id'] ? 'selected' : '' }}>
                                            {{ $option['id'] }} - {{ $option['desc'] }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group col-6">
                                <label>Kategori Merchant (MCC)</label>
                                <select class="form-control" name="mcc" id="mcc" required>
                                    @foreach ($mcc as $dropdown)
                                        <option value="{{ $dropdown['CODE_MCC'] }}"
                                            {{ $merchant_domestic->MCC == $dropdown['CODE_MCC'] ? 'selected' : '' }}>
                                            {{ $dropdown['CODE_MCC'] }} - {{ $dropdown['DESC_MCC'] }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>



                            <div class="form-group col-6">
                                <label>Jumlah Terminal</label>
                                <input type="number" class="form-control" name="JML_TERMINAL" value="1" readonly>
                            </div>

                            <div class="form-group col-6">
                                <label>Tipe Merchant</label>
                                <input type="text" class="form-control" name="MERCHANT_TYPE"
                                    value="{{ $merchant->MERCHANT_TYPE }}">
                            </div>

                            <div class="form-group col-6">
                                <label>NPWP</label>
                                <input type="text" class="form-control" name="NPWP" value="{{ $merchant->NPWP }}">
                            </div>

                            <div class="form-group col-6">
                                <label>KTP</label>
                                <input type="text" class="form-control" name="KTP" value="{{ $merchant->KTP }}">
                            </div>

                            <div class="form-group col-6">
                                <label>Tipe QR</label>
                                <select class="form-control" name="qrType" id="qrType" required>
                                    <option value="D" {{ $merchant->qrType == 'D' ? 'selected' : '' }}>Dinamis</option>
                                    <option value="S" {{ $merchant->qrType == 'S' ? 'selected' : '' }}>Statis</option>
                                    <option value="B" {{ $merchant->qrType == 'B' ? 'selected' : '' }}>Statis & Dinamis</option>
                                </select>
                            </div>
                            
                        </div>
                    </div>
                    <div class="card-footer">
                        <a class="btn btn-info" href="{{ route('merchant.index') }}">Back</a>
                        <button type="submit" class="btn btn-success">Save Changes</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection
