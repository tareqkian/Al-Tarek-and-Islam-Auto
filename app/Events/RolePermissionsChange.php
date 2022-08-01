<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class RolePermissionsChange implements ShouldBroadcast
{
  use Dispatchable, InteractsWithSockets, SerializesModels;

  public $role;
  public $permissions;

  /**
   * Create a new event instance.
   *
   * @return void
   */
  public function __construct($userPermissions,$role)
  {
    $this->permissions = $userPermissions;
    $this->role = $role;
  }

  public function broadcastWith()
  {
    return [
      "data" => $this->permissions,
      'role' => $this->role
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

  /**
   * @return string
   */
  public function broadcastAs()
  {
    return 'RolePermissionsChange';
  }

}
