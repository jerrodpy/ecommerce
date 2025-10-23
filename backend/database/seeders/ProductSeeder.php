<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(int $count = 10): void
    {
        Product::factory()->count($count)->create();
    }
}
