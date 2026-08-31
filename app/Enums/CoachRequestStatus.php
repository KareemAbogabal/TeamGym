<?php

namespace App\Enums;

/**
 * Centralized states for a coach request / assignment lifecycle.
 *
 * @method static self pending()
 * @method static self approved()
 * @method static self rejected()
 * @method static self cancelled()
 * @method static self active()
 * @method static self ended()
 */
enum CoachRequestStatus: string {
  case Pending = 'pending';
  case Approved = 'approved';
  case Rejected = 'rejected';
  case Cancelled = 'cancelled';
  case Active = 'active';
  case Ended = 'ended';

  public static function values(): array {
    return array_map(fn($c) => $c->value, self::cases());
  }
}
