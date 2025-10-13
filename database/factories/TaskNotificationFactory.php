<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Task;
use App\Models\TaskNotification;
use App\Models\User;
use Database\Factories\Traits\HasForTask;
use Database\Factories\Traits\HasForUser;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<TaskNotification>
 */
class TaskNotificationFactory extends Factory
{
    use HasForTask, HasForUser;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'task_id' => Task::factory(),
            'user_id' => User::factory(),
            'message' => $this->faker->text(70),
        ];
    }
}
