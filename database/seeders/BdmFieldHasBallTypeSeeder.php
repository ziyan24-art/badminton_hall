<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BdmFieldHasBallTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Contoh data dummy, silakan sesuaikan sesuai kebutuhan
        DB::table('bdm_field_has_ball_types')->insert([
            [
                'bdm_field_id' => 1,
                'ball_type_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'bdm_field_id' => 2,
                'ball_type_id' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
