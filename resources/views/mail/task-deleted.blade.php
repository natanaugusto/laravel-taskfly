@component('mail::message')
# Task Deleted

{{ $model->title }}

{{ $model->due }}

Thanks,<br>
{{ config('app.name') }}
@endcomponent
