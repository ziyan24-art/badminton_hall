@extends('theme.theme')
@section('title', 'Checkout')

@section('content')
<div class="mt-2 pb-3 mb-2 border-b flex justify-between items-center">
    <h1 class="text-md text-dark font-semibold ">Review Order</h1>
    <a href="#" onclick="return window.history.back()" class="py-2 text-xs font-medium text-gray-500 bg-white">
        <i class="fas fa-xs fa-arrow-left bg-white"></i> Kembali
    </a>
</div>

<div id="description" class="my-3 text-sm">
    <p style="color: black;"><i class="fas fa-th-large"></i> {{ $field->name }} (Rp {{ number_format($field->price) }} / jam)</p>
    <p style="color: black;"><i class="fas fa-calendar-alt mr-2"></i> {{ $dateReadable }}</p>
    <p style="color: black;"><i class="fas fa-clock mr-2"></i> {{ $schedule->start_at }} - {{ $schedule->end_at }} WIB ({{ $hours }} jam)</p>
</div>

<h1 class="text-md text-dark font-semibold border-b-2 pb-3 mb-2" style="color: black;">Informasi Harga</h1>

<form action="{{ route('booking', ['field' => $field->id]) }}" method="POST">
    @csrf
    <input type="hidden" name="schedule" value="{{ request()->schedule }}">
    <input type="hidden" name="transaction_type_id" value="1" id="transaction_type">

    {{-- Jenis Pembayaran --}}
    <div class="w-full mb-3">
        <label class="block font-medium mb-1" style="color: black;">Pilih Jenis Pembayaran</label>
        <div class="flex justify-between space-x-2">
            <div class="payment-radio active" data-id="1">
                <p class="text-xl icon"><i class="fas fa-xs fa-check-circle"></i></p>
                <p class="font-medium">Down Payment 50%</p>
            </div>
            <div class="payment-radio" data-id="2">
                <p class="text-xl icon"><i class="far fa-xs fa-circle"></i></p>
                <p class="font-medium">Bayar Full</p>
            </div>
        </div>
    </div>

    {{-- Metode Pembayaran --}}
    <div class="w-full mb-3">
        <label class="block font-medium mb-1" style="color: black;">Pilih Metode Pembayaran</label>

        @if($paymentTypes->isEmpty())
        <p class="text-red-600">Tidak ada metode pembayaran aktif!</p>
        @else
        <select name="payment_type_id" class="form-select bg-white w-full border p-2 rounded" style="color: black;">
            <option value="" selected disabled style="color: black;">Pilih Metode Pembayaran</option>
            @foreach ($paymentTypes as $type)
            <option value="{{ $type->id }}">{{ $type->bank_name }}</option>
            @endforeach
        </select>
        @endif
    </div>




    {{-- Harga --}}
    <div class="w-full mb-3 bg-white p-3 rounded">
        <div class="flex justify-between mb-2">
            <span>Harga Sewa</span>
            <span>{{ $hours }} x Rp {{ number_format($field->price) }}</span>
        </div>
        <div class="flex justify-between mb-2">
            <span>Total</span>
            <span id="total" class="font-semibold">Rp {{ number_format($priceTotal) }}</span>
        </div>
        <div class="flex justify-between mb-2 border-t border-gray-400 pt-2" id="dp-row">
            <span>DP</span>
            <span id="dp" class="text-success text-2xl">Rp {{ number_format($downPayment) }}</span>
        </div>
    </div>


    {{-- Submit --}}
    <button type="submit" class="btn-primary transition duration-500 w-full py-2 rounded" disabled>
        Booking
    </button>
</form>
@endsection

@section('css')
<link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
@endsection

@section('js')
<script>
    $(document).ready(function() {
        // Ganti metode pembayaran
        $('.payment-radio').click(function() {
            let id = $(this).data('id');
            $('.payment-radio').removeClass('active');
            $('.payment-radio .icon').html('<i class="far fa-xs fa-circle"></i>');
            $(this).addClass('active');
            $(this).find('.icon').html('<i class="fas fa-xs fa-check-circle"></i>');
            $('#transaction_type').val(id);
            updateDPVisibility(id);
        });

        // Aktifkan tombol submit saat metode pembayaran dipilih
        $('select[name=payment_type_id]').on('change', function() {
            $('button[type=submit]')
                .prop('disabled', false)
                .removeClass('btn-gray')
                .addClass('btn-primary');
        });

        // Submit pakai AJAX
        $('form').submit(function(e) {
            e.preventDefault();
            $.ajax({
                url: $(this).attr('action'),
                method: 'POST',
                data: $(this).serialize(),
                dataType: 'json',
                success: function(res) {
                    if (res.success) {
                        toastr('success', res.message, `<a href='/transaction/${res.data.transactionId}'> Bayar</a>`);
                    } else {
                        toastr('error', res.message, 'Tutup');
                    }
                },
                error: function(xhr) {
                    if (xhr.status == 401) {
                        toastr('error', 'Masuk terlebih dahulu!', `<a href='{{ route('login') }}'>Masuk</a>`);
                    } else {
                        toastr('error', 'Terjadi kesalahan');
                    }
                }
            });
        });

        function updateDPVisibility(type) {
            if (type == 1) {
                $('#dp-row').removeClass('hidden').addClass('border-t pt-2');
                $('#total').removeClass('text-success text-2xl');
            } else {
                $('#dp-row').addClass('hidden');
                $('#total').addClass('text-success text-2xl');
            }
        }
    });
</script>
@endsection