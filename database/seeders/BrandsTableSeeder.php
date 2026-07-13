<?php

namespace Database\Seeders;

use App\Models\Brand;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BrandsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $brands = [
            'Apple',
            'Google Pixel',
            'Honor',
            'Huawei',
            'Infinix',
            'itel',
            'Lenovo',
            'Motorola',
            'Nokia',
            'OnePlus',
            'OPPO',
            'POCO',
            'realme',
            'Redmi',
            'Samsung',
            'Sony',
            'TECNO',
            'vivo',
            'Xiaomi',
            'ZTE',
        ];

        foreach ($brands as $brand) {
            Brand::firstOrCreate([
                'name' => $brand,
            ]);
        }
    }
}
