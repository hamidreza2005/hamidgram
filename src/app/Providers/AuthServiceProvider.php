<?php

namespace App\Providers;

use App\Comment;
use App\Policies\CommentPolicy;
use App\Policies\PostPolicy;
use App\Policies\UserPolicy;
use App\Post;
use App\User;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Laravel\Passport\Passport;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
         Post::class => PostPolicy::class,
         Comment::class => CommentPolicy::class,
         User::class => UserPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        Gate::define('is_admin',function (User $user){
            if ($user->type === 'admin'){
                return true;
            }
            return false;
        });

        Gate::define('follow',function(User $user,$model){
            return $user->following()->where('id',$model->id)->count() >= 1 ? false :true;
        });
        
        Gate::define('unFollow',function(User $user,$model){
            return $user->following()->where('id',$model->id)->count() === 1 ? true : false;
        });

        $this->registerPolicies();

//        Passport::routes();
        Passport::personalAccessTokensExpireIn(now()->addMonths(2));
        Passport::refreshTokensExpireIn(now()->addMonths(4));
        Passport::tokensExpireIn(now()->addMonths(2));
    }
}
