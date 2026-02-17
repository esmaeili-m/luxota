<?php
namespace App\Enums;

enum TaskType: string
{
    case TASK = 'task';
    case STORY = 'story';
}

enum TaskPriority: string
{
    case LOW = 'low';
    case HIGH = 'high';
    case CRITICAL = 'critical';
}

enum TaskCategory: string
{
    case BUG = 'bug';
    case IMPROVEMENT = 'improvement';
    case TASK = 'task';
    case CHANGE_REQUEST = 'change_request';
}

enum BusinessStatus: string
{
    case CLIENT_REQUEST = 'client_request';
    case INFRASTRUCTURE = 'infrastructure';
    case BUSINESS_DEV = 'business_dev';
}

enum HasInvoice: string
{
    case FREE = 'free';
    case INVOICED = 'invoiced';
    case NOT_DECLARED = 'not_declared';
}

enum ImplementationType: string
{
    case PLUGIN = 'plugin';
    case CORE = 'core';
}
