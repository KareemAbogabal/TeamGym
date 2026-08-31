<div class="row-item">
  <span>
    {{ $assignment->client ? $assignment->client->fname . ' ' . $assignment->client->lname : '—' }}
    &rarr;
    {{ $assignment->coach ? $assignment->coach->fname . ' ' . $assignment->coach->lname : '—' }}
  </span>
  <small>{{ $assignment->direction }} &middot; {{ $assignment->reason }}</small>
  <form method="post" action="{{ route('coachManagement.manage') }}">
    @csrf
    <input type="hidden" name="assignment_id" value="{{ $assignment->id }}" />
    <input type="hidden" name="note" value="" />
    <button type="submit" name="action" value="approve" class="btn success">{{ __('messages.approve') }}</button>
    <button type="submit" name="action" value="reject" class="btn danger">{{ __('messages.reject') }}</button>
  </form>
</div>
