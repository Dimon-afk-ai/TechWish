<?php
session_start();
require_once __DIR__.'/boot.php';

// Включаем показ всех ошибок на экране
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Создаем функцию для отслеживания процесса загрузки
function debug_log($message) {
    if (!isset($_SESSION['debug_logs'])) {
        $_SESSION['debug_logs'] = [];
    }
    $_SESSION['debug_logs'][] = $message;
}

debug_log("=== Начало обработки запроса ===");
debug_log("User ID: ".(isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 'не установлен'));
debug_log("FILES: ".print_r($_FILES, true));
debug_log("POST: ".print_r($_POST, true));

try {
    // Проверка авторизации
    if (!isset($_SESSION['user_id'])) {
        throw new Exception("Вы не авторизованы");
    }

    // Проверка директории uploads
    $upload_dir = __DIR__.'/uploads/';
    debug_log("Директория для загрузки: ".$upload_dir);
    debug_log("Директория существует: ".(file_exists($upload_dir) ? 'Да' : 'Нет'));
    
    if (!file_exists($upload_dir)) {
        if (!mkdir($upload_dir, 0755, true)) {
            throw new Exception("Не удалось создать директорию uploads");
        }
        debug_log("Директория uploads создана");
    }
    
    debug_log("Права на запись: ".(is_writable($upload_dir) ? 'Да' : 'Нет'));
    
    if (!is_writable($upload_dir)) {
        throw new Exception("Директория uploads недоступна для записи");
    }
    
    // Обработка загрузки аватара
    if (!empty($_FILES['avatar']['name'])) {
        debug_log("Начинаем обработку загрузки аватара");
        
        // Проверка ошибок загрузки
        if ($_FILES['avatar']['error'] !== UPLOAD_ERR_OK) {
            throw new Exception("Ошибка загрузки файла: ".$_FILES['avatar']['error']);
        }
        
        // Проверка типа файла
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
        $file_info = finfo_open(FILEINFO_MIME_TYPE);
        $file_type = finfo_file($file_info, $_FILES['avatar']['tmp_name']);
        finfo_close($file_info);
        
        debug_log("Тип файла: ".$file_type);
        
        if (!in_array($file_type, $allowed_types)) {
            throw new Exception("Допустимы только JPG, PNG или GIF");
        }
        
        // Проверка размера
        if ($_FILES['avatar']['size'] > 2 * 1024 * 1024) {
            throw new Exception("Максимальный размер - 2MB");
        }
        
        // Удаление старого аватара
        debug_log("Проверка наличия старого аватара");
        $stmt = $pdo->prepare("SELECT avatar FROM users WHERE id = ?");
        $stmt->execute([$_SESSION['user_id']]);
        $old_avatar = $stmt->fetchColumn();
        
        if ($old_avatar) {
            debug_log("Старый аватар: ".$old_avatar);
            $old_path = __DIR__.'/'.$old_avatar;
            debug_log("Путь к старому аватару: ".$old_path);
            debug_log("Файл существует: ".(file_exists($old_path) ? 'Да' : 'Нет'));
            
            if (file_exists($old_path)) {
                if (!unlink($old_path)) {
                    debug_log("Не удалось удалить старый аватар");
                } else {
                    debug_log("Старый аватар успешно удален");
                }
            }
        }
        
        // Генерация нового имени файла
        $ext = strtolower(pathinfo($_FILES['avatar']['name'], PATHINFO_EXTENSION));
        $new_filename = 'avatar_'.$_SESSION['user_id'].'_'.time().'.'.$ext;
        $destination = $upload_dir.$new_filename;
        // Исправляем путь к файлу для корректного отображения
        $db_path = 'uploads/'.$new_filename;
        
        debug_log("Новое имя файла: ".$new_filename);
        debug_log("Полный путь назначения: ".$destination);
        debug_log("Путь для БД: ".$db_path);
        
        // Перемещение файла
        debug_log("Попытка перемещения файла...");
        if (!move_uploaded_file($_FILES['avatar']['tmp_name'], $destination)) {
            $error = error_get_last();
            throw new Exception("Ошибка при перемещении файла: ".($error ? $error['message'] : 'неизвестная ошибка'));
        }
        
        debug_log("Файл успешно перемещен");
        debug_log("Проверка существования файла: ".(file_exists($destination) ? 'Да' : 'Нет'));
        
        // Устанавливаем правильные права доступа для файла
        if (!chmod($destination, 0644)) {
            debug_log("Предупреждение: не удалось установить права доступа к файлу");
        } else {
            debug_log("Права доступа к файлу установлены: 0644");
        }
        
        // Обновление БД
        debug_log("Обновление информации в базе данных");
        $stmt = $pdo->prepare("UPDATE users SET avatar = ? WHERE id = ?");
        $result = $stmt->execute([$db_path, $_SESSION['user_id']]);
        
        if (!$result) {
            debug_log("Ошибка БД: ".implode(", ", $stmt->errorInfo()));
            // Удаляем загруженный файл в случае ошибки
            if (file_exists($destination)) {
                unlink($destination);
                debug_log("Файл удален из-за ошибки БД");
            }
            throw new Exception("Ошибка обновления базы данных");
        }
        
        debug_log("База данных успешно обновлена");
        $_SESSION['avatar_updated'] = time();
        $_SESSION['success'] = 'Аватар успешно обновлен!';
        
        // Добавляем информацию о расположении файла для отладки
        debug_log("URL файла для браузера: /".$db_path);
        debug_log("Путь к файлу на сервере: ".$destination);
    }
    
    // Обновление других данных профиля
    if (!empty($_POST['username']) || !empty($_POST['email'])) {
        debug_log("Обновление профиля");
        $updates = [];
        $params = [];
        
        if (!empty($_POST['username'])) {
            $updates[] = "username = ?";
            $params[] = $_POST['username'];
            debug_log("Обновление имени пользователя: ".$_POST['username']);
        }
        
        if (!empty($_POST['email'])) {
            $updates[] = "email = ?";
            $params[] = $_POST['email'];
            debug_log("Обновление email: ".$_POST['email']);
        }
        
        $params[] = $_SESSION['user_id'];
        $sql = "UPDATE users SET ".implode(", ", $updates)." WHERE id = ?";
        
        debug_log("SQL запрос: ".$sql);
        $stmt = $pdo->prepare($sql);
        $result = $stmt->execute($params);
        
        if (!$result) {
            debug_log("Ошибка БД при обновлении профиля: ".implode(", ", $stmt->errorInfo()));
            throw new Exception("Ошибка обновления профиля");
        }
        
        debug_log("Профиль успешно обновлен");
        $_SESSION['success'] = 'Данные обновлены!';
    }
    
    debug_log("=== Обработка запроса завершена успешно ===");
    
    // Перенаправление на страницу профиля
    header('Location: profile.php');
    exit;
    
} catch (Exception $e) {
    debug_log("ОШИБКА: ".$e->getMessage());
    $_SESSION['error'] = $e->getMessage();
    header('Location: profile.php');
    exit;
}