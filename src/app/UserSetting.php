<?php

namespace App;

use App\Casts\twoStepVerificationExpireAt;
use Illuminate\Database\Eloquent\Model;

class UserSetting extends Model
{
    protected $table = 'user_settings';
    protected $fillable = [
      'user_id','email_verification_code','two_step_verification_status','two_step_verification_code','two_step_verification_expire_at','notify_when_get_like','notify_when_get_comment'
    ];

    protected $casts = [
        'two_step_verification_expire_at'=>twoStepVerificationExpireAt::class,
        'notify_when_get_like'=>'boolean',
        'notify_when_get_comment'=>'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
