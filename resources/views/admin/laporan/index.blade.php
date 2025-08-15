@extends('layouts.app', [
'namePage' => 'Laporan',
'class' => 'login-page sidebar-mini ',
'activePage' => 'income',
'backgroundImage' => asset('now') . "/img/bg14.jpg",
'parent' => 'order'
])
@section('title','Laporan Transaksi')

@section('content')
<div class="content mt-5 pt-5">
    <div class="container">
        <div class="card">
            <div class="card-header">
                <h5>Pilih Rentang Tanggal</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.laporan.export') }}" method="GET" target="_blank">
                    <div class="row">
                        <div class="col-md-5">
                            <label for="start_date">Dari Tanggal:</label>
                            <input type="date" id="start_date" name="start_date" class="form-control" required>
                        </div>
                        <div class="col-md-5">
                            <label for="end_date">Sampai Tanggal:</label>
                            <input type="date" id="end_date" name="end_date" class="form-control" required>
                        </div>
                        <div class="col-md-2 d-flex align-items-end">
                            <button type="submit" class="btn btn-success btn-block">
                                <i class="fas fa-file-csv"></i> Export CSV
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection