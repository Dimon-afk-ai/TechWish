<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

require_once __DIR__.'/boot.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $currentPassword = $_POST['current_password'] ?? '';
    $newPassword = $_POST['new_password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';
    
    // Получаем текущий пароль пользователя
    try {
        $stmt = $pdo->prepare("SELECT password FROM users WHERE id = ?");
        $stmt->execute([$_SESSION['user_id']]);
        $user = $stmt->fetch();
        
        if (!$user || !password_verify($currentPassword, $user['password'])) {
            $_SESSION['error'] = "Неверный текущий пароль";
            header('Location: profile.php');
            exit;
        }
        
        if ($newPassword !== $confirmPassword) {
            $_SESSION['error'] = "Новые пароли не совпадают";
            header('Location: profile.php');
            exit;
        }
        
        if (strlen($newPassword) < 6) {
            $_SESSION['error'] = "Пароль должен быть не менее 6 символов";
            header('Location: profile.php');
            exit;
        }
        
        $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);
        $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE id = ?");
        $stmt->execute([$hashedPassword, $_SESSION['user_id']]);
        
        $_SESSION['success'] = "Пароль успешно изменен";
    } catch (PDOException $e) {
        $_SESSION['error'] = "Ошибка базы данных: ".$e->getMessage();
    }
    
    header('Location: profile.php');
    exit;
}