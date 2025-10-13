<?php

declare(strict_types=1);

namespace App\Service\Enums\Traits;

use Exception;

trait HasTranslationLabel
{
    public function label(): string
    {
        $translated = __($this->translationKey());

        if ($translated === $this->translationKey()) {
            return $this->defaultLabel();
        }

        return (string) $translated;
    }

    public function translationKey(): string
    {
        return static::translationPrefix().$this->value;
    }

    protected static function translationPrefix(): string
    {
        throw new Exception(__CLASS__.'::'.__FUNCTION__.' method must be implemented');
    }

    protected function defaultLabel(): string
    {
        return ucfirst(strtolower($this->value));
    }
}
