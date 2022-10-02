@component('mail::message')
# Task Saved

{{ $task->title }}

{{ $task->due }}

Thanks,<br>
{{ config('app.name') }}
@endcomponent
