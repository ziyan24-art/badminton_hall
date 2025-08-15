<?php

namespace Database\Seeders;

use App\Models\BdmField;
use App\Models\BdmImage;
use Illuminate\Database\Seeder;

class BdmImageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $fields = BdmField::all();

        foreach ($fields as $field) {
            BdmImage::create([
                'bdm_field_id' => $field->id,
                'img' => 'images/field/banner1.jpeg',
            ]);

            BdmImage::create([
                'bdm_field_id' => $field->id,
                'img' => 'images/field/banner2.jpeg',
            ]);

            BdmImage::create([
                'bdm_field_id' => $field->id,
                'img' => 'images/field/banner1.jpeg',
            ]);
        }
    }
}
