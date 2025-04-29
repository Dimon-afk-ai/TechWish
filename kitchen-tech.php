
<?php
session_start();
require_once __DIR__.'/boot.php';

// Проверяем, является ли запрос AJAX-запросом
$isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) 
    && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';

// Инициализация переменных со значениями по умолчанию
$error = '';
$products = []; // Инициализация массива товаров
$total_products = 0; // Инициализация общего количества товаров

// Устанавливаем аватар по умолчанию
$avatar = 'default-avatar.jpg';
if (isset($_SESSION['user_id'])) {
    try {
        $stmt = $pdo->prepare("SELECT username, avatar FROM users WHERE id = ?");
        $stmt->execute([$_SESSION['user_id']]);
        $user = $stmt->fetch();
        
        if ($user) {
            $_SESSION['username'] = $user['username'];
            $_SESSION['user_nickname'] = $user['username'];
            $avatar = !empty($user['avatar']) ? $user['avatar'] : 'default-avatar.jpg';
        }
    } catch (PDOException $e) {
        $error = "Ошибка получения данных пользователя: " . $e->getMessage();
        error_log($error);
    }
}

// Получаем параметры фильтрации (из GET или POST в зависимости от типа запроса)
$requestData = $isAjax ? $_POST : $_GET;

$categories = isset($requestData['category']) ? (array)$requestData['category'] : [];
$price_min = isset($requestData['price_min']) && $requestData['price_min'] !== '' ? floatval($requestData['price_min']) : null;
$price_max = isset($requestData['price_max']) && $requestData['price_max'] !== '' ? floatval($requestData['price_max']) : null;
$brands = isset($requestData['brand']) ? (array)$requestData['brand'] : [];
$types = isset($requestData['type']) ? (array)$requestData['type'] : [];
$sort = isset($requestData['sort']) ? $requestData['sort'] : 'newest';
$page = isset($requestData['page']) ? max(1, intval($requestData['page'])) : 1;
$per_page = 12;

// Категории и фильтры
$allCategories = ['Холодильники', 'Плити', 'Посудомийки', 'Мікрохвильовки', 'Кавоварки', 'Блендери', 'Мультиварки', 'Мясорубки'];
$allBrands = ['Samsung', 'Bosch', 'Philips', 'Electrolux', 'Moulinex', 'Tefal', 'Gorenje', 'Zanussi'];
$allTypes = ['Вбудована', 'Окремостояча', 'Компактна', 'Професійна'];

// Проверка наличия таблицы и данных
try {
    // Проверяем существование таблицы kitchen_appliances
    $tableExists = false;
    $checkTable = $pdo->query("SHOW TABLES");
    while($row = $checkTable->fetch(PDO::FETCH_NUM)) {
        if($row[0] === 'kitchen_appliances') {
            $tableExists = true;
            break;
        }
    }
    
    if (!$tableExists) {
        $error = "Таблица 'kitchen_appliances' не существует в базе данных";
        error_log($error);
    } else {
        // Строим SQL запрос для товаров
        $sql = "SELECT * FROM kitchen_appliances WHERE 1=1";
        $params = [];
        
        // Добавляем условия фильтрации
        if (!empty($categories)) {
            $placeholders = implode(',', array_fill(0, count($categories), '?'));
            $sql .= " AND category IN ($placeholders)";
            $params = array_merge($params, $categories);
        }
        
        if ($price_min !== null) {
            $sql .= " AND price >= ?";
            $params[] = $price_min;
        }
        
        if ($price_max !== null) {
            $sql .= " AND price <= ?";
            $params[] = $price_max;
        }
        
        if (!empty($brands)) {
            $placeholders = implode(',', array_fill(0, count($brands), '?'));
            $sql .= " AND brand IN ($placeholders)";
            $params = array_merge($params, $brands);
        }
        
        if (!empty($types)) {
            $placeholders = implode(',', array_fill(0, count($types), '?'));
            $sql .= " AND type IN ($placeholders)";
            $params = array_merge($params, $types);
        }
        
        // Получаем общее количество товаров (для пагинации)
        $count_sql = str_replace("SELECT *", "SELECT COUNT(*)", $sql);
        // Удаляем ORDER BY если он есть в запросе для подсчета
        $count_sql = preg_replace('/ORDER BY.*/i', '', $count_sql);
        
        $count_stmt = $pdo->prepare($count_sql);
        $count_stmt->execute($params);
        $total_products = $count_stmt->fetchColumn();
        
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
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Если товаров нет, записываем информационное сообщение
        if (empty($products) && $total_products == 0) {
            $error = "В таблице 'kitchen_appliances' нет товаров или они не соответствуют фильтрам";
            error_log($error);
        }
    }
} catch (PDOException $e) {
    $error = "Ошибка базы данных: " . $e->getMessage();
    error_log($error);
}

