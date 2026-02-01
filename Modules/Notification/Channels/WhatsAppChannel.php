<?php
namespace Modules\Notification\Channels;
use Modules\Notification\Contracts\ChannelInterface;

class WhatsAppChannel implements ChannelInterface
{
    public function send(string $to, string $message, array $meta = []): bool
    {
        // call whatsapp api
        return true;
    }
}
