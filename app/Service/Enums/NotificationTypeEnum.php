<?php

declare(strict_types=1);

namespace App\Service\Enums;

use App\Service\Enums\Traits\HasBaseEnum;
use App\Service\Enums\Traits\HasTranslationLabel;

enum NotificationTypeEnum: string
{
    use HasBaseEnum, HasTranslationLabel;

    case STATUS_CHANGED = 'status_changed';
    case TASK_ASSIGNED = 'task_assigned';
    case OVERDUE = 'overdue';

    public static function translationPrefix(): string
    {
        return 'enums.notification.type.';
    }
}
