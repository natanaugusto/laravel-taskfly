<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Form task') }}
        </h2>
    </x-slot>

    <div class="py-2 lg:py-6">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="border-b border-gray-200 bg-white p-6">
                    <livewire:task-form :task="$task ?? null">
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
