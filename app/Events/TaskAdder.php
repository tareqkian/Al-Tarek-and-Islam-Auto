<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TaskAdder implements ShouldBroadcast
{
  use Dispatchable, InteractsWithSockets, SerializesModels;

  public $tasks;

  /**
   * Create a new event instance.
   *
   * @return void
   */
  public function __construct($tasks)
  {
    $this->tasks = $tasks;
  }

  public function broadcastWith()
  {
    return [
      'tasks' => $this->tasks
    ];
  }

  /**
   * Get the channels the event should broadcast on.
   *
   * @return \Illuminate\Broadcasting\Channel|array
   */
  public function broadcastOn()
  {
    return new Channel('PricesEvent');
  }
}
