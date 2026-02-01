<?php

namespace Modules\Notification\Contracts;

interface NotificationMessageInterface
{
    public function subject(): ?string;
    public function content(): string;
}
