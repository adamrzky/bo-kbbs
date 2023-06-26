@extends('adminlte::page')

@section('title', 'Dashboard')


@section('css')
<!-- <link rel="stylesheet" href="/css/admin_custom.css"> -->
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
            <h2>Merchants</h2>
        </div>
        <div class="pull-right">
            @can('merchant-create')
            <a class="btn btn-success" href="{{ route('merchant.create') }}"> Create New Merchant</a>
            @endcan
        </div>
    </div>
</div>
<br>

@if ($message = Session::get('success'))
<div class="alert alert-success">
    <p>{{ $message }}</p>
</div>
@endif


<table class="table table-bordered">
    <tr>
        <th>No</th>
        <th>Merchant Name</th>
        <th>Merchant City</th>
        <th>Action</th>
    </tr>

    @foreach ($merchant as $row)
    <tr>
        <td>{{ $row->ID }}</td>
        <td>{{ $row->MERCHANT_NAME }}</td>
        <td>{{ $row->MERCHANT_CITY }}</td>
        <td>
            <a class="btn btn-info" href="{{ route('merchant.show', Crypt::encrypt($row->ID)) }}">Detail</a>

            @can('merchant-edit')
            <a class="btn btn-warning" href="{{ route('merchant.edit',Crypt::encrypt($row->ID)) }}">Edit</a>
            @endcan

            <button class="btn btn-success" onclick="ceksaldo(`{{ Crypt::encrypt($row->ID) }}`)">Saldo</button>
            <button class="btn btn-primary" onclick="cekmutasi(`{{ Crypt::encrypt($row->ID) }}`)">Mutasi</button>
        </td>
    </tr>
    @endforeach
</table>


{!! $merchant->links() !!}

<div class="modal" id="modalSaldo" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content center">
            <div class="modal-header">
                <h5 class="modal-title">Cek Saldo</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group col-12">
                    <label>Nomor Rekening</label>
                    <input type="text" class="form-control" name="norek" id="norek" required readonly>
                </div>
                <div class="form-group col-12">
                    <label>Nama Rekening</label>
                    <input type="text" class="form-control" name="name" id="name" required readonly>
                </div>
                <div class="form-group col-12">
                    <label>Saldo</label>
                    <input type="text" class="form-control" name="balance" id="balance" required readonly>
                </div>
                <br>
                <div>
                    <h5 id="errorResp"></h5>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal" id="modalMutasi" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content center">
            <div class="modal-header">
                <h5 class="modal-title">Mutasi</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <div id="table_mutasi"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    //action cek
    function ceksaldo(id) {
        // define variable
        let token = $("meta[name='csrf-token']").attr("content");

        //ajax
        $.ajax({
            url: `merchant/saldo`,
            type: "POST",
            cache: false,
            data: {
                "id": id,
                "_token": token
            },
            success: function(response) {
                $("#modalSaldo").modal('show');
                $('#norek').val(response.norek);
                $('#name').val(response.name);
                $('#balance').val(response.balance);
            },
            error: function(error) {
                $("#modalMutasi").modal('show');
            }
        });
    };

    function cekmutasi(id) {
        // define variable
        let token = $("meta[name='csrf-token']").attr("content");

        //ajax
        $.ajax({
            url: `merchant/mutasi`,
            type: "POST",
            cache: false,
            data: {
                "id": id,
                "_token": token
            },
            success: function(response) {
                $("#modalMutasi").modal('show');
                body = '';
                for (let index = 0; index < response.length; index++) {
                    const element = JSON.parse(response[index]);
                    body += '<tr>\
                    <td>' + element.TIME + '</td>\
                    <td>' + element.AMT + '</td>\
                    <td>' + element.DESC + '</td>\
                    <td>' + element.MOD + '</td>\
                    <td>' + element.TYPE + '</td>\
                    <td>' + element.REF + '</td>\
                    </tr>';
                }
                html = document.getElementById("table_mutasi");
                html.innerHTML = '<table class="table" width="100%">\
                <thead>\
                    <tr>\
                        <th>Date</th>\
                        <th>Amount</th>\
                        <th>Desc</th>\
                        <th>Trx</th>\
                        <th>Type</th>\
                        <th>Refnum</th>\
                    </tr>\
                    </thead>\
                    <tbody>' + body + '</tbody></table>';


            },
            error: function(error) {
                // $("#modalSaldo").modal('show');
            }
        });
    };
</script>

@endsection