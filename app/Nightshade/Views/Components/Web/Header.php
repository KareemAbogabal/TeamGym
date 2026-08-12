<?php

namespace Nightshade\Views\Components\Web;
use Illuminate\View\Component;

class Header extends Component {
  public function __construct(
    public string $title,
    public string $paragraph,
  ) {}
  public function render() {
    return view('components.web.header');
  }
}
