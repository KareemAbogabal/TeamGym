<?php

namespace Nightshade\Views\Components;

use Illuminate\View\Component;

class Notification extends Component {
  public function __construct(
    public string $type = 'info',
    public string $message = '',
    public ?string $title = null,
  ) {}
  public function render() {
    return view('components.notification');
  }
}
