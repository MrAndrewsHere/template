<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Deal;
use App\Models\Product;
use App\Service\Enums\DealStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Deal>
 */
class DealFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'product_id' => Product::factory(),
            'client_name' => $this->faker->name(),
            'client_phone' => $this->faker->phoneNumber(),
            'comment' => $this->faker->sentence(),
            'status' => DealStatus::random(),
        ];
    }

    public function forProduct(Product $product): static
    {
        return $this->state(fn (array $attributes): array => [
            'product_id' => $product->id,
        ]);
    }

    public function withStatus(string $status): static
    {
        return $this->state(fn (array $attributes): array => [
            'status' => $status,
        ]);
    }
}
