<?php

namespace App\Models\Coach;

use Illuminate\Database\Eloquent\Model;

class QrScanLog extends Model {
  protected $fillable = [
    'qr_code_id', 'code_client', 'scanned_at', 'ip', 'user_agent', 'source',
    'authenticated_user', 'success', 'reason',
  ];
  protected $casts = [
    'scanned_at' => 'datetime',
    'success' => 'boolean',
  ];

  public function qrCode() {
    return $this->belongsTo(ClientQrCode::class, 'qr_code_id', 'id');
  }
}
