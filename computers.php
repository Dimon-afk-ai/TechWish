
<?php
session_start();
require_once __DIR__.'/boot.php';

// Инициализация переменной $error

include ('models/header.php');
include ('controllers/computers.php');

$computers = new ComputersController();
$computers->getAll($pdo,$avatar);





// Подключаем HTML-шаблон



?>