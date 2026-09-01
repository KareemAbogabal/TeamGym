@props(['label' => '', 'checked' => false, 'description' => ''])
<label class="tg-switch">
    <input
        type="checkbox"
        @if ($checked) checked @endif
        {{ $attributes }}
    />
    <span class="tg-switch__track" aria-hidden="true"></span>
    @if ($label)
        <span class="tg-switch__label">{{ $label }}</span>
    @endif
</label>
@if ($description)
    <p class="tg-helper">{{ $description }}</p>
@endif