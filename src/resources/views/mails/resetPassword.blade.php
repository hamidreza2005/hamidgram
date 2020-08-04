@component('mail::message')
# Hi {{$user->username}}

<p>We think you Forget Your Password.</p>
<p>So We have sent Your New Password to Your email</p>

<i>Your New Password is: </i>

## {{$password}}

<b>Notice: Please Change Your Password as soon as posible</b>
Thanks,<br>
{{ config('app.name') }}
@endcomponent
