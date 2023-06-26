@extends('adminlte::page')

@section('title', 'Dashboard')

@section('css')
<!-- <link rel="stylesheet" href="/css/admin_custom.css"> -->
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.1/css/jquery.dataTables.css">
<!-- jQuery Library -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
@stop



@section('content')


<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1> List Transaction </h1>
            </div>
        </div>
    </div>
</section>
<!-- modal detail -->
<div class="modal fade" id="detailModal" tabindex="-1" aria-labelledby="detailModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Detail Transaksi</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group col-12">
                    <label>Status Transaksi</label>
                    <input type="text" class="form-control" name="MERCHANT_ACC_NUMBER" id="MERCHANT_ACC_NUMBER" required readonly>
                </div>
                <div class="form-group col-12">
                    <label>Nama Accquier</label>
                    <input type="text" class="form-control" name="TIP_INDICATOR" id="TIP_INDICATOR" required readonly>
                </div>
                <div class="form-group col-12">
                    <label>Rekening Sumber</label>
                    <input type="text" class="form-control" name="ACCOUNT_NUMBER" id="ACCOUNT_NUMBER" required readonly>
                </div>
                <div class="form-group col-12">
                    <label>Nama Merchant </label>
                    <input type="text" class="form-control" name="MERCHANT_NAME" id="MERCHANT_NAME" required readonly>
                </div>
                <div class="form-group col-12">
                    <label>Alamat Merchant </label>
                    <input type="text" class="form-control" name="MERCHANT_ADDRESS" id="MERCHANT_ADDRESS" required readonly>
                </div>
                <div class="form-group col-12">
                    <label>Kode Pos </label>
                    <input type="text" class="form-control" name="POSTAL_CODE" id="POSTAL_CODE" required readonly>
                </div>
                <div class="form-group col-12">
                    <label>Kode Negara </label>
                    <input type="text" class="form-control" name="MERCHANT_CURRENCY_CODE" id="MERCHANT_CURRENCY_CODE" required readonly>
                </div>
                <div class="form-group col-12">
                    <label>Merchant PAN </label>
                    <input type="text" class="form-control" name="balance" id="balance" required readonly>
                </div>
                <div class="form-group col-12">
                    <label>Terminal ID / Merchant ID </label>
                    <input type="text" class="form-control" name="MERCHANT_ID" id="MERCHANT_ID" required readonly>
                </div>
                <div class="form-group col-12">
                    <label>Costumer PAN </label>
                    <input type="text" class="form-control" name="balance" id="balance" required readonly>
                </div>
                <div class="form-group col-12">
                    <label> Tipe Transaksi </label>
                    <input type="text" class="form-control" name="balance" id="balance" required readonly>
                </div>
                <div class="form-group col-12">
                    <label>Retrieval Reference Number </label>
                    <input type="text" class="form-control" name="RETRIEVAL_REFERENCE_NUMBER" id="RETRIEVAL_REFERENCE_NUMBER" required readonly>
                </div>
                <div class="form-group col-12">
                    <label>Nomor Invoice </label>
                    <input type="text" class="form-control" name="INVOICE_NUMBER" id="INVOICE_NUMBER" required readonly>
                </div>
                <div class="form-group col-12">
                    <label>Nominal Transaksi </label>
                    <input type="text" class="form-control" name="AMOUNT" id="AMOUNT" required readonly>
                </div>

            </div>

        </div>
    </div>
</div>
<!-- modal detail -->

<!-- modal refund -->

