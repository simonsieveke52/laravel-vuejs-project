@component('mail::message')
# New contact request

<strong>Name:</strong> {{ $contact->name }} <br>
<strong>Email:</strong> {{ $contact->email }} <br>
<strong>Phone:</strong> {{ $contact->phone }} <br>
<strong>Content:</strong> {{ $contact->content }} <br>

Thanks,<br>
{{ config('app.name') }}
@endcomponent
