<form class="m-auto block max-w-md" wire:submit.prevent="confirm" novalidate="novalidate">
    @include($inputsView)
    @include('livewire.modals.action-buttons', compact(
        'confirmBtnLabel',
        'cancelBtnColor'
        ))
</form>
