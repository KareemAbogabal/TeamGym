<?php

namespace App\Models\Back;

use Illuminate\Database\Eloquent\Model;
use App\Models\Front\Client;
use App\Models\Back\ActivityElements;
use App\Models\Back\ActivityAttachments;
use App\Models\Back\Employee;

class Activity extends Model {
  protected $table = 'activities';
  protected $fillable = ['code', 'name', 'description', 'code_attachments'];
  public function client() {
    return $this->belongsTo(Client::class, 'code_client', 'code');
  }
  public function employee() {
    return $this->belongsTo(Employee::class, 'code_employee', 'code');
  }
  public function elements() {
    return $this->hasMany(ActivityElements::class, 'code_activities', 'code_attachments');
  }
  public function attachments() {
    return $this->hasMany(ActivityAttachments::class, 'code', 'code_attachments');
  }
}
