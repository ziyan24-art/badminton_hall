<?php

namespace Database\Seeders;

use App\Models\BdmField;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class BdmFieldSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        DB::table('bdm_fields')->truncate();
        Schema::enableForeignKeyConstraints();

        BdmField::insert([
            [
                'name' => 'BDM Lapangan 1',
                'field_type_id' => 1, // Vynl
                'price' => 50000,
                'img' => 'images/field/lapangan1.jpeg',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'BDM Lapangan 2',
                'field_type_id' => 2, // Sintetis
                'price' => 64000,
                'img' => 'images/field/lapangan2.jpeg',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'BDM Lapangan 3',
                'field_type_id' => 2, // Sintetis
                'price' => 64000,
                'img' => 'images/field/lapangan3.jpeg',
                'created_at' => now(),
                'updated_at' => now()
            ],
        ]);
    }
}
