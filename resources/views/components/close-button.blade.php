@php($label = $label !== '' ? $label : __('messages.card-profile-button-close'))
<button
    type="button"
    class="close-profile tg-card-close{{ $extraClass ? ' ' . $extraClass : '' }}"
    @if ($follow) data-follow="{{ $follow }}" @endif
    aria-label="{{ $label }}"
    title="{{ $label }}"
>
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="20" height="20" aria-hidden="true">
        <path d="M19 6.41 17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/>
    </svg>
    <span class="tg-sr-only">{{ $label }}</span>
</button>
