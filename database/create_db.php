<?php

// This script creates the resume_generator database
// Run via: php database/create_db.php

$host = '127.0.0.1';
$port = 3306;
$user = 'root';
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host;port=$port;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->exec("CREATE DATABASE IF NOT EXISTS `resume_generator` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    echo "✅ Database 'resume_generator' created successfully!\n";
    echo "Now run: php artisan migrate\n";
} catch (PDOException $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Make sure WAMP/XAMPP is running and MySQL is started.\n";
}
