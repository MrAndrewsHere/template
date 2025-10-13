<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Task;
use App\Models\User;
use App\Service\Enums\PriorityEnum;
use App\Service\Enums\TaskStatusEnum;
use Database\Factories\Traits\HasForUser;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Task>
 */
class TaskFactory extends Factory
{
    use HasForUser;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory()->notManager(),
            'title' => $this->faker->sentence(4),
            'description' => $this->faker->optional(0.7)->paragraph(3),
            'status' => $this->faker->randomElement(TaskStatusEnum::cases())->value,
            'priority' => $this->faker->randomElement(PriorityEnum::cases())->value,
            'created_at' => now(),
        ];
    }

    public function status(TaskStatusEnum $status): static
    {
        return $this->state(fn (array $attributes): array => [
            'status' => $status->value,
        ]);
    }

    public function recentlyCreated(): static
    {
        return $this->status(TaskStatusEnum::NEW);
    }

    public function inProgress(): static
    {
        return $this->status(TaskStatusEnum::IN_PROGRESS);
    }

    public function completed(): static
    {
        return $this->status(TaskStatusEnum::COMPLETED);
    }

    public function cancelled(): static
    {
        return $this->status(TaskStatusEnum::CANCELLED);
    }

    public function priority(PriorityEnum $priority): static
    {
        return $this->state(fn (array $attributes): array => [
            'priority' => $priority->value,
        ]);
    }

    public function low(): static
    {
        return $this->priority(PriorityEnum::LOW);
    }

    public function high(): static
    {
        return $this->priority(PriorityEnum::HIGH);
    }

    public function normal(): static
    {
        return $this->priority(PriorityEnum::NORMAL);
    }

    public function withDescription(): static
    {
        return $this->state(function (array $attributes): array {
            return [
                'description' => $this->faker->paragraph(3),
            ];
        });
    }

    public function withoutDescription(): static
    {
        return $this->state(function (array $attributes): array {
            return [
                'description' => null,
            ];
        });
    }

    public function withDueDate(): static
    {
        return $this->state(function (array $attributes): array {
            return [
                'created_at' => $this->faker->dateTimeBetween('now', '+30 days'),
            ];
        });
    }

    /**
     * Просроченная задача
     */
    public function overdue(): static
    {
        return $this->state(function (array $attributes): array {
            return [
                'created_at' => $this->faker->dateTimeBetween('-30 days', '-8 day'),
                'status' => $this->faker->randomElement([TaskStatusEnum::IN_PROGRESS])->value,
            ];
        });
    }

    public function dueTomorrow(): static
    {
        return $this->state(function (array $attributes): array {
            return [
                'created_at' => now()->addDay(),
            ];
        });
    }

    public function longTitle(): static
    {
        return $this->state(function (array $attributes): array {
            return [
                'title' => $this->faker->sentence(8),
            ];
        });
    }

    public function shortTitle(): static
    {
        return $this->state(function (array $attributes): array {
            return [
                'title' => $this->faker->words(2, true),
            ];
        });
    }
}
