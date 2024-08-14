<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Forum\ForumCategory;
use App\Models\Forum\ForumThread;
use App\Models\Forum\ForumPost;
use App\Models\Forum\ThreadTag;

class ForumThreadSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $users = User::all();
        $categories = ForumCategory::all();
        $tags = ThreadTag::all();

        for ($i = 0; $i < 20; $i++) {
            $thread = ForumThread::create([
                'user_id' => $users->random()->id,
                'category_id' => $categories->random()->id,
                'title' => "Sample Thread " . ($i + 1),
                'slug' => "sample-thread-" . ($i + 1),
                'content' => "This is the content of sample thread " . ($i + 1),
                'is_sticky' => rand(0, 10) > 8,
                'is_locked' => rand(0, 10) > 9,
                'view_count' => rand(0, 1000),
            ]);

            // Ensure tags exist before trying to attach them
            if ($tags->isNotEmpty()) {
                $thread->tags()->attach($tags->random(rand(1, 3))->pluck('id')->toArray());
            }

            // Create some posts for each thread
            for ($j = 0; $j < rand(1, 10); $j++) {
                ForumPost::create([
                    'user_id' => $users->random()->id,
                    'thread_id' => $thread->id,
                    'content' => "This is a reply to the thread. Reply number " . ($j + 1),
                ]);
            }
        }
    }
}
