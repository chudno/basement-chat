<?php

namespace Haemanthus\Basement\Notifications;

use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Collection;

class PrivateMessageRead extends Notification implements ShouldBroadcast
{
    /**
     * Message sender id.
     *
     * @var int
     */
    protected int $senderId;

    /**
     * List of read messages id.
     *
     * @var array
     */
    protected array $privateMessages;

    /**
     * Create a new notification instance.
     *
     * @param \Illuminate\Foundation\Auth\User&\Haemanthus\Basement\Contracts\User $sender
     * @param \Illuminate\Support\Collection<int,\Haemanthus\Basement\Data\PrivateMessageData> $privateMessages
     */
    public function __construct(Authenticatable $sender, Collection $privateMessages)
    {
        $this->senderId = $sender->id;
        $this->privateMessages = $privateMessages->pluck('id')->toArray();
    }

    /**
     * The event's broadcast name.
     *
     * @return string
     */
    public function broadcastAs(): string
    {
        return 'basement.message.read';
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\PresenceChannel|array
     */
    public function broadcastOn(): PresenceChannel|array
    {
        return new PresenceChannel('basement.contact.' . $this->senderId);
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable): array
    {
        return ['broadcast'];
    }

    /**
     * Get the broadcastable representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\BroadcastMessage
     */
    public function toBroadcast($notifiable): BroadcastMessage
    {
        return new BroadcastMessage([
            'messages' => $this->privateMessages,
        ]);
    }
}