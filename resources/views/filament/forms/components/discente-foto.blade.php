@php $url = $getState(); @endphp

<div class="flex items-center justify-center">
    @if ($url)
        <img
            src="{{ $url }}"
            alt="Foto do discente"
            class="rounded-lg object-cover ring-2 ring-gray-200 dark:ring-gray-700"
            style="width: 90px; height: 120px;"
        />
    @else
        <div class="flex items-center justify-center rounded-lg bg-gray-100 dark:bg-gray-800"
            style="width: 90px; height: 120px;">
            <x-heroicon-o-user class="h-6 w-6 text-gray-400" />
        </div>
    @endif
</div>