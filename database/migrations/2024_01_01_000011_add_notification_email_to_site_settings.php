<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Add a configurable notification email to site settings.
     * When someone submits the public contact form, the message is
     * emailed to this address. Falls back to the profile email if blank.
     */
    public function up(): void
    {
        Schema::table('site_settings', function (Blueprint $table) {
            $table->string('notification_email')->nullable()->after('nav_cta_url');
        });
    }

    public function down(): void
    {
        Schema::table('site_settings', function (Blueprint $table) {
            $table->dropColumn('notification_email');
        });
    }
};
