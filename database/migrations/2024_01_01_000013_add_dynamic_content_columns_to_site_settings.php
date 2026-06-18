<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Consolidate every remaining hardcoded UI string into admin-manageable
     * JSON columns on the site_settings table.
     */
    public function up(): void
    {
        Schema::table('site_settings', function (Blueprint $table) {
            // Public section headings, subtitles & empty-states
            $table->json('section_content')->nullable()->after('stats_items');
            // Hero section: greeting, button labels, typed titles
            $table->json('hero_content')->nullable()->after('section_content');
            // Contact section: heading, description, form labels
            $table->json('contact_content')->nullable()->after('hero_content');
            // Reusable labels: Present, Current, Featured, etc.
            $table->json('labels')->nullable()->after('contact_content');
            // Admin panel branding: title, brand mark, brand name
            $table->json('admin_branding')->nullable()->after('labels');
        });
    }

    public function down(): void
    {
        Schema::table('site_settings', function (Blueprint $table) {
            $table->dropColumn([
                'section_content',
                'hero_content',
                'contact_content',
                'labels',
                'admin_branding',
            ]);
        });
    }
};
