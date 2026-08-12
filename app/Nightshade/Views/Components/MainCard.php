<?php

namespace Nightshade\Views\Components;
use Illuminate\View\Component;

class MainCard extends Component {
  public function __construct(
    public string $state = 'show',
    public string $extraClass = '',
    public string $dataFollow = '',
  ) {}
  public function render() {
    return view('components.main-card');
  }
}
