<?php
// Show PHP errors
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Load your BaseDao and DB config
require_once 'config.php';
require_once 'BaseDao.php';

// Step 1: Connect to DB
$connection = Database::connect();
echo "✅ Connected to database<br><br>";

// Step 2: Initialize BaseDao for 'users' table
$userDao = new BaseDao('users');

// Step 3: Insert a new test user
$testUser = [
    'username' => 'test_user_' . rand(1000, 9999),
    'email' => 'testuser' . rand(1000, 9999) . '@example.com',
    'password_hash' => password_hash('password123', PASSWORD_DEFAULT),
    'role' => 'customer'
];

$inserted = $userDao->insert($testUser);
echo $inserted ? "✅ Insert successful<br>" : "❌ Insert failed<br>";

// Step 4: Get all users
$allUsers = $userDao->getAll();
echo "<pre>👥 All Users:\n";
print_r($allUsers);
echo "</pre>";

// Step 5: Get the last inserted user by ID
$lastId = $connection->lastInsertId();
$singleUser = $userDao->getById($lastId);
echo "<pre>🔍 User with ID $lastId:\n";
print_r($singleUser);
echo "</pre>";

// Step 6: Update user
$updatedData = [
    'username' => 'updated_user_' . rand(1000, 9999),
    'email' => 'updatedemail' . rand(1000, 9999) . '@example.com',
    'password_hash' => password_hash('newpass456', PASSWORD_DEFAULT),
    'role' => 'admin'
];

$updated = $userDao->update($lastId, $updatedData);
echo $updated ? "✅ Update successful<br>" : "❌ Update failed<br>";

// Step 7: Fetch updated user
$updatedUser = $userDao->getById($lastId);
echo "<pre>📝 Updated User:\n";
print_r($updatedUser);
echo "</pre>";

// Step 8: Delete user
$deleted = $userDao->delete($lastId);
echo $deleted ? "🗑️ Delete successful<br>" : "❌ Delete failed<br>";

// Step 9: Confirm deletion
$check = $userDao->getById($lastId);
echo "<pre>🔍 User after deletion (should be empty):\n";
print_r($check);
echo "</pre>";
