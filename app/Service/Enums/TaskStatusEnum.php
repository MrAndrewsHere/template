<?php

declare(strict_types=1);

namespace App\Service\Enums;

use App\Service\Enums\Traits\HasBaseEnum;
use App\Service\Enums\Traits\HasTranslationLabel;

enum TaskStatusEnum: string
{
    use HasBaseEnum, HasTranslationLabel;

    case NEW = 'new';
    case IN_PROGRESS = 'in_progress';
    case COMPLETED = 'completed';
    case CANCELLED = 'cancelled';

    public static function translationPrefix(): string
    {
        return 'enums.task.status.';
    }

    public static function default(): self
    {
        return self::NEW;
    }
}
