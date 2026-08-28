<?php

namespace Database\Seeders;

use App\Models\Plan;
use Illuminate\Database\Seeder;

class PlanSeeder extends Seeder
{
    public function run(): void
    {
        $plans = [
            [
                'name' => 'Free',
                'slug' => 'free',
                'price' => 0,
                'billing_period' => 'monthly',
                'max_products' => 5,
                'max_links' => 5,
                'analytics_enabled' => false,
                'custom_domain' => false,
                'whatsapp_ai' => false,
                'priority_support' => false,
                'features' => [
                    '5 Products',
                    '5 Links',
                    'Basic Profile',
                    'WhatsApp Ordering',
                    'Payment Methods Display',
                ],
                'is_active' => true,
            ],
            [
                'name' => 'Starter',
                'slug' => 'starter',
                'price' => 149,
                'billing_period' => 'monthly',
                'max_products' => 25,
                'max_links' => 15,
                'analytics_enabled' => true,
                'custom_domain' => false,
                'whatsapp_ai' => false,
                'priority_support' => false,
                'features' => [
                    '25 Products',
                    '15 Links',
                    'Basic Analytics',
                    'Custom Theme Colors',
                    'Priority Listing',
                    'Remove BioShop Branding',
                ],
                'is_active' => true,
            ],
            [
                'name' => 'Pro',
                'slug' => 'pro',
                'price' => 299,
                'billing_period' => 'monthly',
                'max_products' => 100,
                'max_links' => 50,
                'analytics_enabled' => true,
                'custom_domain' => true,
                'whatsapp_ai' => false,
                'priority_support' => false,
                'features' => [
                    '100 Products',
                    '50 Links',
                    'Advanced Analytics',
                    'Custom Domain',
                    'SEO Optimization',
                    'Product Categories',
                    'QR Code Generation',
                ],
                'is_active' => true,
            ],
            [
                'name' => 'Business',
                'slug' => 'business',
                'price' => 499,
                'billing_period' => 'monthly',
                'max_products' => -1, // Unlimited
                'max_links' => -1, // Unlimited
                'analytics_enabled' => true,
                'custom_domain' => true,
                'whatsapp_ai' => true,
                'priority_support' => true,
                'features' => [
                    'Unlimited Products',
                    'Unlimited Links',
                    'WhatsApp AI Bot',
                    'Priority Support',
                    'API Access',
                    'Team Members',
                    'Advanced Reports',
                ],
                'is_active' => true,
            ],
        ];

        foreach ($plans as $plan) {
            Plan::updateOrCreate(
                ['slug' => $plan['slug']],
                $plan
            );
        }
    }
}
