<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('username')->unique();
            $table->string('name');
            $table->text('bio')->nullable();
            $table->string('photo')->nullable();
            $table->string('whatsapp')->nullable();
            $table->string('theme_color')->default('#6366F1');
            $table->json('social_links')->nullable();
            $table->json('section_order')->nullable();
            $table->json('section_visibility')->nullable();
            $table->string('seo_title')->nullable();
            $table->text('seo_description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index('username');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('profiles');
    }
};
