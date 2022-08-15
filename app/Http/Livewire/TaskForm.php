<?php

namespace App\Http\Livewire;

use App\Models\Task;
use Livewire\Component;
use Livewire\Redirector;

class TaskForm extends Component
{
    public ?Task $task;
    protected array $rules = [
        'task.title' => 'required|string',
        'task.due' => 'required|date_format:' . Task::DUE_DATETIME_FORMAT,
    ];

    public function mount()
    {
        if (empty($this->task)) {
            $this->task = new Task(['creator_id' => auth()->user()->id]);
        }
    }

    public function render()
    {
        return view('livewire.task-form');
    }

    public function save(): ?Redirector
    {
        if (empty($this->task->creator_id)) {
            $this->task->creator_id =  auth()->user()->id;
        }
        if ($this->task->save()) {
            return redirect(to:route(name:'tasks.index'));
        }
    }
}
