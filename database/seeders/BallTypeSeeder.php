<?php

namespace Database\Seeders;

use App\Models\BallType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class BallTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        DB::table('ball_types')->truncate();
        Schema::enableForeignKeyConstraints();
        BallType::insert([
            'name' => 'Yonex Mavis 350',
            'amount' => 10,
        ]);
        BallType::insert([
            'name' => 'RS Super 99',
            'amount' => 6,
        ]);
        BallType::insert([
            'name' => 'Flypower 77',
            'amount' => 10,
            'is_available' => '0'
        ]);
    }
}
