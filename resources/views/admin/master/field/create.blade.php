@extends('layouts.app', [
'namePage' => 'Tambah Lapangan',
'class' => 'login-page sidebar-mini',
'activePage' => 'field',
'backgroundImage' => asset('now/img/bg14.jpg'),
'parent' => 'master'
])

@section('title', 'Lapangan')

@section('content')
<div class="panel-header panel-header-sm"></div>
<div class="content">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex align-items-center justify-content-between">
                        <h5 class="card-title">Tambah Lapangan</h5>
                        <a href="{{ route('admin.field.index') }}" class="btn btn-round btn-link">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.field.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        {{-- Jenis Lapangan --}}
                        <div class="form-group">
                            <label class="form-label">Jenis Lapangan <small class="text-danger">*</small></label>
                            <select name="field_type_id" class="form-control">
                                <option value="" disabled selected>Pilih Jenis Lapangan</option>
                                @foreach ($fieldTypes as $type)
                                <option value="{{ $type->id }}" {{ old('field_type_id') == $type->id ? 'selected' : '' }}>
                                    {{ $type->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="row">
                            {{-- Gambar Sampul --}}
                            <div class="col-md-6">
                                <label class="form-label">Gambar Sampul <small class="text-danger">*</small></label>
                                <input type="file" name="img" class="d-none" id="cover">
                                <div class="px-2 py-3 rounded border text-secondary upload-image" data-target="#cover">
                                    <i class="fas fa-image"></i> Upload Gambar
                                </div>
                            </div>

                            {{-- Gambar Detail --}}
                            <div class="col-md-6">
                                <label class="form-label">Gambar Detail <small>(opsional)</small></label>
                                <input type="file" name="detail[]" class="d-none" id="images" multiple>
                                <div class="px-2 py-3 rounded border text-secondary upload-image" data-target="#images">
                                    <i class="fas fa-image"></i> Upload Gambar
                                </div>
                            </div>

                            {{-- Nama Lapangan --}}
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Nama Lapangan <small class="text-danger">*</small></label>
                                    <input type="text" name="name" value="{{ old('name') }}" class="form-control" placeholder="Contoh: BDM 1">
                                </div>
                            </div>

                            {{-- Harga Sewa --}}
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Harga Sewa per Jam (IDR) <small class="text-danger">*</small></label>
                                    <input type="text" name="price" value="{{ number_format(old('price')) }}" class="form-control" placeholder="Contoh: 100000" inputmode="numeric">
                                    <input type="hidden" name="price" id="price-submit" value="{{ old('price') }}">
                                </div>
                            </div>

                            {{-- Ukuran --}}
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Panjang (meter) <small class="text-danger">*</small></label>
                                    <input type="text" name="width" value="{{ old('width') }}" class="form-control" placeholder="Contoh: 13.4">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Lebar (meter) <small class="text-danger">*</small></label>
                                    <input type="text" name="height" value="{{ old('height') }}" class="form-control" placeholder="Contoh: 6.1">
                                </div>
                            </div>
                        </div>

                        {{-- Tombol Submit --}}
                        <div class="form-group">
                            <div class="d-flex justify-content-end">
                                <button type="submit" class="btn btn-info">Simpan</button>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('css')
<style>
    .upload-image {
        cursor: pointer;
        background: #f9f9f9;
        text-align: center;
    }
</style>
@endpush

@push('js')
<script>
    const cleanNumber = (value) => {
        return parseFloat(value.toString().replace(/,/g, '').replace(/[^\d.-]/g, '') || 0);
    };

    const formatNumber = (num) => {
        let rev = cleanNumber(num).toString().split('').reverse().join('');
        return rev.match(/\d{1,3}/g).join(',').split('').reverse().join('');
    };

    $(document).ready(() => {
        const price = $('input[name=price]');
        price.on('keyup', function() {
            const val = cleanNumber($(this).val());
            $(this).val(formatNumber(val));
            $('#price-submit').val(val);
        });

        $('.upload-image').on('click', function() {
            const target = $(this).data('target');
            $(target).click();
        });

        $('#cover, #images').on('change', function() {
            const files = this.files;
            const id = $(this).attr('id');
            let label = '';

            for (let i = 0; i < files.length; i++) {
                label += `<span class="badge badge-info">${files[i].name}</span> `;
            }

            $(`.upload-image[data-target="#${id}"]`).html(label || '<i class="fas fa-image"></i> Upload Gambar');
        });
    });
</script>
@endpush