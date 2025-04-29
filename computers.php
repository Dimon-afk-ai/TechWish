<?php
session_start();
require_once __DIR__.'/boot.php';

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
$types = isset($_GET['type']) ? (array)$_GET['type'] : [];
$screens = isset($_GET['screen']) ? array_map('floatval', (array)$_GET['screen']) : [];
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'newest';
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$per_page = 10;

// Строим SQL запрос для товаров
$sql = "SELECT * FROM products WHERE category IN ('notebook', 'tablet', 'bfp')";
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

if (!empty($types)) {
    $placeholders = implode(',', array_fill(0, count($types), '?'));
    $sql .= " AND type IN ($placeholders)";
    $params = array_merge($params, $types);
}

if (!empty($screens)) {
    $placeholders = implode(',', array_fill(0, count($screens), '?'));
    $sql .= " AND screen_size IN ($placeholders)";
    $params = array_merge($params, $screens);
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
    $count_sql = "SELECT COUNT(*) FROM products WHERE category IN ('notebook', 'tablet', 'bfp')";
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
    
    if (!empty($types)) {
        $placeholders = implode(',', array_fill(0, count($types), '?'));
        $count_sql .= " AND type IN ($placeholders)";
        $count_params = array_merge($count_params, $types);
    }
    
    if (!empty($screens)) {
        $placeholders = implode(',', array_fill(0, count($screens), '?'));
        $count_sql .= " AND screen_size IN ($placeholders)";
        $count_params = array_merge($count_params, $screens);
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

$allBrands = ['Apple', 'Samsung', 'Lenovo', 'HP', 'Dell', 'Asus', 'Acer', 'Microsoft', 'Huawei', 'Xiaomi'];
$allRams = [4, 8, 16, 32, 64];
$allStorages = [128, 256, 512, 1024, 2048];
$allTypes = ['Ноутбук', 'Планшет', 'БФП'];
$allScreens = [10.1, 11.6, 13.3, 14, 15.6, 17.3];

// Подключаем HTML-шаблон
$templateData = [
    'avatar' => $avatar,
    'products' => $products,
    'error' => $error,
    'brands' => $allBrands,
    'rams' => $allRams,
    'storages' => $allStorages,
    'types' => $allTypes,
    'screens' => $allScreens,
    'price_min' => $price_min,
    'price_max' => $price_max,
    'sort' => $sort,
    'selectedBrands' => $brands,
    'selectedRams' => $rams,
    'selectedStorages' => $storages,
    'selectedTypes' => $types,
    'selectedScreens' => $screens
];

extract($templateData);
?>
<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ноутбуки, Планшети, БФП | TechWish</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #0066cc;
            --secondary: #00ccff;
            --accent: #ff3366;
            --light-bg: #f5f7fa;
            --dark-text: #333;
            --card-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }
        
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: var(--light-bg);
            color: var(--dark-text);
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
            color: var(--accent);
        }
        
        .user-profile {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid var(--secondary);
        }
        
        .main-container {
            display: flex;
            width: 90%;
            max-width: 1200px;
            margin: 30px auto;
            gap: 30px;
        }
        
        .filters-column {
            width: 250px;
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
            margin-bottom: 30px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .filters-container {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: var(--card-shadow);
        }
        
        .filter-section {
            margin-bottom: 25px;
        }
        
        .filter-section h3 {
            margin-bottom: 10px;
            color: var(--primary);
            font-size: 16px;
        }
        
        .filter-options {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }
        
        .filter-checkbox {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 14px;
        }
        
        .price-range {
            display: flex;
            gap: 10px;
        }
        
        .price-range input {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
        }
        
        .sorting {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 20px;
        }
        
        .sort-select {
            padding: 8px 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            background: white;
            font-size: 14px;
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
        }
        
        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
        }
        
        .product-image {
            width: 100%;
            height: 200px;
            object-fit: contain;
            background: #f9f9f9;
            padding: 20px;
            border-bottom: 1px solid #eee;
        }
        
        .product-info {
            padding: 20px;
        }
        
        .product-brand {
            color: #666;
            font-size: 14px;
            margin-bottom: 5px;
        }
        
        .product-name {
            font-weight: bold;
            margin-bottom: 10px;
            font-size: 18px;
            height: 45px;
            overflow: hidden;
        }
        
        .product-specs {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-bottom: 10px;
            font-size: 14px;
            color: #666;
        }
        
        .product-spec {
            display: flex;
            align-items: center;
            gap: 5px;
            background: #f5f5f5;
            padding: 4px 8px;
            border-radius: 4px;
        }
        
        .product-type {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 4px;
            font-size: 12px;
            margin-bottom: 5px;
        }
        
        .type-notebook {
            background-color: #e3f2fd;
            color: #1565c0;
        }
        
        .type-tablet {
            background-color: #e8f5e9;
            color: #2e7d32;
        }
        
        .type-bfp {
            background-color: #f3e5f5;
            color: #7b1fa2;
        }
        
        .product-price {
            color: var(--primary);
            font-weight: bold;
            font-size: 20px;
            margin: 15px 0;
        }
        
        .product-actions {
            display: flex;
            gap: 10px;
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
            background: #0055aa;
        }
        
        .btn-outline {
            background: white;
            color: var(--primary);
            border: 1px solid var(--primary);
        }
        
        .btn-outline:hover {
            background: #f0f7ff;
        }
        
        .pagination {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin-top: 40px;
        }
        
        .pagination a {
            padding: 8px 15px;
            border-radius: 6px;
            background: white;
            color: var(--primary);
            text-decoration: none;
            border: 1px solid #ddd;
            font-size: 14px;
        }
        
        .pagination a.active {
            background: var(--primary);
            color: white;
            border-color: var(--primary);
        }
        
        footer {
            background: var(--dark-text);
            color: white;
            padding: 30px 0;
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
            color: var(--secondary);
            margin-bottom: 15px;
        }
        
        .footer-section a {
            color: #ccc;
            display: block;
            margin-bottom: 8px;
            text-decoration: none;
            font-size: 14px;
        }
        
        .footer-section a:hover {
            color: white;
        }
        
        .no-results {
            text-align: center;
            grid-column: 1 / -1;
            padding: 40px;
            color: #666;
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
            color: var(--dark-text);
            text-decoration: none;
            transition: all 0.3s;
            gap: 10px;
            font-size: 14px;
        }
        
        .dropdown a:hover {
            background: var(--light-bg);
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
            padding: 5px 10px;
            border-radius: 20px;
            transition: all 0.3s;
        }
        
        .user-profile:hover {
            background: rgba(0,0,0,0.05);
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
            background: rgba(0,102,204,0.1);
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
                display: block;
            }
            
            .filters-container {
                display: none;
            }
            
            .filters-container.visible {
                display: block;
            }
        }
    </style>
