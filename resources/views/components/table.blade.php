<div class="table">
  <div class="header">
    @foreach($header as $column)
      <h4>{{ $column }}</h4>
    @endforeach
  </div>
  <div class="body">
    @if(trim($slot) !== '')
      {!! $slot !!}
    @else
      <div class="choose">
        <p>Choose an exercise</p>
      </div>
    @endif
  </div>
</div>
