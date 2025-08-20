@props(['title'])

<div class="mb-6 border rounded-lg p-4 bg-gray-50">
    <h4 class="font-semibold text-gray-800 text-md mb-3 border-b pb-1">{{ $title }}</h4>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
        {{ $slot }}
    </div>
</div>
