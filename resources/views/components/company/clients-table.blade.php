<x-components::table :header="[__('messages.client'), __('messages.form-email'), __('messages.form-phone'), __('messages.current-coach'), __('messages.details')]">
  @forelse ($clients as $cl)
    <div class="row">
      <p class="search">{{ $cl->fname }} {{ $cl->lname }}</p>
      <p>{{ $cl->email ?? '—' }}</p>
      <p>{{ $cl->phone ?? '—' }}</p>
      <p>{{ ($clientCoaches[$cl->code] ?? null) ?: '—' }}</p>
      <p>
        <button type="button"
          class="client-request-trigger"
          data-code="{{ $cl->code }}"
          data-name="{{ $cl->fname }} {{ $cl->lname }}">
          <i class="fa-solid fa-user-plus"></i>
          {{ __('messages.request-client') }}
        </button>
      </p>
    </div>
  @empty
    <div class="row"><p class="empty-row">{{ __('messages.no-clients') }}</p></div>
  @endforelse
</x-components::table>
