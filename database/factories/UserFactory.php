<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\User;
use App\Service\Enums\UserPositionEnum;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends Factory<User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
            'position' => UserPositionEnum::random()->value,
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes): array => [
            'email_verified_at' => null,
        ]);
    }

    public function position(UserPositionEnum $position): static
    {
        return $this->state(fn (array $attributes): array => [
            'position' => $position->value,
        ]);
    }

    public function notManager(): static
    {
        return $this->position($this->faker->randomElement([UserPositionEnum::TESTER, UserPositionEnum::DEVELOPER]));
    }

    public function manager(): static
    {
        return $this->position(UserPositionEnum::MANAGER);
    }

    public function developer(): static
    {
        return $this->position(UserPositionEnum::DEVELOPER);
    }

    public function tester(): static
    {
        return $this->position(UserPositionEnum::TESTER);
    }
}
