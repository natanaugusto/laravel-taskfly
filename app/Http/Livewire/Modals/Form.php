<?php

namespace App\Http\Livewire\Modals;

use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Livewire\Exceptions\CannotBindToModelDataWithoutValidationRuleException;

class Form extends Modal
{
    public $model;
    public $inputRules;
    public $inputsView;

    public function mount(): void
    {
        if (is_string($this->model)) {
            $this->model = new $this->model(['creator_id' => auth()->user()->id]);
        }
        $this->rules = $this->inputRules;
    }

    public function render(): View
    {
        return view('livewire.modals.form');
    }

    protected function getModel(mixed $model): Model
    {
        switch (gettype(value:$model)) {
            case 'object':
                if ($model->id !== $this->model['id']) {
                    throw new CannotBindToModelDataWithoutValidationRuleException(key: $model, component:$this);
                }
                break;
            case 'string':
            case 'integer':
                if (!is_numeric(value:$model)) {
                    throw new CannotBindToModelDataWithoutValidationRuleException(key:$model, component:$this);
                }
                if ((int)$model !== $this->model['id']) {
                    throw new CannotBindToModelDataWithoutValidationRuleException(key:$model, component:$this);
                }
                $model = $this->instance->getModel()::find($this->model['id']);
                $model->fill(Arr::only(array:$this->model, keys:$model->getFillable()));
                break;
            default:
                $model = $this->model;
                if (is_null($model->creator_id)) {
                    $model->creator_id = auth()->user()->id;
                }
                break;
        }
        return $model;
    }
}
