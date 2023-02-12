<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = [
            ['name' => 'A1', 'description' => 'рука'],
            ['name' => 'B1', 'description' => 'нога'],
        ];

        foreach ($categories as $category) {
            Category::insert($category);
        }
    }
}
