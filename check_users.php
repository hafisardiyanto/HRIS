<?php
require 'vendor/autoload.php';
require 'bootstrap/app.php';

use App\Models\User;
use Illuminate\Support\Facades\Hash;

echo "=== Users in Database ===\n";
$users = User::all();

if ($users->isEmpty()) {
    echo "No users found\n";
} else {
    foreach ($users as $user) {
        echo "ID: {$user->id_user} | Username: {$user->username} | Email: {$user->email} | Role: {$user->role}\n";
        echo "Password Hash: " . substr($user->password, 0, 40) . "...\n";
        echo "---\n";
    }
}

echo "\n=== Testing Hash Verification ===\n";
if (!$users->isEmpty()) {
    $testUser = $users->first();
    $testPassword = '1345678';
    $isValid = Hash::check($testPassword, $testUser->password);
    echo "Testing password '1345678' with user '{$testUser->username}': " . ($isValid ? 'VALID' : 'INVALID') . "\n";
}
