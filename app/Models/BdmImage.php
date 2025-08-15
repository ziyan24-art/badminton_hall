<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class BdmImage extends Model
{
    use HasFactory;

    protected $table = 'bdm_images';

    protected $fillable = ['bdm_field_id', 'img'];

    /**
     * Helper untuk upload banyak gambar ke bdm_images.
     *
     * @param array $files
     * @param int $bdmFieldId
     * @return bool
     */
    public static function uploadDetailImg($files, $bdmFieldId)
    {
        try {
            $files = is_array($files) ? $files : [$files]; // <- Tambahkan ini

            foreach ($files as $file) {
                $ext = $file->extension();
                $filename = Str::random(10) . "." . $ext;
                $fullPath = "bdm-field/detail-{$filename}";
                $file->storeAs("public", $fullPath);
                self::create([
                    'bdm_field_id' => $bdmFieldId,
                    'img' => "storage/$fullPath",
                ]);
            }
            return true;
        } catch (Exception $e) {
            // Bisa log $e->getMessage() di sini
            return false;
        }
    }
}
