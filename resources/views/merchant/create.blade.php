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
							<input type="number" class="form-control" name="norek" id="norek" value="{{ old('norek') }}" required>
						</div>
						<div class="form-group col-6">
							<label>Nama Merchant</label>
							<input type="text" class="form-control" name="merchant" id="merchant" value="{{ old('merchant') }}" required>
						</div>
						<div class="form-group col-6">
							<label>Kategori Merchant (MCC)</label>
							<select class="form-control" name="mcc" id="mcc" required>
								@foreach ($mcc as $dropdown)
								<option value="{{ $dropdown['CODE_MCC'] }}">{{ $dropdown['CODE_MCC'] }} - {{ $dropdown['DESC_MCC'] }}</option>
								@endforeach
							</select>
						</div>
						<div class="form-group col-6">
							<label>Kriteria Merchant (Criteria)</label>
							<select class="form-control" name="criteria" id="criteria" required>
								@foreach ($criteria as $dropdown)
								<option value="{{ $dropdown['id'] }}">{{ $dropdown['id'] }} - {{ $dropdown['desc'] }}</option>
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
							<label>Alamat</label>
							<textarea class="form-control" name="address" id="address" rows="3">{{ old('address') }}</textarea>
						</div>
						<div class="form-group col-6">
							<label>Kodepos</label>
							<input type="number" class="form-control" name="postalcode" id="postalcode" value="{{ old('postalcode') }}">
						</div>
						<div class="form-group col-6">
							<label>Fee Merchant</label>
							<input type="number" class="form-control" name="fee" id="fee" value="0" min="0" max="100" value="{{ old('fee') }}">
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
				url: `http://182.23.93.76:10002/api.php?negara=ID&prov=` + provinsi,
				type: 'GET',
				success: function(msg) {
					var res = JSON.parse(msg);
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
@endsection