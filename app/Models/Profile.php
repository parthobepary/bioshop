<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Profile extends Model
{
    protected $fillable = [
        'user_id',
        'username',
        'name',
        'bio',
        'photo',
        'whatsapp',
        'theme_color',
        'social_links',
        'section_order',
        'section_visibility',
        'seo_title',
        'seo_description',
        'is_active',
    ];

    protected $casts = [
        'social_links' => 'array',
        'section_order' => 'array',
        'section_visibility' => 'array',
        'is_active' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function links(): HasMany
    {
        return $this->hasMany(Link::class)->orderBy('sort_order');
    }

    public function categories(): HasMany
    {
        return $this->hasMany(Category::class)->orderBy('sort_order');
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class)->orderBy('sort_order');
    }

    public function paymentMethods(): HasMany
    {
        return $this->hasMany(PaymentMethod::class)->orderBy('sort_order');
    }

    public function pageViews(): HasMany
    {
        return $this->hasMany(PageView::class);
    }

    public function whatsappClicks(): HasMany
    {
        return $this->hasMany(WhatsappClick::class);
    }

    public function whatsappConversations(): HasMany
    {
        return $this->hasMany(WhatsappConversation::class);
    }

    public function whatsappSettings(): HasOne
    {
        return $this->hasOne(WhatsappSetting::class);
    }

    public function getRouteKeyName(): string
    {
        return 'username';
    }
}
