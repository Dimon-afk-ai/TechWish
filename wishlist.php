<?php 
session_start();
require_once __DIR__.'/boot.php';

// Подключаем необходимые файлы
include ('models/header.php');
include ('controllers/WishlistController.php');

// Инициализируем контроллер и вызываем метод getAll
$wishlistController = new WishlistController();
$wishlistController->getAll($pdo, $avatar);
?>