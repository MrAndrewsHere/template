<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_get_unauthorized(): void
    {
        $this->getJson(route('user'))->assertStatus(401);
        $this->getJson(route('tasks.index'))->assertStatus(401);
    }

    public function test_can_get_user(): void
    {
        Sanctum::actingAs(
            User::factory()->create(),
            ['*']
        );

        $testResponse = $this->getJson(route('user'));

        $testResponse->assertOk();
    }

    public function test_can_register(): void
    {
        $user = User::factory()->make()->toArray();
        $user['password'] = $user['password_confirmation'] = '123456789';

        $testResponse = $this->postJson(route('register.store'), $user);

        $testResponse->assertStatus(201);

    }

    public function test_can_login(): void
    {
        $user = User::factory()->create(['password' => '123456789']);
        $data = $user->toArray();
        $data['password'] = '123456789';

        $testResponse = $this->postJson(route('login.store'), $data);

        $testResponse->assertOk();

    }
}
