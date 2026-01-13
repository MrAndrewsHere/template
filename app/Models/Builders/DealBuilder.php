<?php

declare(strict_types=1);

namespace App\Models\Builders;

use App\Models\Deal;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class DealBuilder extends QueryBuilder
{
    public static function for(Builder|Relation|string|null $subject = null, ?Request $request = null): static
    {
        return parent::for($subject ?? Deal::query(), $request)
            ->allowedFilters([
                AllowedFilter::partial('client_name'),
                AllowedFilter::exact('status'),
            ])->allowedSorts([
                'status',
                'created_at',
            ]);
    }

    public function product(int $product_id): static
    {
        return $this->where('product_id', $product_id);
    }
}
