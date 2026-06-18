<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Add a dedicated dark-mode logo column. The existing `logo`
     * column is treated as the light-mode logo. Fallback logic in
     * the model ensures a single uploaded logo is used for both
     * themes when only one is provided.
     */
    public function up(): void
    {
        Schema::table('profiles', function (Blueprint $table) {
            $table->string('logo_dark')->nullable()->after('logo');
        });
    }

    public function down(): void
    {
        Schema::table('profiles', function (Blueprint $table) {
            $table->dropColumn('logo_dark');
        });
    }
};
