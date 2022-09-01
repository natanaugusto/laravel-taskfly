<div class="float-left">
    <button type="button" wire:click.prevent="$emit('openModal', 'modals.form', {{ json_encode($editButtonParams) }})"
        class="inline-block rounded border border-gray-300 bg-yellow-300 p-1 text-xs text-gray-800 transition duration-150 ease-in-out hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-800 focus:ring-offset-2">
        <x-icon name="pencil-alt" />
    </button>
    <button type="button" wire:click.prevent="$emit('openModal', 'modals.confirm', {{ json_encode($deleteButtonParams) }})"
        class="inline-block rounded border border-gray-300 bg-red-300 p-1 text-xs text-gray-800 transition duration-150 ease-in-out hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-800 focus:ring-offset-2">
        <x-icon name="trash" />
    </button>
</div>
