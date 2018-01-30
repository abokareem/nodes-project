@component('mail::message')
# Email confirmation

Click on button for confirmation your email.

@component('mail::button', ['url' => route('email.confirm.frontend', [$token])])
Go to site
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
