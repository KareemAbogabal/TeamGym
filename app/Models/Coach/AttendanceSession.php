<?php

namespace App\Models\Coach;

use Illuminate\Database\Eloquent\Model;
use App\Models\Front\Client;
use App\Models\Back\Employee;

class AttendanceSession extends Model {
  protected $fillable = [
    'code_client', 'entrance_at', 'exit_at', 'entrance_source', 'exit_source',
    'entrance_employee', 'exit_employee', 'entrance_device', 'exit_device', 'status',
  ];
  protected $casts = [
    'entrance_at' => 'datetime',
    'exit_at' => 'datetime',
  ];

  public function client() {
    return $this->belongsTo(Client::class, 'code_client', 'code');
  }

  public function enter(Employee|string|null $by): void {
    $this->entrance_at = now();
    $this->entrance_employee = is_object($by) ? $by->code : $by;
    $this->status = 'open';
    $this->save();
  }

  public function leave(Employee|string|null $by): void {
    $this->exit_at = now();
    $this->exit_employee = is_object($by) ? $by->code : $by;
    $this->status = 'closed';
    $this->save();
  }
}
