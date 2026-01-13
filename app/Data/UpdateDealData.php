<?php

declare(strict_types=1);

namespace App\Data;

use App\Http\Requests\UpdateDealRequest;
use App\Service\Enums\DealStatus;
use App\Share\DTO\BaseDTO;
use Stevebauman\Purify\Facades\Purify;

class UpdateDealData extends BaseDTO
{
    public function __construct(
        public ?string $comment,
        public ?DealStatus $status,
    ) {}

    public static function fromRequest(UpdateDealRequest $request): self
    {
        return new self(
            comment: Purify::clean($request->comment),
            status: DealStatus::tryFrom($request->status) ?? DealStatus::default(),
        );
    }
}
