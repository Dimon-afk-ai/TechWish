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

include ('models/header.php');

// Получаем параметры фильтрации (из GET или POST в зависимости от типа запроса)
$requestData = $isAjax ? $_POST : $_GET;

$categories = isset($requestData['category']) ? (array)$requestData['category'] : [];
$price_min = isset($requestData['price_min']) && $requestData['price_min'] !== '' ? floatval($requestData['price_min']) : null;
$price_max = isset($requestData['price_max']) && $requestData['price_max'] !== '' ? floatval($requestData['price_max']) : null;
$brands = isset($requestData['brand']) ? (array)$requestData['brand'] : [];
$powers = isset($requestData['power']) ? (array)$requestData['power'] : [];
$sort = isset($requestData['sort']) ? $requestData['sort'] : 'newest';
$page = isset($requestData['page']) ? max(1, intval($requestData['page'])) : 1;
$per_page = 12;

// Категории и фильтры
$allCategories = ['Пилососи', 'Пральні машини', 'Холодильники', 'Плити', 'Посудомийки', 'Мікрохвильовки'];
$allBrands = ['Samsung', 'LG', 'Bosch', 'Philips', 'Electrolux', 'Whirlpool', 'Indesit', 'Beko'];
$allPowers = ['До 1000 Вт', '1000-1500 Вт', '1500-2000 Вт', '2000+ Вт'];

// Проверка наличия таблицы и данных
try {
    // Проверяем существование таблицы home_appliances
    $tableExists = false;
    $checkTable = $pdo->query("SHOW TABLES");
    while($row = $checkTable->fetch(PDO::FETCH_NUM)) {
        if($row[0] === 'home_appliances') {
            $tableExists = true;
            break;
        }
    }
    
    if (!$tableExists) {
        $error = "Таблица 'home_appliances' не существует в базе данных";
        error_log($error);
    } else {
        // Строим SQL запрос для товаров
        $sql = "SELECT * FROM home_appliances WHERE 1=1";
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
        
        if (!empty($powers)) {
            $placeholders = implode(',', array_fill(0, count($powers), '?'));
            $sql .= " AND power IN ($placeholders)";
            $params = array_merge($params, $powers);
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
            $error = "В таблице 'home_appliances' нет товаров или они не соответствуют фильтрам";
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
                        
                        <?php if (!empty($product['power'])): ?>
                        <span class="product-spec">
                            <i class="fas fa-bolt"></i> <?php echo htmlspecialchars($product['power']); ?>
                        </span>
                        <?php endif; ?>
                        
                        <?php if (!empty($product['dimensions'])): ?>
                        <span class="product-spec">
                            <i class="fas fa-ruler-combined"></i> <?php echo htmlspecialchars($product['dimensions']); ?>
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
            echo '<a href="homeappliances.php?page=1" data-page="1">&laquo;&laquo;</a>';
            echo '<a href="homeappliances.php?page=' . ($page - 1) . '" data-page="' . ($page - 1) . '">&laquo;</a>';
        }
        
        for ($i = $start; $i <= $end; $i++) {
            $active = $i == $page ? 'class="active"' : '';
            echo '<a href="homeappliances.php?page=' . $i . '" ' . $active . ' data-page="' . $i . '">' . $i . '</a>';
        }
        
        if ($page < $total_pages) {
            echo '<a href="homeappliances.php?page=' . ($page + 1) . '" data-page="' . ($page + 1) . '">&raquo;</a>';
            echo '<a href="homeappliances.php?page=' . $total_pages . '" data-page="' . $total_pages . '">&raquo;&raquo;</a>';
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
    'powers' => $allPowers,
    'price_min' => $price_min,
    'price_max' => $price_max,
    'sort' => $sort,
    'selectedCategories' => $categories,
    'selectedBrands' => $brands,
    'selectedPowers' => $powers,
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
extract($templateData);
include 'views/homecomfort.html';
?>
