<?php

namespace App\Enums;

enum QrStatus: string {
  case Active = 'active';
  case Revoked = 'revoked';
  case Expired = 'expired';

  public static function values(): array {
    return array_map(fn($c) => $c->value, self::cases());
  }
}
