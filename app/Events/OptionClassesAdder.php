<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class OptionClassesAdder implements ShouldBroadcast
{
  use Dispatchable, InteractsWithSockets, SerializesModels;

  public $optionClass;

  /**
   * Create a new event instance.
   *
   * @return void
   */
  public function __construct($optionClass)
  {
    $this->optionClass = $optionClass;
  }

  public function broadcastWith()
  {
    return [
      'optionClass' => $this->optionClass
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
      new Channel('ClassesEvent'),
      new Channel('OptionsEvent')
    ];
  }
}
