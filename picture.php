<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

require_once __DIR__.'/boot.php';

$user_id = $_SESSION['user_id'];

try {
    // Обработка аватара
    if (!empty($_FILES['avatar']['name'])) {
        // Проверка ошибок загрузки
        if ($_FILES['avatar']['error'] !== UPLOAD_ERR_OK) {
            $_SESSION['error'] = 'Помилка завантаження файлу: ' . $_FILES['avatar']['error'];
            header('Location: profile.php');
            exit;
        }

        // Проверка типа файла
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
        if (!in_array($_FILES['avatar']['type'], $allowed_types)) {
            $_SESSION['error'] = 'Дозволені лише зображення (JPEG, PNG, GIF)';
            header('Location: profile.php');
            exit;
        }

        // Проверка размера файла (максимум 2MB)
        $max_size = 2 * 1024 * 1024;
        if ($_FILES['avatar']['size'] > $max_size) {
            $_SESSION['error'] = 'Файл занадто великий (максимум 2MB)';
            header('Location: profile.php');
            exit;
        }

        $upload_dir = __DIR__ . '/uploads/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }

        // Удаляем старый аватар, если он существует
        $stmt = $pdo->prepare("SELECT avatar FROM users WHERE id = ?");
        $stmt->execute([$user_id]);
        $old_avatar = $stmt->fetchColumn();
        
        if ($old_avatar && file_exists(__DIR__ . '/' . $old_avatar)) {
            unlink(__DIR__ . '/' . $old_avatar);
        }

        $avatar_name = uniqid() . '_' . basename($_FILES['avatar']['name']);
        $avatar_path = $upload_dir . $avatar_name;
        $avatar_db_path = 'uploads/' . $avatar_name;

        if (move_uploaded_file($_FILES['avatar']['tmp_name'], $avatar_path)) {
            $stmt = $pdo->prepare("UPDATE users SET avatar = ? WHERE id = ?");
            $stmt->execute([$avatar_db_path, $user_id]);
        } else {
            $_SESSION['error'] = 'Помилка збереження файлу';
            header('Location: profile.php');
            exit;
        }
    }

    // Обновление имени и email
    $username = $_POST['username'] ?? '';
    $email = $_POST['email'] ?? '';

    $stmt = $pdo->prepare("UPDATE users SET username = ?, email = ? WHERE id = ?");
    $stmt->execute([$username, $email, $user_id]);

    $_SESSION['success'] = 'Профіль успішно оновлено';
    header('Location: profile.php');
    exit;

} catch (PDOException $e) {
    $_SESSION['error'] = "Помилка оновлення профілю: " . $e->getMessage();
    header('Location: profile.php');
    exit;
}