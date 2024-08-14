<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\News;
use App\Models\User;
use Illuminate\Support\Str;

class NewsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::first(); // Assume the first user will be the author

        if ($user) {
            for ($i = 1; $i <= 10; $i++) {
                News::create([
                    'title' => 'Sample News Article ' . $i,
                    'content' => 'This is the content for sample news article ' . $i . '. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque in libero velit.',
                    'slug' => Str::slug('Sample News Article ' . $i),
                    'user_id' => $user->id,
                    'published_at' => now(),
                    'image' => null, // or provide a sample image path if needed
                ]);
            }
        } else {
            $this->command->info('No users found. Please create a user first.');
        }
    }
}
