<?php

namespace App\Http\Controllers\Admin\Order;

use App\Http\Controllers\Controller;
use App\Models\ShuttleTransaction;
use App\Models\User;
use App\Models\Shuttlecock;
use Illuminate\Http\Request;

class ShuttleOrderController extends Controller
{
    public function index()
    {
        $users = User::whereHas('shuttleTransactions')->get();
        $shuttlecocks = Shuttlecock::all();
        $activePage = 'shuttle-order';

        return view('admin.shuttle_orders.index', compact('users', 'shuttlecocks', 'activePage'));
    }

    public function apiIndex(Request $request)
    {
        $transactions = ShuttleTransaction::with(['user'])
            ->select('shuttle_transactions.*')
            ->when($request->user_id, fn($q) => $q->where('user_id', $request->user_id))
            ->when($request->shuttlecock_id, fn($q) => $q->where('shuttlecock_brand', $request->shuttlecock_id))
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->when($request->start_date && $request->end_date, function ($q) use ($request) {
                return $q->whereBetween('created_at', [
                    $request->start_date . ' 00:00:00',
                    $request->end_date . ' 23:59:59'
                ]);
            })
            ->orderByDesc('created_at');

        return datatables()->of($transactions)
            ->addIndexColumn()

            // Nama user
            ->addColumn('user.name', fn($row) => $row->user?->name ?? '-')

            // Nama shuttlecock
            ->addColumn('shuttlecock_brand', fn($row) => $row->shuttlecock_brand ?? '-')

            // Metode pembayaran
            ->addColumn('payment_type', fn($row) => $row->payment_type ?? '-')

            // Harga
            ->editColumn('total_price', fn($row) => $row->total_price)

            // Status dengan badge
            ->editColumn('status', function ($row) {
                $badge = match ($row->status) {
                    'pending' => 'warning',
                    'paid' => 'success',
                    'verified' => 'primary',
                    'processing' => 'info',
                    'cancelled' => 'danger',
                    default => 'secondary'
                };
                $label = match ($row->status) {
                    'pending' => 'Menunggu Pembayaran',
                    'paid' => 'Lunas',
                    'verified' => 'Terverifikasi',
                    'processing' => 'Diproses',
                    'cancelled' => 'Dibatalkan',
                    default => ucfirst($row->status)
                };
                return '<span class="badge badge-' . $badge . '">' . $label . '</span>';
            })

            // Tanggal
            ->addColumn(
                'created_at_formatted',
                fn($row) =>
                $row->created_at ? $row->created_at->toIso8601String() : null
            )

            // Gambar bukti pembayaran
            ->addColumn('payment_proof', function ($row) {
                if (!$row->payment_proof) return '-';
                $url = asset('storage/' . $row->payment_proof);
                return '<a href="' . $url . '" target="_blank" class="btn btn-sm btn-info">
                            <i class="fas fa-eye"></i> Lihat
                        </a>';
            })


            // Aksi (pakai partial view)
            ->addColumn('action', function ($row) {
                $id = $row->id;
                return view('admin.shuttle_orders.partials.actions', compact('id'))->render();
            })

            // Render HTML pada kolom tertentu
            ->rawColumns(['status', 'payment_proof', 'action'])

            ->toJson();
    }



    public function show($id)
    {
        $transaction = ShuttleTransaction::with(['user'])->findOrFail($id);
        $activePage = 'shuttle-order';

        return view('admin.shuttle_orders.show', compact('transaction', 'activePage'));
    }

    public function edit($id)
    {
        $transaction = ShuttleTransaction::findOrFail($id);
        $statuses = [
            'pending' => 'Menunggu Pembayaran',
            'paid' => 'Lunas',
            'processing' => 'Diproses',
            'cancelled' => 'Dibatalkan'
        ];

        return view('admin.shuttle_orders.edit', compact('transaction', 'statuses'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,paid,processing,cancelled'
        ]);

        $transaction = ShuttleTransaction::findOrFail($id);
        $transaction->update([
            'status' => $request->status
        ]);

        return redirect()->route('admin.shuttle_order.index')
            ->with('success', 'Status order berhasil diperbarui');
    }

    public function destroy($id)
    {
        try {
            $transaction = ShuttleTransaction::findOrFail($id);
            $transaction->delete();

            return request()->expectsJson()
                ? response()->json(['success' => true])
                : redirect()->route('admin.shuttle_order.index')->with('success', 'Pesanan shuttlecock berhasil dihapus');
        } catch (\Exception $e) {
            return request()->expectsJson()
                ? response()->json(['success' => false, 'message' => 'Gagal menghapus pesanan'])
                : redirect()->route('admin.shuttle_order.index')->with('error', 'Gagal menghapus pesanan');
        }
    }


    public function validatePayment(Request $request, $id)
    {
        $transaction = ShuttleTransaction::findOrFail($id);

        $validated = $request->validate([
            'is_valid' => 'required|boolean'
        ]);

        if ($validated['is_valid']) {
            $transaction->update([
                'status' => 'paid'
            ]);
        } else {
            $transaction->update([
                'status' => 'pending'
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Status pembayaran berhasil diperbarui',
            'new_status' => $transaction->status
        ]);
    }
    public function updateStatus(Request $request, $id)
    {
        try {
            $transaction = \App\Models\ShuttleTransaction::findOrFail($id);

            $transaction->status = $request->status;
            $transaction->save();

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui status: ' . $e->getMessage()
            ], 500);
        }
    }
}
