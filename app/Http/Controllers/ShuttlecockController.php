<?php

namespace App\Http\Controllers;

use App\Models\Shuttlecock;
use App\Models\ShuttleTransaction;
use App\Models\PaymentType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ShuttlecockController extends Controller
{
    /**
     * Menampilkan form checkout shuttlecock
     */
    public function showCheckoutForm($id)
    {
        $shuttle = Shuttlecock::findOrFail($id);

        if (!$shuttle->is_available) {
            return redirect()->back()->with('error', 'Shuttlecock tidak tersedia');
        }

        $payments = PaymentType::where('is_active', true)->get();

        return view('user.shuttle.checkout', compact('shuttle', 'payments'));
    }

    /**
     * Proses checkout shuttlecock
     */
    public function prosesCheckout(Request $request, $id)
    {
        $validated = $request->validate([
            'quantity' => 'required|integer|min:1',
            'payment_type' => 'required|string|max:255',
            'payment_proof' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $shuttle = Shuttlecock::findOrFail($id);

        if ($shuttle->stock < $validated['quantity']) {
            return back()->withInput()->with('error', 'Stok tidak mencukupi. Stok tersedia: ' . $shuttle->stock);
        }

        $proofPath = $request->file('payment_proof')->store('payment_proofs/shuttlecocks', 'public');

        ShuttleTransaction::create([
            'user_id' => auth()->id(),
            'shuttlecock_brand' => $shuttle->brand,
            'payment_type' => $validated['payment_type'],
            'quantity' => $validated['quantity'],
            'total_price' => $shuttle->price * $validated['quantity'],
            'payment_proof' => $proofPath,
            'status' => 'pending'
        ]);

        $shuttle->decrement('stock', $validated['quantity']);

        if ($shuttle->stock <= 0) {
            $shuttle->update(['is_available' => false]);
        }

        return redirect()->route('app')->with('success', 'Pembelian berhasil! Menunggu verifikasi admin.');
    }

    /**
     * Halaman sukses
     */
    public function checkoutSuccess($id)
    {
        $transaction = ShuttleTransaction::where('user_id', auth()->id())->findOrFail($id);

        return view('user.shuttle.checkout-success', compact('transaction'));
    }

    /**
     * Riwayat transaksi user
     */
    public function transaksi()
    {
        $transactions = ShuttleTransaction::where('user_id', auth()->id())
            ->latest()
            ->paginate(10);

        return view('shuttle.transactions', compact('transactions'));
    }

    /**
     * Detail transaksi
     */
    public function detailTransaksi($id)
    {
        $transaction = ShuttleTransaction::where('user_id', auth()->id())->findOrFail($id);

        return view('shuttle.transaction-detail', compact('transaction'));
    }

    /**
     * Alias lama
     */
    public function transactionDetail($id)
    {
        return $this->detailTransaksi($id);
    }

    /**
     * Verifikasi oleh admin
     */
    public function verifyPayment($id)
    {
        $transaction = ShuttleTransaction::findOrFail($id);

        $transaction->update([
            'status' => 'verified',
            'verified_at' => now(),
            'verified_by' => auth()->id()
        ]);

        return back()->with('success', 'Pembayaran berhasil diverifikasi');
    }

 
    /**
     * API beli
     */
    public function beli(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);

        $shuttle = Shuttlecock::findOrFail($id);

        if (!$shuttle->is_available || $shuttle->stock < $request->quantity) {
            return response()->json([
                'success' => false,
                'message' => 'Shuttlecock tidak tersedia atau stok habis'
            ], 400);
        }

        return response()->json([
            'success' => true,
            'redirect_url' => route('user.shuttle.checkout', $id)
        ]);
    }
}
