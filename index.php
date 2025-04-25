<?php
session_start();
require_once __DIR__.'/boot.php';

// Устанавливаем аватар по умолчанию
$avatar = 'default-avatar.jpg';

if (isset($_SESSION['user_id'])) {
    try {
        // Получаем актуальные данные из БД
        $stmt = $pdo->prepare("SELECT username, avatar FROM users WHERE id = ?");
        $stmt->execute([$_SESSION['user_id']]);
        $user = $stmt->fetch();
        
        if ($user) {
            // Обновляем сессионные переменные
            $_SESSION['username'] = $user['username'];
            $_SESSION['user_nickname'] = $user['username']; // Для index.html
            
            if (!empty($user['avatar'])) {
                $avatar = $user['avatar'];
            }
        }
    } catch (PDOException $e) {
        // Ошибка - используем значения по умолчанию
    }
}

// Передаем аватар в HTML
$GLOBALS['current_avatar'] = $avatar;




include 'index.html';
?>