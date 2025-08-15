@extends('theme.theme')
@section('title','Riwayat Pembelian Shuttlecock')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold mb-6">Riwayat Pembelian Shuttlecock</h1>

    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <table class="min-w-full">
            <thead class="bg-gray-100">
                <tr>
                    <th class="py-3 px-4 text-left">Tanggal</th>
                    <th class="py-3 px-4 text-left">Shuttlecock</th>
                    <th class="py-3 px-4 text-left">Jumlah</th>
                    <th class="py-3 px-4 text-left">Total</th>
                    <th class="py-3 px-4 text-left">Status</th>
                    <th class="py-3 px-4 text-left">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($transactions as $transaction)
                <tr class="border-t">
                    <td class="py-3 px-4">{{ $transaction->created_at->format('d/m/Y') }}</td>
                    <td class="py-3 px-4">{{ $transaction->shuttlecock->brand }}</td>
                    <td class="py-3 px-4">{{ $transaction->quantity }} pcs</td>
                    <td class="py-3 px-4">Rp{{ number_format($transaction->total_price, 0, ',', '.') }}</td>
                    <td class="py-3 px-4">
                        <span class="px-2 py-1 rounded-full text-xs 
                            {{ $transaction->status == 'verified' ? 'bg-green-100 text-green-800' : 
                               ($transaction->status == 'paid' ? 'bg-blue-100 text-blue-800' : 
                               'bg-yellow-100 text-yellow-800') }}">
                            {{ ucfirst($transaction->status) }}
                        </span>
                    </td>
                    <td class="py-3 px-4">
                        <a href="{{ route('shuttle.transaction.detail', $transaction->id) }}"
                            class="text-blue-600 hover:text-blue-800">Detail</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="py-4 px-4 text-center text-gray-500">Belum ada transaksi</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection