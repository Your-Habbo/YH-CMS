<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Forum\ThreadTag;

class ThreadTagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $tags = [
            ['name' => 'Tips', 'slug' => 'tips'],
            ['name' => 'Question', 'slug' => 'question'],
            ['name' => 'Guide', 'slug' => 'guide'],
            ['name' => 'News', 'slug' => 'news'],
            ['name' => 'Suggestion', 'slug' => 'suggestion'],
        ];

        foreach ($tags as $tag) {
            ThreadTag::firstOrCreate(['slug' => $tag['slug']], $tag);
        }
    }
}
