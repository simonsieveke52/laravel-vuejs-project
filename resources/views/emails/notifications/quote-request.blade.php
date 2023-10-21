@component('mail::message')
# New Quote request

<strong>Name:</strong> {{ $quote->name }} <br>
<strong>Email:</strong> {{ $quote->email }} <br>
<strong>Phone:</strong> {{ $quote->phone }} <br>
<strong>Content:</strong> {{ $quote->content }} <br>

Thanks,<br>
{{ config('app.name') }}
@endcomponent