// Если это AJAX-запрос, возвращаем только данные в формате JSON
if ($isAjax) {
    // Формируем HTML для товаров
    ob_start();
    
    if (empty($products)) {
        ?>
        <div class="no-results">
            <i class="fas fa-search"></i>
            <h3>Нічого не знайдено</h3>
            <p>Спробуйте змінити параметри фільтрації</p>
            <button id="resetFilters" class="btn btn-primary" style="margin-top: 20px;">
                Показати всі товари
            </button>
        </div>
        <?php
    } else {
        foreach ($products as $product) {
            ?>
            <div class="product-card">
                <div class="product-image-container">
                    <img src="<?php echo htmlspecialchars($product['image_url']); ?>" 
                         alt="<?php echo htmlspecialchars($product['name']); ?>" 
                         class="product-image">
                </div>
                <div class="product-info">
                    <div class="product-brand"><?php echo htmlspecialchars($product['brand']); ?></div>
                    <h3 class="product-name"><?php echo htmlspecialchars($product['name']); ?></h3>
                    
                    <div class="product-specs">
                        <span class="product-spec">
                            <i class="fas fa-box"></i> <?php echo htmlspecialchars($product['category']); ?>
                        </span>
                        
                        <?php if (!empty($product['type'])): ?>
                        <span class="product-spec">
                            <i class="fas fa-cube"></i> <?php echo htmlspecialchars($product['type']); ?>
                        </span>
                        <?php endif; ?>
                        
                        <?php if (!empty($product['features'])): ?>
                        <span class="product-spec">
                            <i class="fas fa-star"></i> <?php echo htmlspecialchars($product['features']); ?>
                        </span>
                        <?php endif; ?>
                    </div>
                    
                    <div class="product-price"><?php echo number_format($product['price'], 0, '', ' '); ?> ₴</div>
                    <div class="product-actions">
                        <a href="wishlist.php?action=add&id=<?php echo $product['id']; ?>" class="btn btn-outline wishlist-btn">
                            <i class="far fa-heart"></i>
                        </a>
                        <a href="cart.php?action=add&id=<?php echo $product['id']; ?>" class="btn btn-primary cart-btn">
                            <i class="fas fa-cart-plus"></i> Купити
                        </a>
                    </div>
                </div>
            </div>
            <?php
        }
    }
    
    $products_html = ob_get_clean();
    
    // Формируем HTML для пагинации
    ob_start();
    
    if ($total_products > $per_page) {
        $total_pages = ceil($total_products / $per_page);
        $start = max(1, $page - 2);
        $end = min($start + 4, $total_pages);
        
        if ($page > 1) {
            echo '<a href="kitchen.php?page=1" data-page="1">&laquo;&laquo;</a>';
            echo '<a href="kitchen.php?page=' . ($page - 1) . '" data-page="' . ($page - 1) . '">&laquo;</a>';
        }
        
        for ($i = $start; $i <= $end; $i++) {
            $active = $i == $page ? 'class="active"' : '';
            echo '<a href="kitchen.php?page=' . $i . '" ' . $active . ' data-page="' . $i . '">' . $i . '</a>';
        }
        
        if ($page < $total_pages) {
            echo '<a href="kitchen.php?page=' . ($page + 1) . '" data-page="' . ($page + 1) . '">&raquo;</a>';
            echo '<a href="kitchen.php?page=' . $total_pages . '" data-page="' . $total_pages . '">&raquo;&raquo;</a>';
        }
    }
    
    $pagination_html = ob_get_clean();
    
    // Формируем JSON-ответ
    $response = [
        'products_html' => $products_html,
        'pagination_html' => $pagination_html,
        'total_products' => $total_products,
        'page' => $page,
        'total_pages' => ceil($total_products / $per_page)
    ];
    
    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}

