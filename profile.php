<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

require_once __DIR__.'/boot.php';

try {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $user = $stmt->fetch();
    
    if (!$user) {
        throw new Exception('Пользователь не найден');
    }
} catch (PDOException $e) {
    die("Ошибка базы данных");
}

// Устанавливаем переменные для header.php
$page_title = 'Профиль пользователя';
$show_profile_content = true; // Флаг для отображения профиля
$profile_user = $user; // Передаем данные пользователя

include __DIR__.'/views/header_profile.html';