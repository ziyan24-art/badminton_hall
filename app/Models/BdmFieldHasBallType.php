<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BdmFieldHasBallType extends Model
{
    use HasFactory;

    protected $table = 'bdm_field_has_ball_types'; // sesuai nama tabel baru

    protected $fillable = [
        'bdm_field_id',
        'ball_type_id'
    ];

    public function bdm_field()
    {
        return $this->belongsTo(BdmField::class, 'bdm_field_id');
    }

    public function ball_type()
    {
        return $this->belongsTo(BallType::class, 'ball_type_id');
    }
}
