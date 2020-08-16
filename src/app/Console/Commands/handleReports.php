<?php

namespace App\Console\Commands;

use App\Post;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Notification;

class handleReports extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reports';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Handle Reports';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $posts = Post::all();
        foreach ($posts as $post){
            if ($post->reports()->count() >= 500){
//                if (config('image.delete_reported_post_automatically')){
                    $post->delete();
//                }else{
//                Notification::send();
//                }
            };
        }
        return  0;
    }
}
