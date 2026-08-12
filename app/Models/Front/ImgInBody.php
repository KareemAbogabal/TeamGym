<?php

namespace App\Models\Front;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cookie;

class ImgInBody extends Model {
  public function addImgInBody($img) {
    $exists = self::where('code', Cookie::get('login_client'))->first();
    if (!$exists) {
      $this->code = Cookie::get('login_client');
      $this->img = $img;
      $this->save();
    } else {
      $row = self::where('code', Cookie::get('login_client'))->first();
      $row->img = $img;
      $row->save();
    };
  }
}
