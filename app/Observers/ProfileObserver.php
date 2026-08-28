<?php

namespace App\Observers;

use App\Models\Profile;
use App\Services\CacheService;

class ProfileObserver
{
    public function __construct(
        protected CacheService $cacheService
    ) {}

    /**
     * Handle the Profile "updated" event.
     */
    public function updated(Profile $profile): void
    {
        $this->cacheService->clearProfileCache($profile->username);
    }

    /**
     * Handle the Profile "deleted" event.
     */
    public function deleted(Profile $profile): void
    {
        $this->cacheService->clearAllProfileCaches($profile);
    }
}
