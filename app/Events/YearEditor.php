<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class YearEditor implements ShouldBroadcast
{
  use Dispatchable, InteractsWithSockets, SerializesModels;

  public $year;

  /**
   * Create a new event instance.
   *
   * @return void
   */
  public function __construct($year)
  {
    $this->year = $year;
  }

  public function broadcastWith(){
    return [
      'year' => $this->year
    ];
  }

  /**
   * Get the channels the event should broadcast on.
   *
   * @return \Illuminate\Broadcasting\Channel|array
   */
  public function broadcastOn()
  {
    return new Channel('YearsEvent');
  }
}
