@props([
    'label' => '',
    'for' => '',
    'required' => false,
    'helper' => '',
    'error' => '',
    'labelClass' => '',
])
<div {{ $attributes->merge(['class' => 'tg-field']) }}>
    @if ($label)
        <label @if ($for) for="{{ $for }}" @endif @if($labelClass) class="{{ $labelClass }}" @endif>
            {{ $label }}@if ($required)<span aria-hidden="true"> *</span>@endif
        </label>
    @endif
    {{ $slot }}
    @if ($error)
        <span class="tg-field-error" role="alert">{{ $error }}</span>
    @elseif ($helper)
        <span class="tg-helper">{{ $helper }}</span>
    @endif
</div>