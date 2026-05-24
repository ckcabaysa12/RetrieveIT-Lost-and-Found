<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $names = [
            'Wallet',
            'Phone',
            'Bag',
            'Keys',
            'Documents',
            'Electronics',
            'Clothing',
            'ID Card',
            'Other',
        ];

        foreach ($names as $name) {
            Category::firstOrCreate(['name' => $name]);
        }
    }
}
