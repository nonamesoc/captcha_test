<?php

try {
    $env = parse_ini_file('.env');
    $conn = new PDO("mysql:host=" . $env['DB_HOST'] . ";dbname=" . $env['DB_DATABASE'], $env['DB_USERNAME'], $env['DB_PASSWORD']);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $stmt = $conn->prepare("CREATE TABLE messages (id INT AUTO_INCREMENT PRIMARY KEY, title VARCHAR(255) NOT NULL, text TEXT NOT NULL) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB");
    $stmt->execute();
    echo "Таблица создана";
}
catch(PDOException $e) {
    echo "Не удалось создать таблицу: " . $e->getMessage();
}