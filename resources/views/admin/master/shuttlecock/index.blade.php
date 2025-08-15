@extends('layouts.app', [
'namePage' => 'Kelola Shuttlecock',
'class' => 'login-page sidebar-mini',
'activePage' => 'shuttlecock',
'backgroundImage' => asset('now') . "/img/bg14.jpg",
'parent' => 'master'
])

@section('title','Shuttlecock')

@section('content')
<div class="panel-header panel-header-sm"></div>
<div class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="card">

                <!-- Header -->
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title">KELOLA SHUTTLECOCK</h4>
                    <div>
                        <a href="#" data-toggle="modal" data-target="#modalCreate" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus"></i> Tambah Shuttlecock
                        </a>
                    </div>
                </div>

                <!-- Search -->
                <div class="px-3 pb-3">
                    <form action="" method="get" class="form-inline">
                        <div class="input-group input-group-sm">
                            <input type="text" name="q" value="{{ request()->q }}" placeholder="Cari..." class="form-control">
                            <div class="input-group-append">
                                <button class="btn btn-outline-secondary" type="submit">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Body -->
                <div class="card-body">
                    <p class="text-muted">Hasil : {{ $shuttles->count() }}</p>
                    <div class="row">
                        @foreach($shuttles as $shuttle)
                        <div class="col-md-4 mb-4">
                            <div class="card shadow-sm h-100">

                                <div class="card-body">
                                    <h5 class="card-title text-center">{{ $shuttle->brand }}</h5>
                                    <div class="d-flex justify-content-between mb-2">
                                        <span class="text-muted">Status:</span>
                                        <span class="{{ $shuttle->is_available ? 'text-success' : 'text-danger' }}">
                                            {{ $shuttle->is_available ? 'Tersedia' : 'Kosong' }}
                                        </span>
                                    </div>
                                    <div class="d-flex justify-content-between mb-2">
                                        <span class="text-muted">Stok:</span>
                                        <span>{{ $shuttle->stock }} pcs</span>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <span class="text-muted">Harga:</span>
                                        <span>Rp{{ number_format($shuttle->price, 0, ',', '.') }}</span>
                                    </div>
                                </div>

                                <!-- Tombol Aksi -->
                                <div class="card-footer bg-white">
                                    <div class="d-flex justify-content-between">
                                        <button class="btn btn-warning btn-sm btn-edit" data-id="{{ $shuttle->id }}">
                                            <i class="fas fa-edit"></i> Edit
                                        </button>
                                        <button class="btn btn-danger btn-sm btn-delete" data-id="{{ $shuttle->id }}">
                                            <i class="fas fa-trash"></i> Hapus
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- Footer Pagination -->
                <div class="card-footer">
                    {{ $shuttles->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

<!-- Modal Tambah Shuttlecock -->
<div class="modal fade" id="modalCreate" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-md" role="document">
        <form action="{{ route('admin.shuttlecock.store') }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Shuttlecock</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Merk Shuttlecock</label>
                        <input type="text" name="brand" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Stok</label>
                        <input type="number" name="stock" class="form-control" required min="0">
                    </div>
                    <div class="form-group">
                        <label>Harga (Rp)</label>
                        <input type="number" name="price" class="form-control" required min="0">
                    </div>
                    <div class="form-group">
                        <label>Status Ketersediaan</label>
                        <select name="is_available" class="form-control" required>
                            <option value="1">Tersedia</option>
                            <option value="0">Kosong</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Modal Edit Shuttlecock -->
<div class="modal fade" id="modalEdit" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-md" role="document">
        <form id="editForm" method="POST">
            @csrf
            @method('PATCH')
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Shuttlecock</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" id="edit-id">
                    <div class="form-group">
                        <label>Merk Shuttlecock</label>
                        <input type="text" name="brand" id="edit-brand" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Stok</label>
                        <input type="number" name="stock" id="edit-stock" class="form-control" required min="0">
                    </div>
                    <div class="form-group">
                        <label>Harga (Rp)</label>
                        <input type="number" name="price" id="edit-price" class="form-control no-spinner" required min="0">
                    </div>
                    <div class="form-group">
                        <label>Status Ketersediaan</label>
                        <select name="is_available" id="edit-is_available" class="form-control" required>
                            <option value="1">Tersedia</option>
                            <option value="0">Kosong</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-warning">Update</button>
                </div>
            </div>
        </form>
    </div>
</div>

@push('css')
<style>
    .card {
        transition: all 0.3s ease;
    }

    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
    }

    /* Hilangkan spinner pada input number */
    input[type=number]::-webkit-inner-spin-button,
    input[type=number]::-webkit-outer-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }

    /* Untuk Firefox */
    input[type=number] {
        -moz-appearance: textfield;
        appearance: textfield;
    }
