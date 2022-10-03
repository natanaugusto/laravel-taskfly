@component('mail::message')
# Task Created

{{ $model->title }}

{{ $model->due }}

Thanks,<br>
{{ config('app.name') }}
@endcomponent
