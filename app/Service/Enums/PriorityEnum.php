<?php

declare(strict_types=1);

namespace App\Service\Enums;

use App\Service\Enums\Traits\HasBaseEnum;
use App\Service\Enums\Traits\HasTranslationLabel;

enum PriorityEnum: string
{
    use HasBaseEnum, HasTranslationLabel;

    case HIGH = 'high';
    case NORMAL = 'normal';
    case LOW = 'low';

    public static function translationPrefix(): string
    {
        return 'enums.task.priority.';
    }

    public static function default(): self
    {
        return self::NORMAL;
    }
}
