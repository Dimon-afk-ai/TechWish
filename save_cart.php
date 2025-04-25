<?php
session_start();

// Получаем JSON-данные из запроса
$data = file_get_contents("php://input");

// Преобразуем JSON в массив PHP
$cart = json_decode($data, true);

if ($cart) {
    $_SESSION['cart'] = $cart; // Сохраняем корзину в сессии
    echo json_encode(["status" => "success"]);
} else {
    echo json_encode(["status" => "error", "message" => "Немає даних"]);
}
?>