<?php

namespace App\Models\Back;

use Illuminate\Database\Eloquent\Model;
use App\Models\Back\System;

class FeatureSystem extends Model {
  public function system() {
    return $this->belongsTo(System::class, 'code_system', 'code');
  }
}
