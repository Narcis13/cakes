<?php
require_once __DIR__ . '/config/bootstrap.php';

use Cake\ORM\TableRegistry;
use Authentication\PasswordHasher\DefaultPasswordHasher;

// Get users table
$usersTable = TableRegistry::getTableLocator()->get('Users');

// Check if user exists
$user = $usersTable->find()->where(['email' => 'admin@medilab.com'])->first();

if ($user) {
    echo "User found:\n";
    echo "ID: " . $user->id . "\n";
    echo "Email: " . $user->email . "\n";
    echo "Role: " . $user->role . "\n";
    echo "Password hash: " . substr($user->password, 0, 20) . "...\n";
    
    // Test password verification
    $hasher = new DefaultPasswordHasher();
    $isValid = $hasher->check('admin123', $user->password);
    echo "Password check result: " . ($isValid ? 'VALID' : 'INVALID') . "\n";
} else {
    echo "User not found!\n";
    
    // Show all users
    $allUsers = $usersTable->find()->toArray();
    echo "Total users in database: " . count($allUsers) . "\n";
    foreach ($allUsers as $u) {
        echo "- ID: {$u->id}, Email: {$u->email}\n";
    }
}
?>
