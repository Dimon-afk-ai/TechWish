<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "warhammer_db";  // База данных

// Подключение к базе данных
$conn = new mysqli($servername, $username, $password, $dbname);

// Проверка соединения
if ($conn->connect_error) {
    die("Помилка підключення: " . $conn->connect_error);
}

// Получаем данные из формы
$name = $_POST['user'];
$nickname = $_POST['nickname'];
$email = $_POST['email'];
$choice = $_POST['choice'];

// SQL-запрос на вставку (убедитесь, что таблица называется `users`, а не `warhammer_db`)
$sql = "INSERT INTO users (name, nickname, email, choice) VALUES ('$name', '$nickname', '$email', '$choice')";

if ($conn->query($sql) === TRUE) {
    header("Location: thank_you.php");
    exit();
} else {
    echo "Помилка: " . $conn->error;
}

$conn->close();
?>