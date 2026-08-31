<?php

namespace Nightshade\Views\Components\Company;
use Illuminate\View\Component;

class ClientsTable extends Component {
  public function __construct(
    public $clients = [],
    public $clientCoaches = [],
  ) {}
  public function render() {
    return view('components.company.clients-table');
  }
}
