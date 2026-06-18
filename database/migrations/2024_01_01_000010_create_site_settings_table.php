<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Site-wide settings for the public header, footer, and SEO meta.
     * A single row holds every customizable value (nav items, footer
     * text, social links, etc.) so they can be edited from the admin
     * panel without touching code.
     */
    public function up(): void
    {
        Schema::create('site_settings', function (Blueprint $table) {
            $table->id();

            // ---- Header / Navigation ----
            $table->json('nav_items')->nullable();
            $table->boolean('show_nav_cta')->default(true);
            $table->string('nav_cta_label')->default('Hire Me');
            $table->string('nav_cta_url')->default('#contact');

            // ---- Footer ----
            $table->text('footer_about')->nullable();
            $table->string('footer_copyright')->nullable();
            $table->json('footer_nav_items')->nullable();
            $table->string('footer_social_facebook')->nullable();
            $table->string('footer_social_linkedin')->nullable();
            $table->string('footer_social_github')->nullable();
            $table->string('footer_social_twitter')->nullable();

            // ---- SEO ----
            $table->string('meta_title')->nullable();
            $table->string('meta_description')->nullable();
            $table->string('meta_keywords')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('site_settings');
    }
};
