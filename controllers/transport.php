<?php

require_once __DIR__.'/../models/transport.php';

class TransportController
{
    private $model;
    private $params;

    public function __construct()
    {
        $this->model = new TransportModel();
        
        $this->params = [
            'categories' => isset($_GET['category']) ? (array)$_GET['category'] : [],
            'price_min' => isset($_GET['price_min']) && $_GET['price_min'] !== '' ? floatval($_GET['price_min']) : null,
            'price_max' => isset($_GET['price_max']) && $_GET['price_max'] !== '' ? floatval($_GET['price_max']) : null,
            'brands' => isset($_GET['brand']) ? (array)$_GET['brand'] : [],
            'types' => isset($_GET['type']) ? (array)$_GET['type'] : [],
            'sort' => isset($_GET['sort']) ? $_GET['sort'] : 'newest',
            'page' => isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1,
            'per_page' => 12
        ];
    }

    public function getAll($pdo, $avatar)
    {
        $result = $this->model->getAll($this->params, $pdo);
        
        // Все возможные фильтры для персонального транспорта
        $allCategories = ['Електросамокати', 'Гіроскутери', 'Моноколеса', 'Електровелосипеди', 'Сигвеї'];
        $allBrands = ['Xiaomi', 'Segway', 'Ninebot', 'Kugoo', 'Smart Balance', 'Hoverbot', 'Airwheel'];
        $allTypes = ['Дорослий', 'Дитячий', 'Спортивний', 'Туристичний', 'Міський'];
        
        $templateData = [
            'avatar' => $avatar,
            'products' => $result['products'],
            'error' => $result['error'],
            'categories' => $allCategories,
            'brands' => $allBrands,
            'types' => $allTypes,
            'price_min' => $this->params['price_min'],
            'price_max' => $this->params['price_max'],
            'sort' => $this->params['sort'],
            'selectedCategories' => $this->params['categories'],
            'selectedBrands' => $this->params['brands'],
            'selectedTypes' => $this->params['types'],
            'total_products' => $result['total_products'],
            'per_page' => $this->params['per_page'],
            'page' => $this->params['page']
        ];

        extract($templateData);
        include __DIR__.'/../views/transport.html';
    }
}