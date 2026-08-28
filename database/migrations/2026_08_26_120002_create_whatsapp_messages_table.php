<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('whatsapp_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('conversation_id')->constrained('whatsapp_conversations')->onDelete('cascade');
            $table->string('wa_message_id')->nullable()->index();
            $table->enum('direction', ['incoming', 'outgoing']);
            $table->enum('sender_type', ['customer', 'ai', 'seller']);
            $table->text('content');
            $table->enum('message_type', ['text', 'image', 'document', 'audio', 'video', 'location', 'contact', 'order'])->default('text');
            $table->json('media')->nullable();
            $table->json('metadata')->nullable();
            $table->enum('status', ['sent', 'delivered', 'read', 'failed'])->default('sent');
            $table->foreignId('product_id')->nullable()->constrained()->onDelete('set null');
            $table->timestamps();

            $table->index(['conversation_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('whatsapp_messages');
    }
};
