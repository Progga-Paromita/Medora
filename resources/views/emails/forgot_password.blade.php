@component('mail::message')
# Hello {{ $user->name }},

We received a request to reset your password. You can reset your password by clicking the button below:

@component('mail::button', ['url' => url('reset/' . $user->remember_token)])
Reset Password
@endcomponent

If you did not request a password reset, no further action is required.

Thanks,<br>
{{ config('app.name') }}
@endcomponent
