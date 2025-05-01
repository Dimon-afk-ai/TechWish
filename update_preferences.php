<?php
// update_preferences.php - Обробник для оновлення налаштувань користувача

// Ініціалізуємо сесію
session_start();

// Перевіряємо, чи користувач авторизований
if (!isset($_SESSION['user_id'])) {
    // Якщо не авторизований, перенаправляємо на сторінку логіну
    header("Location: login.php");
    exit;
}

// Включаємо файл з підключенням до бази даних
require_once "boot.php";

// Проверяем, создано ли соединение
if (!isset($pdo) || $pdo === null) {
    die("Ошибка: соединение с базой данных не установлено");
}

// Ініціалізуємо змінні для повідомлень
$success_message = '';
$error_message = '';

// Перевіряємо, чи форма була відправлена методом POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Отримуємо ID користувача з сесії
    $user_id = $_SESSION['user_id'];
    
    // Отримуємо та очищаємо дані форми
    $theme = htmlspecialchars($_POST['theme'] ?? 'dark');
    
    // Обробляємо чекбокси (вони будуть відсутні у $_POST, якщо не відмічені)
    $notification_email = isset($_POST['notification_email']) ? 1 : 0;
    $notification_site = isset($_POST['notification_site']) ? 1 : 0;
    
    // Отримуємо налаштування приватності
    $privacy = htmlspecialchars($_POST['privacy'] ?? 'public');
    
    try {
        // Підготовлюємо SQL запит для оновлення налаштувань
        $stmt = $pdo->prepare("
            UPDATE user_preferences 
            SET theme = ?, notification_email = ?, notification_site = ?, privacy = ? 
            WHERE user_id = ?
        ");
        
        // Перевіряємо, чи існує запис для цього користувача в таблиці налаштувань
        $check_stmt = $pdo->prepare("SELECT COUNT(*) as count FROM user_preferences WHERE user_id = ?");
        $check_stmt->execute([$user_id]);
        $result = $check_stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($result['count'] > 0) {
            // Якщо запис існує, оновлюємо його
            $stmt->execute([$theme, $notification_email, $notification_site, $privacy, $user_id]);
        } else {
            // Якщо запису немає, створюємо новий
            $insert_stmt = $pdo->prepare("
                INSERT INTO user_preferences (user_id, theme, notification_email, notification_site, privacy)
                VALUES (?, ?, ?, ?, ?)
            ");
            $insert_stmt->execute([$user_id, $theme, $notification_email, $notification_site, $privacy]);
        }
        
        // Оновлюємо дані сесії, щоб відображати зміни одразу
        $_SESSION['user_preferences'] = [
            'theme' => $theme,
            'notification_email' => $notification_email,
            'notification_site' => $notification_site,
            'privacy' => $privacy
        ];
        
        $success_message = "Налаштування успішно оновлені!";
    } catch (PDOException $e) {
        // Обробка помилок бази даних
        $error_message = "Помилка при оновленні налаштувань: " . $e->getMessage();
        // Для розробки можна логувати помилку
        error_log("Database Error: " . $e->getMessage());
    }
}

// Перенаправляємо на сторінку профілю з повідомленням
header("Location: profile.php?success=" . urlencode($success_message) . "&error=" . urlencode($error_message));
exit;
?>