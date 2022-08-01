<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PermissionsGenerator implements ShouldBroadcast
{
  use Dispatchable, InteractsWithSockets, SerializesModels;

  public $generatedPermissions;

  /**
   * Create a new event instance.
   *
   * @return void
   */
  public function __construct($permissions)
  {
    $this->generatedPermissions = $permissions;
  }


  public function broadcastWith()
  {
    return [
      "generatedPermissions" => $this->generatedPermissions
    ];
  }

  /**
   * Get the channels the event should broadcast on.
   *
   * @return \Illuminate\Broadcasting\Channel|array
   */
  public function broadcastOn()
  {
    return new Channel('permissionsEvent');
  }
}
