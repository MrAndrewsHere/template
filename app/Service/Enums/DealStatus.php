<?php

declare(strict_types=1);

namespace App\Service\Enums;

use App\Share\Enums\Traits\HasBaseEnum;
use App\Share\Enums\Traits\HasTranslationLabel;

enum DealStatus: string
{
    use HasBaseEnum, HasTranslationLabel;

    case New = 'new';
    case InProgress = 'in_progress';
    case Done = 'done';
    case Canceled = 'canceled';

    protected static function translationPrefix(): string
    {
        return 'enums.deal_status.';
    }

    public static function default(): self
    {
        return self::New;
    }
}
