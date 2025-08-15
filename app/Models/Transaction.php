<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Transaction extends Model
{
    use HasFactory;

    protected $table = 'transactions';

    protected $fillable = [
        'order_id',
        'transaction_type_id',
        'payment_type_id',
        'proof_file',
        'code',
        'amount',
        'is_valid',
        'expired_payment',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function transaction_type()
    {
        return $this->belongsTo(TransactionType::class);
    }

    public function payment_type()
    {
        return $this->belongsTo(PaymentType::class);
    }

    /*
    |--------------------------------------------------------------------------
    | HELPER METHODS
    |--------------------------------------------------------------------------
    */

    /**
     * Upload bukti pembayaran ke storage dan update data transaksi
     *
     * @param \Illuminate\Http\UploadedFile $file
     * @return bool
     */
    public function uploadPayment($file)
    {
        try {
            // Hapus file lama jika ada
            if (!empty($this->proof_file) && Storage::exists(str_replace('storage/', 'public/', $this->proof_file))) {
                Storage::delete(str_replace('storage/', 'public/', $this->proof_file));
            }

            // Buat path dan nama file baru
            $ext = $file->getClientOriginalExtension();
            $type = $this->transaction_type_id == 1 ? 'down-payment' : 'full';
            $filename = auth()->user()->id . "-" . Str::random(30) . "." . $ext;
            $path = "payment/{$type}/{$filename}";

            // Simpan file ke storage
            $file->storeAs("public", $path);

            // Update kolom proof_file di database
            $this->update(['proof_file' => "storage/{$path}"]);
            $this->touch(); // Update updated_at

            return true;
        } catch (Exception $e) {
            Log::error('Gagal upload bukti pembayaran: ' . $e->getMessage());
            return false;
        }
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
}
