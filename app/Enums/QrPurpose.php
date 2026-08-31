<?php

namespace App\Enums;

/**
 * Explicit purpose/scopes for QR identity tokens.
 *
 * Tokens must never be accepted by an endpoint that expects a different purpose.
 */
enum QrPurpose: string {
  case Attendance = 'attendance';
  case ClientLogin = 'client_login';
  case ClientIdentity = 'client_identity';
  case GymCheckin = 'gym_checkin';
  case GymCheckout = 'gym_checkout';
  case StaffScan = 'staff_scan';

  public static function values(): array {
    return array_map(fn($c) => $c->value, self::cases());
  }
}
