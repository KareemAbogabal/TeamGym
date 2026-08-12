<?php

namespace Nightshade\Views\Components;
use Illuminate\View\Component;

class Warning extends Component {
  public function __construct(
    public string $text,
  ) {}
  public function render() {
    return view('Warning.warning');
  }
}
