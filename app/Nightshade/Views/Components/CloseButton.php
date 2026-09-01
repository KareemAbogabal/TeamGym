<?php

namespace Nightshade\Views\Components;

use Illuminate\View\Component;

class CloseButton extends Component {
  public function __construct(
    public string $label = '',
    public string $follow = '',
    public string $extraClass = '',
  ) {}

  public function render() {
    return view('components.close-button');
  }
}
