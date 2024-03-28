@extends('adminlte::page')

@section('title', 'Dashboard')

@section('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css">
@stop

@section('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
@stop

@section('content')
    <!-- Script -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <style type="text/css">
        .left {
            text-align: left;
        }

        .right {
            text-align: right;
        }

        .center {
            text-align: center;
        }

        .justify {
            text-align: justify;
        }
    </style>

    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Generate QRIS </h1>
                </div>
            </div>
        </div>
    </section>

    @if (!empty(Session::get('error_code')) && Session::get('error_code') == 5)
        <script>
            $(function() {
                $('#modalQr').modal('modalQr');
            });
        </script>
    @endif

    <div class="row">
        <div class="col-md-12">
            <div class="card card-default">
                <form action="{{ route('qris.hit') }}" method="POST">
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            <div class="form-group col-6">
                                <label>QR Type :</label>
                                <select class="form-control select2" name="TYPE" id="TYPE" required
                                    onchange="checkQRType()">
                                    <option value="STATIS">STATIS</option>
                                    <option value="DINAMIS">DINAMIS</option>
                                </select>
                            </div>
                            <div class="form-group col-6">
                                <label>Merchant Code:</label>
                                <select class="form-control select2" name="MERCHANT_ID" id="MERCHANT_ID" required>
                                    @foreach (array_reverse($merchant) as $dropdown)
                                        <option value="{{ $dropdown->ID }}"> {{ $dropdown->MERCHANT_CODE }} -
                                            {{ $dropdown->MERCHANT_NAME }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-6">
                                <label>AMOUNT:</label>
                                <input type="number" id="AMOUNT" name="AMOUNT" class="form-control" min="10000"
                                    max="1000000">
                            </div>
                            <div class="form-group col-6">
                                <label>TIP_INDICATOR:</label>
                                <input type="number" id="TIP_INDICATOR" class="form-control">
                            </div>
                            <div class="form-group col-6">
                                <label>FEE_AMOUNT:</label>
                                <input type="number" id="FEE_AMOUNT" class="form-control">
                            </div>
                            <div class="form-group col-6">
                                <label>FEE_AMOUNT_PERCENTAGE: (%) </label>
                                <input type="number" id="FEE_AMOUNT_PERCENTAGE" class="form-control">
                            </div>
                        </div>
                    </div>
                    {{-- <div class=" col-xs-12 col-sm-12 col-md-12 text-center card-footer d-flex justify-content-between align-items-center  "> --}}
                        <div class="col-xs-12 col-sm-12 col-md-12 text-center card-footer d-flex justify-content-between">
                        <!-- Tombol Submit -->
                        <button type="button" class="btn btn-success" id="store">Submit</button>
                    
                        
                        <div id="loading" style="display: none;">
                            <div class="spinner-border text-primary" role="status">
                                <span class="sr-only">Loading...</span>
                            </div>
                        </div>
                    </div>
                    
                </form>
            </div>
        </div>
    </div>


    <!-- tambahkan elemen untuk animasi loading -->
    <div class="modal" id="modalQr" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content center">
                <div class="modal-header">
                    <h5 class="modal-title">Generate QR </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div id="previewQr"></div>
                    <br>
                    <div>
                        <h5 id="errorResp"> </h5>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" id="btnDownload">Unduh</button>
                </div>
            </div>
        </div>
    </div>


    <script>
        //button create post event
        $(document).ready(function() {
            $('.select2').select2();
        });

        //disable amount if dinamis qr selected
        function checkQRType() {
            var qrType = document.getElementById("TYPE").value;
            var amountInput = document.getElementById("AMOUNT");

            if (qrType === "DINAMIS") {
                amountInput.disabled = true;
            } else {
                amountInput.disabled = false;
            }
        }




        //action create post
        $('#store').click(function(e) {
            e.preventDefault();

            // Tampilkan animasi loading
            $('#loading').show();

            // Nonaktifkan tombol submit
            $(this).prop('disabled', true);

            let qrType = $('#qrType').val();
            let MERCHANT_ID = $('#MERCHANT_ID').val();
            let AMOUNT = $('#AMOUNT').val();
            let TIP_INDICATOR = $('#TIP_INDICATOR').val();
            let FEE_AMOUNT = $('#FEE_AMOUNT').val();
            let FEE_AMOUNT_PERCENTAGE = $('#FEE_AMOUNT_PERCENTAGE').val();
            let TYPE = $('#TYPE').val();
            let token = $("meta[name='csrf-token']").attr("content");

            $.ajax({
                url: `qris/hit`,
                type: "POST",
                cache: false,
                data: {
                    "qrType": qrType,
                    "MERCHANT_ID": MERCHANT_ID,
                    "AMOUNT": AMOUNT,
                    "TIP_INDICATOR": TIP_INDICATOR,
                    "FEE_AMOUNT": FEE_AMOUNT,
                    "FEE_AMOUNT_PERCENTAGE": FEE_AMOUNT_PERCENTAGE,
                    "TYPE": TYPE,
                    "_token": token
                },
                success: function(response) {
                    $("#modalQr").modal('show');
                    $('#previewQr').html(`<img src="data:image/png;base64,` + response.qr + `" \>`);
                    $('#errorResp').html(JSON.stringify(response.error));

                    // Sembunyikan animasi loading
                    $('#loading').hide();

                    // Aktifkan kembali tombol submit
                    $('#store').prop('disabled', false);
                },
                error: function(error) {
                    $("#modalQr").modal('show');
                    // Sembunyikan animasi loading
                    $('#loading').hide();

                    // Aktifkan kembali tombol submit
                    $('#store').prop('disabled', false);
                }
            });
        });


        // Tindakan saat tombol Simpan diklik
        $('#btnSave').click(function() {
            // Tambahkan kode untuk menyimpan QR di sini
            alert('QR berhasil disimpan.');
        });

        // Tindakan saat tombol Unduh diklik
        $('#btnDownload').click(function() {
            // Mendapatkan gambar QR dari elemen img di dalam modal
            var qrImage = $('#previewQr img').attr('src');

            // Membuat elemen <a> untuk mengunduh gambar
            var downloadLink = document.createElement('a');
            downloadLink.href = qrImage;
            downloadLink.download = 'QR_Code.png';

            // Menambahkan elemen <a> ke dalam dokumen dan mengkliknya secara otomatis
            document.body.appendChild(downloadLink);
            downloadLink.click();
            document.body.removeChild(downloadLink);

            alert('QR berhasil diunduh.');
        });
    </script>

@endsection
