@component('mail::message')
# Hello {{$user->username}}
We think you registered in our Website;So Confirm Your Email in advance

@component('mail::button', ['url' => route('emailConfirmation',['code'=>$user->email_verification_code])])
Confirm Email
@endcomponent

<small> if the button doesn't work copy url below</small>
<b>{{route('emailConfirmation',['code'=>$user->email_verification_code])}}</b>
Thanks,<br>
{{ config('app.name') }}
@endcomponent
