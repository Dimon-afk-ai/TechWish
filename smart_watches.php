<?php
session_start();
require_once __DIR__.'/boot.php';

// Инициализация переменной $error

include ('models/header.php');
include ('controllers/smart_watches.php');

$audio = new SmartWatchesController();
$audio->getAll($pdo,$avatar);





// Подключаем HTML-шаблон



?>