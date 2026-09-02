<?php

namespace App\Models\Front;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class ImgInBody extends Model {
  public function addImgInBody($img) {
    $code = Auth::guard('client')->user()?->code;
    $exists = self::where('code', $code)->first();
    if (!$exists) {
      $this->code = $code;
      $this->img = $img;
      $this->save();
    } else {
      $row = self::where('code', $code)->first();
      $row->img = $img;
      $row->save();
    };
  }
}
