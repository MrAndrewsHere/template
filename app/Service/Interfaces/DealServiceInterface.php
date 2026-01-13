<?php

declare(strict_types=1);

namespace App\Service\Interfaces;

use App\Data\StoreDealData;
use App\Data\UpdateDealData;
use App\Models\Deal;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface DealServiceInterface
{
    public function index(int $product_id): LengthAwarePaginator;

    public function create(StoreDealData $data): Deal;

    public function get(int|Deal $deal): Deal;

    public function update(int|Deal $deal, UpdateDealData $data): bool;

    public function delete(int|Deal $deal): bool;
}
