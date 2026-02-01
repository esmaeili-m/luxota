<?php
namespace Modules\Notification\Contracts;

interface NotificationRepositoryInterface
{
    public function store(array $data);
}

