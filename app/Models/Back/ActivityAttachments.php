<?php

namespace App\Models\Back;

use Illuminate\Database\Eloquent\Model;
use App\Models\Back\ActivityElements;

class ActivityAttachments extends Model {
  protected $table = 'activity_attachments';
  protected $fillable = ['code', 'img', 'video'];
  public function activity() {
    return $this->belongsTo(ActivityElements::class, 'code', 'id');
  }
}
