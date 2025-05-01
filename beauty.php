<?php
session_start();
require_once __DIR__.'/boot.php';

// Инициализация переменной $error

include ('models/header.php');
include ('controllers/beauty.php');

$audio = new BeautyHealthController();
$audio->getAll($pdo,$avatar);





// Подключаем HTML-шаблон



?>