<?php

namespace Nightshade\Views\Components\Company;
use Illuminate\View\Component;

class RecordsForm extends Component {
  public function __construct(
    public string $state = 'records-form',
    public string $dataFollowButton = '',
    public $settingCompany = null,
    public $supplements = [],
    public $systems = [],
  ) {}
  public function render() {
    return view('components.company.recordsForm');
  }
}
