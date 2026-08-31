<?php

namespace Nightshade\Views\Components\Company;
use Illuminate\View\Component;

class CoachRequestRow extends Component {
  public function __construct(
    public $assignment = null,
  ) {}
  public function render() {
    return view('components.company.coach-request-row');
  }
}
