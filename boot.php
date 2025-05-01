<?php
// Старт сессии (один раз)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Функция подключения к БД
function pdo(): PDO
{
    static $pdo;

    if (!$pdo) {
        // Проверяем существование файла конфигурации
        $config_file = __DIR__.'/config.php';
        if (!file_exists($config_file)) {
            die("Файл конфигурации не найден: $config_file");
        }

        $config = include $config_file;
        
        // Проверяем структуру конфигурации
        if (!is_array($config) || 
            !isset($config['db_host'], $config['db_name'], $config['db_user'], $config['db_pass'])) {
            die("Неверная структура конфигурационного файла");
        }

        try {
            $dsn = 'mysql:host='.$config['db_host'].';dbname='.$config['db_name'].';charset=utf8mb4';
            $pdo = new PDO($dsn, $config['db_user'], $config['db_pass'], [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false
            ]);
        } catch (PDOException $e) {
            die("Ошибка подключения к базе данных: " . $e->getMessage());
        }
    }

    return $pdo;
}


// Функция для вывода flash-сообщений
function flash(?string $message = null)
{
    if ($message !== null) {
        $_SESSION['flash'] = $message;
    } else {
        if (!empty($_SESSION['flash'])) {
            echo "<div class='alert alert-danger mb-3'>".htmlspecialchars($_SESSION['flash'])."</div>";
            unset($_SESSION['flash']);
        }
    }
}

// Проверка авторизации
function check_auth(): bool
{
    return !empty($_SESSION['user_id']);
}

// Подключаемся к БД при загрузке файла
try {
    $pdo = pdo();
} catch (PDOException $e) {
    die("Ошибка подключения к базе данных: " . $e->getMessage());
}
?>
