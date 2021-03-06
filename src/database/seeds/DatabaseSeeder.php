<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
         $this->call(UserSeeder::class);
         $this->call(PostSeeder::class);
         $this->call(CommentSeeder::class);
         $this->call(ViewSeeder::class);
         $this->call(ReportSeeder::class);
         $this->call(FollowSeeder::class);
         $this->call(LikeSeeder::class);
    }
}
