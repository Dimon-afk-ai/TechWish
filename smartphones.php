<?php
session_start();
require_once __DIR__.'/boot.php';

// Инициализация переменной $error


include ('models/header.php');
include ('controllers/smartphone.php');

$smartphone = new SmartphoneController();
$smartphone->getAll($pdo,$avatar);





// Подключаем HTML-шаблон



?>
