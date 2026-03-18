@php $url = $getState(); @endphp

<div class="flex items-center justify-center">
    @if ($url)
        <img
            src="{{ $url }}"
            alt="Foto do discente"
            class="h-12 w-12 rounded-full object-cover ring-2 ring-gray-200 dark:ring-gray-700"
        />
    @else
        <div class="flex h-12 w-12 items-center justify-center rounded-full bg-gray-100 dark:bg-gray-800">
            <x-heroicon-o-user class="h-6 w-6 text-gray-400" />
        </div>
    @endif
</div>