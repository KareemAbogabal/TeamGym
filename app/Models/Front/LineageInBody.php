<?php

namespace App\Models\Front;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cookie;
use App\Models\Front\Client;
use Carbon\Carbon;

class LineageInBody extends Model {
  public function client() {
    return $this->belongsTo(Client::class, 'code', 'code');
  }
  public function addLineages($name, $lineage) {
    $client = Client::where("code", Cookie::get('login_client'))->first();
    $exists = self::where('code', $client->code)->get();
    $date = strtolower(Carbon::now('Africa/Cairo')->format('F'));
    $months = [
      'january', 'february', 'march', 'april', 'may', 'june',
      'july', 'august', 'september', 'october', 'november', 'december'
    ];
    if (count($exists) !== 16) {
      $this->code = $client->code;
      $this->name = $name;
      foreach ($months as $m) {
        if ($m == $date) {
          $this->$m = $lineage;
          break;
        } else {
          if ($this->$m == NULL) {
            $this->$m = 0;
          };
        };
      };
      $this->save();
    } else {
      $row = self::where('code', $client->code)->where('name', $name)->first();
      foreach ($months as $m) {
        if ($m == $date) {
          $row->$m = $lineage;
          break;
        } else {
          if ($row->$m == NULL) {
            $row->$m = 0;
          };
        };
      };
      $row->save();
    };
  }
}