</style>
@endpush

@push('js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(document).ready(function() {
        // HANDLE EDIT
        $('.btn-edit').click(function() {
            const id = $(this).data('id');
            $.ajax({
                url: `/admin/shuttlecock/${id}`,
                type: 'GET',
                dataType: 'json',
                success: function(res) {
                    if (res.success) {
                        const shuttle = res.data;
                        const formEdit = $('#editForm');

                        formEdit.attr('action', `/admin/shuttlecock/update/${shuttle.id}`);
                        $('#edit-id').val(shuttle.id);
                        $('#edit-brand').val(shuttle.brand);
                        $('#edit-stock').val(shuttle.stock);
                        $('#edit-price').val(shuttle.price);
                        $('#edit-is_available').val(shuttle.is_available ? '1' : '0');

                        $('#modalEdit').modal('show');
                    } else {
                        Swal.fire('Error', res.message || 'Data tidak ditemukan', 'error');
                    }
                },
                error: function(xhr) {
                    let errorMsg = 'Gagal memuat data shuttlecock';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMsg = xhr.responseJSON.message;
                    }
                    Swal.fire('Error', errorMsg, 'error');
                }
            });
        });

        // Handle submit form edit
        $('#editForm').submit(function(e) {
            e.preventDefault();
            const form = $(this);
            const submitBtn = form.find('button[type="submit"]');
            submitBtn.prop('disabled', true);

            Swal.fire({
                title: 'Memperbarui',
                html: 'Sedang menyimpan perubahan...',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading()
                }
            });

            $.ajax({
                url: form.attr('action'),
                type: 'POST',
                data: form.serialize(),
                success: function(res) {
                    if (res.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: res.message || 'Data berhasil diperbarui',
                            timer: 2000,
                            showConfirmButton: false
                        }).then(() => {
                            window.location.reload();
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal!',
                            text: res.message || 'Terjadi kesalahan saat menyimpan'
                        });
                    }
                },
                error: function(xhr) {
                    let errorMessage = 'Terjadi kesalahan saat menyimpan';
                    if (xhr.status === 422) {
                        const errors = xhr.responseJSON.errors;
                        errorMessage = '';
                        for (const key in errors) {
                            errorMessage += errors[key][0] + '\n';
                        }
                    } else if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    }
                    Swal.fire({
                        icon: 'error',
                        title: 'Error ' + (xhr.status || ''),
                        html: errorMessage.replace(/\n/g, '<br>')
                    });
                },
                complete: function() {
                    submitBtn.prop('disabled', false);
                }
            });
        });

        // HANDLE DELETE
        $('.btn-delete').click(function() {
            const id = $(this).data('id');
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Data shuttlecock akan dihapus permanen!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Menghapus',
                        html: 'Sedang menghapus data...',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading()
                        }
                    });

                    $.ajax({
                        url: `/admin/shuttlecock/delete/${id}`,
                        type: 'POST',
                        data: {
                            _method: 'DELETE',
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(res) {
                            if (res.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Terhapus!',
                                    text: res.message,
                                    timer: 2000,
                                    showConfirmButton: false
                                }).then(() => {
                                    window.location.reload();
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Gagal!',
                                    text: res.message || 'Gagal menghapus data'
                                });
                            }
                        },
                        error: function(xhr) {
                            let errorMsg = 'Terjadi kesalahan saat menghapus';
                            if (xhr.status === 404) {
                                errorMsg = 'Data tidak ditemukan';
                            } else if (xhr.responseJSON && xhr.responseJSON.message) {
                                errorMsg = xhr.responseJSON.message;
                            }
                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                text: errorMsg
                            });
                        }
                    });
                }
            });
        });
    });
</script>
@endpush