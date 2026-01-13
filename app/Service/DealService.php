<?php

declare(strict_types=1);

namespace App\Service;

use App\Data\StoreDealData;
use App\Data\UpdateDealData;
use App\Models\Builders\DealBuilder;
use App\Models\Deal;
use App\Models\Product;
use App\Service\Interfaces\DealServiceInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class DealService implements DealServiceInterface
{
    public function index(int $product_id): LengthAwarePaginator
    {
        $product = $this->findOrFailProduct($product_id);

        return DealBuilder::for()
            ->product($product->id)
            ->paginate()
            ->withQueryString();
    }

    public function create(StoreDealData $data): Deal
    {
        $product = $this->findOrFailProduct($data->product_id);

        return $product->deals()
            ->create($data->except('product_id')->toArray());
    }

    public function get(int|Deal $deal): Deal
    {
        return Deal::query()->findOrFail($deal instanceof Deal ? $deal->id : $deal);
    }

    public function update(int|Deal $deal, UpdateDealData $data): bool
    {
        $deal = $deal instanceof Deal ? $deal : $this->get($deal);

        return $deal->update($data->toArray());
    }

    public function delete(int|Deal $deal): bool
    {
        $deal = $deal instanceof Deal ? $deal : $this->get($deal);

        return $deal->delete();
    }

    private function findOrFailProduct(int $product_id): Product
    {
        return Product::query()
            ->findOrFail($product_id);
    }
}
