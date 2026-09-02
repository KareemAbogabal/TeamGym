<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;

/**
 * Server-side, short-lived, single-use verification codes used for password
 * reset / OTP flows.
 *
 * Codes are cryptographically random, purpose-bound (client vs employee),
 * bound to the intended account identifier, expire after 10 minutes, are
 * invalidated after one use, and have a brute-force attempt limit.
 *
 * Nothing sensitive is stored in a cookie: the code only ever lives in
 * server-side cache and the user's email inbox.
 */
class ResetCodeService {
  public const TYPE_CLIENT = 'client';
  public const TYPE_EMPLOYEE = 'company';

  public const TTL_SECONDS = 600;
  public const MAX_ATTEMPTS = 5;

  public static function issue(string $type, string $identifier): string {
    $code = (string) random_int(100000, 999999);
    Cache::put(self::key($type, $identifier), [
      'hash' => Hash::make($code),
      'identifier' => $identifier,
      'expires_at' => Carbon::now()->addSeconds(self::TTL_SECONDS)->timestamp,
      'attempts' => 0,
    ], self::TTL_SECONDS);
    return $code;
  }

  public static function verify(string $type, string $identifier, string $code): bool {
    $key = self::key($type, $identifier);
    $row = Cache::get($key);
    if (!$row) {
      return false;
    }
    if (Carbon::now()->timestamp > (int) ($row['expires_at'] ?? 0)) {
      self::burn($type, $identifier);
      return false;
    }
    if ((int) ($row['attempts'] ?? 0) >= self::MAX_ATTEMPTS) {
      self::burn($type, $identifier);
      return false;
    }
    if (!Hash::check($code, (string) ($row['hash'] ?? ''))) {
      $row['attempts'] = (int) ($row['attempts'] ?? 0) + 1;
      Cache::put($key, $row, max(1, (int) ($row['expires_at'] ?? 0) - Carbon::now()->timestamp));
      return false;
    }
    self::burn($type, $identifier);
    return true;
  }

  public static function pending(string $type, string $identifier): bool {
    return (bool) Cache::get(self::key($type, $identifier));
  }

  public static function burn(string $type, string $identifier): void {
    Cache::forget(self::key($type, $identifier));
  }

  private static function key(string $type, string $identifier): string {
    return sprintf('passreset.%s.%s', $type, mb_strtolower(trim($identifier)));
  }
}