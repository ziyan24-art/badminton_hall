@extends('theme.theme')
@section('title','Checkout Shuttlecock')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-md mx-auto bg-white rounded-lg shadow-md overflow-hidden">
        <div class="bg-blue-600 py-4 px-6">
            <h1 class="text-xl font-bold text-white">Checkout Shuttlecock</h1>
        </div>

        <div class="p-6">
            <!-- Informasi Produk -->
            <div class="mb-6 border-b pb-4">
                <h2 class="text-lg font-semibold">{{ $shuttle->brand }}</h2>
                <p class="text-gray-600">Harga: Rp{{ number_format($shuttle->price, 0, ',', '.') }}/pcs</p>
                <p class="text-gray-600">Stok Tersedia: {{ $shuttle->stock }} pcs</p>
            </div>

            <form action="{{ route('user.shuttle.checkout.proses', $shuttle->id) }}" method="POST" enctype="multipart/form-data">
                @csrf

                <input type="hidden" name="shuttlecock_brand" value="{{ $shuttle->brand }}">

                <!-- Jumlah Pembelian -->
                <div class="mb-4">
                    <label for="quantity" class="block text-gray-700 mb-2 font-medium">Jumlah Pembelian</label>
                    <input type="number" id="quantity" name="quantity" min="1" max="{{ $shuttle->stock }}" value="1"
                        class="w-full px-3 py-2 border rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                    <p class="text-sm text-gray-500 mt-1">Max: {{ $shuttle->stock }} pcs</p>
                </div>

                <!-- Total Harga -->
                <div class="mb-4 bg-blue-50 p-3 rounded-lg">
                    <p class="font-medium text-gray-700">Total Pembayaran:</p>
                    <p class="text-xl font-bold text-blue-600" id="total-price">
                        Rp{{ number_format($shuttle->price, 0, ',', '.') }}
                    </p>
                </div>

                <!-- Metode Pembayaran -->
                <div class="mb-6">
                    <label for="payment_type" class="block text-gray-700 mb-2 font-medium">Metode Pembayaran</label>
                    <select id="payment_type" name="payment_type"
                        class="w-full px-3 py-2 border rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                        <option value="">-- Pilih Metode Pembayaran --</option>
                        <option value="Transfer ke BANK ABC - 1234567890 a.n. Nama Perusahaan">Transfer ke BANK ABC - 1234567890 a.n. Nama Perusahaan</option>
                        <option value="Transfer ke BANK XYZ - 9876543210 a.n. CV Contoh Jaya">Transfer ke BANK XYZ - 9876543210 a.n. CV Contoh Jaya</option>
                        <option value="Bayar Tunai di Tempat">Bayar Tunai di Tempat</option>
                    </select>
                </div>

                <!-- Upload Bukti Pembayaran -->
                <div class="mb-6" id="proof-wrapper">
                    <label class="block text-gray-700 mb-2 font-medium">Upload Bukti Pembayaran</label>
                    <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md">
                        <div class="space-y-1 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <div class="flex text-sm text-gray-600">
                                <label for="payment_proof" class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none">
                                    <span>Upload file</span>
                                    <input id="payment_proof" name="payment_proof" type="file" class="sr-only" accept="image/*,.pdf">
                                </label>
                                <p class="pl-1">atau drag and drop</p>
                            </div>
                            <p class="text-xs text-gray-500">PNG, JPG, PDF (Max. 2MB)</p>
                        </div>
                    </div>
                </div>

                <!-- Tombol Submit -->
                <div class="mt-6">
                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white py-3 px-4 rounded-md shadow-md transition duration-200">
                        Konfirmasi Pembayaran
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const quantityInput = document.getElementById('quantity');
        const totalPriceElement = document.getElementById('total-price');
        const price = @json($shuttle->price ?? 0);

        function updateTotal() {
            const quantity = parseInt(quantityInput.value) || 0;
            const total = quantity * price;
            totalPriceElement.textContent = 'Rp' + total.toLocaleString('id-ID');
        }

        quantityInput.addEventListener('input', updateTotal);
        updateTotal();

        // Logika sembunyikan bukti pembayaran jika metode = "Bayar Tunai"
        const paymentSelect = document.getElementById('payment_type');
        const proofWrapper = document.getElementById('proof-wrapper');
        const proofInput = document.getElementById('payment_proof');

        function toggleProofUpload() {
            if (paymentSelect.value === 'Bayar Tunai di Tempat') {
                proofWrapper.style.display = 'none';
                proofInput.removeAttribute('required');
            } else {
                proofWrapper.style.display = 'block';
                proofInput.setAttribute('required', true);
            }
        }

        paymentSelect.addEventListener('change', toggleProofUpload);
        toggleProofUpload();
    });
</script>
@endsection