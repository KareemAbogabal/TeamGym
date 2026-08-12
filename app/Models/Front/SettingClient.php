<?php

namespace App\Models\Front;

use Illuminate\Database\Eloquent\Model;
use App\Models\Front\Client;

class SettingClient extends Model {
  public function client() {
    return $this->belongsTo(Client::class, 'code_client', 'code');
  }
}
