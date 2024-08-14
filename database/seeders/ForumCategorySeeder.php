<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Forum\ForumCategory;

class ForumCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $categories = [
            ['name' => 'General Discussion', 'slug' => 'general-discussion'],
            ['name' => 'Habbo Help', 'slug' => 'habbo-help'],
            ['name' => 'Trading', 'slug' => 'trading'],
            ['name' => 'Room Design', 'slug' => 'room-design'],
            ['name' => 'Events', 'slug' => 'events'],
        ];
    
        foreach ($categories as $category) {
            ForumCategory::firstOrCreate(['slug' => $category['slug']], $category);
        }
    }
}
