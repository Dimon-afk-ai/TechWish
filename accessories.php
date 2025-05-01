<?php
session_start();
require_once __DIR__.'/boot.php';

// Инициализация переменной $error


include ('models/header.php');
include ('controllers/accessories.php');

$product = new AccessoriesControllerr();
$product->getAll($pdo,$avatar);
       
// Подключаем HTML-шаблон


?>
