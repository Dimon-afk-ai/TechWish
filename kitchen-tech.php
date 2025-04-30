<?php
session_start();
require_once __DIR__.'/boot.php';

// Устанавливаем аватар по умолчанию
$avatar = 'default-avatar.jpg';
if (isset($_SESSION['user_id'])) {
    try {
        $stmt = $pdo->prepare("SELECT username, avatar FROM users WHERE id = ?");
        $stmt->execute([$_SESSION['user_id']]);
        $user = $stmt->fetch();
        
        if ($user) {
            $_SESSION['username'] = $user['username'];
            $avatar = !empty($user['avatar']) ? $user['avatar'] : 'default-avatar.jpg';
        }
    } catch (PDOException $e) {
        error_log("Ошибка получения данных пользователя: " . $e->getMessage());
    }
}

require_once __DIR__.'/controllers/Kitchen-tech.php';
$controller = new KitchenController();
$controller->getAll($pdo, $avatar);