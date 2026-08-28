<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class WhatsappConversation extends Model
{
    use HasFactory;

    protected $fillable = [
        'profile_id',
        'customer_phone',
        'customer_name',
        'wa_conversation_id',
        'status',
        'last_message_at',
        'unread_count',
        'metadata',
    ];

    protected $casts = [
        'last_message_at' => 'datetime',
        'metadata' => 'array',
    ];

    public function profile(): BelongsTo
    {
        return $this->belongsTo(Profile::class);
    }

    public function messages(): HasMany
    {
        return $this->hasMany(WhatsappMessage::class, 'conversation_id');
    }

    public function latestMessage()
    {
        return $this->hasOne(WhatsappMessage::class, 'conversation_id')->latestOfMany();
    }

    public function markAsRead(): void
    {
        $this->update(['unread_count' => 0]);
        $this->messages()
            ->where('direction', 'incoming')
            ->where('status', '!=', 'read')
            ->update(['status' => 'read']);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeWithUnread($query)
    {
        return $query->where('unread_count', '>', 0);
    }
}
