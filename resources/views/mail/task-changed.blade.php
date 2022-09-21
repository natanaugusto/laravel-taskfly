@component('mail::message')
# Task

{{ $task->title }}

{{ $task->due }}

Thanks,<br>
{{ config('app.name') }}
@endcomponent
