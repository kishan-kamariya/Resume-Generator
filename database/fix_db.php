<?php

// Fix InnoDB tablespace conflict by dropping and recreating the database
$host = '127.0.0.1';
$port = 3306;
$user = 'root';
$pass = '';
$dbName = 'resume_generator';

try {
    $pdo = new PDO("mysql:host=$host;port=$port;charset=utf8mb4", $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_TIMEOUT => 10,
    ]);

    echo "✅ Connected to MySQL\n";

    // Drop the database completely (removes all .ibd files too)
    $pdo->exec("DROP DATABASE IF EXISTS `$dbName`");
    echo "🗑️  Dropped database '$dbName'\n";

    // Recreate fresh
    $pdo->exec("CREATE DATABASE `$dbName` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    echo "✅ Created fresh database '$dbName'\n";

    echo "\n🚀 Now run: php artisan migrate\n";

} catch (PDOException $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
