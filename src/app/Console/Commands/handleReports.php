<?php

namespace App\Console\Commands;

use App\Notifications\NotifyAdminToDeleteReportedPost;
use App\Post;
use App\User;
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
        $posts = Post::withCount('reports')->get();
        foreach ($posts as $post){
            if ($post->reports_count >= 500){
                if (config('image.delete_reported_post_automatically')){
                    $post->delete();
                }else{
                    Notification::send(User::where('type','admin')->get(),new NotifyAdminToDeleteReportedPost($post));
                }
            };
        }
        $this->info("Reports handled");
        return 0;
    }
}
