<label class="m-2 block p-2">
    <span class="text-gray-700">{{ __('Title') }}</span>
    <input wire:model="model.title" type="text"
        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" />
</label>
<label class="m-2 block p-2">
    <span class="text-gray-700">{{ __('Due') }}</span>
    <input wire:model="model.due" type="datetime-local"
        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" />
</label>
