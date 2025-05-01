<?php
require_once __DIR__.'/../models/smart_watches.php';

class SmartWatchesController {
    private $model;
    private $params;

    public function __construct() {
        $this->model = new SmartWatchesModel();
        
        $this->params = [
            'categories' => isset($_GET['category']) ? (array)$_GET['category'] : [],
            'subcategories' => isset($_GET['subcategory']) ? (array)$_GET['subcategory'] : [],
            'brands' => isset($_GET['brand']) ? (array)$_GET['brand'] : [],
            'price_min' => isset($_GET['price_min']) && $_GET['price_min'] !== '' ? floatval($_GET['price_min']) : null,
            'price_max' => isset($_GET['price_max']) && $_GET['price_max'] !== '' ? floatval($_GET['price_max']) : null,
            'features' => isset($_GET['feature']) ? (array)$_GET['feature'] : [],
            'sort' => isset($_GET['sort']) ? $_GET['sort'] : 'newest',
            'page' => isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1,
            'per_page' => 12
        ];
    }

    public function getAll($pdo, $avatar) {
        $result = $this->model->getAll($this->params, $pdo);
        
        // Всі можливі фільтри
        $allCategories = [
            'Смарт-годинники' => [
                'Apple Watch', 'Wear OS', 'Tizen', 'Fitbit', 'Garmin', 'Amazfit', 'Huawei Watch'
            ],
            'Механічні годинники' => [
                'Класичні', 'Хронографи', 'Дайверські', 'Авіаційні', 'Люксові'
            ],
            'Фітнес-трекери' => [
                'Базові', 'Просунуті', 'Для бігу', 'Для плавання', 'Дитячі'
            ],
            'Розумні браслети' => [
                'З моніторингом здоров\'я', 'Модні', 'З NFC', 'З GPS'
            ],
            'Аксесуари' => [
                'Ремінці', 'Зарядні станції', 'Захисні плівки', 'Чохли'
            ]
        ];

        $allBrands = ['Apple', 'Samsung', 'Xiaomi', 'Huawei', 'Garmin', 'Fitbit', 'Amazfit', 'Fossil', 'Casio', 'Seiko', 'Rolex'];
        $allFeatures = ['GPS', 'NFC', 'Водонепроникність', 'Моніторинг серця', 'Крокомір', 'Спалт-трекер', 'Бездротова зарядка', 'Кров\'яний тиск', 'Кисень в крові'];

        $templateData = [
            'avatar' => $avatar,
            'products' => $result['products'],
            'error' => $result['error'],
            'categories' => $allCategories,
            'brands' => $allBrands,
            'features' => $allFeatures,
            'price_min' => $this->params['price_min'],
            'price_max' => $this->params['price_max'],
            'sort' => $this->params['sort'],
            'selectedCategories' => $this->params['categories'],
            'selectedSubcategories' => $this->params['subcategories'],
            'selectedBrands' => $this->params['brands'],
            'selectedFeatures' => $this->params['features'],
            'total_products' => $result['total_products'],
            'per_page' => $this->params['per_page'],
            'page' => $this->params['page']
        ];

        extract($templateData);
        include __DIR__.'/../views/smart_watches.html';
    }
}