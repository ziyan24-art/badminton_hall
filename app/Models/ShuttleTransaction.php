<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShuttleTransaction extends Model
{
    protected $table = 'shuttle_transactions';

    protected $fillable = [
        'user_id',
        'shuttlecock_brand',    // ✅ Tambahkan
        'payment_type',         // ✅ Tambahkan
        'quantity',
        'total_price',
        'payment_proof',
        'status'
    ];

    // Relasi ke user tetap ada
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // ✅ Hapus relasi yang sudah tidak digunakan lagi karena sekarang brand dan payment_type adalah string biasa
    // public function shuttlecock()
    // {
    //     return $this->belongsTo(Shuttlecock::class);
    // }

    // public function payment_type()
    // {
    //     return $this->belongsTo(PaymentType::class);
    // }
}
