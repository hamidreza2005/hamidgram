<?php

namespace App\Jobs;

use Gregwar\Image\Image;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class handleUploadedImageJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    private $path;
    private $image;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($image)
    {
        $this->path = storage_path('app/public/').$image;
        $this->image = Image::open($this->path)->setActualCacheDir(storage_path('framework/cache/images'));
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $height = $this->image->height();
        $width = $this->image->width();
        if($width < 512 && $width!=$height){
            $this->image->resize(512,null,'white');
        }
        if($height < 512 && $width!=$height){
            $this->image->resize(null,512,'white');
        }
        $this->image->save($this->path,'png');
//        $filepath = '/'.now()->year.'/';
    }
}
