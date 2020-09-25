<?php

namespace App;

use App\Casts\CreatedAtCast;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class View extends Model
{
    use HasFactory;
    protected $table = 'views';
    protected $fillable = [
      'post_id','user_id','viewed_at'
    ];
    public $timestamps = false;

    protected $casts = [
      'viewed_at'=>CreatedAtCast::class
    ];

    public function post()
    {
        return $this->belongsTo(Post::class);
    }
}
