<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use \Illuminate\Support\Facades\DB;

class FollowSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user_ids = \App\User::pluck('id')->toArray();
        foreach ($user_ids as $userId){
            DB::table('follows')->insert([
                'follower_id'=>$userId,
                'following_id'=>\Illuminate\Support\Arr::random($user_ids),
            ]);
        }
    }
}
