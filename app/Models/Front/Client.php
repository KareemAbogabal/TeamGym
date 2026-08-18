<?php

namespace App\Models\Front;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Models\Notification;
use App\Models\Front\Cardio;
use App\Models\Front\LineageInBody;
use App\Models\Front\SettingClient;
use App\Models\Back\Activity;
use App\Models\Back\Record;
use App\Models\Back\RequestsPayment;
use App\Models\Back\Payment;
use App\Models\Back\History;
use App\Models\Back\CustomerRequests;

class Client extends Authenticatable {
  protected $fillable = ['code', 'fname', 'lname', 'email', 'phone', 'state', 'category', 'documentation', 'img', 'year_inbody',];
  protected static function booted(): void {
    static::creating(function ($client) {
      if (empty($client->year_inbody)) {
        $client->year_inbody = (int) now()->format('Y');
      };
    });
  }
  public function activities() {
    return $this->hasMany(Activity::class, 'code_client', 'code');
  }
  public function records() {
    return $this->hasMany(Record::class, 'code_client', 'code');
  }
  public function requestsPayments() {
    return $this->hasMany(RequestsPayment::class, 'code_client', 'code');
  }
  public function payment() {
    return $this->hasMany(Payment::class, 'code_client', 'code');
  }
  public function history() {
    return $this->hasMany(History::class, 'code_client', 'code');
  }
  public function lineageInBodies() {
    return $this->hasMany(LineageInBody::class, 'code', 'code');
  }
  public function settings() {
    return $this->hasOne(SettingClient::class, 'code_client', 'code');
  }
  public function notifications() {
    return $this->hasMany(Notification::class, 'code_client', 'code');
  }
  public function customerRequests() {
    return $this->hasMany(CustomerRequests::class, 'code_client', 'code');
  }
  public function cardio() {
    return $this->hasMany(Cardio::class, 'code_client', 'code');
  }
}
