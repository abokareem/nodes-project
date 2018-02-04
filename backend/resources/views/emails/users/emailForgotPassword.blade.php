@component('mail::message')
# {{$user->name}}

##Your reset password link.

@component('mail::button', ['url' => route('reset.password',['token' => $token])])
Reset password
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent

