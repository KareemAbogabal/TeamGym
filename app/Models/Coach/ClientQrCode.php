<?php

namespace App\Models\Coach;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\Front\Client;
use App\Enums\QrPurpose;
use App\Enums\QrStatus;

class ClientQrCode extends Model {
  protected $fillable = [
    'code_client', 'barcode', 'token_hash', 'token_version', 'purpose', 'status',
    'expires_at', 'last_scanned_at', 'scan_count', 'created_by', 'revoked_at',
  ];
  protected $casts = [
    'expires_at' => 'datetime',
    'last_scanned_at' => 'datetime',
    'revoked_at' => 'datetime',
    'scan_count' => 'integer',
  ];

  protected $hidden = ['token_hash'];

  public function client() {
    return $this->belongsTo(Client::class, 'code_client', 'code');
  }

  public function scanLogs() {
    return $this->hasMany(QrScanLog::class, 'qr_code_id', 'id');
  }

  public static function hashToken(string $rawToken): string {
    return hash('sha256', $rawToken);
  }

  public static function generateRawToken(): string {
    return Str::random(64);
  }

  public function isValidFor(QrPurpose $purpose): bool {
    if ($this->status !== QrStatus::Active->value) {
      return false;
    }
    if ($this->purpose !== $purpose->value) {
      return false;
    }
    if ($this->expires_at !== null && $this->expires_at->isPast()) {
      return false;
    }
    return true;
  }
}
