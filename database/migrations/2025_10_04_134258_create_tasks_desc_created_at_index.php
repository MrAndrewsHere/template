<?php

declare(strict_types=1);

use App\Models\Task;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    protected array $columns = ['status', 'priority', 'user_id'];

    /**
     * Составные индексы с обратным по дате создания
     */
    public function up(): void
    {

        $NULLS_LAST = DB::connection()->getDriverName() === 'pgsql' ? 'NULLS LAST' : '';

        foreach ($this->columns as $col) {
            $sql = sprintf(
                'CREATE INDEX %s ON %s (%s, created_at DESC %s)',
                $this->indexName($col),
                $this->table(),
                $col,
                $NULLS_LAST
            );

            DB::statement($sql);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        foreach ($this->columns as $col) {
            DB::statement(sprintf('DROP INDEX IF EXISTS %s', $this->indexName($col)));
        }
    }

    protected function indexName(string $col): string
    {
        return sprintf('%s_%s_created_at_desc_idx', $this->table(), $col);
    }

    protected function table(): string
    {
        return new Task()->getTable();
    }
};
