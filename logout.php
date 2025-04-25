<?php
require_once __DIR__.'/boot.php';

// Уничтожаем все данные сессии
session_start();
session_destroy();
header("Location: index.php");
exit();
