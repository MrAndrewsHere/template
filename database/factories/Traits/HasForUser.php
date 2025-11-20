<?php

declare(strict_types=1);

namespace Database\Factories\Traits;

use App\Models\User;

trait HasForUser
{
    public function forUser(User $user): static
    {
        return $this->state(function (array $attributes) use ($user): array {
            return [
                'user_id' => $user->id,
            ];
        });
    }
}
