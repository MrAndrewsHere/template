<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Deal;
use App\Models\Product;
use App\Service\Enums\DealStatus;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

final class DealApiTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function test_can_create_a_deal(): void
    {
        $product = Product::factory()->create();
        $deal = Deal::factory()->forProduct($product)->make();

        $response = $this->postJson(route('deals.store'), $deal->toArray());

        $response->assertStatus(201)
            ->assertJsonStructure([
                'data' => ['id', 'product_id', 'client_name', 'client_phone', 'comment', 'status'],
            ]);
    }

    /** @test */
    public function test_returns_validation_error_when_creating_deal_without_required_fields(): void
    {
        $response = $this->postJson(route('deals.store'), []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['product_id', 'client_name', 'client_phone']);
    }

    /** @test */
    public function test_can_get_deals_by_product_id(): void
    {
        $product = Product::factory()->create();
        Deal::factory(3)->forProduct($product)->create();

        $response = $this->getJson(route('deals.index', ['product_id' => $product->id]));

        $response->assertStatus(200)
            ->assertJsonCount(3, 'data');
    }

    /** @test */
    public function test_can_get_single_deal(): void
    {
        $deal = Deal::factory()->create();

        $response = $this->getJson(route('deals.show', $deal));

        $response->assertStatus(200)
            ->assertJsonPath('data.id', $deal->id);
    }

    /** @test */
    public function test_returns_404_for_nonexistent_deal(): void
    {
        $response = $this->getJson(route('deals.show', 999));

        $response->assertStatus(404);
    }

    /** @test */
    public function test_can_update_deal_comment_and_status(): void
    {
        $deal = Deal::factory()->create();

        $response = $this->patchJson(route('deals.update', $deal), [
            'comment' => 'Updated comment',
            'status' => DealStatus::Done->value,
        ]);

        $response->assertStatus(200)
            ->assertJsonPath('data.comment', 'Updated comment')
            ->assertJsonPath('data.status', DealStatus::Done->toArray());
    }

    /** @test */
    public function test_can_delete_deal(): void
    {
        $deal = Deal::factory()->create();

        $response = $this->deleteJson(route('deals.destroy', $deal));

        $response->assertStatus(204);
        $this->assertDatabaseMissing('deals', ['id' => $deal->id]);
    }
}
