<?php
require_once __DIR__.'/../models/accessories.php';

class AccessoriesControllerr
 {
    private $model;
    private $params;

    public function __construct() {
        $this->model = new AccessoriesModel();
        
        $this->params = [
            'categories' => isset($_GET['category']) ? (array)$_GET['category'] : [],
            'price_min' => isset($_GET['price_min']) && $_GET['price_min'] !== '' ? floatval($_GET['price_min']) : null,
            'price_max' => isset($_GET['price_max']) && $_GET['price_max'] !== '' ? floatval($_GET['price_max']) : null,
            'brands' => isset($_GET['brand']) ? (array)$_GET['brand'] : [],
            'types' => isset($_GET['type']) ? (array)$_GET['type'] : [],
            'connections' => isset($_GET['connection']) ? (array)$_GET['connection'] : [],
            'capacities' => isset($_GET['capacity']) ? (array)$_GET['capacity'] : [],
            'sort' => isset($_GET['sort']) ? $_GET['sort'] : 'newest',
            'page' => isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1,
            'per_page' => 12
        ];
    }

    public function getAll($pdo, $avatar) {
        $result = $this->model->getAll($this->params, $pdo);
        
        // Всі можливі фільтри
        $allCategories = [
            'Накопичувачі', 
            'Кабелі та адаптери', 
            'Мережеве обладнання', 
            'Зарядні пристрої', 
            'Чохли та сумки',
            'Підставки та док-станції',
            'Гарнітури та акустика'
        ];
        
        $allBrands = ['Samsung', 'Kingston', 'Sandisk', 'TP-Link', 'Xiaomi', 'Baseus', 'Logitech', 'JBL'];
        $allTypes = ['SSD', 'HDD', 'USB Flash', 'Power Bank', 'Router', 'Switch'];
        $allConnections = ['USB-A', 'USB-C', 'Thunderbolt', 'HDMI', 'Ethernet', 'Wireless'];
        $allCapacities = ['До 64GB', '64GB-256GB', '256GB-1TB', '1TB і більше'];

        $templateData = [
            'avatar' => $avatar,
            'products' => $result['products'],
            'error' => $result['error'],
            'categories' => $allCategories,
            'brands' => $allBrands,
            'types' => $allTypes,
            'connections' => $allConnections,
            'capacities' => $allCapacities,
            'price_min' => $this->params['price_min'],
            'price_max' => $this->params['price_max'],
            'sort' => $this->params['sort'],
            'selectedCategories' => $this->params['categories'],
            'selectedBrands' => $this->params['brands'],
            'selectedTypes' => $this->params['types'],
            'selectedConnections' => $this->params['connections'],
            'selectedCapacities' => $this->params['capacities'],
            'total_products' => $result['total_products'],
            'per_page' => $this->params['per_page'],
            'page' => $this->params['page']
        ];

        extract($templateData);
        include __DIR__.'/../views/accessories.html';
    }
}