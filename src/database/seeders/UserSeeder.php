<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->createAdmin();
        User::factory(9)->create()->each(function ($user){
            $user->setting()->create();
        });

    }

    private function createAdmin()
    {
        $data = [
            'username'=>'hamidreza2005',
            'email'=>'h.r.hassani2005@gmail.com',
            'bio'=>'it is not important',
            'avatarUrl'=>'/2020/default.png',
            'password'=>bcrypt(1234),
            'remember_token' => Str::random(10),
            'email_verified_at'=>now()
        ];
        $user = new \App\User($data);
        $user->type = "admin";
        $user->save();
        $user->setting()->create();
    }
}
