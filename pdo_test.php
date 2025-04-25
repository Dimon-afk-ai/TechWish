<?php
echo "<pre>";
print_r(PDO::getAvailableDrivers());
echo "</pre>";

// Тест подключения
try {
    $pdo = new PDO('mysql:host=localhost;dbname=base', 'root', '');
    echo "Подключение успешно!";
    
    // Тест записи
    $pdo->exec("CREATE TABLE IF NOT EXISTS test_pdo (id INT)");
    $pdo->exec("INSERT INTO test_pdo VALUES (1)");
    echo "Запись успешна!";
    
} catch (PDOException $e) {
    echo "Ошибка: " . $e->getMessage();
}