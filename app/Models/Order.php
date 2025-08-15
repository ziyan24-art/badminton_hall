<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $table = 'orders';

    protected $fillable = [
        'user_id',
        'bdm_field_id',
        'status_transaction_id',
        'hours',
        'price',
        'play_date',
        'start_at',
        'end_at',
    ];

    // Relation Method

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function bdm_field()
    {
        return $this->belongsTo(BdmField::class, 'bdm_field_id', 'id');
    }

    public function status_transaction()
    {
        return $this->belongsTo(StatusTransaction::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'order_id', 'id');
    }

    // Helper

    /**
     * Cek jadwal berdasarkan field_id, tanggal, jam mulai dan selesai
     *
     * @param int $field_id
     * @param string $date
     * @param string $time_start
     * @param string $time_end
     * @return bool 
     */
    public static function isScheduleExist($fieldId, $playDate, $start, $end)
    {
        return self::where('bdm_field_id', $fieldId)
            ->whereDate('play_date', $playDate)
            ->where(function ($query) use ($start, $end) {
                $query->where(function ($q) use ($start, $end) {
                    $q->where('start_at', '<', $end)
                        ->where('end_at', '>', $start);
                });
            })
            ->exists();
    }
}
