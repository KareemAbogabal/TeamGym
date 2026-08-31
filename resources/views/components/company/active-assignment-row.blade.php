<div class="row-item">
  <span>
    {{ $assignment->client ? $assignment->client->fname . ' ' . $assignment->client->lname : '—' }}
    &rarr;
    {{ $assignment->coach ? $assignment->coach->fname . ' ' . $assignment->coach->lname : '—' }}
  </span>
  <form method="post" action="{{ route('coachManagement.manage') }}">
    @csrf
    <input type="hidden" name="assignment_id" value="{{ $assignment->id }}" />
    <input type="hidden" name="note" value="" />
    <button type="submit" name="action" value="end" class="btn danger">{{ __('messages.end') }}</button>
  </form>
</div>
