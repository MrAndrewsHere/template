<?php

declare(strict_types=1);

namespace App\Models\Builders;

use App\Service\Enums\TaskStatusEnum;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\Enums\FilterOperator;
use Spatie\QueryBuilder\QueryBuilder;

class TaskBuilder extends QueryBuilder
{
    public static function for(Builder|Relation|string $subject, ?Request $request = null): static
    {
        return parent::for($subject, $request)
            ->allowedFilters([
                AllowedFilter::belongsTo('user_id', 'user')->ignore(null),
                AllowedFilter::exact('status')->ignore(null),
                AllowedFilter::exact('priority')->ignore(null),

                AllowedFilter::operator(
                    name: 'min_date',
                    filterOperator: FilterOperator::GREATER_THAN_OR_EQUAL,
                    internalName: 'created_at')
                    ->ignore(null),

                AllowedFilter::operator(
                    name: 'max_date',
                    filterOperator: FilterOperator::LESS_THAN_OR_EQUAL,
                    internalName: 'created_at')
                    ->ignore(null),

            ])->allowedSorts([
                'created_at',
                'user_id',
                'status',
                'priority',
            ]);
    }

    public function withComments(): static
    {
        return $this->with(['comments' => fn ($b) => $b->paginate(15)]);
    }

    public function withUser(): static
    {
        return $this->with(['user:id,name,position']);
    }

    public function status(TaskStatusEnum $enum): static
    {
        return $this->where('status', $enum->value);
    }

    public function inProgress(): static
    {
        return $this->status(TaskStatusEnum::IN_PROGRESS);
    }

    public function overdueForWeek(): static
    {
        return $this->whereDate('created_at', '<', today()->subDays(7));
    }

    public function checkOverdue(): static
    {
        return $this->inProgress()->overdueForWeek();
    }
}
