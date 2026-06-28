<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement('ALTER TABLE experiences MODIFY description TEXT NULL');
        DB::statement('ALTER TABLE projects MODIFY description TEXT NULL');
    }

    public function down(): void
    {
        DB::statement('ALTER TABLE experiences MODIFY description TEXT NOT NULL');
        DB::statement('ALTER TABLE projects MODIFY description TEXT NOT NULL');
    }
};
