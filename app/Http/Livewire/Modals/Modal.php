<?php

namespace App\Http\Livewire\Modals;

use Illuminate\Database\Eloquent\Model;
use Livewire\Exceptions\PublicPropertyNotFoundException;
use LivewireUI\Modal\ModalComponent;
use Psy\Exception\TypeErrorException;

abstract class Modal extends ModalComponent
{
    public const ACCEPTS_MODEL_AS = ['object', 'integer'];

    public $title;

    public $confirmBtnLabel = 'Ok';

    public $confirmBtnColor = 'blue';

    public $cancelBtnLabel = 'Cancel';

    public $cancelBtnColor = 'gray';

    /**
     * @var null|array [$class,, $model, $event]
     */
    public $confirmAction = null;

    protected object $instance;

    public function confirm(): void
    {
        if (is_null($this->confirmAction)) {
            throw new PublicPropertyNotFoundException(property:'confirmAction', component:__CLASS__);
        }

        [$class, $action, $model, $event] = $this->confirmAction;
        $model = $this->setInstance(app()->make(abstract:$class))->getModel($model);

        if ($this->instance->{$action}($model)) {
            if ($event) {
                $this->emit($event);
            }
            $this->closeModal();
        }
    }

    public function cancel(): void
    {
        $this->closeModal();
    }

    public function setInstance(object $instance): self
    {
        $this->instance = $instance;

        return $this;
    }

    protected function getModel(mixed $model): Model
    {
        if (in_array(gettype($model), self::ACCEPTS_MODEL_AS)) {
            if (is_numeric($model)) {
                $model = $this->instance->getModel()::find($model);
            }
        } else {
            throw new TypeErrorException(
                'ConfirmModal just accept '.implode(separator:',', array:self::ACCEPTS_MODEL_AS).'. '.gettype($model).' was passed'
            );
        }

        return $model;
    }
}
