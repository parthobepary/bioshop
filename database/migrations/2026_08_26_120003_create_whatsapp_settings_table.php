<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('whatsapp_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('profile_id')->unique()->constrained()->onDelete('cascade');
            $table->boolean('ai_enabled')->default(true);
            $table->string('welcome_message')->nullable();
            $table->string('away_message')->nullable();
            $table->json('faq_items')->nullable();
            $table->json('quick_replies')->nullable();
            $table->string('business_hours_start')->nullable();
            $table->string('business_hours_end')->nullable();
            $table->json('business_days')->nullable();
            $table->text('ai_instructions')->nullable();
            $table->boolean('auto_reply_enabled')->default(true);
            $table->boolean('order_notifications')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('whatsapp_settings');
    }
};
