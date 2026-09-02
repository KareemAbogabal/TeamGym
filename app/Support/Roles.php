<?php

namespace App\Support;

/**
 * Single role normalization mechanism (central authorization source).
 *
 * Submitted role names are NEVER trusted. They are normalized to one
 * canonical representation before storage and before any privilege check.
 */
class Roles {
  public const ADMIN = 'admin';
  public const COACH = 'coach';
  public const RECEPTION = 'reception';
  public const EMPLOYEE = 'employee';

  public const ALLOWED = [
    self::ADMIN,
    self::COACH,
    self::RECEPTION,
    self::EMPLOYEE,
  ];

  public static function normalize($role): string {
    $role = mb_strtolower(trim((string) $role));
    switch ($role) {
      case 'admin':
      case 'administrator':
        return self::ADMIN;
      case 'coach':
      case 'trainer':
        return self::COACH;
      case 'reception':
      case 'receptionist':
        return self::RECEPTION;
      case '':
      case 'employee':
      case 'staff':
        return self::EMPLOYEE;
      default:
        return $role;
    }
  }

  public static function isAllowed($role): bool {
    return in_array(self::normalize($role), self::ALLOWED, true);
  }

  public static function isAdmin($role): bool {
    return self::normalize($role) === self::ADMIN;
  }

  public static function isCoach($role): bool {
    return self::normalize($role) === self::COACH;
  }

  public static function isReception($role): bool {
    return self::normalize($role) === self::RECEPTION;
  }

  public static function isPrivileged($role): bool {
    return in_array(self::normalize($role), [self::ADMIN, self::COACH, self::RECEPTION], true);
  }
}