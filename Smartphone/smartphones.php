<?php
session_start();
require_once __DIR__.'/../boot.php';

// Инициализация переменной $error
$error = '';

// Устанавливаем аватар по умолчанию
$avatar = 'default-avatar.jpg';
if (isset($_SESSION['user_id'])) {
    try {
        // Получаем актуальные данные из БД
        $stmt = $pdo->prepare("SELECT username, avatar FROM users WHERE id = ?");
        $stmt->execute([$_SESSION['user_id']]);
        $user = $stmt->fetch();
        
        if ($user) {
            // Обновляем сессионные переменные
            $_SESSION['username'] = $user['username'];
            $_SESSION['user_nickname'] = $user['username'];
            $avatar = !empty($user['avatar']) ? $user['avatar'] : 'default-avatar.jpg';
        }
    } catch (PDOException $e) {
        $error = "Ошибка получения данных пользователя: " . $e->getMessage();
        error_log($error);
    }
}

// Параметры фильтрации
$brands = isset($_GET['brand']) ? (array)$_GET['brand'] : [];
$price_min = isset($_GET['price_min']) ? floatval($_GET['price_min']) : null;
$price_max = isset($_GET['price_max']) ? floatval($_GET['price_max']) : null;
$rams = isset($_GET['ram']) ? array_map('intval', (array)$_GET['ram']) : [];
$storages = isset($_GET['storage']) ? array_map('intval', (array)$_GET['storage']) : [];
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'newest';
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$per_page = 10;

// Строим SQL запрос для товаров
$sql = "SELECT * FROM products WHERE 1=1";
$params = [];

// Добавляем условия фильтрации
if (!empty($brands)) {
    $placeholders = implode(',', array_fill(0, count($brands), '?'));
    $sql .= " AND brand IN ($placeholders)";
    $params = array_merge($params, $brands);
}

if ($price_min !== null) {
    $sql .= " AND price >= ?";
    $params[] = $price_min;
}

if ($price_max !== null) {
    $sql .= " AND price <= ?";
    $params[] = $price_max;
}

if (!empty($rams)) {
    $placeholders = implode(',', array_fill(0, count($rams), '?'));
    $sql .= " AND ram IN ($placeholders)";
    $params = array_merge($params, $rams);
}

if (!empty($storages)) {
    $placeholders = implode(',', array_fill(0, count($storages), '?'));
    $sql .= " AND storage IN ($placeholders)";
    $params = array_merge($params, $storages);
}

// Добавляем сортировку
switch ($sort) {
    case 'price_asc': $sql .= " ORDER BY price ASC"; break;
    case 'price_desc': $sql .= " ORDER BY price DESC"; break;
    case 'popular': $sql .= " ORDER BY views DESC"; break;
    default: $sql .= " ORDER BY created_at DESC"; // newest
}

// Добавляем пагинацию
$sql .= " LIMIT ? OFFSET ?";
$params[] = $per_page;
$params[] = ($page - 1) * $per_page;

// Выполняем запрос для получения товаров
try {
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Получаем общее количество товаров (для пагинации)
    $count_sql = "SELECT COUNT(*) FROM products WHERE 1=1";
    $count_params = [];
    
    // Те же условия фильтрации, что и для основного запроса
    if (!empty($brands)) {
        $placeholders = implode(',', array_fill(0, count($brands), '?'));
        $count_sql .= " AND brand IN ($placeholders)";
        $count_params = array_merge($count_params, $brands);
    }
    
    if ($price_min !== null) {
        $count_sql .= " AND price >= ?";
        $count_params[] = $price_min;
    }
    
    if ($price_max !== null) {
        $count_sql .= " AND price <= ?";
        $count_params[] = $price_max;
    }
    
    if (!empty($rams)) {
        $placeholders = implode(',', array_fill(0, count($rams), '?'));
        $count_sql .= " AND ram IN ($placeholders)";
        $count_params = array_merge($count_params, $rams);
    }
    
    if (!empty($storages)) {
        $placeholders = implode(',', array_fill(0, count($storages), '?'));
        $count_sql .= " AND storage IN ($placeholders)";
        $count_params = array_merge($count_params, $storages);
    }
    
    $count_stmt = $pdo->prepare($count_sql);
    $count_stmt->execute($count_params);
    $total_products = $count_stmt->fetchColumn();
    
} catch (PDOException $e) {
    $error = "Ошибка базы данных: " . $e->getMessage();
    error_log($error);
}

// Отладочная информация (можно удалить после тестирования)
if (empty($products)) {
    error_log("SQL запрос: " . $sql);
    error_log("Параметры: " . print_r($params, true));
    error_log("Всего товаров: " . $total_products);
}

$allBrands = ['Apple', 'Samsung', 'Xiaomi', 'Huawei', 'OnePlus', 'Realme', 'Oppo', 'Vivo', 'Google', 'Asus'];
$allRams = [4, 6, 8, 12, 16];
$allStorages = [64, 128, 256, 512, 1024];

// Подключаем HTML-шаблон
$templateData = [
    'avatar' => $avatar,
    'products' => $products,
    'error' => $error,
    'brands' => $allBrands,
    'rams' => $allRams,
    'storages' => $allStorages,
    'price_min' => $price_min,
    'price_max' => $price_max,
    'sort' => $sort,
    'selectedBrands' => $brands,
    'selectedRams' => $rams,
    'selectedStorages' => $storages
];

extract($templateData);
include 'smartphones.html';
?>
