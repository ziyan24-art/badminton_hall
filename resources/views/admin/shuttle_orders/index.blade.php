@extends('layouts.app', [
'namePage' => 'Shuttle Order',
'class' => 'login-page sidebar-mini',
'activePage' => 'shuttle-order',
'backgroundImage' => asset('now') . "/img/bg14.jpg",
'parent' => 'order'
])

@section('title','Shuttle Order')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Shuttle Order</h4>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-3">
                        <label>Filter Pengguna</label>
                        <select class="form-control filter-select" id="user-filter">
                            <option value="">Semua Pengguna</option>
                            @foreach($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label>Filter Shuttlecock</label>
                        <select class="form-control filter-select" id="shuttlecock-filter">
                            <option value="">Semua Shuttlecock</option>
                            @foreach($shuttlecocks as $shuttlecock)
                            <option value="{{ $shuttlecock->id }}">{{ $shuttlecock->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label>Filter Status</label>
                        <select class="form-control filter-select" id="status-filter">
                            <option value="">Semua Status</option>
                            <option value="pending">Menunggu Pembayaran</option>
                            <option value="paid">Lunas</option>
                            <option value="processing">Diproses</option>
                            <option value="cancelled">Dibatalkan</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label>Filter Tanggal Mulai</label>
                        <input type="date" class="form-control filter-select" id="start-date-filter">
                    </div>
                    <div class="col-md-2">
                        <label>Filter Tanggal Akhir</label>
                        <input type="date" class="form-control filter-select" id="end-date-filter">
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-striped" id="shuttle-order-table">
                        <thead class="text-primary">
                            <tr>
                                <th>No</th>
                                <th>Nama Pelanggan</th>
                                <th>Jenis Shuttlecock</th>
                                <th>Jumlah</th>
                                <th>Total Harga</th>
                                <th>Metode Pembayaran</th>
                                <th>Bukti Pembayaran</th>
                                <th>Status</th>
                                <th>Tanggal</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('css')
<style>
    .edit-mode-row {
        background-color: #fffde7 !important;
    }

    .badge {
        padding: 5px 10px;
        border-radius: 4px;
        font-size: 12px;
        font-weight: 600;
    }

    .badge-success {
        background-color: #28a745;
        color: white;
    }

    .badge-warning {
        background-color: #ffc107;
        color: black;
    }

    .badge-primary {
        background-color: #007bff;
        color: white;
    }

    .badge-info {
        background-color: #17a2b8;
        color: white;
    }

    .badge-danger {
        background-color: #dc3545;
        color: white;
    }

    .badge-secondary {
        background-color: #6c757d;
        color: white;
    }

    .validation-notification {
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 9999;
        width: 350px;
        display: none;
    }

    .status-select-wrapper {
        position: relative;
    }

    .status-select-wrapper .select-arrow {
        position: absolute;
        right: 10px;
        top: 50%;
        transform: translateY(-50%);
        pointer-events: none;
    }

    .status-select {
        appearance: none;
        padding-right: 30px;
    }
</style>
@endpush

@push('js')

<script>
    $(document).ready(function() {
        // Inisialisasi Toastr
        if (typeof toastr !== 'undefined') {
            toastr.options = {
                closeButton: true,
                progressBar: true,
                positionClass: "toast-top-right",
                timeOut: "3000"
            };
        }

        // Inisialisasi DataTable
        const table = $('#shuttle-order-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('api.shuttle.orders') }}",
                data: function(d) {
                    d.user_id = $('#user-filter').val();
                    d.shuttlecock_brand = $('#shuttlecock-filter').val();
                    d.status = $('#status-filter').val();
                    d.start_date = $('#start-date-filter').val();
                    d.end_date = $('#end-date-filter').val();
                },
                error: function(xhr) {
                    console.error('Gagal memuat data:', xhr.responseText);
                    toastr.error('Terjadi kesalahan saat memuat data Shuttle Order');
                }
            },
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'user.name',
                    name: 'user.name',
                    render: d => d ?? '-'
                },
                {
                    data: 'shuttlecock_brand',
                    name: 'shuttlecock_brand',
                    render: d => d ?? '-'
                },
                {
                    data: 'quantity',
                    name: 'quantity',
                    render: d => d ?? 0
                },
                {
                    data: 'total_price',
                    name: 'total_price',
                    render: d => 'Rp ' + (Number(d) || 0).toLocaleString('id-ID')
                },
                {
                    data: 'payment_type',
                    name: 'payment_type',
                    render: d => d ?? '-'
                },
                {
                    data: 'payment_proof',
                    name: 'payment_proof',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'status',
                    name: 'status'
                },
                {
                    data: 'created_at_formatted',
                    name: 'created_at',
                    render: function(data) {
                        if (!data) return '-';
                        const date = new Date(data);
                        return isNaN(date) ? '-' : date.toLocaleString('id-ID', {
                            day: '2-digit',
                            month: 'short',
                            year: 'numeric',
                            hour: '2-digit',
                            minute: '2-digit'
                        });
                    }
                },
                {
                    data: 'id',
                    name: 'action',
                    orderable: false,
                    searchable: false,
                    render: function(id) {
                        return `
                            <button class="btn btn-warning btn-sm edit-btn" title="Validasi Order" data-id="${id}">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn btn-danger btn-sm delete-btn ml-1" title="Hapus Order" data-id="${id}">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        `;
                    }
                }
            ],
            drawCallback: function() {
                $('[title]').tooltip();
            }
        });

        // Filter
        $('.filter-select').on('change', function() {
            table.ajax.reload(null, false);
        });

        // Validasi Order
        $(document).on('click', '.edit-btn', function() {
            const orderId = $(this).data('id');

            Swal.fire({
                title: 'Validasi Shuttle Order',
                text: `Tandai order #${orderId} sebagai valid dan ubah status menjadi LUNAS?`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Valid',
                cancelButtonText: 'Tidak Valid',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    updateStatus(orderId, 'paid');
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    toastr.info('Status tidak diubah');
                }
            });
        });

        function updateStatus(orderId, status) {
            $.ajax({
                url: `/admin/shuttle_order/shuttle_orders/${orderId}/update-status`,
                method: 'POST',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    status: status
                },
                beforeSend: function() {
                    toastr.info('Mengubah status...');
                },
                success: function(response) {
                    if (response.success) {
                        toastr.success('Status berhasil diperbarui');
                        table.ajax.reload(null, false);
                    } else {
                        toastr.error(response.message || 'Gagal memperbarui status');
                    }
                },
                error: function(xhr) {
                    toastr.error('Terjadi kesalahan saat memperbarui status');
                    console.error(xhr.responseText);
                }
            });
        }

        // Hapus Order
        $(document).on('click', '.delete-btn', function() {
            const orderId = $(this).data('id');

            Swal.fire({
                title: 'Hapus Shuttle Order?',
                text: `Data order #${orderId} akan dihapus permanen.`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    deleteOrder(orderId);
                }
            });
        });

        function deleteOrder(orderId) {
            $.ajax({
                url: `/admin/shuttle_order/${orderId}`,
                type: 'DELETE',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response.success) {
                        toastr.success('Order berhasil dihapus.');
                        table.ajax.reload(null, false);
                    } else {
                        toastr.error(response.message || 'Gagal menghapus order.');
                    }
                },
                error: function(xhr) {
                    toastr.error('Terjadi kesalahan saat menghapus order.');
                    console.error(xhr.responseText);
                }
            });
        }
    });
</script>


@endpush