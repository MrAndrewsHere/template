<?php

use App\Service\Enums\DealStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('deals', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->text('client_name');
            $table->text('client_phone');
            $table->text('comment')->nullable();
            $table->string('status', 20)->default(DealStatus::default()->value);
            $table->timestamps();

            $table->index('product_id');

            $table->index(['product_id', 'status']);
            $table->index(['product_id', 'created_at']);

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('deals');
    }
};
