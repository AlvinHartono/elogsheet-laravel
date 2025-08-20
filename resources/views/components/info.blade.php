@props(['label', 'value'])

<div>
    <div class="text-gray-500 font-medium">{{ $label }}:</div>
    <div class="mt-1 text-gray-800">{{ $value ?? '-' }}</div>
</div>
