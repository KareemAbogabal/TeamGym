<?php

namespace App\Models\Back;

use Illuminate\Database\Eloquent\Model;
use App\Models\Back\Activity;
use App\Models\Back\ActivityAttachments;

class ActivityElements extends Model {
  protected $table = 'activity_elements';
  protected $fillable = ['code_activities', 'name', 'ratio', 'sets'];
  public function activity() {
    return $this->belongsTo(Activity::class, 'code_activities', 'code_attachments');
  }
  public function attachments() {
    return $this->hasMany(ActivityAttachments::class, 'code', 'id');
  }
}
