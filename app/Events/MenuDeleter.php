<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MenuDeleter implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $deletedMenu;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($menu)
    {
        $this->deletedMenu = $menu;
    }

    public function broadcastWith()
    {
      return [
        "menu" => $this->deletedMenu
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

    public function broadcastAS()
    {
      return "MenuDeleter";
    }
}
