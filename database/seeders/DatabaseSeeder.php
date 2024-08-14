<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run()
    {
        // Create 100 fake users
        User::factory()->count(100)->create();
    
        // Call other seeders
        $this->call([
            ForumCategorySeeder::class,
            ThreadTagSeeder::class,
        ]);
    
        // Then seed the threads and posts
        $this->call([
            ForumThreadSeeder::class,
            NewsSeeder::class,
        ]);
    }
}
