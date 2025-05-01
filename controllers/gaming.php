<?php

require_once __DIR__.'/../models/gaming.php';

class GamingController
{
    private $model;
    private $params;

    public function __construct()
    {
        $this->model = new GamingModel();
        
        $this->params = [
            'categories' => isset($_GET['category']) ? (array)$_GET['category'] : [],
            'price_min' => isset($_GET['price_min']) && $_GET['price_min'] !== '' ? floatval($_GET['price_min']) : null,
            'price_max' => isset($_GET['price_max']) && $_GET['price_max'] !== '' ? floatval($_GET['price_max']) : null,
            'brands' => isset($_GET['brand']) ? (array)$_GET['brand'] : [],
            'types' => isset($_GET['type']) ? (array)$_GET['type'] : [],
            'platforms' => isset($_GET['platform']) ? (array)$_GET['platform'] : [],
            'sort' => isset($_GET['sort']) ? $_GET['sort'] : 'newest',
            'page' => isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1,
            'per_page' => 12,
            'is_ajax' => !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
                         strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'
        ];
    }

    public function getAll($pdo, $avatar)
    {
        $result = $this->model->getAll($this->params, $pdo);
        
        // Всі можливі фільтри
        $allCategories = ['Ігрові консолі', 'Геймпади', 'VR-очки', 'Геймерські клавіатури', 'Миші', 'Монітори', 'Акустика'];
        $allBrands = ['Sony', 'Microsoft', 'Nintendo', 'Razer', 'Logitech', 'SteelSeries', 'HyperX', 'ASUS', 'Acer'];
        $allTypes = ['Професійний', 'Середній', 'Бюджетний', 'Кишеньковий'];
        $allPlatforms = ['PlayStation', 'Xbox', 'PC', 'Nintendo Switch', 'Mobile'];

        $templateData = [
            'avatar' => $avatar,
            'products' => $result['products'],
            'error' => $result['error'],
            'categories' => $allCategories,
            'brands' => $allBrands,
            'types' => $allTypes,
            'platforms' => $allPlatforms,
            'price_min' => $this->params['price_min'],
            'price_max' => $this->params['price_max'],
            'sort' => $this->params['sort'],
            'selectedCategories' => $this->params['categories'],
            'selectedBrands' => $this->params['brands'],
            'selectedTypes' => $this->params['types'],
            'selectedPlatforms' => $this->params['platforms'],
            'total_products' => $result['total_products'],
            'per_page' => $this->params['per_page'],
            'page' => $this->params['page'],
            'is_ajax' => $this->params['is_ajax']
        ];

        extract($templateData);
        include __DIR__.'/../views/gaming.html';
    }
}