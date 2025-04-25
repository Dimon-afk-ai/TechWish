<?php
require __DIR__.'/boot.php';
$stmt = $pdo->prepare("UPDATE users SET avatar = ? WHERE id = ?");
$test = $stmt->execute(['uploads/test.jpg', 1]); // Подставьте реальный ID
echo $test ? "Успешно" : "Ошибка: ".implode(', ', $stmt->errorInfo());