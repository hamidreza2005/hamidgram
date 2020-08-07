<?php

namespace App;

use App\Casts\twoStepVerificationExpireAt;
use Illuminate\Database\Eloquent\Model;

class UserSetting extends Model
{
    protected $table = 'user_settings';
    protected $fillable = [
      'user_id','email_verification_code','two_step_verification_status','two_step_verification_code','two_step_verification_expire_at'
    ];

    protected $casts = [
        'two_step_verification_expire_at'=>twoStepVerificationExpireAt::class
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
