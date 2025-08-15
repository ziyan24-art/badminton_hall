<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Transaction;
use Carbon\Carbon;
use Exception;
use Helpers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class TransactionController extends Controller
{
    public function index()
    {
        $orders = Order::where('user_id', auth()->id())
            ->orderByDesc('id')
            ->paginate(5);

        return view('user.transaction.index', compact('orders'));
    }

    public function history()
    {
        return view('user.transaction.history');
    }

    public function order(Order $order)
    {
        if ($order->user_id !== auth()->id()) {
            return redirect()->back()->withErrors('Invalid data!');
        }

        $transactions = $order->transactions;
        $schedule = Carbon::parse($order->play_date)->locale('id')->translatedFormat('l, d F Y');
        $timeStart = Carbon::parse($order->start_at)->format('H:i');
        $timeEnd = Carbon::parse($order->end_at)->format('H:i');

        return view('user.transaction.order', compact('order', 'transactions', 'schedule', 'timeStart', 'timeEnd'));
    }

    public function detail(Transaction $transaction)
    {
        if ($transaction->order->user_id !== auth()->id()) {
            return redirect()->back()->withErrors('Invalid data!');
        }

        $schedule = Carbon::parse($transaction->order->play_date)->locale('id')->translatedFormat('l, d F Y');
        $timeStart = Carbon::parse($transaction->order->start_at)->format('H:i');
        $timeEnd = Carbon::parse($transaction->order->end_at)->format('H:i');

        return view('user.transaction.detail', compact('transaction', 'schedule', 'timeStart', 'timeEnd'));
    }

    public function pay(Transaction $transaction)
    {
        try {
            if ($transaction->order->user_id !== auth()->id()) {
                return redirect()->back()->withErrors('Akses ditolak!');
            }

            $validator = Validator::make(request()->all(), [
                'proof_file' => 'required|file|max:5120|mimes:png,jpg,jpeg,docx,pdf'
            ], [
                'proof_file.required' => 'Bukti pembayaran wajib diupload!',
                'proof_file.file' => 'Bukti pembayaran harus berupa file!',
                'proof_file.max' => 'Ukuran file terlalu besar. Maksimal 5MB!',
                'proof_file.mimes' => 'Format file tidak didukung! (png, jpg, jpeg, docx, pdf)'
            ]);

            if ($validator->fails()) {
                $errors = Helpers::setErrors($validator->errors()->messages());
                return redirect()->back()->with('errors', $errors);
            }

            $request = $validator->validated();
            $this->uploadProof($transaction, $request['proof_file']);

            $newStatus = ($transaction->transaction_type_id == 1) ? 3 : 4;
            $transaction->order->update(['status_transaction_id' => $newStatus]);

            $this->notifyAdmin($transaction);

            return redirect()->back()->withSuccess('Pembayaran sedang diproses! Notifikasi telah dikirim ke admin.');
        } catch (Exception $e) {
            return redirect()->back()->withErrors($e->getMessage());
        }
    }

    private function uploadProof(Transaction $transaction, $file)
    {
        $folder = $transaction->transaction_type_id == 1 ? 'payment/down-payment' : 'payment/full';
        $path = $file->store($folder, 'public');

        $transaction->proof_file = $path;
        $transaction->save();
    }

    private function notifyAdmin(Transaction $transaction)
    {
        try {
            $adminPhone = env('WA_ADMIN_NUMBER');
            $token = env('WABLAS_TOKEN');
            $secretKey = env('WABLAS_SECRET_KEY');

            if (!$token || !$secretKey) {
                Log::error('[Wablas Error] Token atau Secret Key tidak tersedia');
                return;
            }

            $user = auth()->user();
            $order = $transaction->order;

            $fieldName = $order->bdm_field->name ?? '-';
            $schedule = Carbon::parse($order->play_date)->locale('id')->translatedFormat('l, d F Y');
            $start = Carbon::parse($order->start_at)->format('H:i');
            $end = Carbon::parse($order->end_at)->format('H:i');
            $duration = $order->hours ?? 0;
            $total = number_format($transaction->amount + $transaction->code, 0, ',', '.');

            $message = "*📢 Pesanan Baru Masuk!*\n\n"
                . "*Nama:* {$user->name}\n"
                . "*No HP:* {$user->phone}\n"
                . "*Lapangan:* {$fieldName}\n"
                . "*Jadwal:* {$schedule}, {$start} - {$end} WIB ({$duration} jam)\n"
                . "*Total Tagihan:* Rp {$total}\n\n"
                . "✅ Bukti pembayaran telah diunggah. Harap segera dicek di panel admin.";

            $response = Http::withHeaders([
                'Authorization' => $token,
                'Content-Type' => 'application/json'
            ])->post('https://sby.wablas.com/api/send-message', [
                'phone' => $adminPhone,
                'message' => $message
            ]);

            if (!$response->successful()) {
                Log::error('[Wablas Failed] ' . $response->body());
            }
        } catch (\Exception $e) {
            Log::error('[Wablas Exception] ' . $e->getMessage());
        }
    }

    public function repayment(Order $order)
    {
        return view('user.transaction.repayment');
    }
}
