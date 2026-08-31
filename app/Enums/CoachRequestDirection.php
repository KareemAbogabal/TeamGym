<?php

namespace App\Enums;

/**
 * The direction of a coach request.
 *
 * client_to_coach : a client asked to be assigned to a coach.
 * coach_to_client : a coach asked to train a specific client.
 */
enum CoachRequestDirection: string {
  case ClientToCoach = 'client_to_coach';
  case CoachToClient = 'coach_to_client';

  public static function values(): array {
    return array_map(fn($c) => $c->value, self::cases());
  }
}
