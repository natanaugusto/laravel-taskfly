@component('mail::message')
# Task

{{ $model->title }}

{{ $model->due }}

Thanks,<br>
{{ config('app.name') }}
@endcomponent
