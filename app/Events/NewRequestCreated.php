<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewRequestCreated implements ShouldBroadcastNow {
  use Dispatchable, InteractsWithSockets, SerializesModels;
  public $count;
  public $userId;
  public $page;
  public function __construct(int $count, int $userId, string $page) {
    $this->count = $count;
    $this->userId = $userId;
    $this->page = $page;
  }
  public function broadcastOn() {
    return new PrivateChannel("requests.{$this->userId}");
  }
  public function broadcastAs(){
    return 'NewRequestCreated';
  }
  public function broadcastWith() {
    return [
      'count' => $this->count,
      'page' => $this->page,
    ];
  }
}
