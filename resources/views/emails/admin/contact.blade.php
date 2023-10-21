@component('mail::message')
@isset($message)
# Contact form message from {{ config('app.name') }}
@else
# Newsletter signup from {{ config('app.name') }}
@endisset
@isset($name)
## Name:
{{ $name }}
@endisset
@isset($email)
## Contact:
{{ $email }}
@endisset
@isset($phone)

**{{ $phone }}**
@endisset
@isset($message)
## Message:
{{ $message }}
@endisset
@endcomponent
