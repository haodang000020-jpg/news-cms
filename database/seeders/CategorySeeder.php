<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
          $news = Category::create([
            'name' => 'Tin tức',
            'slug' => 'tin-tuc'
        ]);

        Category::create([
            'parent_id' => $news->id,
            'name' => 'Thời sự',
            'slug' => 'thoi-su'
        ]);

        Category::create([
            'parent_id' => $news->id,
            'name' => 'Kinh tế',
            'slug' => 'kinh-te'
        ]);

        Category::create([
            'parent_id' => $news->id,
            'name' => 'Giáo dục',
            'slug' => 'giao-duc'
        ]);
    }
}
