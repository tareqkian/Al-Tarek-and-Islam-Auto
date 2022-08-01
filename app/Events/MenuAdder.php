<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MenuAdder implements ShouldBroadcast
{
  use Dispatchable, InteractsWithSockets, SerializesModels;

  public $newMenu;

  /**
   * Create a new event instance.
   *
   * @return void
   */
  public function __construct($menu)
  {
    $this->newMenu = $menu;
  }

  public function broadcastWith()
  {
    return [
      "menu" => $this->newMenu
    ];
  }

  /**
   * Get the channels the event should broadcast on.
   *
   * @return \Illuminate\Broadcasting\Channel|array
   */
  public function broadcastOn()
  {
    return new Channel('MenuBuilderEvent');
  }

  public function broadcastAs()
  {
    return "MenuAdder";
  }
}
