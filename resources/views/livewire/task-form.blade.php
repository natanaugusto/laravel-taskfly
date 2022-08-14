<form class="m-auto block max-w-md" wire:submit.prevent="save" novalidate="novalidate">
    <label class="m-2 block p-2">
        <span class="text-gray-700">{{ __('Title') }}</span>
        <input wire:model="task.title" type="text"
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" />
    </label>
    <label class="m-2 block p-2">
        <span class="text-gray-700">{{ __('Due') }}</span>
        <input wire:model="task.due" type="datetime-local"
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" />
    </label>
    <div class="m-2 block p-2">
        <button type="submit"
            class="hover:bg-gree-100 block rounded border border-gray-300 bg-green-300 py-2 px-4 text-gray-800 transition duration-150 ease-in-out">
            Save
        </button>
    </div>
</form>
