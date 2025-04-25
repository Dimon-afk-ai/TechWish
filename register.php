<?php
// Включение отладки (должно быть в самом начале файла)
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__.'/php_errors.log');
error_reporting(E_ALL);

// Старт сессии
session_start();

// Конфигурация подключения
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "base";

// Логирование попытки подключения
file_put_contents(__DIR__.'/debug.log', "[".date('Y-m-d H:i:s')."] Attempting DB connection\n", FILE_APPEND);

// Создаем подключение
$conn = new mysqli($servername, $username, $password, $dbname);

// Проверка соединения
if ($conn->connect_error) {
    $error_msg = "Connection failed: " . $conn->connect_error;
    file_put_contents(__DIR__.'/debug.log', "[".date('Y-m-d H:i:s')."] $error_msg\n", FILE_APPEND);
    die($error_msg);
}

// Логирование успешного подключения
file_put_contents(__DIR__.'/debug.log', "[".date('Y-m-d H:i:s')."] DB connected successfully\n", FILE_APPEND);

// Обработка POST-запроса
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Проверяем, какая форма была отправлена
    if (isset($_POST['register'])) {
        // Логирование получения POST-данных
        file_put_contents(__DIR__.'/debug.log', "[".date('Y-m-d H:i:s')."] Received POST data: ".print_r($_POST, true)."\n", FILE_APPEND);
        
        // Получаем данные
        $username = trim($_POST['username'] ?? '');
        $raw_password = $_POST['password'] ?? '';
        
        // Валидация
        if (strlen($username) < 3) {
            $error = "Логин должен быть не менее 3 символов";
            file_put_contents(__DIR__.'/debug.log', "[".date('Y-m-d H:i:s')."] Validation error: $error\n", FILE_APPEND);
            $_SESSION['error'] = $error;
            header("Location: register.php");
            exit();
        }
        
        if (strlen($raw_password) < 6) {
            $error = "Пароль должен быть не менее 6 символов";
            file_put_contents(__DIR__.'/debug.log', "[".date('Y-m-d H:i:s')."] Validation error: $error\n", FILE_APPEND);
            $_SESSION['error'] = $error;
            header("Location: register.php");
            exit();
        }
        
        // Хеширование пароля
        $hashed_password = password_hash($raw_password, PASSWORD_BCRYPT);
        
        // Логирование перед выполнением запроса
        file_put_contents(__DIR__.'/debug.log', "[".date('Y-m-d H:i:s')."] Trying to register user: $username\n", FILE_APPEND);
        
        // Подготовленный запрос для безопасности
        $stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
        $stmt->bind_param("ss", $username, $hashed_password);
        
        if ($stmt->execute()) {
            // Успешная регистрация
            $_SESSION['user_id'] = $stmt->insert_id;
            $_SESSION['username'] = $username;
            
            // Логирование успеха
            file_put_contents(__DIR__.'/debug.log', "[".date('Y-m-d H:i:s')."] User registered successfully. ID: ".$stmt->insert_id."\n", FILE_APPEND);
            
            header("Location: index.php");
            exit();
        } else {
            // Обработка ошибки
            $error = "Ошибка регистрации: " . $conn->error;
            
            // Логирование ошибки
            file_put_contents(__DIR__.'/debug.log', "[".date('Y-m-d H:i:s')."] Registration failed: $error\n", FILE_APPEND);
            
            if ($conn->errno == 1062) {
                $_SESSION['error'] = "Пользователь с таким именем уже существует";
            } else {
                $_SESSION['error'] = $error;
            }
            header("Location: register.php");
            exit();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Регистрация в Империуме</title>
    <style>
        body {
            background: url('https://i.pinimg.com/736x/23/2f/b0/232fb0f2c9d89634b4bd81344f7c62de.jpg') no-repeat center center fixed;
            background-size: cover;
            font-family: 'Cinzel', serif;
            color: #FFD700;
            text-align: center;
        }
        .container {
            background: rgba(0, 0, 0, 0.8);
            padding: 20px;
            border: 2px solid #FFD700;
            width: 320px;
            margin: auto;
            margin-top: 100px;
            border-radius: 10px;
            box-shadow: 0px 0px 20px rgba(255, 215, 0, 0.5);
        }
        input {
            width: calc(100% - 22px);
            padding: 10px;
            margin: 10px 0;
            border: 2px solid #FFD700;
            background: #333;
            color: #FFD700;
            font-size: 16px;
            border-radius: 5px;
            text-align: center;
        }
        button {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 2px solid #FFD700;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            border-radius: 5px;
            background: #8B0000;
            color: white;
        }
        button:hover {
            background: #FFD700;
            color: black;
        }
        .error {
            color: red;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Регистрация в Империуме</h1>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="error">
                <?php echo $_SESSION['error']; ?>
                <?php unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="register.php">
            <input type="text" name="username" placeholder="Логин Акколита" required>
            <input type="password" name="password" placeholder="Код Генетического Наследия" required>
            <button type="submit" name="register">Регистрация</button>
        </form>

        <form method="GET" action="login.php">
            <button type="submit">Уже зарегистрированы? Войти</button>
        </form>
    </div>
</body>
</html>