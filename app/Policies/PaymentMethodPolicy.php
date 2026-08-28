<?php

namespace App\Policies;

use App\Models\PaymentMethod;
use App\Models\User;

class PaymentMethodPolicy
{
    public function update(User $user, PaymentMethod $paymentMethod): bool
    {
        return $user->profile && $user->profile->id === $paymentMethod->profile_id;
    }

    public function delete(User $user, PaymentMethod $paymentMethod): bool
    {
        return $user->profile && $user->profile->id === $paymentMethod->profile_id;
    }
}
