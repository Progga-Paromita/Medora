@component('mail::message')
# Hello {{ $user->name }} {{ $user->last_name }},

Your account password for **{{ config('app.name') }}** has been reset by the system administrator.

Your new temporary password is:
**{{ $newPassword }}**

Please login using this temporary password and update it in **My Account** as soon as possible.

@component('mail::button', ['url' => url('/')])
Login Now
@endcomponent

If you have any questions, please contact the administrator.

Thanks,<br>
{{ config('app.name') }}
@endcomponent
