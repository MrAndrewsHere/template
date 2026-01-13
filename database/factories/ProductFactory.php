<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Deal;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            //
        ];
    }

    public function hasDeals(int $count = 5, ?callable $callback = null): static
    {
        return $this->has(
            Deal::factory()
                ->count($count)
                ->when($callback, fn ($factory) => $factory->state($callback)),
            'deals'
        );
    }
}
