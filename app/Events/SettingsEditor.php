<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SettingsEditor implements ShouldBroadcast
{
  use Dispatchable, InteractsWithSockets, SerializesModels;

  public $settings;

  /**
   * Create a new event instance.
   *
   * @return void
   */
  public function __construct($settings)
  {
    $this->settings = $settings;
  }

  public function broadcastWith(){
    return [
      'settings' => $this->settings
    ];
  }

  /**
   * Get the channels the event should broadcast on.
   *
   * @return \Illuminate\Broadcasting\Channel|array
   */
  public function broadcastOn()
  {
    return new Channel('MainEvents');
  }
}
