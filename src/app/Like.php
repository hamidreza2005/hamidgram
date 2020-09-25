<?php

namespace App;

use App\Casts\CreatedAtCast;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Like extends Model
{
    use HasFactory;
    protected $table = 'likes';

    public $timestamps = false;

    protected $fillable = [
       'user_id','post_id'
    ] ;

    protected $casts = [
      'liked_at'=> CreatedAtCast::class
    ];
}
