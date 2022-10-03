@component('mail::message')
# Task Updated

{{ $model->title }}

{{ $model->due }}

Thanks,<br>
{{ config('app.name') }}
@endcomponent
