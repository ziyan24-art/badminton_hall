<?php

namespace App\Exports;

use App\Models\Transaction;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class LaporanExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Transaction::with(['order.bdm_field', 'order.balls', 'user'])
            ->where('status', 'paid_off') // hanya transaksi lunas
            ->get()
            ->map(function ($trx) {
                return [
                    'Nama Pemesan' => $trx->user->name ?? '-',
                    'Lapangan' => $trx->order->bdm_field->name ?? '-',
                    'Bola' => $trx->order->balls->pluck('name')->implode(', ') ?? '-',
                    'Tanggal Order' => $trx->order->created_at->format('Y-m-d'),
                    'Total Tagihan' => 'Rp' . number_format($trx->order->total_price, 0, ',', '.'),
                    'Status Transaksi' => ucwords(str_replace('_', ' ', $trx->status)),
                ];
            });
    }

    public function headings(): array
    {
        return [
            'Nama Pemesan',
            'Lapangan',
            'Bola',
            'Tanggal Order',
            'Total Tagihan',
            'Status Transaksi',
        ];
    }
}
