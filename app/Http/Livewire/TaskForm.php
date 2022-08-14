<?php

namespace App\Http\Livewire;

use App\Models\Task;
use Livewire\Component;

class TaskForm extends Component
{
    public Task $task;
    protected array $rules = [
        'task.title' => 'required|string',
        'task.due' => 'required|date_format:' . Task::DUE_DATETIME_FORMAT,
    ];

    public function render()
    {
        return view('livewire.task-form');
    }

    public function save(): bool
    {
        return $this->task->save();
    }
}
