<?php

namespace App\Http\Controllers\Admin\Order;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Carbon\Carbon;
use Exception;
use Helpers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TransactionController extends Controller
{
    public function json(Transaction $transaction)
    {
        try {
            $transaction->load('payment_type'); // pastikan relasi dimuat
            return response()->json(['success' => true, 'data' => $transaction]);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function update(Transaction $transaction, Request $request)
    {
        try {
            $rules = [
                'is_valid' => 'required|numeric',
            ];

            // Hanya DP dan non-cash yang butuh bukti pembayaran
            if (
                $transaction->transaction_type_id == 1 &&
                empty($transaction->proof_file) &&
                !$transaction->payment_type?->is_cash
            ) {
                $rules['proof_file'] = 'required|file|mimes:jpg,jpeg,png,pdf|max:2048';
            }

            $validator = Validator::make($request->all(), $rules, [
                'is_valid.required' => 'Status wajib dipilih!',
                'is_valid.numeric' => 'Status tidak valid!',
                'proof_file.required' => 'Bukti pembayaran wajib diunggah untuk pembayaran non-cash!',
            ]);

            if ($validator->fails()) {
                $errors = Helpers::setErrors($validator->errors()->messages());
                return redirect()->back()->with('errors', $errors);
            }

            $data = $validator->validated();

            // Simpan bukti pembayaran jika diunggah
            if ($request->hasFile('proof_file')) {
                $data['proof_file'] = $request->file('proof_file')->store('proofs', 'public');
            }

            $transaction->update($data);

            $order = $transaction->order;
            $totalOrder = $order->price * $order->hours;
            $paidAmount = $transaction->amount + $transaction->code;

            // Ambil pelunasan (jika ada)
            $repayment = Transaction::where([
                'order_id' => $order->id,
                'transaction_type_id' => 2
            ]);

            // ===== JIKA DP =====
            if ($transaction->transaction_type_id == 1) {
                if ($data['is_valid'] == 1) {
                    if ($paidAmount < $totalOrder) {
                        // Belum lunas, buat pelunasan jika belum ada
                        if (!$repayment->exists()) {
                            Transaction::create([
                                'order_id' => $order->id,
                                'transaction_type_id' => 2,
                                'code' => rand(100, 999),
                                'amount' => $totalOrder - $paidAmount,
                                'expired_payment' => Carbon::parse($order->end_at)->addHours(2),
                            ]);
                        }
                        $order->update(['status_transaction_id' => 5]); // DP Valid
                    } else {
                        // Lunas langsung → update amount agar tampil full
                        $transaction->update([
                            'amount' => $totalOrder - $transaction->code
                        ]);
                        $repayment->delete(); // Hapus pelunasan jika ada
                        $order->update(['status_transaction_id' => 6]); // Lunas
                    }
                } else {
                    // Tidak valid → hapus pelunasan
                    $order->update(['status_transaction_id' => 2]); // Menunggu Validasi
                    $repayment->delete();
                }
            }

            // ===== JIKA PELUNASAN =====
            if ($transaction->transaction_type_id == 2) {
                if ($data['is_valid'] == 1) {
                    $order->update(['status_transaction_id' => 6]); // Lunas
                } else {
                    $order->update(['status_transaction_id' => 5]); // Kembali ke hanya DP
                }
            }

            return redirect()->back()->withSuccess('Data transaksi telah diubah!');
        } catch (Exception $e) {
            return redirect()->back()->with('errors', $e->getMessage());
        }
    }
}
