<?php

namespace App\Models\Back;

use Illuminate\Database\Eloquent\Model;
use App\Models\Back\Imports;
use App\Models\Back\Lineage;

class Snacks extends Model {
  public function imports() {
    return $this->hasMany(Imports::class, 'code_snaks', 'code');
  }
  public function lineage() {
    return $this->hasMany(Lineage::class, 'code_snacks', 'code');
  }
}
