
<?php
session_start();
require_once __DIR__.'/boot.php';

// Инициализация переменной $error

include ('models/header.php');
include ('controllers/tv.php');

$computers = new TVMediaController();
$computers->getAll($pdo,$avatar);





// Подключаем HTML-шаблон



?>