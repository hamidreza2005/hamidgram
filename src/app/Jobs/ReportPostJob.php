<?php

namespace App\Jobs;

use App\Report;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;

class ReportPostJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    private $post;
    private $user;
    /**
     * @var string
     */
    private $reason;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($post,$user,$reason = "inappropriate")
    {
        //
        $this->post = $post;
        $this->user = $user;
        $this->reason = $reason;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if (Cache::has('report_'.$this->user->id.$this->post->id)){
            return;
        }
        $report = new Report();
        $report->user_id = $this->user->id;
        $report->post_id = $this->post->id;
        $report->reason = $this->reason;
        $report->save();
        Cache::put('report_'.$this->user->id.$this->post->id,true,60*24*7);
    }
}
