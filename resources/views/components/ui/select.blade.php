@props(['invalid' => false, 'options' => []])
<select {{ $attributes->merge(['class' => 'tg-select' . ($invalid ? ' is-invalid' : '')]) }}>
    @if (is_array($options) && count($options) > 0)
        @foreach ($options as $value => $label)
            <option value="{{ $value }}">{{ $label }}</option>
        @endforeach
    @else
        {{ $slot }}
    @endif
</select>