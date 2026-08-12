<?php

namespace Nightshade\Views\Components;
use Illuminate\View\Component;

class Table extends Component {
  public function __construct(
    public array $header,
  ) {}
  public function render() {
    return view('components.table');
  }
}
