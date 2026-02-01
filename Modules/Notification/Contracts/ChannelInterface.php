<?php
namespace Modules\Notification\Contracts;

interface ChannelInterface
{
public function send(string $to, string $message, array $meta = []): bool;
}

