<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Task;
use App\Models\User;
use App\Service\Enums\TaskStatusEnum;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Task>
 */
class TaskFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'title' => $this->faker->sentence(4),
            'description' => $this->faker->optional(0.7)->paragraph(3),
            'status' => $this->faker->randomElement(TaskStatusEnum::cases())->value,
            'due_date' => $this->faker->optional(0.6)->dateTimeBetween('now', '+30 days')?->format('Y-m-d'),
        ];
    }

    public function pending(): static
    {
        return $this->state(function (array $attributes): array {
            return [
                'status' => TaskStatusEnum::PENDING->value,
            ];
        });
    }

    public function inProgress(): static
    {
        return $this->state(function (array $attributes): array {
            return [
                'status' => TaskStatusEnum::IN_PROGRESS->value,
            ];
        });
    }

    public function done(): static
    {
        return $this->state(function (array $attributes): array {
            return [
                'status' => TaskStatusEnum::DONE->value,
            ];
        });
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
                'due_date' => $this->faker->dateTimeBetween('now', '+30 days')->format('Y-m-d'),
            ];
        });
    }

    /**
     * Задача без срока выполнения
     */
    public function withoutDueDate(): static
    {
        return $this->state(function (array $attributes): array {
            return [
                'due_date' => null,
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
                'due_date' => $this->faker->dateTimeBetween('-30 days', '-1 day')->format('Y-m-d'),
                'status' => $this->faker->randomElement([TaskStatusEnum::PENDING, TaskStatusEnum::IN_PROGRESS])->value,
            ];
        });
    }

    public function dueToday(): static
    {
        return $this->state(function (array $attributes): array {
            return [
                'due_date' => now()->format('Y-m-d'),
            ];
        });
    }

    public function dueTomorrow(): static
    {
        return $this->state(function (array $attributes): array {
            return [
                'due_date' => now()->addDay()->format('Y-m-d'),
            ];
        });
    }

    public function forUser(User $user): static
    {
        return $this->state(function (array $attributes) use ($user): array {
            return [
                'user_id' => $user->id,
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
