<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use App\Models\Coach\AttendanceSession;
use App\Models\Front\Client;
use App\Models\Back\Employee;

/**
 * Unified attendance state machine.
 *
 * The single source of truth for checking a client in/out. All attendance
 * entry points (QR terminal, EAN-13 barcode scanner, code-based scan) must go
 * through here so there is exactly ONE entrance/exit business rule.
 *
 * Rules enforced:
 *  - No open session  -> open one (entrance).
 *  - Open session     -> close it (exit).
 *  - Prevents duplicate entrance / exit / multiple concurrently-open sessions
 *    via a per-client advisory row lock inside a transaction.
 */
class AttendanceService {
  /**
   * Toggle a client's attendance session.
   *
   * @return array{state: string, session: AttendanceSession, at: string}
   */
  public function toggle(Client $client, Employee|string|null $by = null): array {
    return DB::transaction(function () use ($client, $by) {
      // Lock the client's open session (if any) to serialize concurrent scans.
      $open = AttendanceSession::where('code_client', $client->code)
        ->where('status', 'open')
        ->orderByDesc('id')
        ->lockForUpdate()
        ->first();

      if ($open) {
        $open->leave($by);
        return [
          'state' => 'exit',
          'session' => $open,
          'at' => $open->exit_at ? $open->exit_at->toIso8601String() : now()->toIso8601String(),
          'entrance_at' => $open->entrance_at ? $open->entrance_at->toIso8601String() : null,
        ];
      }

      $session = new AttendanceSession();
      $session->code_client = $client->code;
      $session->enter($by);

      return [
        'state' => 'entrance',
        'session' => $session,
        'at' => $session->entrance_at ? $session->entrance_at->toIso8601String() : now()->toIso8601String(),
        'entrance_at' => $session->entrance_at ? $session->entrance_at->toIso8601String() : null,
      ];
    });
  }
}
