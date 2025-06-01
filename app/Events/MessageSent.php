<?php
namespace App\Events;

use App\Models\Message;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageSent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;
    public $sender_name;

    public function __construct(Message $message, $sender_name)
    {
        $this->message = $message; // Menyimpan pesan
        $this->sender_name = $sender_name; // Menyimpan nama pengirim
    }

    public function broadcastOn()
    {
        return new PrivateChannel('chat.' . $this->message->receiver_id); // Channel untuk menerima pesan
    }
}
