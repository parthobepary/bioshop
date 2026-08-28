<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('whatsapp_conversations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('profile_id')->constrained()->onDelete('cascade');
            $table->string('customer_phone');
            $table->string('customer_name')->nullable();
            $table->string('wa_conversation_id')->nullable()->index();
            $table->enum('status', ['active', 'resolved', 'pending'])->default('active');
            $table->timestamp('last_message_at')->nullable();
            $table->integer('unread_count')->default(0);
            $table->json('metadata')->nullable();
            $table->timestamps();

            $table->index(['profile_id', 'customer_phone']);
            $table->index(['profile_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('whatsapp_conversations');
    }
};
