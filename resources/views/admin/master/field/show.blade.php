@extends('layouts.app', [
'namePage' => 'Detail Lapangan',
'class' => 'login-page sidebar-mini ',
'activePage' => 'field',
'backgroundImage' => asset('now') . "/img/bg14.jpg",
'parent' => 'master'
])
@section('title','Detail Lapangan')
@section('content')

<div class="panel-header panel-header-sm"></div>
<div class="content">
    <div class="container">
        <div class="card">
            <div class="card-header text-center">
                <h4>{{ $field->name }}</h4>
                <img src="{{ asset($field->img) }}" class="img-fluid rounded" style="max-height: 300px;">
            </div>
            <div class="card-body">
                <p><strong>Harga:</strong> Rp {{ number_format($field->price) }}</p>
                <p><strong>Ukuran:</strong> {{ $field->width }}m x {{ $field->height }}m</p>
                <p><strong>Jenis:</strong> {{ $field->field_type->name }}</p>
                <p><strong>Status:</strong>
                    <span class="badge badge-{{ $field->is_available == 1 ? 'success' : 'danger' }}">
                        {{ $field->is_available ? 'Tersedia' : 'Tidak Tersedia' }}
                    </span>
                </p>

                <hr>
                <h5>Gambar Detail</h5>
                <div class="row">
                    @forelse($images as $img)
                    <div class="col-md-4 mb-3">
                        <img src="{{ asset($img->img) }}" class="img-fluid rounded">
                    </div>
                    @empty
                    <div class="col-12">Tidak ada gambar detail</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

@endsection