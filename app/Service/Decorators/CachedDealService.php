<?php

declare(strict_types=1);

namespace App\Service\Decorators;

use App\Data\StoreDealData;
use App\Data\UpdateDealData;
use App\Models\Deal;
use App\Service\Interfaces\DealServiceInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;

class CachedDealService implements DealServiceInterface
{
    private int $ttl;

    public function __construct(
        private DealServiceInterface $decorated
    ) {
        $this->ttl = (int) config('cache.deals_service.ttl', 300); // 5 минут по умолчанию
    }

    public function index(int $product_id): LengthAwarePaginator
    {
        $cacheKey = "deals.product.{$product_id}.page.".request()->get('page', 1);

        return Cache::remember($cacheKey, $this->ttl, function () use ($product_id): LengthAwarePaginator {
            return $this->decorated->index($product_id);
        });
    }

    public function create(StoreDealData $data): Deal
    {
        $deal = $this->decorated->create($data);

        Cache::forget("deals.product.{$data->product_id}.*");

        return $deal;
    }

    public function get(int|Deal $deal): Deal
    {
        $id = $deal instanceof Deal ? $deal->id : $deal;
        $cacheKey = "deal.{$id}";

        return Cache::remember($cacheKey, $this->ttl, function () use ($deal): Deal {
            return $this->decorated->get($deal);
        });
    }

    public function update(int|Deal $deal, UpdateDealData $data): bool
    {
        $result = $this->decorated->update($deal, $data);

        if ($result) {
            $dealId = $deal instanceof Deal ? $deal->id : $deal;
            $dealModel = $deal instanceof Deal ? $deal : Deal::find($dealId);

            Cache::forget("deal.{$dealId}");
            Cache::forget("deals.product.{$dealModel->product_id}.*");
        }

        return $result;
    }

    public function delete(int|Deal $deal): bool
    {
        $dealModel = $deal instanceof Deal ? $deal : Deal::find($deal);
        $productId = $dealModel->product_id;
        $dealId = $dealModel->id;

        $result = $this->decorated->delete($deal);

        if ($result) {
            Cache::forget("deal.{$dealId}");
            Cache::forget("deals.product.{$productId}.*");
        }

        return $result;
    }
}
