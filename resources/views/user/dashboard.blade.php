@extends('theme.theme')
@section('title','Booking Online BDM')
@section('content')
{{-- Spacer untuk menurunkan banner --}}
<div class="mt-4"></div>

{{-- Slider --}}
<div id="slider" class="my-3">
    <div class="swiper mySwiper h-36 md:h-64">
        <div class="swiper-wrapper">
            <div class="swiper-slide">
                <img src="{{ asset('images/banner/banner1.jpeg') }}" class="object-cover h-36 md:h-64 w-full rounded" alt="">
            </div>
            <div class="swiper-slide">
                <img src="{{ asset('images/banner/banner2.jpeg') }}" class="object-cover h-36 md:h-64 w-full rounded" alt="">
            </div>
        </div>
        <!-- Optional: pagination (dots) -->
        <div class="swiper-pagination"></div>
    </div>
</div>

{{-- Tombol WhatsApp di atas Shuttlecock --}}
<div class="flex justify-start my-3">
    <a href="https://wa.me/{{ env('WA_ADMIN_NUMBER', '6282214063294') }}?text=Halo%20Admin,%20saya%20ingin%20bertanya%20tentang%20produk%20Shuttlecock"
        target="_blank"
        class="inline-flex items-center bg-green-500 hover:bg-green-600 text-white font-medium px-4 py-2 rounded shadow">
        <i class="fab fa-whatsapp mr-2"></i> Hubungi Admin
    </a>
</div>

{{-- Shuttlecock Section --}}
@if($shuttles->count() > 0)
<div class="my-3">
    <h1 class="text-xl font-bold text-light border-b-2 pb-3">Pilihan Shuttlecock</h1>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-3">
        @foreach ($shuttles as $shuttle)
        <div class="bg-white rounded-lg shadow-lg overflow-hidden border border-gray-200 transform hover:scale-105 transition duration-300">
            <div class="p-4">
                <div class="text-center font-bold text-lg text-blue-600 mb-3">{{ $shuttle->brand }}</div>
                <div class="space-y-2">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Stok:</span>
                        <span class="font-medium">{{ $shuttle->stock }} pcs</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Harga:</span>
                        <span class="font-bold text-green-600">Rp{{ number_format($shuttle->price, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Status:</span>
                        <span class="font-medium {{ $shuttle->is_available ? 'text-green-600' : 'text-red-600' }}">
                            {{ $shuttle->is_available ? 'Tersedia' : 'Habis' }}
                        </span>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 text-center">
                <button
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md font-medium transition duration-300 {{ !$shuttle->is_available ? 'opacity-50 cursor-not-allowed' : '' }}"
                    {{ !$shuttle->is_available ? 'disabled' : '' }}
                    onclick="beliShuttlecock({{ $shuttle->id }})">
                    <i class="fas fa-shopping-cart mr-2"></i>Beli Sekarang
                </button>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endif

{{-- Fields Section --}}
<h1 class="text-xl font-bold text-light border-b-2 pb-3 mt-8">Booking Lapangan</h1>
<div class="my-3 grid grid-flow-row grid-cols-1 md:grid-cols-3 gap-4 auto-rows-max">
    @foreach ($bdm_fields as $field)
    <x-product-card :field="$field" />
    @endforeach
</div>
@endsection

@section('css')
{{-- Ganti ke CDN Swiper versi stabil --}}
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />
<style>
    .swiper {
        width: 100%;
        height: 100%;
    }

    .swiper-slide img {
        object-fit: cover;
        width: 100%;
        height: 100%;
        border-radius: 0.5rem;
    }

    body {
        background: linear-gradient(rgba(0, 0, 0, 0.4), rgba(0, 0, 0, 0.4)),
        url('{{ asset("images/background/bg4.jpeg") }}') no-repeat center center fixed;
        background-size: cover;
    }
</style>
@endsection

@section('js')
<script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/js/all.min.js"></script>
<script>
    // Inisialisasi Swiper
    const swiper = new Swiper('.mySwiper', {
        direction: 'horizontal',
        loop: true,
        autoplay: {
            delay: 2000,
            disableOnInteraction: false,
        },
        pagination: {
            el: '.swiper-pagination',
            clickable: true,
        },
    });

    // Fungsi Beli Shuttlecock
    function beliShuttlecock(shuttleId) {
        window.location.href = '{{ route("user.shuttle.checkout", "") }}/' + shuttleId;
    }

    // Fungsi Booking Lapangan dengan Cek Login
    
</script>
@endsection