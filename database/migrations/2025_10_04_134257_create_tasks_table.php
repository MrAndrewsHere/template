<?php

declare(strict_types=1);

use App\Service\Enums\TaskStatusEnum;
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
        Schema::create('tasks', function (Blueprint $blueprint): void {
            $blueprint->id();

            $blueprint->foreignId('user_id')
                ->constrained()
                ->onDelete('cascade');

            $blueprint->string('title');
            $blueprint->text('description')->nullable();
            $blueprint->string('status', 25)->default(TaskStatusEnum::default()->value);
            $blueprint->date('due_date')->nullable();
            $blueprint->timestamps();

            $blueprint->index('user_id'); // posgresql не создает автоматически индекс на внешний ключ

            $blueprint->index('status');
            $blueprint->index('due_date');

            $blueprint->index(['user_id', 'status']);
            $blueprint->index(['user_id', 'due_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