<div class="modal" id="refundModal" tabindex="-1" aria-labelledby="detailModalLabel" aria-hidden="true">
    <div class="modal-dialog ">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Detail Refund</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group col-12">
                    <label>Waktu Transaksi</label>
                    <input type="text" class="form-control" name="bit_12_RF" id="bit_12_RF" required readonly>
                </div>
                <div class="form-group col-12">
                    <label>Status Transaksi</label>
                    <input type="text" class="form-control" name="STATUS_TRANSACTION_RF" id="STATUS_TRANSACTION_RF" required readonly>
                </div>
                <div class="form-group col-12">
                    <label>Accquiring Institution Name</label>
                    <input type="text" class="form-control" name="" id="" value="Bank KBBS" required readonly>
                </div>
                <div class="form-group col-12">
                    <label>Merchant PAN</label>
                    <input type="text" class="form-control" name="MERCHANT_NAME" id="MERCHANT_NAME" value="9360052177010119420" required readonly>
                </div>
                <div class="form-group col-12">
                    <label>Issuing Institution Name </label>
                    <input type="text" class="form-control" name="ISSUING_INSTITUTION_NAME" id="ISSUING_INSTITUTION_NAME" required readonly>
                </div>
                <div class="form-group col-12">
                    <label>Issuing Costumer Name </label>
                    <input type="text" class="form-control" name="ISSUING_CUSTOMER_NAME" id="ISSUING_CUSTOMER_NAME" required readonly>
                </div>
                <div class="form-group col-12">
                    <label>Costumer PAN</label>
                    <input type="text" class="form-control" name="CUSTOMER_PAN" id="CUSTOMER_PAN" required readonly>
                </div>
                <div class="form-group col-12">
                    <label>RRN Refund</label>
                    <input type="text" class="form-control" name="RETRIEVAL_REFERENCE_NUMBER_RF" id="RETRIEVAL_REFERENCE_NUMBER_RF" required readonly>
                </div>
                <div class="form-group col-12">
                    <label>Refund Amount </label>
                    <input type="text" class="form-control" name="CURRENT_AMOUNT_REFUND_RF" id="CURRENT_AMOUNT_REFUND_RF" required readonly>
                </div>
                <div class="form-group col-12">
                    <label>Convinience fee/tips </label>
                    <input type="text" class="form-control" name="FEE_AMOUNT_RF" id="FEE_AMOUNT_RF" required readonly>
                </div>
                <div class="form-group col-12">
                    <label> Transaction Type </label>
                    <input type="text" class="form-control" name="TRANSACTION_TYPE_RF" id="TRANSACTION_TYPE_RF" required readonly>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- modal refund -->
<!-- modal succes trx -->

<div class="modal" id="trxModal" tabindex="-1" aria-labelledby="detailModalLabel" aria-hidden="true">
    <div class="modal-dialog ">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Detail Transaksi</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group col-12">
                    <label>Waktu Transaksi</label>
                    <input type="text" class="form-control" name="bit_12_TRX" id="bit_12_TRX" required readonly>
                </div>
                <div class="form-group col-12">
                    <label>Status Transaksi</label>
                    <input type="text" class="form-control" name="STATUS_TRANSACTION_TRX" id="STATUS_TRANSACTION_TRX" required readonly>
                </div>
                <div class="form-group col-12">
                    <label>Accquiring Institution Name</label>
                    <input type="text" class="form-control" name="" id="" required value="Bank KBBS" readonly>
                </div>
                <div class="form-group col-12">
                    <label>Merchant PAN</label>
                    <input type="text" class="form-control" name="MERCHANT_NAME" id="MERCHANT_NAME" value="9360052177010119420" required readonly>
                </div>
                <div class="form-group col-12">
                    <label>Issuing Institution Name </label>
                    <input type="text" class="form-control" name="ISSUING_INSTITUTION_NAME_TRX" id="ISSUING_INSTITUTION_NAME_TRX" required readonly>
                </div>
                <div class="form-group col-12">
                    <label>Issuing Costumer Name </label>
                    <input type="text" class="form-control" name="ISSUING_CUSTOMER_NAME_TRX" id="ISSUING_CUSTOMER_NAME_TRX" required readonly>
                </div>
                <div class="form-group col-12">
                    <label>Costumer PAN</label>
                    <input type="text" class="form-control" name="CUSTOMER_PAN_TRX" id="CUSTOMER_PAN_TRX" required readonly>
                </div>
                <div class="form-group col-12">
                    <label>RRN</label>
                    <input type="text" class="form-control" name="RETRIEVAL_REFERENCE_NUMBER_TRX" id="RETRIEVAL_REFERENCE_NUMBER_TRX" required readonly>
                </div>
                <div class="form-group col-12">
                    <label>Payment Amount </label>
                    <input type="text" class="form-control" name="AMOUNT_TRX" id="AMOUNT_TRX" required readonly>
                </div>
                <div class="form-group col-12">
                    <label>Convinience fee/tips </label>
                    <input type="text" class="form-control" name="FEE_AMOUNT_TRX" id="FEE_AMOUNT_TRX" required readonly>
                </div>
                <div class="form-group col-12">
                    <label> Transaction Type </label>
                    <input type="text" class="form-control" name="TRANSACTION_TYPE_TRX" id="TRANSACTION_TYPE_TRX" required readonly>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- modal succes trx -->

