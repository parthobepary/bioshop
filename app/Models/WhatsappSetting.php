<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WhatsappSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'profile_id',
        'ai_enabled',
        'welcome_message',
        'away_message',
        'faq_items',
        'quick_replies',
        'business_hours_start',
        'business_hours_end',
        'business_days',
        'ai_instructions',
        'auto_reply_enabled',
        'order_notifications',
    ];

    protected $casts = [
        'ai_enabled' => 'boolean',
        'auto_reply_enabled' => 'boolean',
        'order_notifications' => 'boolean',
        'faq_items' => 'array',
        'quick_replies' => 'array',
        'business_days' => 'array',
    ];

    public function profile(): BelongsTo
    {
        return $this->belongsTo(Profile::class);
    }

    public function isWithinBusinessHours(): bool
    {
        if (!$this->business_hours_start || !$this->business_hours_end) {
            return true;
        }

        $now = now();
        $dayOfWeek = strtolower($now->format('l'));

        if ($this->business_days && !in_array($dayOfWeek, $this->business_days)) {
            return false;
        }

        $start = \Carbon\Carbon::parse($this->business_hours_start);
        $end = \Carbon\Carbon::parse($this->business_hours_end);
        $currentTime = \Carbon\Carbon::parse($now->format('H:i'));

        return $currentTime->between($start, $end);
    }

    public function getDefaultFaqItems(): array
    {
        return [
            [
                'question' => 'ডেলিভারি কতদিনে হয়?',
                'answer' => 'অর্ডার কনফার্ম হওয়ার ২-৩ দিনের মধ্যে ডেলিভারি হয়।',
            ],
            [
                'question' => 'ডেলিভারি চার্জ কত?',
                'answer' => 'ঢাকার ভিতরে ৬০ টাকা, ঢাকার বাইরে ১২০ টাকা।',
            ],
            [
                'question' => 'পেমেন্ট কিভাবে করব?',
                'answer' => 'বিকাশ, নগদ অথবা রকেটে পেমেন্ট করতে পারবেন।',
            ],
            [
                'question' => 'রিটার্ন পলিসি কি?',
                'answer' => 'প্রোডাক্টে সমস্যা থাকলে ৩ দিনের মধ্যে রিটার্ন করতে পারবেন।',
            ],
        ];
    }
}
