<?php

namespace Database\Seeders;

use App\Models\Shuttlecock;
use Illuminate\Database\Seeder;

class ShuttlecockSeeder extends Seeder
{
    public function run(): void
    {
        $dummyData = [
            [
                'brand' => 'Yonex Mavis 350',
                'stock' => 20,
                'price' => 70000,
                'is_available' => true,
            ],
            [
                'brand' => 'RS Super 99',
                'stock' => 15,
                'price' => 60000,
                'is_available' => true,
            ],
            [
                'brand' => 'Flypower 77',
                'stock' => 0,
                'price' => 55000,
                'is_available' => false,
            ],
        ];

        foreach ($dummyData as $data) {
            Shuttlecock::create($data);
        }
    }
}