<!-- HTML -->
{{-- <div> --}}
<!-- Filters -->
{{-- <div class="ui-bordered px-4 pt-4 mb-4">
      <div class="form-row">
          <div class="col-md-4 mb-3">
              <div class="form-group">
                  <label class="form-label">Amount</label>
                  <input class="form-control" type="text" name="searchByAmount" id="searchByAmount">
              </div>
          </div>
          <div class="col-md-4 mb-3">
              <div class="form-group">
                  <label class="form-label">Amount</label>
                  <input  type="text" name="toko" id="toko">
                  <select  class="form-control" id='searchByStatus'>
                     <option value=''>-- Status Transfer --</option>
                     <option value='0'>Belum Bayar</option>
                     <option value='1'>Bayar</option>
                     <option value='2'>Refund</option>
                   </select>
              </div>
          </div>
       		
          <div class="col-md col-xl-2 mb-2">
              <label class="form-label d-none d-md-block">&nbsp;</label>
              <button type="button" class="btn btn-secondary btn-block" id="search"><i class="fa fa-search"></i> Cari</button>
          </div>
      </div>
  </div> --}}
<!-- / Filters -->

<div class="card-body">
    <table id="tbl_list" class="table table-striped table-bordered" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th>NO</th>
                <th>TRANSACTION_ID</th>
                <th>MERCHANT_ID</th>
                {{-- <th>AMOUNT_TIP_PERCENTAGE</th> --}}
                <th>EXPIRE_DATE_TIME</th>
                <th>CREATED_AT</th>
                {{-- <th>TIP_INDICATOR</th> --}}
                <th>FEE_AMOUNT</th>
                <th>STATUS_TRANSFER</th>
                <th>RRN</th>
                <th>AMOUNT</th>
                <th>AMOUNT_REFUND</th>
                <th>QR Type</th>
                <th>SHOW</th>
                <th>STATUS</th>
            </tr>
        </thead>
        <tbody>

        </tbody>
    </table>
</div>


@endsection

