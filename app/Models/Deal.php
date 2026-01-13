<?php

declare(strict_types=1);

namespace App\Models;

use App\Service\Enums\DealStatus;
use Database\Factories\DealFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Deal extends Model
{
    /** @use HasFactory<DealFactory> */
    use HasFactory;

    protected $fillable = [
        'product_id',
        'client_name',
        'client_phone',
        'comment',
        'status',
    ];

    protected $casts = [
        'status' => DealStatus::class,
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function isNew(): bool
    {
        return $this->status === DealStatus::New;
    }

    public function isDone(): bool
    {
        return $this->status === DealStatus::Done;
    }

    public function isCancelled(): bool
    {
        return $this->status === DealStatus::Canceled;
    }

    public function isInProgress(): bool
    {
        return $this->status === DealStatus::InProgress;
    }
}
