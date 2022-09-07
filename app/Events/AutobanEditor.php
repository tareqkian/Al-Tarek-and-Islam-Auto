<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AutobanEditor implements ShouldBroadcast
{
  use Dispatchable, InteractsWithSockets, SerializesModels;

  public $autoban;

  /**
   * Create a new event instance.
   *
   * @return void
   */
  public function __construct($autoban)
  {
    $this->autoban = $autoban;
  }

  public function broadcastWith()
  {
    return [
      'autoban' => $this->autoban
    ];
  }

  /**
   * Get the channels the event should broadcast on.
   *
   * @return \Illuminate\Broadcasting\Channel|array
   */
  public function broadcastOn()
  {
    return [
      new Channel('AutobansEvent'),
      new Channel('PricelistsEvent'),
//      new Channel('PricesEvent'),
    ];
  }
}
