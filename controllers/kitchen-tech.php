<?php

require_once __DIR__.'/../models/kitchen-tech.php';

class KitchenController
{
    public function __construct()
    {
        $this->model = new KitchenModel();
        
        // Параметры фильтрации
        $categories = isset($_GET['category']) ? (array)$_GET['category'] : [];
        $price_min = isset($_GET['price_min']) ? floatval($_GET['price_min']) : null;
        $price_max = isset($_GET['price_max']) ? floatval($_GET['price_max']) : null;
        $brands = isset($_GET['brand']) ? (array)$_GET['brand'] : [];
        $types = isset($_GET['type']) ? (array)$_GET['type'] : [];
        $sort = isset($_GET['sort']) ? $_GET['sort'] : 'newest';
        $page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
        $per_page = 12;
        
        $this->params = [
            'categories' => $categories,
            'price_min' => $price_min,
            'price_max' => $price_max,
            'brands' => $brands,
            'types' => $types,
            'sort' => $sort,
            'page' => $page,
            'per_page' => $per_page,
        ];
    }

    public function getAll($pdo, $avatar)
    {
        $products = $this->model->getAll($this->params, $pdo);
        extract($this->params);

        $allCategories = ['Холодильники', 'Плити', 'Посудомийки', 'Мікрохвильовки', 'Кавоварки', 'Блендери', 'Мультиварки', 'Мясорубки'];
        $allBrands = ['Samsung', 'Bosch', 'Philips', 'Electrolux', 'Moulinex', 'Tefal', 'Gorenje', 'Zanussi'];
        $allTypes = ['Вбудована', 'Окремостояча', 'Компактна', 'Професійна'];

        $templateData = [
            'avatar' => $avatar,
            'products' => $products['products'],
            'error' => $products['error'],
            'categories' => $allCategories,
            'brands' => $allBrands,
            'types' => $allTypes,
            'price_min' => $price_min,
            'price_max' => $price_max,
            'sort' => $sort,
            'selectedCategories' => $categories,
            'selectedBrands' => $brands,
            'selectedTypes' => $types,
            'total_products' => $products['total_products'],
            'page' => $page,
            'per_page' => $per_page
        ];
        
        extract($templateData);
        include __DIR__.'/../views/kitchen-tech.html';
    }
}