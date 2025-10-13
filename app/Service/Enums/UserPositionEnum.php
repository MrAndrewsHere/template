<?php

declare(strict_types=1);

namespace App\Service\Enums;

use App\Service\Enums\Traits\HasBaseEnum;
use App\Service\Enums\Traits\HasTranslationLabel;

enum UserPositionEnum: string
{
    use HasBaseEnum, HasTranslationLabel;

    case MANAGER = 'manager';
    case DEVELOPER = 'developer';
    case TESTER = 'tester';

    public static function translationPrefix(): string
    {
        return 'enums.user.position.';
    }

    public static function default(): self
    {
        return self::MANAGER;
    }
}
