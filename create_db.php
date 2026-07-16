<?php
try {
    $pdo = new PDO('mysql:host=127.0.0.1', 'root', '');
    $pdo->exec('CREATE DATABASE IF NOT EXISTS sidmini CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;');
    echo "Database sidmini created successfully.\n";
} catch (PDOException $e) {
    die("DB ERROR: " . $e->getMessage());
}
