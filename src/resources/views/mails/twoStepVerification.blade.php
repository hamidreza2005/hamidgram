@component('mail::message')
# Hi {{$user->username}}

I think Someone wants to enter to your Account. If he is you Please Confirm
@component('mail::button', ['url' => route('twoStepVerification',['code'=>$user->setting->two_step_verification_code])])
Button Text
@endcomponent

If above Button Doesn't Work Please Use this URL
## {{route('twoStepVerification',['code'=>$user->setting->two_step_verification_code])}}

Thanks,<br>
{{ config('app.name') }}
@endcomponent
