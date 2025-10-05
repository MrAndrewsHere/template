<?php

declare(strict_types=1);

namespace App\Service\Enums;

enum TaskStatusEnum: string
{
    case PENDING = 'pending';
    case IN_PROGRESS = 'in_progress';
    case DONE = 'done';

    private const TRANSLATION_PREFIX = 'enums.task.status.';

    public function label(): string
    {
        $translated = __($this->translationKey());

        if ($translated === $this->translationKey()) {
            return $this->defaultLabel();
        }

        return (string) $translated;
    }

    private function defaultLabel(): string
    {
        return ucfirst(strtolower($this->value));
    }

    public function translationKey(): string
    {
        return self::TRANSLATION_PREFIX.$this->value;
    }

    public static function default(): \App\Service\Enums\TaskStatusEnum
    {
        return self::PENDING;
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