</head>
<body>
    <header>
        <nav>
            <a href="/" class="logo">
                <i class="fas fa-heart logo-icon"></i>
                <span>TechWish</span>
            </a>
            <div class="auth-buttons">
                <?php if(isset($_SESSION['user_id'])): ?>
                    <div class="menu">
                        <div class="user-profile">
                        <img src="/<?php echo $avatar; ?>" alt="Аватар" class="user-avatar">                             
                        <span><?php echo htmlspecialchars($_SESSION['username']); ?></span>
                    </div>
                        <div class="dropdown profile-dropdown">
                            <a href="/profile.php">
                                <i class="fas fa-user-circle"></i>
                                <span>Профіль</span>
                            </a>
                            <a href="/wishlist.php">
                                <i class="fas fa-heart"></i>
                                <span>Вишлист</span>
                            </a>
                            <a href="/logout.php">
                                <i class="fas fa-sign-out-alt"></i>
                                <span>Вийти</span>
                            </a>
                        </div>
                    </div>
                <?php else: ?>
                    <a href="/login.php" class="auth-btn">
                        <i class="fas fa-sign-in-alt"></i>
                        <span>Увійти</span>
                    </a>
                    <a href="/register.php" class="auth-btn">
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
            
            <form method="get" action="notebooks-tablets.php" class="filters-container" id="filtersForm">
                <div class="filter-section">
                    <h3>Тип пристрою</h3>
                    <div class="filter-options">
                        <?php foreach ($types as $type): ?>
                            <label class="filter-checkbox">
                                <input type="checkbox" name="type[]" value="<?php echo $type; ?>" 
                                    <?php echo (isset($_GET['type']) && in_array($type, $_GET['type'])) ? 'checked' : ''; ?>>
                                <?php echo $type; ?>
                            </label>
                        <?php endforeach; ?>
                    </div>
                </div>
                
                <div class="filter-section">
                    <h3>Бренди</h3>
                    <div class="filter-options">
                        <?php foreach ($brands as $br): ?>
                            <label class="filter-checkbox">
                                <input type="checkbox" name="brand[]" value="<?php echo $br; ?>" 
                                    <?php echo (isset($_GET['brand']) && in_array($br, $_GET['brand'])) ? 'checked' : ''; ?>>
                                <?php echo $br; ?>
                            </label>
                        <?php endforeach; ?>
                    </div>
                </div>
                
                <div class="filter-section">
                    <h3>Ціна</h3>
                    <div class="price-range">
                        <input type="number" name="price_min" placeholder="Від" value="<?php echo isset($_GET['price_min']) ? $_GET['price_min'] : ''; ?>">
                        <input type="number" name="price_max" placeholder="До" value="<?php echo isset($_GET['price_max']) ? $_GET['price_max'] : ''; ?>">
                    </div>
                </div>
                
                <div class="filter-section">
                    <h3>Оперативна пам'ять</h3>
                    <div class="filter-options">
                        <?php foreach ($rams as $r): ?>
                            <label class="filter-checkbox">
                                <input type="checkbox" name="ram[]" value="<?php echo $r; ?>" 
                                    <?php echo (isset($_GET['ram']) && in_array($r, $_GET['ram'])) ? 'checked' : ''; ?>>
                                <?php echo $r; ?> ГБ
                            </label>
                        <?php endforeach; ?>
                    </div>
                </div>
                
                <div class="filter-section">
                    <h3>Вбудована пам'ять</h3>
                    <div class="filter-options">
                        <?php foreach ($storages as $s): ?>
                            <label class="filter-checkbox">
                                <input type="checkbox" name="storage[]" value="<?php echo $s; ?>" 
                                    <?php echo (isset($_GET['storage']) && in_array($s, $_GET['storage'])) ? 'checked' : ''; ?>>
                                <?php echo $s; ?> ГБ
                            </label>
                        <?php endforeach; ?>
                    </div>
                </div>
                
                <div class="filter-section">
                    <h3>Діагональ екрана</h3>
                    <div class="filter-options">
                        <?php foreach ($screens as $sc): ?>
                            <label class="filter-checkbox">
                                <input type="checkbox" name="screen[]" value="<?php echo $sc; ?>" 
                                    <?php echo (isset($_GET['screen']) && in_array($sc, $_GET['screen'])) ? 'checked' : ''; ?>>
                                <?php echo $sc; ?>"
                            </label>
                        <?php endforeach; ?>
                    </div>
                </div>
                
                <div class="filter-section">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-filter"></i> Фільтрувати
                    </button>
                    <a href="notebooks-tablets.php" class="btn btn-outline" style="margin-top: 10px;">
                        <i class="fas fa-times"></i> Скинути
                    </a>
                </div>
            </form>
        </div>
        
        <div class="products-column">
            <h1 class="page-title">
                <i class="fas fa-laptop"></i>
                <span>Ноутбуки, Планшети, БФП</span>
            </h1>
            
            <div class="sorting">
                <select name="sort" class="sort-select" onchange="window.location.href=this.value">
                    <option value="notebooks-tablets.php?sort=newest" <?php echo (isset($_GET['sort']) && $_GET['sort'] === 'newest') ? 'selected' : ''; ?>>Новітні</option>
                    <option value="notebooks-tablets.php?sort=price_asc" <?php echo (isset($_GET['sort']) && $_GET['sort'] === 'price_asc') ? 'selected' : ''; ?>>Від дешевих</option>
                    <option value="notebooks-tablets.php?sort=price_desc" <?php echo (isset($_GET['sort']) && $_GET['sort'] === 'price_desc') ? 'selected' : ''; ?>>Від дорогих</option>
                    <option value="notebooks-tablets.php?sort=popular" <?php echo (isset($_GET['sort']) && $_GET['sort'] === 'popular') ? 'selected' : ''; ?>>Популярні</option>
                </select>
            </div>
            
            <div class="products-grid">
                <?php if (empty($products)): ?>
                    <div class="no-results">
                        <i class="fas fa-search" style="font-size: 48px; margin-bottom: 20px;"></i>
                        <h3>Нічого не знайдено</h3>
                        <p>Спробуйте змінити параметри фільтрації</p>
                    </div>
                <?php else: ?>
                    <?php foreach ($products as $product): ?>
                        <div class="product-card">
                            <img src="<?php echo htmlspecialchars($product['image_url']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" class="product-image">
                            <div class="product-info">
                                <div class="product-brand"><?php echo htmlspecialchars($product['brand']); ?></div>
                                <span class="product-type type-<?php echo strtolower($product['type']); ?>">
                                    <?php echo htmlspecialchars($product['type']); ?>
                                </span>
                                <h3 class="product-name"><?php echo htmlspecialchars($product['name']); ?></h3>
                                
                                <div class="product-specs">
                                    <?php if (!empty($product['ram'])): ?>
                                        <span class="product-spec">
                                            <i class="fas fa-memory"></i> <?php echo htmlspecialchars($product['ram']); ?> ГБ
                                        </span>
                                    <?php endif; ?>
                                    
                                    <?php if (!empty($product['storage'])): ?>
                                        <span class="product-spec">
                                            <i class="fas fa-hdd"></i> <?php echo htmlspecialchars($product['storage']); ?> ГБ
                                        </span>
                                    <?php endif; ?>
                                    
                                    <?php if (!empty($product['screen_size'])): ?>
                                        <span class="product-spec">
                                            <i class="fas fa-tablet-alt"></i> <?php echo htmlspecialchars($product['screen_size']); ?>"
                                        </span>
                                    <?php endif; ?>
                                    
                                    <?php if (!empty($product['processor'])): ?>
                                        <span class="product-spec">
                                            <i class="fas fa-microchip"></i> <?php echo htmlspecialchars($product['processor']); ?>
                                        </span>
                                    <?php endif; ?>
                                </div>
                                
                                <div class="product-price"><?php echo number_format($product['price'], 0, '', ' '); ?> ₴</div>
                                <div class="product-actions">
                                    <a href="wishlist.php?action=add&id=<?php echo $product['id']; ?>" class="btn btn-outline">
                                        <i class="far fa-heart"></i>
                                    </a>
                                    <a href="cart.php?action=add&id=<?php echo $product['id']; ?>" class="btn btn-primary">
                                        <i class="fas fa-cart-plus"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
            
            <div class="pagination">
                <?php
                $total_pages = ceil($total_products / $per_page);
                $current_page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
                $query_string = $_GET;
                
                // Удаляем page из query string чтобы добавить его заново
                unset($query_string['page']);
                $base_url = 'notebooks-tablets.php?' . http_build_query($query_string);
                
                if ($total_pages > 1): ?>
                    <?php if ($current_page > 1): ?>
                        <a href="<?php echo $base_url . '&page=' . ($current_page - 1); ?>">&laquo;</a>
                    <?php endif; ?>
                    
                    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                        <a href="<?php echo $base_url . '&page=' . $i; ?>" <?php echo $i == $current_page ? 'class="active"' : ''; ?>><?php echo $i; ?></a>
                    <?php endfor; ?>
                    
                    <?php if ($current_page < $total_pages): ?>
                        <a href="<?php echo $base_url . '&page=' . ($current_page + 1); ?>">&raquo;</a>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <footer>
        <div class="footer-content">
            <div class="footer-section">
                <h3>Каталог</h3>
                <a href="smartphones.php">Смартфони</a>
                <a href="notebooks-tablets.php">Ноутбуки</a>
                <a href="notebooks-tablets.php">Планшети</a>
                <a href="notebooks-tablets.php">БФП</a>
                <a href="#">Навушники</a>
            </div>
            
            <div class="footer-section">
                <h3>Клієнтам</h3>
                <a href="#">Доставка і оплата</a>
                <a href="#">Гарантія</a>
                <a href="#">Повернення</a>
                <a href="#">Кредит</a>
            </div>
            
            <div class="footer-section">
                <h3>Про нас</h3>
                <a href="#">Про компанію</a>
                <a href="#">Контакти</a>
                <a href="#">Вакансії</a>
                <a href="#">Блог</a>
            </div>
            
            <div class="footer-section">
                <h3>Контакти</h3>
                <a href="#"><i class="fas fa-phone"></i> 0 800 123 456</a>
                <a href="#"><i class="fas fa-envelope"></i> info@techwish.ua</a>
                <a href="#"><i class="fas fa-map-marker-alt"></i> Київ, вул. Технічна 15</a>
            </div>
        </div>
    </footer>

    <script>
        // Toggle filters on mobile
        document.getElementById('filterToggle').addEventListener('click', function() {
            document.getElementById('filtersForm').classList.toggle('visible');
        });
        
        // Динамическое обновление URL при изменении сортировки
        document.querySelector('.sort-select').addEventListener('change', function() {
            window.location.href = this.value;
        });
        
        // Обработка фильтров
        document.querySelector('form').addEventListener('submit', function(e) {
            // Можно добавить валидацию, например, что price_min <= price_max
            if (document.querySelector('input[name="price_min"]').value && 
                document.querySelector('input[name="price_max"]').value &&
                parseFloat(document.querySelector('input[name="price_min"]').value) > 
                parseFloat(document.querySelector('input[name="price_max"]').value)) {
                alert('Максимальна ціна повинна бути більше мінімальної');
                e.preventDefault();
            }
        });
    </script>
</body>
</html>