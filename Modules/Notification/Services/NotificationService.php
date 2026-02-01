<?php

namespace Modules\Notification\Services;

class NotificationService
{
    public function __construct(
        protected NotificationRepositoryInterface $repository
    ) {}

    public function send(
        string $channel,
        string $to,
        string $message,
        array $meta = []
    ) {
        $driver = ChannelFactory::make($channel);

        $result = $driver->send($to, $message, $meta);

        $this->repository->store([
            'channel' => $channel,
            'to' => $to,
            'message' => $message,
            'status' => $result ? 'sent' : 'failed',
        ]);

        return $result;
    }
}

