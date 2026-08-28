<?php

namespace App\Policies;

use App\Models\Category;
use App\Models\User;

class CategoryPolicy
{
    public function update(User $user, Category $category): bool
    {
        return $user->profile && $user->profile->id === $category->profile_id;
    }

    public function delete(User $user, Category $category): bool
    {
        return $user->profile && $user->profile->id === $category->profile_id;
    }
}
