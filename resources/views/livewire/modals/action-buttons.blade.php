<div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
    <button wire:click="confirm" type="button"
        class="bg-{{ $confirmBtnColor }}-600 hover:bg-{{ $confirmBtnColor }}-700 focus:ring-{{ $confirmBtnColor }}-500 inline-flex w-full justify-center rounded-md border border-transparent px-4 py-2 text-base font-medium text-white shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 sm:ml-3 sm:w-auto sm:text-sm">
        {{ __($confirmBtnLabel) }}
    </button>
    <button wire:click="cancel" type="button"
        class="bg-{{ $cancelBtnColor }}-200 text-{{ $cancelBtnColor }}-700 hover:bg-{{ $cancelBtnColor }}-50 mt-3 inline-flex w-full justify-center rounded-md border border-gray-300 px-4 py-2 text-base font-medium shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
        {{ __($cancelBtnLabel) }}
    </button>
</div>
