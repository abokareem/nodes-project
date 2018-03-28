@component('mail::message')
# User message from Contact Us form

User name: {{$name}}

User email : {{$fromEmail}}

User subject: {{$subject}}

User message : {{$message}}

Thanks,<br>
{{ config('app.name') }}
@endcomponent
