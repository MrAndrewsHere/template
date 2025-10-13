<?php

declare(strict_types=1);

namespace Tests\Feature;

use Tests\TestCase;

class JsonOnlyTest extends TestCase
{
    /**
     * Если запрос был сделан не в формате JSON, то выдавать ошибку некорректного запроса.
     */
    public function test_the_application_returns_400_response(): void
    {
        $this->get(route('tasks.index'))->assertStatus(400);
        $this->get(route('tasks.show', ['task' => 111]))->assertStatus(400);
    }
}
