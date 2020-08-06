<?php

namespace App;

use App\Casts\updateAvailableCast;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $table = 'comments';
    protected $fillable = [
      'post_id','body','parent_id'
    ];
    protected $casts = [
      "update_available_until"=>updateAvailableCast::class
    ];
    public function replies()
    {
        return $this->hasMany(self::class,'parent_id','id');
    }
}
