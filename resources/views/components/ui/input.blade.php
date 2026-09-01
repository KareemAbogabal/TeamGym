@props(['type' => 'text', 'invalid' => false])
<input
    type="{{ $type }}"
    {{ $attributes->merge(['class' => 'tg-input' . ($invalid ? ' is-invalid' : '')]) }}
/>