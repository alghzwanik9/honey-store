<?php
use App\Models\User;
use Illuminate\Support\Facades\Hash;

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    $user = User::create([
        'name' => 'Admin',
        'email' => 'admin@admin.com',
        'password' => Hash::make('password'),
        'is_admin' => true,
    ]);
    echo "Admin user created successfully: ID " . $user->id . "\n";
} catch (\Exception $e) {
    echo "Error creating user: " . $e->getMessage() . "\n";
}
