<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Fix the "Years Experience" stat which previously used
 * {experience_count} (number of job positions) instead of the real
 * total years of experience. Convert existing stored rows to use the
 * new {experience_years} dynamic placeholder so the About Me stat
 * matches the Work Experience total.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('site_settings')) {
            return;
        }

        foreach (DB::table('site_settings')->get() as $row) {
            $stats = $row->stats_items ?? null;

            if (empty($stats) || ! is_string($stats)) {
                continue;
            }

            $items = json_decode($stats, true);

            if (! is_array($items)) {
                continue;
            }

            $changed = false;

            foreach ($items as &$item) {
                $label = trim((string) ($item['label'] ?? ''));
                $value = trim((string) ($item['value'] ?? ''));

                if ($value === '{experience_count}'
                    && in_array(strtolower($label), ['years experience', 'experience', 'years'], true)) {
                    $item['value'] = '{experience_years}';
                    $changed = true;
                }
            }
            unset($item);

            if ($changed) {
                DB::table('site_settings')
                    ->where('id', $row->id)
                    ->update(['stats_items' => json_encode($items)]);
            }
        }
    }

    public function down(): void
    {
        // Irreversible data change — no meaningful rollback.
    }
};
