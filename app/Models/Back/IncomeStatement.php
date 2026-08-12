<?php

namespace App\Models\Back;

use Illuminate\Database\Eloquent\Model;
use App\Models\Back\Lineage;

class IncomeStatement extends Model {
  public function lineages() {
    return $this->hasMany(Lineage::class, 'IncomeStatement', 'code');
  }
}
