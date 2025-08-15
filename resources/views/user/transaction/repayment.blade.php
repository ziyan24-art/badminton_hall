@extends('theme.theme')
@section('title','Bayar Pelunasan')
@section('content')
<div class="mt-2  pb-3 border-b border-primary flex justify-between items-center">
    <h1 class="text-md text-dark font-semibold">Pelunasan</h1>
    <a href="#" onclick="return window.history.go(-1)" class="py-2 text-xs font-medium text-gray-500"><i
            class="fas fa-xs fa-arrow-left"></i> Kembali</a>
</div>
<div id="description" class="my-3 px-3 py-4 bg-white shadow-2xl rounded-md">
    <h2 class="text-md text-black font-semibold border-b-2 pb-2 mb-2">Informasi Jadwal</h2>
    <div class="mb-3 text-gray-800 font-medium">
        <p>
            <i class="fas mr-2 fa-futbol"></i> Lapangan X (Rp. 75,000 / jam)
        </p>
        <p><i class="fas mr-2 fa-calendar"></i> Jumat, 27 Agustus 2021</p>
        <p><i class="fas mr-2 fa-clock"></i> 08:00 - 11:00 WIB (2 jam)</p>
    </div>
    <h2 class="text-md text-black font-semibold border-b-2 pb-2 mb-2">Informasi Harga</h2>
    <form action="{{ route('app.transaction.repayment', $transaction->id) }}" method="post">
        @csrf
        <label for="payment_type_id">Pilih Metode Pembayaran</label>
        <select name="payment_type_id" class="form-select bg-white" required>
            <option value="" disabled selected>-- Pilih --</option>
            @foreach ($paymentTypes as $type)
            <option value="{{ $type->id }}">{{ $type->bank_name }}</option>
            @endforeach
        </select>

        <div class="flex justify-between mb-3">
            <p>Total</p>
            <p>Rp. {{ number_format($total) }}</p>
        </div>
        <div class="flex justify-between mb-3">
            <p>DP</p>
            <p>Rp. {{ number_format($dp) }}</p>
        </div>
        <div class="flex justify-between mb-3">
            <p>Tagihan</p>
            <p class="text-success text-2xl">Rp. {{ number_format($total - $dp) }}</p>
        </div>

        <button type="submit" class="btn-primary mt-2 w-full" disabled>
            Bayar Pelunasan
        </button>
    </form>

</div>


@endsection
@section('css')
@endsection
@section('js')
<script>
    $(document).ready(function() {
        //select on change
        $('select').change(function() {
            $('button[type=submit]').attr('disabled', false);
            $('button[type=submit]').removeClass('btn-gray').addClass('btn-primary');
        })
        // on Submit
        $('form').submit(function(e) {
            e.preventDefault();
            const URL = $(this).attr('action');
            const TYPE = $(this).attr('method');
            const DATA = $(this).serialize();
            $.ajax({
                url: URL,
                type: TYPE,
                data: DATA,
                dataType: 'json',
                success: function(data) {
                    if (data?.success) {
                        return toastr('success', data?.message, `<a href='?schedule=${data?.data}'> Check</a>`);
                    }
                    if (data?.error) {
                        return toastr('error', data?.message, `Saya Paham`);
                    }
                    return toastr('error', data?.message, `Cari Jadwal Lain`);
                },
                error: function(xhr, status, err) {
                    toastr('error', err);
                }
            })
        })
    })

    function checkPaymentType(type) {
        let activeClass = `text-success text-2xl`;
        let separator = `border-t border-gray-400`;
        let dp = $('#dp');
        let dpParent = dp.parent();
        let total = $('#total');
        let totalParent = total.parent();
        if (type == 1) { //DP
            dpParent.removeClass('hidden');
            dpParent.addClass(separator);
            totalParent.removeClass(separator);
            total.removeClass(activeClass);
        } else {
            dpParent.removeClass(separator);
            totalParent.addClass(separator);
            total.addClass(`${activeClass}`);
            dpParent.addClass('hidden');
        }
    }
</script>
@endsection