<?php

namespace Modules\Notification\Factories;

use Modules\Notification\Channels\WhatsAppChannel;
use Modules\Notification\Contracts\ChannelInterface;

class ChannelFactory
{
    public static function make(string $driver): ChannelInterface
    {
        return match ($driver) {
            'whatsapp' => app(WhatsAppChannel::class),
//            'bale' => app(BaleChannel::class),
            default => throw new \Exception('Channel not supported'),
        };
    }
}
