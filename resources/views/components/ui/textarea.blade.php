@props(['invalid' => false])
<textarea {{ $attributes->merge(['class' => 'tg-textarea' . ($invalid ? ' is-invalid' : '')]) }}>{{ $slot }}</textarea>