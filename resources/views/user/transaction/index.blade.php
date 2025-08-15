@extends('theme.theme')
@section('title','Transaksi')

@section('content')
<div class="my-3 flex justify-between items-center space-x-0">
    <div class="order-flex active">
        <a href="#">Order</a>
    </div>
</div>

<div id="order" class="flex flex-col md:flex-col space-y-4"> {{-- ubah flex-row jadi flex-col --}}
    @empty($orders)
    <p class="text-center text-gray-500 bg-white block w-full py-4 rounded shadow-md">
        <span class="text-3xl text-primary block">
            <i class="fas fa-sad-tear"></i>
        </span>
        Anda belum pernah memesan
    </p>
    @else
    @foreach ($orders as $order)
    <div class="mb-4"> {{-- Tambahkan margin antar kartu --}}
        <x-transaction-card :order="$order" />
    </div>
    @endforeach

    <div class="mt-4">
        {{ $orders->links() }}
    </div>
    @endempty
</div>
@endsection