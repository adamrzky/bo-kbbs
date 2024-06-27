@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Add New Merchant</h1>
                </div>
            </div>
        </div>
    </section>

    @if ($errors->any())
        <div class="alert alert-danger">
            <label>Whoops!</label> There were some problems with your input.<br><br>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="row">
        <div class="col-md-12">
            <div class="card card-default">
                <form action="{{ route('merchant.store') }}" method="POST">
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            <div class="form-group col-6">
                                <label>Nomor Rekening</label>
                                <input type="number" class="form-control" name="norek" id="norek"
                                    value="{{ old('norek') }}" required>
                                <button type="button" onclick="cekNorek()" class="btn btn-info">Cek No Rek</button>
                            </div>

                            <div class="form-group col-6">
                                <label>Nama Merchant</label>
                                <input type="text" class="form-control" name="merchant" id="merchant"
                                    value="{{ old('merchant') }}" required>
                            </div>

                            <div class="form-group col-6">
                                <label>MPAN</label>
                                <input type="text" class="form-control" name="mpan" id="mpan"
                                    value="{{ old('mpan') }}" required>
                            </div>

                            <div class="form-group col-6">
                                <label>NMID</label>
                                <input type="text" class="form-control" name="nmid" id="nmid"
                                    value="{{ old('nmid') }}" required>
                            </div>

                            <div class="form-group col-6">
                                <label>MID</label>
                                <input type="text" class="form-control" name="mid" id="mid"
                                    value="{{ old('mid') }}" required>
                            </div>

                            <div class="form-group col-6">
                                <label>Tipe Merchant</label>
                                <select class="form-control" name="type" id="type" onchange="toggleFields()"
                                    required>
                                    <option value="individual">Individu</option>
                                    <option value="business">Badan Usaha</option>
                                </select>
                            </div>

                            <div class="form-group col-6" id="ktpField" style="display:none;">
                                <label>KTP</label>
                                <input type="text" class="form-control" name="ktp" id="ktp">
                            </div>

                            <div class="form-group col-6" id="npwpField" style="display:none;">
                                <label>NPWP</label>
                                <input type="text" class="form-control" name="npwp" id="npwp">
                            </div>


                            <div class="form-group col-6">
                                <label>Tipe QR</label>
                                <select class="form-control" name="qrType" id="qrType" required>
                                    <option value="D">Dinamis</option>
                                    <option value="S">Statis</option>
                                    <option value="B">Statis & Dinamis</option>
                                </select>
                            </div>



                            <div class="form-group col-6">
                                <label>Kategori Merchant (MCC)</label>
                                <select class="form-control" name="mcc" id="mcc" required>
                                    @foreach ($mcc as $dropdown)
                                        <option value="{{ $dropdown['CODE_MCC'] }}">{{ $dropdown['CODE_MCC'] }} -
                                            {{ $dropdown['DESC_MCC'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-6">
                                <label>Kriteria Merchant (Criteria)</label>
                                <select class="form-control" name="criteria" id="criteria" required>
                                    @foreach ($criteria as $dropdown)
                                        <option value="{{ $dropdown['id'] }}">{{ $dropdown['id'] }} -
                                            {{ $dropdown['desc'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-6">
                                <label>Provinsi</label>
                                <select class="form-control" name="prov" id="prov" required>
                                    <option value="">-</option>
                                    @foreach ($prov as $dropdown)
                                        <option value="{{ $dropdown['CD_PROP'] }}">{{ $dropdown['PROPINSI'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-6">
                                <label>Kota</label>
                                <select class="form-control" name="city" id="city" required>
                                    <option value="Bandung">Bandung</option>
                                </select>
                            </div>
                            <div class="form-group col-6">
                                <label>Kodepos</label>
                                <input type="number" class="form-control" name="postalcode" id="postalcode"
                                    value="{{ old('postalcode') }}">
                            </div>
                            <div class="form-group col-6">
                                <label>Alamat</label>
                                <textarea class="form-control" name="address" id="address" rows="3">{{ old('address') }}</textarea>
                            </div>
                            <div hidden class="form-group  col-6">
                                <label>Fee Merchant</label>
                                <input type="number" class="form-control" name="fee" id="fee" hidden
                                    value="0" min="0" max="100" value="{{ old('fee') }}">
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <a class="btn btn-info" href="{{ route('merchant.index') }}">Back</a>
                        <button type="submit" class="btn btn-success">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>
        function prov() {
            $("#prov").change(function() {
                var provinsi = $("#prov").val();
                $.ajax({
                    url: `/api/getLokasi/${provinsi}`,
                    type: 'GET',
                    success: function(msg) {

                        var res = msg;

                        // console.log(res);
                        var select = $('#city');
                        var option = new Option('-', '');
                        select.focus();
                        $('option', select).remove();
                        select.append($(option));

                        $.each(res, function(text, key) {
                            var option = new Option(key.KOTA);
                            select.append($(option));
                        });
                    }
                });
            });
        }

        $(document).ready(function() {
            prov();
        });
    </script>

    <script>
        function toggleFields() {
            var type = $('#type').val();
            if (type == 'individual') {
                $('#ktpField').show();
                $('#npwpField').hide();
            } else if (type == 'business') {
                $('#ktpField').hide();
                $('#npwpField').show();
            }
        }

        $(document).ready(function() {
            toggleFields(); // Call on document ready to set the correct field
            prov(); // Initialize your existing province-city logic
        });
    </script>

    <script>
        function cekNorek() {
            var norek = document.getElementById('norek').value;
            $.ajax({
                url: '{{ route('merchant.rekening') }}', // Memanfaatkan route yang diberi nama untuk membangun URL
                type: 'POST',
                data: {
                    norek: norek,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.rc !== '0000') {
                        alert('Nomor Rekening tidak valid: ' + response.msg);
                    } else {
                        alert('Nomor Rekening valid');
                    }
                },
                error: function(xhr) {
                    alert('Error: ' + xhr.statusText);
                }
            });
        }
    </script>




@endsection
