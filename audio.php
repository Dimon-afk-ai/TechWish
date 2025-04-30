<?php
session_start();
require_once __DIR__.'/boot.php';

// Инициализация переменной $error

include ('models/header.php');
include ('controllers/audio.php');

$audio = new AudioController();
$audio->getAll($pdo,$avatar);





// Подключаем HTML-шаблон



?>