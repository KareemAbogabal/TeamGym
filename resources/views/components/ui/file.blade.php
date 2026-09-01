@props(['label' => 'Upload file', 'hint' => '', 'accept' => '', 'name' => ''])
<label class="tg-file" @if ($attributes->has('id')) for="{{ $attributes->get('id') }}" @endif>
    <input
        type="file"
        @if ($name) name="{{ $name }}" @endif
        @if ($accept) accept="{{ $accept }}" @endif
        @if ($attributes->has('id')) id="{{ $attributes->get('id') }}" @endif
        {{ $attributes->except(['id']) }}
    />
    <span class="tg-file__icon" aria-hidden="true">&#128206;</span>
    <span class="tg-file__text">{{ $label }}</span>
    @if ($hint)
        <span class="tg-file__hint">{{ $hint }}</span>
    @endif
</label>