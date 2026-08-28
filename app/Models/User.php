<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'role',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    public function profile(): HasOne
    {
        return $this->hasOne(Profile::class);
    }

    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function activeSubscription(): HasOne
    {
        return $this->hasOne(Subscription::class)->where('status', 'active')->latest();
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function hasProfile(): bool
    {
        return $this->profile !== null;
    }

    /**
     * Get the user's current active plan
     */
    public function currentPlan(): ?Plan
    {
        $subscription = $this->subscriptions()
            ->where('status', 'active')
            ->where(function ($query) {
                $query->whereNull('ends_at')
                    ->orWhere('ends_at', '>', now());
            })
            ->with('plan')
            ->first();

        if ($subscription) {
            return $subscription->plan;
        }

        // Return free plan as default
        return Plan::where('slug', 'free')->first();
    }

    /**
     * Check if user has an active paid subscription
     */
    public function hasActiveSubscription(): bool
    {
        return $this->subscriptions()
            ->where('status', 'active')
            ->where(function ($query) {
                $query->whereNull('ends_at')
                    ->orWhere('ends_at', '>', now());
            })
            ->whereHas('plan', function ($query) {
                $query->where('price', '>', 0);
            })
            ->exists();
    }

    /**
     * Check if user can add more products
     */
    public function canAddProduct(): bool
    {
        $plan = $this->currentPlan();
        if (!$plan) return true;
        if ($plan->max_products === -1) return true;

        $currentCount = $this->profile?->products()->count() ?? 0;
        return $currentCount < $plan->max_products;
    }

    /**
     * Check if user can add more links
     */
    public function canAddLink(): bool
    {
        $plan = $this->currentPlan();
        if (!$plan) return true;
        if ($plan->max_links === -1) return true;

        $currentCount = $this->profile?->links()->count() ?? 0;
        return $currentCount < $plan->max_links;
    }

    /**
     * Get remaining product slots
     */
    public function remainingProducts(): int|string
    {
        $plan = $this->currentPlan();
        if (!$plan) return 'Unlimited';
        if ($plan->max_products === -1) return 'Unlimited';

        $currentCount = $this->profile?->products()->count() ?? 0;
        return max(0, $plan->max_products - $currentCount);
    }

    /**
     * Get remaining link slots
     */
    public function remainingLinks(): int|string
    {
        $plan = $this->currentPlan();
        if (!$plan) return 'Unlimited';
        if ($plan->max_links === -1) return 'Unlimited';

        $currentCount = $this->profile?->links()->count() ?? 0;
        return max(0, $plan->max_links - $currentCount);
    }
}
