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
                    <form class="m-auto block max-w-md">
                        <label class="m-2 block p-2">
                            <span class="text-gray-700">{{ __('Title') }}</span>
                            <input type="text"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" />
                        </label>
                        <label class="m-2 block p-2">
                            <span class="text-gray-700">{{ __('Due') }}</span>
                            <input type="datetime-local"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" />
                        </label>
                        <div class="m-2 block p-2">
                            <button type="submit"
                                class="hover:bg-gree-100 block rounded border border-gray-300 bg-green-300 py-2 px-4 text-gray-800 transition duration-150 ease-in-out">
                                Save
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
