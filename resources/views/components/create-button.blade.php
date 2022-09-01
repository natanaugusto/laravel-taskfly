<div class="float-right">
    <button type="button" wire:click.prevent="$emit('openModal', 'modals.form', {{ json_encode($editButtonParams) }})"
        class="bg-green-600 hover:bg-green-700 focus:ring-green-500 inline-flex w-full justify-center rounded-md border border-transparent px-2 py-2 text-base font-medium text-white shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 sm:ml-3 sm:w-auto sm:text-sm">
        <span class="font-weight-bold">Create</span>
        <x-icon class="ml-2" name="plus-circle" />
    </button>
</div>
