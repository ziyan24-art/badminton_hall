<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\ShuttleTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Carbon\Carbon;

class LaporanController extends Controller
{
    public function index()
    {
        return view('admin.laporan.index');
    }

    public function exportManual(Request $request): StreamedResponse|string
    {
        $start = Carbon::parse($request->start_date)->startOfDay();
        $end = Carbon::parse($request->end_date)->endOfDay();

        // Ambil semua order dengan status 'Selesai' (status_transaction_id = 6) pada rentang tanggal
        $orders = Order::with(['bdm_field', 'user', 'status_transaction'])
            ->where('status_transaction_id', 6)
            ->whereBetween('created_at', [$start, $end])
            ->get();

        // Transaksi Shuttlecock
        $shuttleTransactions = ShuttleTransaction::with('user')
            ->whereIn('status', ['paid', 'paid_off'])
            ->whereBetween('created_at', [$start, $end])
            ->get();


        if ($orders->isEmpty() && $shuttleTransactions->isEmpty()) {
            return back()->with('error', 'Data tidak ditemukan dalam rentang tanggal yang dipilih.');
        }

        $filename = 'laporan_gabungan_' . now()->format('Ymd_His') . '.csv';

        $callback = function () use ($orders, $shuttleTransactions) {
            try {
                $handle = fopen('php://output', 'w');

                // Header CSV
                fputcsv($handle, [
                    'Tipe Transaksi',
                    'Nama Pemesan',
                    'Item',
                    'Tanggal Order',
                    'Total Tagihan',
                    'Metode Pembayaran / Status',
                ]);

                // Transaksi Lapangan
                foreach ($orders as $order) {
                    fputcsv($handle, [
                        'Lapangan',
                        $order->user->name ?? '-',
                        ($order->bdm_field->name ?? '-') . ' | ' .
                            'Tanggal: ' . ($order->play_date ?? '-') . ' | ' .
                            'Jam: ' . \Carbon\Carbon::parse($order->start_at)->format('H:i') . ' - ' . \Carbon\Carbon::parse($order->end_at)->format('H:i') . ' | ' .
                            'Durasi: ' . $order->hours . ' jam',
                        $order->created_at?->format('Y-m-d') ?? '-',
                        'Rp' . number_format($order->price * $order->hours, 0, ',', '.'),
                        $order->status_transaction->name ?? '-',
                    ]);
                }


                // Transaksi Shuttlecock
                foreach ($shuttleTransactions as $shuttle) {
                    fputcsv($handle, [
                        'Shuttlecock',
                        $shuttle->user->name ?? '-', // dari relasi user
                        $shuttle->shuttlecock_brand ?? '-',
                        $shuttle->created_at?->format('Y-m-d') ?? '-',
                        'Rp' . number_format($shuttle->total_price ?? 0, 0, ',', '.'),
                        $shuttle->status . ' / ' . ($shuttle->payment_type ?? '-'),
                    ]);
                }


                fclose($handle);
            } catch (\Throwable $e) {
                Log::error('CSV Export Error: ' . $e->getMessage());
                echo "Terjadi kesalahan saat mengekspor data.";
            }
        };

        return response()->streamDownload($callback, $filename, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ]);
    }
}