// Если это обычный запрос, продолжаем с отображением HTML-страницы
$templateData = [
    'avatar' => $avatar,
    'products' => $products,
    'error' => $error,
    'categories' => $allCategories,
    'brands' => $allBrands,
    'types' => $allTypes,
    'price_min' => $price_min,
    'price_max' => $price_max,
    'sort' => $sort,
    'selectedCategories' => $categories,
    'selectedBrands' => $brands,
    'selectedTypes' => $types,
    'total_products' => $total_products,
    'per_page' => $per_page,
    'page' => $page
];

extract($templateData);

// Выводим отладочную информацию, если есть ошибка
if (!empty($error) && $_SERVER['REMOTE_ADDR'] == '127.0.0.1') {
    echo '<pre style="background-color: #ffebee; color: #c62828; padding: 15px; margin: 20px; border-radius: 5px;">';
    echo "Ошибка: " . htmlspecialchars($error) . "\n";
    echo "SQL: " . htmlspecialchars($sql ?? 'Нет SQL-запроса') . "\n";
    echo "Параметры: " . print_r($params ?? [], true) . "\n";
    echo '</pre>';
}
?>
<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Кухонна техніка | TechWish</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #2196F3;
            --primary-dark: #1976D2;
            --primary-light: #BBDEFB;
            --secondary: #E3F2FD;
            --accent: #64B5F6;
            --light-bg: #f8f9fa;
            --dark-text: #0D47A1;
            --light-text: #E3F2FD;
            --card-shadow: 0 4px 12px rgba(33, 150, 243, 0.1);
        }
        
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: var(--light-bg);
            color: #333;
            line-height: 1.6;
        }
        
        header {
            background: white;
            padding: 15px 0;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            position: sticky;
            top: 0;
            z-index: 100;
        }
        
        nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            width: 90%;
            max-width: 1200px;
            margin: 0 auto;
        }
        
        .logo {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 24px;
            font-weight: bold;
            color: var(--primary);
            text-decoration: none;
        }
        
        .logo-icon {
            color: #ff0000; /* Червоний колір */
        }
        
        .main-container {
            display: flex;
            width: 90%;
            max-width: 1200px;
            margin: 30px auto;
            gap: 30px;
        }
        
        .filters-column {
            width: 280px;
            flex-shrink: 0;
            position: sticky;
            top: 80px;
            align-self: flex-start;
            height: calc(100vh - 110px);
            overflow-y: auto;
        }
        
        .products-column {
            flex-grow: 1;
        }
        
        .page-title {
            color: var(--primary);
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 28px;
        }
        
        .filters-container {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: var(--card-shadow);
        }
        
        .filter-section {
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 1px solid #eee;
        }
        
        .filter-section:last-child {
            border-bottom: none;
        }
        
        .filter-section h3 {
            margin-bottom: 15px;
            color: var(--primary);
            font-size: 16px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .filter-options {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }
        
        .filter-checkbox {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 14px;
            cursor: pointer;
        }
        
        .filter-checkbox input {
            cursor: pointer;
        }
        
        .price-range {
            display: flex;
            gap: 10px;
        }
        
        .price-range input {
            width: 100%;
            padding: 8px 12px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 14px;
        }
        
        .sorting {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        
        .results-count {
            font-size: 14px;
            color: #666;
        }
        
        .sort-select {
            padding: 8px 12px;
            border: 1px solid #ddd;
            border-radius: 6px;
            background: white;
            font-size: 14px;
            cursor: pointer;
        }
        
        .products-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 25px;
        }
        
        .product-card {
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: var(--card-shadow);
            transition: transform 0.3s, box-shadow 0.3s;
            display: flex;
            flex-direction: column;
        }
        
        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(33, 150, 243, 0.15);
        }
        
        .product-image-container {
            position: relative;
            padding-top: 100%;
            background: #f9f9f9;
        }
        
        .product-image {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: contain;
            padding: 20px;
        }
        
        .product-info {
            padding: 20px;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
        }
        
        .product-brand {
            color: #666;
            font-size: 14px;
            margin-bottom: 5px;
        }
        
        .product-name {
            font-weight: bold;
            margin-bottom: 10px;
            font-size: 16px;
            flex-grow: 1;
        }
        
        .product-specs {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-bottom: 15px;
            font-size: 13px;
        }
        
        .product-spec {
            display: flex;
            align-items: center;
            gap: 5px;
            background: #f5f5f5;
            padding: 4px 10px;
            border-radius: 12px;
        }
        
        .product-price {
            color: var(--primary);
            font-weight: bold;
            font-size: 20px;
            margin: 10px 0 15px;
        }
        
        .product-actions {
            display: flex;
            gap: 10px;
            margin-top: auto;
        }
        
        .btn {
            padding: 10px 15px;
            border-radius: 6px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s;
            flex: 1;
            text-align: center;
            text-decoration: none;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 5px;
            font-size: 14px;
        }
        
        .btn-primary {
            background: var(--primary);
            color: white;
            border: none;
        }
        
        .btn-primary:hover {
            background: var(--primary-dark);
        }
        
        .btn-outline {
            background: white;
            color: var(--primary);
            border: 1px solid var(--primary);
        }
        
        .btn-outline:hover {
            background: var(--secondary);
        }
        
        .pagination {
            display: flex;
            justify-content: center;
            gap: 8px;
            margin-top: 40px;
            flex-wrap: wrap;
        }
        
        .pagination a {
            padding: 8px 15px;
            border-radius: 6px;
            background: white;
            color: var(--primary);
            text-decoration: none;
            border: 1px solid #ddd;
            font-size: 14px;
            min-width: 40px;
            text-align: center;
        }
        
        .pagination a.active {
            background: var(--primary);
            color: white;
            border-color: var(--primary);
        }
        
        footer {
            background: var(--dark-text);
            color: white;
            padding: 40px 0 20px;
            margin-top: 50px;
        }
        
        .footer-content {
            width: 90%;
            max-width: 1200px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 30px;
        }
        
        .footer-section h3 {
            color: var(--light-text);
            margin-bottom: 15px;
            font-size: 18px;
        }
        
        .footer-section a {
            color: #BBDEFB;
            display: block;
            margin-bottom: 10px;
            text-decoration: none;
            font-size: 14px;
            transition: color 0.3s;
        }
        
        .footer-section a:hover {
            color: white;
        }
        
        .no-results {
            text-align: center;
            grid-column: 1 / -1;
            padding: 60px 20px;
            color: #666;
        }
        
        .no-results i {
            font-size: 48px;
            margin-bottom: 20px;
            color: #ccc;
        }
        
        .no-results h3 {
            margin-bottom: 10px;
            color: #444;
        }
        
        .menu {
            position: relative;
            display: inline-block;
        }
        
        .dropdown {
            display: none;
            position: absolute;
            right: 0;
            top: 100%;
            background: white;
            min-width: 200px;
            box-shadow: 0 8px 16px rgba(0,0,0,0.1);
            border-radius: 8px;
            z-index: 1000;
            overflow: hidden;
        }
        
        .menu:hover .dropdown {
            display: block;
        }
        
        .dropdown a {
            display: flex;
            align-items: center;
            padding: 12px 16px;
            color: #333;
            text-decoration: none;
            transition: all 0.3s;
            gap: 10px;
            font-size: 14px;
        }
        
        .dropdown a:hover {
            background: var(--secondary);
            color: var(--primary);
        }
        
        .dropdown a i {
            width: 20px;
            text-align: center;
        }
        
        .user-profile {
            display: flex;
            align-items: center;
            gap: 10px;
            cursor: pointer;
            padding: 8px 12px;
            border-radius: 20px;
            transition: all 0.3s;
        }
        
        .user-profile:hover {
            background: rgba(33, 150, 243, 0.1);
        }
        
        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid var(--secondary);
        }
        
        .auth-btn {
            padding: 8px 16px;
            border-radius: 20px;
            text-decoration: none;
            color: var(--primary);
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s;
            font-size: 14px;
        }
        
        .auth-btn:hover {
            background: rgba(33, 150, 243, 0.1);
        }
        
        .filter-btn {
            display: none;
            padding: 10px 15px;
            background: var(--primary);
            color: white;
            border: none;
            border-radius: 6px;
            margin-bottom: 20px;
            cursor: pointer;
            width: 100%;
            justify-content: center;
            gap: 8px;
        }
        
        .filter-reset {
            display: block;
            text-align: center;
            margin-top: 15px;
            color: var(--primary);
            font-size: 14px;
        }
        
        .social-links {
            display: flex;
            gap: 15px;
            margin-top: 20px;
        }
        
        .social-links a {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: rgba(255,255,255,0.1);
            color: white;
            transition: all 0.3s;
        }
        
        .social-links a:hover {
            background: var(--light-text);
            color: var(--dark-text);
        }
        
        .copyright {
            text-align: center;
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid rgba(255,255,255,0.1);
            font-size: 13px;
            color: #BBDEFB;
        }
        
        @media (max-width: 992px) {
            .main-container {
                flex-direction: column;
            }
            
            .filters-column {
                width: 100%;
                position: static;
                height: auto;
                margin-bottom: 20px;
            }
            
            .filter-btn {
                display: flex;
            }
            
            .filters-container {
                display: none;
            }
            
            .filters-container.visible {
                display: block;
            }
            
            .sorting {
                flex-direction: column;
                gap: 15px;
                align-items: flex-start;
            }
        }
        
        @media (max-width: 576px) {
            .products-grid {
                grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
            }
            
            .price-range {
                flex-direction: column;
            }
            
            nav {
                flex-wrap: wrap;
                gap: 15px;
            }
            
            .logo {
                order: 1;
            }
            
            .auth-buttons {
                order: 3;
                width: 100%;
                justify-content: center;
            }
            
            .search {
                order: 2;
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <header>
        <nav>
            <a href="index.php" class="logo">
                <i class="fas fa-heart logo-icon"></i>
                <span>TechWish</span>
            </a>
            
            <div class="auth-buttons">
                <?php if(isset($_SESSION['user_id'])): ?>
                    <div class="menu">
                        <div class="user-profile">
                            <img src="<?php echo $avatar; ?>" alt="Аватар" class="user-avatar">                             
                            <span><?php echo htmlspecialchars($_SESSION['username']); ?></span>
                        </div>
                        <div class="dropdown profile-dropdown">
                            <a href="profile.php">
                                <i class="fas fa-user-circle"></i>
                                <span>Профіль</span>
                            </a>
                            <a href="wishlist.php">
                                <i class="fas fa-heart"></i>
                                <span>Вишлист</span>
                            </a>
                            <a href="orders.php">
                                <i class="fas fa-box-open"></i>
                                <span>Замовлення</span>
                            </a>
                            <a href="logout.php">
                                <i class="fas fa-sign-out-alt"></i>
                                <span>Вийти</span>
                            </a>
                        </div>
                    </div>
                <?php else: ?>
                    <a href="login.php" class="auth-btn">
                        <i class="fas fa-sign-in-alt"></i>
                        <span>Увійти</span>
                    </a>
                    <a href="register.php" class="auth-btn">
                        <i class="fas fa-user-plus"></i>
                        <span>Реєстрація</span>
                    </a>
                <?php endif; ?>
            </div>
        </nav>
    </header>

    <div class="main-container">
        <div class="filters-column">
            <button class="filter-btn" id="filterToggle">
                <i class="fas fa-filter"></i> Фільтри
            </button>
            
            <form method="get" action="kitchen.php" class="filters-container" id="filtersForm">
                <div class="filter-section">
                    <h3><i class="fas fa-list"></i> Категорії</h3>
                    <div class="filter-options">
                        <?php foreach ($allCategories as $cat): ?>
                            <label class="filter-checkbox">
                                <input type="checkbox" name="category[]" value="<?php echo $cat; ?>" 
                                    <?php echo in_array($cat, $selectedCategories) ? 'checked' : ''; ?>>
                                <?php echo $cat; ?>
                            </label>
                        <?php endforeach; ?>
                    </div>
                </div>
                
                <div class="filter-section">
                    <h3><i class="fas fa-tag"></i> Ціна</h3>
                    <div class="price-range">
                        <input type="number" name="price_min" placeholder="Від" 
                               value="<?php echo $price_min !== null ? $price_min : ''; ?>">
                        <input type="number" name="price_max" placeholder="До" 
                               value="<?php echo $price_max !== null ? $price_max : ''; ?>">
                    </div>
                </div>
                
                <div class="filter-section">
                    <h3><i class="fas fa-industry"></i> Бренди</h3>
                    <div class="filter-options">
                        <?php foreach ($allBrands as $brand): ?>
                            <label class="filter-checkbox">
                                <input type="checkbox" name="brand[]" value="<?php echo $brand; ?>" 
                                    <?php echo in_array($brand, $selectedBrands) ? 'checked' : ''; ?>>
                                <?php echo $brand; ?>
                            </label>
                        <?php endforeach; ?>
                    </div>
                </div>
                
                <div class="filter-section">
                    <h3><i class="fas fa-cube"></i> Тип</h3>
                    <div class="filter-options">
                        <?php foreach ($allTypes as $type): ?>
                            <label class="filter-checkbox">
                                <input type="checkbox" name="type[]" value="<?php echo $type; ?>" 
                                    <?php echo in_array($type, $selectedTypes) ? 'checked' : ''; ?>>
                                <?php echo $type; ?>
                            </label>
                        <?php endforeach; ?>
                    </div>
                </div>
                
                <div class="filter-section">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-filter"></i> Застосувати фільтри
                    </button>
                    <a href="kitchen.php" class="filter-reset">
                        <i class="fas fa-times"></i> Скинути всі фільтри
                    </a>
                </div>
            </form>
        </div>
        
        <div class="products-column">
            <h1 class="page-title">
                <i class="fas fa-blender"></i>
                <span>Кухонна техніка</span>
            </h1>
            
            <div class="sorting">
                <div class="results-count">
                    Знайдено товарів: <?php echo $total_products; ?>
                </div>
                <select name="sort" class="sort-select" onchange="window.location.href=this.value">
                    <option value="kitchen.php?<?php echo http_build_query(array_merge($_GET, ['sort' => 'newest'])); ?>" 
                        <?php echo $sort === 'newest' ? 'selected' : ''; ?>>Новітні</option>
                    <option value="kitchen.php?<?php echo http_build_query(array_merge($_GET, ['sort' => 'price_asc'])); ?>" 
                        <?php echo $sort === 'price_asc' ? 'selected' : ''; ?>>Від дешевих</option>
                    <option value="kitchen.php?<?php echo http_build_query(array_merge($_GET, ['sort' => 'price_desc'])); ?>" 
                        <?php echo $sort === 'price_desc' ? 'selected' : ''; ?>>Від дорогих</option>
                    <option value="kitchen.php?<?php echo http_build_query(array_merge($_GET, ['sort' => 'popular'])); ?>" 
                        <?php echo $sort === 'popular' ? 'selected' : ''; ?>>Популярні</option>
                </select>
            </div>
            
            <div class="products-grid">
                <?php if (empty($products)): ?>
                    <div class="no-results">
                        <i class="fas fa-search"></i>
                        <h3>Нічого не знайдено</h3>
                        <p>Спробуйте змінити параметри фільтрації</p>
                        <a href="kitchen.php" class="btn btn-primary" style="margin-top: 20px;">
                            Показати всі товари
                        </a>
                    </div>
                <?php else: ?>
                    <?php foreach ($products as $product): ?>
                        <div class="product-card">
                            <div class="product-image-container">
                                <img src="<?php echo htmlspecialchars($product['image_url']); ?>" 
                                     alt="<?php echo htmlspecialchars($product['name']); ?>" 
                                     class="product-image">
                            </div>
                            <div class="product-info">
                                <div class="product-brand"><?php echo htmlspecialchars($product['brand']); ?></div>
                                <h3 class="product-name"><?php echo htmlspecialchars($product['name']); ?></h3>
                                
                                <div class="product-specs">
                                    <span class="product-spec">
                                        <i class="fas fa-box"></i> <?php echo htmlspecialchars($product['category']); ?>
                                    </span>
                                    
                                    <?php if (!empty($product['type'])): ?>
                                    <span class="product-spec">
                                        <i class="fas fa-cube"></i> <?php echo htmlspecialchars($product['type']); ?>
                                    </span>
                                    <?php endif; ?>
                                    
                                    <?php if (!empty($product['features'])): ?>
                                    <span class="product-spec">
                                        <i class="fas fa-star"></i> <?php echo htmlspecialchars($product['features']); ?>
                                    </span>
                                    <?php endif; ?>
                                </div>
                                
                                <div class="product-price"><?php echo number_format($product['price'], 0, '', ' '); ?> ₴</div>
                                <div class="product-actions">
                                    <a href="wishlist.php?action=add&id=<?php echo $product['id']; ?>" class="btn btn-outline">
                                        <i class="far fa-heart"></i>
                                    </a>
                                    <a href="cart.php?action=add&id=<?php echo $product['id']; ?>" class="btn btn-primary">
                                        <i class="fas fa-cart-plus"></i> Купити
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
            
            <?php if ($total_products > $per_page): ?>
            <div class="pagination">
                <?php if ($page > 1): ?>
                    <a href="<?php echo 'kitchen.php?' . http_build_query(array_merge($_GET, ['page' => 1])); ?>">
                        &laquo;&laquo;
                    </a>
                    <a href="<?php echo 'kitchen.php?' . http_build_query(array_merge($_GET, ['page' => $page - 1])); ?>">
                        &laquo;
                    </a>
                <?php endif; ?>
                
                <?php 
                $start = max(1, $page - 2);
                $end = min($start + 4, ceil($total_products / $per_page));
                
                for ($i = $start; $i <= $end; $i++): ?>
                    <a href="<?php echo 'kitchen.php?' . http_build_query(array_merge($_GET, ['page' => $i])); ?>" 
                       <?php echo $i == $page ? 'class="active"' : ''; ?>>
                        <?php echo $i; ?>
                    </a>
                <?php endfor; ?>
                
                <?php if ($page < ceil($total_products / $per_page)): ?>
                    <a href="<?php echo 'kitchen.php?' . http_build_query(array_merge($_GET, ['page' => $page + 1])); ?>">
                        &raquo;
                    </a>
                    <a href="<?php echo 'kitchen.php?' . http_build_query(array_merge($_GET, ['page' => ceil($total_products / $per_page)])); ?>">
                        &raquo;&raquo;
                    </a>
                <?php endif; ?>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <footer>
        <div class="footer-content">
            <div class="footer-section">
                <h3>Каталог</h3>
                <a href="kitchen.php">Вся техніка</a>
                <a href="kitchen.php?category=Холодильники">Холодильники</a>
                <a href="kitchen.php?category=Плити">Плити</a>
                <a href="kitchen.php?category=Посудомийки">Посудомийки</a>
                <a href="kitchen.php?category=Мікрохвильовки">Мікрохвильовки</a>
            </div>
            
            <div class="footer-section">
                <h3>Клієнтам</h3>
                <a href="#">Доставка і оплата</a>
                <a href="#">Гарантія</a>
                <a href="#">Повернення</a>
                <a href="#">Сервісні центри</a>
                <a href="#">Кредит</a>
            </div>
            
            <div class="footer-section">
                <h3>Компанія</h3>
                <a href="#">Про нас</a>
                <a href="#">Контакти</a>
                <a href="#">Вакансії</a>
                <a href="#">Блог</a>
                <a href="#">Партнерам</a>
            </div>
            
            <div class="footer-section">
                <h3>Контакти</h3>
                <a href="#"><i class="fas fa-phone"></i> 0 800 123 456</a>
                <a href="#"><i class="fas fa-envelope"></i> info@techwish.ua</a>
                <a href="#"><i class="fas fa-map-marker-alt"></i> Київ, вул. Технічна 15</a>
                
                <div class="social-links">
                    <a href="#"><i class="fab fa-facebook-f"></i></a>
                    <a href="#"><i class="fab fa-instagram"></i></a>
                    <a href="#"><i class="fab fa-telegram"></i></a>
                    <a href="#"><i class="fab fa-youtube"></i></a>
                </div>
            </div>
        </div>
        
        <div class="copyright">
            &copy; 2023 TechWish. Всі права захищені.
        </div>
    </footer>

    <script>
        // Toggle filters on mobile
        document.getElementById('filterToggle').addEventListener('click', function() {
            document.getElementById('filtersForm').classList.toggle('visible');
        });
        
        // Validate price range
        document.querySelector('form').addEventListener('submit', function(e) {
            const priceMin = document.querySelector('input[name="price_min"]');
            const priceMax = document.querySelector('input[name="price_max"]');
            
            if (priceMin.value && priceMax.value && parseFloat(priceMin.value) > parseFloat(priceMax.value)) {
                alert('Максимальна ціна повинна бути більше мінімальної');
                e.preventDefault();
            }
        });
        
        // Preserve filters when sorting
        document.querySelector('.sort-select').addEventListener('change', function() {
            const url = new URL(this.value);
            const params = new URLSearchParams(window.location.search);
            
            // Preserve all current filters
            params.forEach((value, key) => {
                if (key !== 'sort' && key !== 'page') {
                    if (Array.isArray(value)) {
                        value.forEach(v => url.searchParams.append(key, v));
                    } else {
                        url.searchParams.append(key, value);
                    }
                }
            });
            
            window.location.href = url.toString();
        });
    </script>
</body>
</html>