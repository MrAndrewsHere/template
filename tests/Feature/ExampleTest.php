<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    /**
     * Test that the application returns a 404 error for non-existent routes.
     */
    public function test_the_application_returns_a_404_error_for_non_existent_routes(): void
    {
        $response = $this->get('/nonexistentroute');

        $response->assertStatus(404);
    }
}
