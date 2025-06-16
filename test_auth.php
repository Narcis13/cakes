<?php
/**
 * Debug script to test authentication
 */
require_once __DIR__ . '/vendor/autoload.php';

use Authentication\PasswordHasher\DefaultPasswordHasher;

// Test password hashing
$hasher = new DefaultPasswordHasher();
$plainPassword = 'admin123';
$hashedPassword = '$2y$12$lMVFI77j9CPrfKPkm6G6New0wUwnnLloUbP2mDyeU13CXAlw/63sS';

echo "Testing password verification:\n";
echo "Plain password: {$plainPassword}\n";
echo "Hashed password: {$hashedPassword}\n";

$isValid = $hasher->check($plainPassword, $hashedPassword);
echo "Verification result: " . ($isValid ? 'VALID' : 'INVALID') . "\n";

// Create a new hash for comparison
$newHash = $hasher->hash($plainPassword);
echo "New hash: {$newHash}\n";

$newCheck = $hasher->check($plainPassword, $newHash);
echo "New hash verification: " . ($newCheck ? 'VALID' : 'INVALID') . "\n";
?>
