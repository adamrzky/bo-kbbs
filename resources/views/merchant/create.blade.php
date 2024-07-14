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
                                <div class="input-group">
                                    <input type="number" class="form-control" name="norek" id="norek"
                                        value="{{ old('norek') }}" required>
                                    <div class="input-group-append">
                                        <span id="norekStatus" class="input-group-text"></span>
                                        <button type="button" onclick="cekNorek()" class="btn btn-info">Cek No Rek</button>
                                    </div>
                                </div>
                            </div>


                            <div class="form-group col-6">
                                <label>Nama Merchant</label>
                                <input type="text" class="form-control" name="merchant" id="merchant"
                                    value="{{ old('merchant') }}" required>
                            </div>

                            <div class="form-group col-6">
                                <label>MID</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" name="mid" id="mid"
                                        value="{{ old('mid') }}" readonly required>

                                    <div class="input-group-append">
                                        <span id="midStatus" class="input-group-text"></span>
                                        <button type="button" onclick="generateMid()" class="btn btn-info">Generate
                                            MID</button>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group col-6">
                                <label>NMID</label>
                                <input type="text" class="form-control" name="nmid" id="nmid"
                                    value="{{ old('nmid') }}">
                            </div>


                            <div class="form-group col-6">
                                <label>MPAN</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" name="mpan" id="mpan"
                                        value="{{ old('mpan') }}" readonly>
                                    <div class="input-group-append">
                                        <button type="button" onclick="generateMpan()" class="btn btn-info">Generate
                                            MPAN</button>
                                    </div>
                                </div>
                            </div>


                            <div class="form-group col-6">
                                <label>Cabang</label>
                                <select class="form-control" name="cabang" id="cabang" required>
                                    @foreach ($cabangs as $cabang)
                                        <option value="{{ $cabang->CPC_MC_KODE_CABANG }}"
                                            data-lokasi="{{ $cabang->CPC_MC_KODE_LOKASI }}">
                                            {{ $cabang->CPC_MC_NAMA }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>



                            <div class="form-group col-6">
                                <label>Tipe Merchant</label>
                                <select class="form-control" name="merchantTipe" id="merchantTipe" onchange="toggleFields()"
                                    required>
                                    <option value="1">Individu</option>
                                    <option value="2">Badan Usaha</option>
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
                                    <option value="">-</option>
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
        $("#prov").change(function() {
            var provinsi = $("#prov").val();
            var negara = 'ID'; // Asumsikan negara selalu ID seperti dalam contoh

            $.ajax({
                url: `http://103.182.72.16:10002/api.php?negara=${negara}&prov=${provinsi}`,
                type: 'GET',
                success: function(msg) {
                    var res = JSON.parse(msg); // Jika respon adalah JSON string
                    var select = $('#city');
                    select.empty(); // Menghapus semua option yang ada
                    select.append(new Option('-', '')); // Menambahkan option default

                    $.each(res, function(index, item) {
                        var option = new Option(item.KOTA, item.KODE);
                        select.append($(option));
                    });
                },
                error: function(xhr, status, error) {
                    console.log("Error: " + error);
                }
            });
        });

        $(document).ready(function() {
            prov();
        });

        function toggleFields() {
            var type = $('#merchantTipe').val();
            if (type == '1') {
                $('#ktpField').show();
                $('#npwpField').hide();
            } else if (type == '2') {
                $('#ktpField').hide();
                $('#npwpField').show();
            }
        }

        $(document).ready(function() {
            toggleFields(); // Call on document ready to set the correct field
            prov(); // Initialize your existing province-city logic
        });

        function cekNorek() {
            var norek = document.getElementById('norek').value;
            var statusIndicator = document.getElementById('norekStatus');
            var inputNorek = document.getElementById('norek');

            // Tampilkan animasi loading
            statusIndicator.innerHTML = '<i class="fas fa-spinner fa-spin" style="font-size: 1.5em;"></i>';

            $.ajax({
                url: '{{ route('merchant.rekening') }}',
                type: 'POST',
                data: {
                    norek: norek,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.rc != '0000') {
                        statusIndicator.innerHTML =
                            '<i class="fas fa-times" style="font-size: 1.5em; color: red;"></i>';

                        alert('Nomor Rekening tidak valid: ' + response.msg);
                    } else {
                        statusIndicator.innerHTML =
                            '<i class="fas fa-check" style="font-size: 1.5em; color: green;"></i>';

                        // alert('Nomor Rekening valid');
                    }
                },
                error: function(xhr) {
                    alert('Error: ' + xhr.statusText);
                    statusIndicator.innerHTML =
                        '<i class="fas fa-times" style="font-size: 1.5em; color: red;"></i>';
                }
            });
        }


        function generateMid() {
            var selectedCabang = document.getElementById('cabang');
            var kodeCabang = selectedCabang.options[selectedCabang.selectedIndex].value;
            var kodeLokasi = selectedCabang.options[selectedCabang.selectedIndex].getAttribute('data-lokasi');
            var statusIndicator = document.getElementById('midStatus'); // Pastikan elemen ini ada di HTML

            statusIndicator.innerHTML = '<i class="fas fa-spinner fa-spin" style="font-size: 1.5em;"></i>';

            $.ajax({
                url: '{{ route('merchant.generateMid') }}',
                type: 'POST',
                data: {
                    kodeCabang: kodeCabang,
                    kodeLokasi: kodeLokasi,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.success) {
                        document.getElementById('mid').value = response.mid;
                        statusIndicator.innerHTML =
                            '<i class="fas fa-check" style="font-size: 1.5em; color: green;"></i>';
                    } else {
                        alert('Gagal menghasilkan MID: ' + response.message);
                        statusIndicator.innerHTML =
                            '<i class="fas fa-times" style="font-size: 1.5em; color: red;"></i>';
                    }
                },
                error: function(xhr) {
                    alert('Error: ' + xhr.statusText);
                    statusIndicator.innerHTML =
                        '<i class="fas fa-times" style="font-size: 1.5em; color: red;"></i>';
                }
            });
        }

        function generateMpan() {
            var mid = document.getElementById('mid').value;
            if (mid) {
                // NNS + "0" + MID
                var nns = '9360052'; // Contoh NNS, sesuaikan dengan kebutuhan Anda
                var baseMpan = nns + "0" + mid;

                // Menghitung Luhn digit dan menambahkannya di akhir
                var luhnDigit = calculateLuhn(baseMpan);
                var mpan = baseMpan + luhnDigit;

                document.getElementById('mpan').value = mpan;
            } else {
                alert('MID belum dihasilkan. Silakan generate MID terlebih dahulu.');
            }
        }

        function calculateLuhn(input) {
            let sum = 0;
            let shouldDouble = false; // Mulai dari kanan, double setiap digit kedua
            for (let i = input.length - 1; i >= 0; i--) {
                let digit = parseInt(input.charAt(i));

                if (shouldDouble) {
                    digit *= 2;
                    if (digit > 9) {
                        digit -= 9;
                    }
                }

                sum += digit;
                shouldDouble = !shouldDouble;
            }

            let checkDigit = (10 - (sum % 10)) % 10;
            return checkDigit;
        }
    </script>
@endsection
