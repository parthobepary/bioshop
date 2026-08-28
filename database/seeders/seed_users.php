<?php

require __DIR__ . '/../../vendor/autoload.php';

$app = require_once __DIR__ . '/../../bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use App\Models\Profile;
use App\Models\Product;
use App\Models\Link;
use App\Models\PaymentMethod;
use App\Models\Plan;
use Illuminate\Support\Facades\Hash;

echo "Seeding users...\n";

// Create Admin
$admin = User::create([
    'name' => 'Admin',
    'email' => 'admin@bioshop.com',
    'password' => Hash::make('password123'),
    'role' => 'admin',
    'email_verified_at' => now(),
]);
echo "Created admin: admin@bioshop.com\n";

// Create Sellers
$sellers = [
    [
        'name' => 'Fatima Rahman',
        'email' => 'fatima@bioshop.com',
        'username' => 'hijabhouse',
        'bio' => 'Premium hijabs and modest fashion',
    ],
    [
        'name' => 'Rakib Hassan',
        'email' => 'rakib@bioshop.com',
        'username' => 'techgadgetsbd',
        'bio' => 'Latest tech gadgets at best prices',
    ],
    [
        'name' => 'Tasnim Akter',
        'email' => 'tasnim@bioshop.com',
        'username' => 'foodieparadise',
        'bio' => 'Homemade food delivery',
    ],
];

foreach ($sellers as $s) {
    $user = User::create([
        'name' => $s['name'],
        'email' => $s['email'],
        'password' => Hash::make('password123'),
        'role' => 'user',
        'email_verified_at' => now(),
    ]);

    $profile = Profile::create([
        'user_id' => $user->id,
        'username' => $s['username'],
        'name' => $s['name'],
        'bio' => $s['bio'],
        'is_active' => true,
    ]);

    // Add products
    for ($i = 1; $i <= 3; $i++) {
        Product::create([
            'profile_id' => $profile->id,
            'name' => "Product $i",
            'description' => "Description for product $i",
            'price' => rand(100, 5000),
            'is_active' => true,
            'sort_order' => $i,
        ]);
    }

    // Add links
    Link::create([
        'profile_id' => $profile->id,
        'title' => 'Facebook',
        'url' => 'https://facebook.com/' . $s['username'],
        'is_active' => true,
        'sort_order' => 1,
    ]);

    Link::create([
        'profile_id' => $profile->id,
        'title' => 'Instagram',
        'url' => 'https://instagram.com/' . $s['username'],
        'is_active' => true,
        'sort_order' => 2,
    ]);

    // Add payment method
    PaymentMethod::create([
        'profile_id' => $profile->id,
        'type' => 'bkash',
        'account_number' => '017' . rand(10000000, 99999999),
        'account_name' => $s['name'],
        'is_active' => true,
    ]);

    echo "Created seller: {$s['email']} with profile @{$s['username']}\n";
}

echo "\nSeeding complete!\n";
echo "Admin: admin@bioshop.com / password123\n";
echo "Sellers: fatima@bioshop.com, rakib@bioshop.com, tasnim@bioshop.com / password123\n";
