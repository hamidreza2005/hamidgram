<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserSetting extends Model
{
    protected $table = 'user_settings';
    protected $fillable = [
      'user_id','email_verification_code','two_step_verification_status','two_step_verification_code'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
