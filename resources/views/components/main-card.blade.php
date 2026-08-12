<div class="main-card{{ $extraClass ? ' ' . $extraClass : '' }}" data-state="{{ $state }}"@if($dataFollow) data-follow="{{ $dataFollow }}"@endif>
  <div class="card">
    {{ $slot }}
  </div>
</div>