@section('js')
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.js"></script>
<script type="text/javascript">
    $('#searchByAmount').keyup(function() {
        DataTable();
    });

    $('#tbl_list').on('click', '.detail-btn', function() {
        var id = $(this).data('id');

    });


    $(document).ready(function() {
        $('#tbl_list').DataTable({
            processing: true,
            serverSide: true,
            scrollX: true,
            ajax: {
                url: "{{ route('transactions.data') }}",
            },
            order: [
                [4, 'desc']
            ],
            columns: [{
                    render: function(data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    },
                },
                {
                    data: 'TRANSACTION_ID'
                },
                {
                    data: 'MERCHANT_ID'
                },

                {
                    data: 'EXPIRE_DATE_TIME'
                },
                {
                    data: 'CREATED_AT'
                },

                {
                    data: 'FEE_AMOUNT'
                },
                {
                    data: 'STATUS_TRANSFER'
                },
                {
                    data: 'RETRIEVAL_REFERENCE_NUMBER'
                },
                {
                    data: 'AMOUNT'
                },
                {
                    data: 'AMOUNT_REFUND'
                },
                {
                    data: 'QR_TYPE',
                    render: function(data, type, row, meta) {
                        if (data === 11) {
                            return 'static';
                        } else if (data === 12) {
                            return 'dinamic';
                        } else {
                            return '';
                        }
                    },
                },
                {
                    render: function(data, type, row, meta) {

                        return '<div class="btn-group">' +
                            '<button class="btn btn-sm btn-info detail-btn" onclick="showDetail(\'' +
                            row.ID + '\')">Detail</button>' +
                            '</div>';

                    }
                },
                {
                    render: function(data, type, row, meta) {
                        if (row.TRANSFER_STATUS === 3 && row.RC_FUND == 68) {
                            return '<div class="btn-group">' +
                                '<button class="btn btn-sm btn-danger refund-btn" onclick="refundDetail(\'' +
                                row.ID + '\')">Suspect</button>' +
                                '</div>';
                        } else if (row.TRANSFER_STATUS === 3 && row.RC_FUND == 00) {
                            return '<div class="btn-group">' +
                                '<button class="btn btn-sm btn-success refund-btn" onclick="refundDetail(\'' +
                                row.ID + '\')">Refund</button>' +
                                '</div>';
                        } else {
                            return '<div class="btn-group">' +
                                '<button class="btn btn-sm btn-primary detail-btn" onclick="trxDetail(\'' +
                                row.ID + '\')">Detail</button>' +
                                '</div>';
                        }
                    }
                },

            ],
        });

    });

    function showDetail(id) {
        // console.log(id);
        // const myHeaders = new Headers();
        // myHeaders.append('Content-Type', 'application/json');
        // myHeaders.append('Accept', 'application/json');
        // myHeaders.append('Access-Control-Allow-*', '*'); // Add this line to set the CORS header

        // const myRequest = new Request("{{ route('transactions.detail', '32b18591-d77f-48ed-804b-12ab7478fe0d') }}", {
        //     method: 'GET',
        //     headers: myHeaders,
        //     mode: 'cors', // Add this line to set the mode to 'cors'
        //     cache: 'default'
        // });
        var url = "{{ route('transactions.detail', ':id') }}";
        url = url.replace(':id', id);

        fetch(url)
            .then(response => response.json())
            .then(data => {
                $('#detailModal').modal('show');

                $('#ID').val(data.ID);
                $('#INVOICE_NUMBER').val(data.INVOICE_NUMBER);
                $('#RETRIEVAL_REFERENCE_NUMBER').val(data.RETRIEVAL_REFERENCE_NUMBER);
                $('#AMOUNT').val(data.AMOUNT);
                $('#MERCHANT_ID').val(data.MERCHANT_ID);
                $('#MERCHANT_NAME').val(data.MERCHANT['MERCHANT_NAME']);
                $('#POSTAL_CODE').val(data.MERCHANT['POSTAL_CODE']);
                $('#MERCHANT_ADDRESS').val(data.MERCHANT['MERCHANT_ADDRESS']);
                $('#MERCHANT_CURRENCY_CODE').val(data.MERCHANT['MERCHANT_CURRENCY_CODE']);
                $('#ACCOUNT_NUMBER').val(data.MERCHANT['ACCOUNT_NUMBER']);
                $('#MERCHANT_NAME').val(data.MERCHANT['MERCHANT_NAME']);
            })
            .catch(error => console.error(error));
    }


    function trxDetail(id) {

        var url = "{{ route('transactions.detail', ':id') }}";
        url = url.replace(':id', id);

        fetch(url)
            .then(response => response.json())
            .then(data => {
                let time = data.bit_12.substring(0, 2) + ':' + data.bit_12.substring(2, 4) + ':' + data.bit_12
                    .substring(4, 6) + ' ' + data.UPDATED_AT.substring(0, 10);

                $('#trxModal').modal('show');
                $('#ID').val(data.ID);
                $('#CREATED_AT_TRX').val(data.CREATED_AT);
                $('#RETRIEVAL_REFERENCE_NUMBER_TRX').val(data.RETRIEVAL_REFERENCE_NUMBER);
                $('#AMOUNT_TRX').val(data.AMOUNT);
                $('#ACQUIRING_INSTITUTION_NAME_TRX').val(data.ACQUIRING_INSTITUTION_NAME);
                $('#ISSUING_INSTITUTION_NAME_TRX').val(data.NNS);
                $('#ISSUING_CUSTOMER_NAME_TRX').val(data.ISSUING_CUSTOMER_NAME);
                $('#CUSTOMER_PAN_TRX').val(data.CUSTOMER_PAN);
                $('#MPAN_TRX').val(data.MPAN);
                $('#FEE_AMOUNT_TRX').val(data.FEE_AMOUNT);
                $('#bit_12_TRX').val(time);


                if (data.TRANSFER_STATUS === 1) {
                    $('#TRANSACTION_TYPE_TRX').val('Payment');
                } else {
                    $('#TRANSACTION_TYPE_TRX').val('Refund');
                }

                if (data.TRANSFER_STATUS === 1) {
                    $('#STATUS_TRANSACTION_TRX').val('Success');
                } else if (data.TRANSFER_STATUS === 3 && data.RC_FUND == 68) {
                    $('#STATUS_TRANSACTION_TRX').val('Suspect');
                } else {
                    $('#STATUS_TRANSACTION_TRX').val('Refund');
                }




            })
            .catch(error => console.error(error));
    }

    function refundDetail(id) {
            var url = "{{ route('transactions.detail', ':id') }}";
            url = url.replace(':id', id);

            fetch(url)
            .then(response => response.json())
            .then(data => {
                let time = data.bit_12.substring(0, 2) + ':' + data.bit_12.substring(2, 4) + ':' + data.bit_12
                    .substring(4, 6) + ' ' + data.UPDATED_AT.substring(0, 10);

                $('#refundModal').modal('show');

                $('#ID').val(data.ID);
                $('#CREATED_AT_RF').val(data.CREATED_AT);
                $('#RETRIEVAL_REFERENCE_NUMBER_RF').val(data.RRN_REFUND);
                $('#AMOUNT').val(data.AMOUNT);
                $('#AMOUNT_REFUND').val(data.AMOUNT_REFUND);
                $('#ACQUIRING_INSTITUTION_NAME').val(data.ACQUIRING_INSTITUTION_NAME);
                $('#ISSUING_INSTITUTION_NAME').val(data.NNS);
                $('#ISSUING_CUSTOMER_NAME').val(data.ISSUING_CUSTOMER_NAME);
                $('#CUSTOMER_PAN').val(data.CUSTOMER_PAN);
                $('#FEE_AMOUNT_RF').val(data.FEE_AMOUNT);
                $('#CURRENT_AMOUNT_REFUND_RF').val(data.CURRENT_AMOUNT_REFUND);
                $('#MPAN_RF').val(data.MPAN);
                $('#bit_12_RF').val(time);

                if (data.TRANSFER_STATUS === 1) {
                    $('#TRANSACTION_TYPE_RF').val('Payment');
                } else {
                    $('#TRANSACTION_TYPE_RF').val('Refund');
                }

                if (data.TRANSFER_STATUS === 1) {
                    $('#STATUS_TRANSACTION_RF').val('Success');
                } else if (data.TRANSFER_STATUS === 3 && data.RC_FUND == 68) {
                    $('#STATUS_TRANSACTION_RF').val('REFUND IN PROGRESS');
                } else {
                    $('#STATUS_TRANSACTION_RF').val('Refund');
                }





            })
            .catch(error => console.error(error));
    }
</script>
@endsection