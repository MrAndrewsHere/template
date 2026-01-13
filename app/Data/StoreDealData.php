<?php

declare(strict_types=1);

namespace App\Data;

use App\Http\Requests\StoreDealRequest;
use App\Service\Enums\DealStatus;
use App\Share\DTO\BaseDTO;
use Stevebauman\Purify\Facades\Purify;

class StoreDealData extends BaseDTO
{
    public function __construct(
        public int $product_id,
        public string $client_name,
        public string $client_phone,
        public ?string $comment,
        public DealStatus $status,
    ) {}

    public static function fromRequest(StoreDealRequest $request): self
    {
        return new self(
            product_id: $request->product_id,
            client_name: $request->client_name,
            client_phone: $request->client_phone,
            comment: Purify::clean($request->comment),
            status: DealStatus::tryFrom($request->status) ?? DealStatus::default(),
        );
    }
}
