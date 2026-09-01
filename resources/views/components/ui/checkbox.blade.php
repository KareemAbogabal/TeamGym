@props(['label' => '', 'checked' => false, 'description' => ''])
<label class="tg-check">
    <input
        type="checkbox"
        @if ($checked) checked @endif
        {{ $attributes }}
    />
    @if ($label)
        <span>{{ $label }}</span>
    @endif
</label>
@if ($description)
    <p class="tg-helper">{{ $description }}</p>
@endif