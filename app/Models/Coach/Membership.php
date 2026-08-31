<?php

namespace App\Models\Coach;

use Illuminate\Database\Eloquent\Model;
use App\Models\Front\Client;

class Membership extends Model {
  protected $fillable = [
    'code_client', 'system_code', 'package_name', 'starts_at', 'ends_at',
    'status', 'amount', 'paid', 'frozen_at', 'cancelled_at', 'created_by',
  ];
  protected $casts = [
    'starts_at' => 'datetime',
    'ends_at' => 'datetime',
    'frozen_at' => 'datetime',
    'cancelled_at' => 'datetime',
    'amount' => 'float',
    'paid' => 'float',
  ];

  public function client() {
    return $this->belongsTo(Client::class, 'code_client', 'code');
  }
}
