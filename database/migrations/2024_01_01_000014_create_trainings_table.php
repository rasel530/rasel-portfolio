<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('trainings', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('organization')->nullable();
            $table->text('description')->nullable();
            $table->text('long_description')->nullable();
            $table->string('image')->nullable();
            $table->string('certificate_url')->nullable();
            $table->string('duration')->nullable();
            $table->year('start_year')->nullable();
            $table->year('end_year')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('trainings');
    }
};
