<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WhatsappMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'conversation_id',
        'wa_message_id',
        'direction',
        'sender_type',
        'content',
        'message_type',
        'media',
        'metadata',
        'status',
        'product_id',
    ];

    protected $casts = [
        'media' => 'array',
        'metadata' => 'array',
    ];

    public function conversation(): BelongsTo
    {
        return $this->belongsTo(WhatsappConversation::class, 'conversation_id');
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function isFromCustomer(): bool
    {
        return $this->direction === 'incoming';
    }

    public function isFromAI(): bool
    {
        return $this->sender_type === 'ai';
    }

    public function isFromSeller(): bool
    {
        return $this->sender_type === 'seller';
    }

    public function scopeIncoming($query)
    {
        return $query->where('direction', 'incoming');
    }

    public function scopeOutgoing($query)
    {
        return $query->where('direction', 'outgoing');
    }
}
