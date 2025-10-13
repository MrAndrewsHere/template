<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\QueryBuilder\QueryBuilder;

class Base extends Model
{
    /**
     * @phpstan-return QueryBuilder
     */
    public static function spatieQueryBuilder(): QueryBuilder
    {
        return QueryBuilder::for(static::query());
    }
}
