<?php

namespace App\Support;

/**
 * EAN-13 helpers.
 *
 * Valid EAN-13: exactly 13 numeric digits; the 13th digit is the check digit
 * computed from the first 12 with the 1-3-1-3... weighting starting from the
 * right-most payload digit.
 */
class Ean13 {
  /** Compute the EAN-13 check digit for a 12-digit payload. */
  public static function checkDigit(string $twelve): int {
    $twelve = preg_replace('/\D/', '', $twelve);
    $twelve = str_pad(substr($twelve, 0, 12), 12, '0', STR_PAD_LEFT);
    $sum = 0;
    for ($i = 0; $i < 12; $i++) {
      $digit = (int) $twelve[$i];
      $sum += ($i % 2 === 0) ? $digit : $digit * 3;
    }
    $mod = (10 - ($sum % 10)) % 10;
    return $mod;
  }

  /** Build a full 13-digit EAN-13 from a 12-digit payload. */
  public static function build(string $twelve): string {
    return str_pad(preg_replace('/\D/', '', $twelve), 12, '0', STR_PAD_LEFT) . self::checkDigit($twelve);
  }

  /** Validate that a value is a well-formed EAN-13 (13 digits + valid check digit). */
  public static function isValid(string $barcode): bool {
    if (!preg_match('/^\d{13}$/', $barcode)) {
      return false;
    }
    $payload = substr($barcode, 0, 12);
    $check = (int) $barcode[12];
    return self::checkDigit($payload) === $check;
  }

  /**
   * Generate a random valid EAN-13. Uses an 8-digit unique payload plus a
   * 4-digit intra-instance random sequence to keep collisions negligible.
   * Callers are responsible for enforcing DB-level uniqueness.
   */
  public static function generate(): string {
    // 8-digit unique block, zero-padded, guaranteed below 100,000,000.
    $base = (string) (mt_rand(1, 99999999));
    $base = str_pad($base, 8, '0', STR_PAD_LEFT);
    $seq = str_pad((string) mt_rand(0, 9999), 4, '0', STR_PAD_LEFT);
    return self::build($base . $seq);
  }
}
