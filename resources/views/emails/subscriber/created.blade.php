@component('mail::message')
# Mail list subscription

Email: {{ $subscriber->email }}

Thanks,<br>
{{ config('app.name') }}
@endcomponent
