<table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
    <tr>
        @foreach($header as $head)
        <th scope="col" class="py-3 px-6">
            {{ __($head) }}
        </th>
        @endforeach
    </tr>
    </thead>
    <tbody>
    @foreach($data as $item)
    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
        @foreach($header as $head)
        <td class="py-4 px-6">
            {{ __($item[$head]) }}
        </td>
        @endforeach
    </tr>
    @endforeach
    </tbody>
</table>
