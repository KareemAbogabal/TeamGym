<?php

namespace Nightshade\Views\Components\Web;
use Illuminate\View\Component;

class Profile extends Component {
  public function __construct(
    public string $name,
    public string $state,
    public string $documentation,
    public string $img,
    public array $lineages,
    public array $muscles,
    public array $fats,
    public array $water
  ) {}
  public function render() {
    return view('components.web.profile');
  }
}
