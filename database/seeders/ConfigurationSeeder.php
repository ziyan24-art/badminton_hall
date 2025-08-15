<?php

namespace Database\Seeders;

use App\Models\Configuration;
use Exception;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ConfigurationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        try {
            DB::table('configurations')->truncate();
            Configuration::create([
                'name' => 'app.name',
                'content' => 'Hall Pemda'
            ]);
            Configuration::create([
                'name' => 'app.description',
                'content' => 'Hall Pemda adalah situs penyedia booking lapangan futsal.'
            ]);
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
}
