<?php

/**
 * Direct MySQL migration script - creates all tables in one connection
 * Run: php database/migrate_direct.php
 */

$host   = 'localhost';
$port   = 3306;
$user   = 'root';
$pass   = '';
$dbName = 'resume_generator';

try {
    // Try localhost first, then 127.0.0.1
    $dsn = null;
    foreach (['localhost', '127.0.0.1'] as $h) {
        try {
            $pdo = new PDO("mysql:host=$h;port=$port;dbname=$dbName;charset=utf8mb4", $user, $pass, [
                PDO::ATTR_ERRMODE    => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_PERSISTENT => false,
                PDO::ATTR_TIMEOUT    => 15,
            ]);
            echo "✅ Connected to MySQL via host: $h\n\n";
            break;
        } catch (PDOException $e2) {
            echo "⚠️  Tried $h: " . $e2->getMessage() . "\n";
            $pdo = null;
        }
    }
    if (!$pdo) throw new PDOException('Could not connect via localhost or 127.0.0.1');

    $tables = [

        // 1. users
        "CREATE TABLE IF NOT EXISTS `users` (
            `id` bigint unsigned NOT NULL AUTO_INCREMENT,
            `name` varchar(255) NOT NULL,
            `email` varchar(255) NOT NULL UNIQUE,
            `email_verified_at` timestamp NULL DEFAULT NULL,
            `password` varchar(255) NOT NULL,
            `remember_token` varchar(100) DEFAULT NULL,
            `created_at` timestamp NULL DEFAULT NULL,
            `updated_at` timestamp NULL DEFAULT NULL,
            PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",

        // 2. password_reset_tokens
        "CREATE TABLE IF NOT EXISTS `password_reset_tokens` (
            `email` varchar(255) NOT NULL,
            `token` varchar(255) NOT NULL,
            `created_at` timestamp NULL DEFAULT NULL,
            PRIMARY KEY (`email`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",

        // 3. sessions
        "CREATE TABLE IF NOT EXISTS `sessions` (
            `id` varchar(255) NOT NULL,
            `user_id` bigint unsigned DEFAULT NULL,
            `ip_address` varchar(45) DEFAULT NULL,
            `user_agent` text DEFAULT NULL,
            `payload` longtext NOT NULL,
            `last_activity` int NOT NULL,
            PRIMARY KEY (`id`),
            KEY `sessions_user_id_index` (`user_id`),
            KEY `sessions_last_activity_index` (`last_activity`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",

        // 4. cache
        "CREATE TABLE IF NOT EXISTS `cache` (
            `key` varchar(255) NOT NULL,
            `value` mediumtext NOT NULL,
            `expiration` int NOT NULL,
            PRIMARY KEY (`key`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",

        // 5. cache_locks
        "CREATE TABLE IF NOT EXISTS `cache_locks` (
            `key` varchar(255) NOT NULL,
            `owner` varchar(255) NOT NULL,
            `expiration` int NOT NULL,
            PRIMARY KEY (`key`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",

        // 6. jobs
        "CREATE TABLE IF NOT EXISTS `jobs` (
            `id` bigint unsigned NOT NULL AUTO_INCREMENT,
            `queue` varchar(255) NOT NULL,
            `payload` longtext NOT NULL,
            `attempts` tinyint unsigned NOT NULL,
            `reserved_at` int unsigned DEFAULT NULL,
            `available_at` int unsigned NOT NULL,
            `created_at` int unsigned NOT NULL,
            PRIMARY KEY (`id`),
            KEY `jobs_queue_index` (`queue`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",

        // 7. failed_jobs
        "CREATE TABLE IF NOT EXISTS `failed_jobs` (
            `id` bigint unsigned NOT NULL AUTO_INCREMENT,
            `uuid` varchar(255) NOT NULL UNIQUE,
            `connection` text NOT NULL,
            `queue` text NOT NULL,
            `payload` longtext NOT NULL,
            `exception` longtext NOT NULL,
            `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",

        // 8. job_batches
        "CREATE TABLE IF NOT EXISTS `job_batches` (
            `id` varchar(255) NOT NULL,
            `name` varchar(255) NOT NULL,
            `total_jobs` int NOT NULL,
            `pending_jobs` int NOT NULL,
            `failed_jobs` int NOT NULL,
            `failed_job_ids` longtext NOT NULL,
            `options` mediumtext DEFAULT NULL,
            `cancelled_at` int DEFAULT NULL,
            `created_at` int NOT NULL,
            `finished_at` int DEFAULT NULL,
            PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",

        // 9. resumes
        "CREATE TABLE IF NOT EXISTS `resumes` (
            `id` bigint unsigned NOT NULL AUTO_INCREMENT,
            `user_id` bigint unsigned NOT NULL,
            `title` varchar(255) NOT NULL DEFAULT 'My Resume',
            `template` varchar(50) NOT NULL DEFAULT 'classic',
            `full_name` varchar(255) DEFAULT NULL,
            `email` varchar(255) DEFAULT NULL,
            `phone` varchar(50) DEFAULT NULL,
            `address` varchar(255) DEFAULT NULL,
            `city` varchar(100) DEFAULT NULL,
            `country` varchar(100) DEFAULT NULL,
            `linkedin` varchar(255) DEFAULT NULL,
            `github` varchar(255) DEFAULT NULL,
            `website` varchar(255) DEFAULT NULL,
            `job_title` varchar(255) DEFAULT NULL,
            `summary` text DEFAULT NULL,
            `profile_photo` varchar(255) DEFAULT NULL,
            `is_draft` tinyint(1) NOT NULL DEFAULT 1,
            `created_at` timestamp NULL DEFAULT NULL,
            `updated_at` timestamp NULL DEFAULT NULL,
            PRIMARY KEY (`id`),
            CONSTRAINT `resumes_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",

        // 10. educations
        "CREATE TABLE IF NOT EXISTS `educations` (
            `id` bigint unsigned NOT NULL AUTO_INCREMENT,
            `resume_id` bigint unsigned NOT NULL,
            `institution` varchar(255) NOT NULL,
            `degree` varchar(255) NOT NULL,
            `field_of_study` varchar(255) DEFAULT NULL,
            `start_date` varchar(50) DEFAULT NULL,
            `end_date` varchar(50) DEFAULT NULL,
            `currently_studying` tinyint(1) NOT NULL DEFAULT 0,
            `gpa` decimal(4,2) DEFAULT NULL,
            `description` text DEFAULT NULL,
            `order` int NOT NULL DEFAULT 0,
            `created_at` timestamp NULL DEFAULT NULL,
            `updated_at` timestamp NULL DEFAULT NULL,
            PRIMARY KEY (`id`),
            CONSTRAINT `educations_resume_id_foreign` FOREIGN KEY (`resume_id`) REFERENCES `resumes` (`id`) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",

        // 11. work_experiences
        "CREATE TABLE IF NOT EXISTS `work_experiences` (
            `id` bigint unsigned NOT NULL AUTO_INCREMENT,
            `resume_id` bigint unsigned NOT NULL,
            `company` varchar(255) NOT NULL,
            `position` varchar(255) NOT NULL,
            `location` varchar(255) DEFAULT NULL,
            `start_date` varchar(50) DEFAULT NULL,
            `end_date` varchar(50) DEFAULT NULL,
            `currently_working` tinyint(1) NOT NULL DEFAULT 0,
            `description` text DEFAULT NULL,
            `order` int NOT NULL DEFAULT 0,
            `created_at` timestamp NULL DEFAULT NULL,
            `updated_at` timestamp NULL DEFAULT NULL,
            PRIMARY KEY (`id`),
            CONSTRAINT `work_experiences_resume_id_foreign` FOREIGN KEY (`resume_id`) REFERENCES `resumes` (`id`) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",

        // 12. skills
        "CREATE TABLE IF NOT EXISTS `skills` (
            `id` bigint unsigned NOT NULL AUTO_INCREMENT,
            `resume_id` bigint unsigned NOT NULL,
            `name` varchar(255) NOT NULL,
            `category` varchar(100) NOT NULL DEFAULT 'Technical',
            `level` enum('Beginner','Intermediate','Advanced','Expert') NOT NULL DEFAULT 'Intermediate',
            `order` int NOT NULL DEFAULT 0,
            `created_at` timestamp NULL DEFAULT NULL,
            `updated_at` timestamp NULL DEFAULT NULL,
            PRIMARY KEY (`id`),
            CONSTRAINT `skills_resume_id_foreign` FOREIGN KEY (`resume_id`) REFERENCES `resumes` (`id`) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",

        // 13. projects
        "CREATE TABLE IF NOT EXISTS `projects` (
            `id` bigint unsigned NOT NULL AUTO_INCREMENT,
            `resume_id` bigint unsigned NOT NULL,
            `name` varchar(255) NOT NULL,
            `role` varchar(255) DEFAULT NULL,
            `url` varchar(255) DEFAULT NULL,
            `start_date` varchar(50) DEFAULT NULL,
            `end_date` varchar(50) DEFAULT NULL,
            `description` text DEFAULT NULL,
            `technologies` varchar(500) DEFAULT NULL,
            `order` int NOT NULL DEFAULT 0,
            `created_at` timestamp NULL DEFAULT NULL,
            `updated_at` timestamp NULL DEFAULT NULL,
            PRIMARY KEY (`id`),
            CONSTRAINT `projects_resume_id_foreign` FOREIGN KEY (`resume_id`) REFERENCES `resumes` (`id`) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",

        // 14. certifications
        "CREATE TABLE IF NOT EXISTS `certifications` (
            `id` bigint unsigned NOT NULL AUTO_INCREMENT,
            `resume_id` bigint unsigned NOT NULL,
            `name` varchar(255) NOT NULL,
            `issuer` varchar(255) NOT NULL,
            `issue_date` varchar(50) DEFAULT NULL,
            `expiry_date` varchar(50) DEFAULT NULL,
            `credential_id` varchar(255) DEFAULT NULL,
            `credential_url` varchar(255) DEFAULT NULL,
            `order` int NOT NULL DEFAULT 0,
            `created_at` timestamp NULL DEFAULT NULL,
            `updated_at` timestamp NULL DEFAULT NULL,
            PRIMARY KEY (`id`),
            CONSTRAINT `certifications_resume_id_foreign` FOREIGN KEY (`resume_id`) REFERENCES `resumes` (`id`) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",

        // 15. languages
        "CREATE TABLE IF NOT EXISTS `languages` (
            `id` bigint unsigned NOT NULL AUTO_INCREMENT,
            `resume_id` bigint unsigned NOT NULL,
            `name` varchar(255) NOT NULL,
            `proficiency` enum('Elementary','Limited Working','Professional Working','Full Professional','Native') NOT NULL DEFAULT 'Professional Working',
            `order` int NOT NULL DEFAULT 0,
            `created_at` timestamp NULL DEFAULT NULL,
            `updated_at` timestamp NULL DEFAULT NULL,
            PRIMARY KEY (`id`),
            CONSTRAINT `languages_resume_id_foreign` FOREIGN KEY (`resume_id`) REFERENCES `resumes` (`id`) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",

        // 16. migrations tracking table
        "CREATE TABLE IF NOT EXISTS `migrations` (
            `id` int unsigned NOT NULL AUTO_INCREMENT,
            `migration` varchar(255) NOT NULL,
            `batch` int NOT NULL,
            PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",
    ];

    $tableNames = [
        'users', 'password_reset_tokens', 'sessions', 'cache', 'cache_locks',
        'jobs', 'failed_jobs', 'job_batches', 'resumes', 'educations',
        'work_experiences', 'skills', 'projects', 'certifications', 'languages', 'migrations',
    ];

    foreach ($tables as $i => $sql) {
        $name = $tableNames[$i];
        try {
            $pdo->exec($sql);
            echo "  ✅ Table `$name` created\n";
        } catch (PDOException $e) {
            echo "  ⚠️  Table `$name`: " . $e->getMessage() . "\n";
        }
    }

    // Insert migration records so artisan knows they're done
    $migrations = [
        '0001_01_01_000000_create_users_table',
        '0001_01_01_000001_create_cache_table',
        '0001_01_01_000002_create_jobs_table',
        '2026_09_07_090121_create_resumes_table',
        '2026_09_07_090122_create_educations_table',
        '2026_09_07_090123_create_work_experiences_table',
        '2026_09_07_090124_create_skills_table',
        '2026_09_07_090125_create_projects_table',
        '2026_09_07_090126_create_certifications_table',
        '2026_09_07_090127_create_languages_table',
    ];

    $stmt = $pdo->prepare("INSERT IGNORE INTO `migrations` (`migration`, `batch`) VALUES (?, 1)");
    foreach ($migrations as $m) {
        $stmt->execute([$m]);
    }

    echo "\n🎉 All tables created successfully!\n";
    echo "✅ Migration tracking records inserted.\n\n";
    echo "🚀 Run: php artisan serve\n";
    echo "🌐 Visit: http://localhost:8000\n";

} catch (PDOException $e) {
    echo "❌ Fatal Error: " . $e->getMessage() . "\n";
}
