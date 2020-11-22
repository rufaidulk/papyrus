<?php

namespace Database\Seeders;

use App\Models\Tag;
use App\Models\Clap;
use App\Models\User;
use App\Models\Topic;
use App\Models\Article;
use App\Models\Comment;
use App\Models\Subject;
use App\Models\ArticleTag;
use App\Models\Commission;
use App\Models\Profile;
use App\Models\Subscription;
use Illuminate\Database\Seeder;
use App\Models\SubscriptionPlan;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        User::factory()->count(5)->create()->each(function(User $user) {
            Profile::factory()->create([
                'user_id' => $user->id
            ]);
        });
        Subject::factory()->count(5)->create();
        Topic::factory()->count(20)->create();
        Tag::factory()->count(15)->create();
        Article::factory()->count(20)->create();
        Comment::factory()->count(80)->create();
        Clap::factory()->count(30)->create();
        Commission::factory()->count(5)->create();
        ArticleTag::factory()->count(60)->create();
        SubscriptionPlan::factory()->count(5)->create();
        Subscription::factory()->count(3)->create();
    }
}
