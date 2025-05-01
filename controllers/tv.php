<?php
require_once __DIR__.'/../models/tv.php';

class TVMediaController {
    private $model;
    private $params;

    public function __construct() {
        $this->model = new TVMediaModel();
        
        $this->params = [
            'categories' => isset($_GET['category']) ? (array)$_GET['category'] : [],
            'price_min' => isset($_GET['price_min']) && $_GET['price_min'] !== '' ? floatval($_GET['price_min']) : null,
            'price_max' => isset($_GET['price_max']) && $_GET['price_max'] !== '' ? floatval($_GET['price_max']) : null,
            'brands' => isset($_GET['brand']) ? (array)$_GET['brand'] : [],
            'resolutions' => isset($_GET['resolution']) ? (array)$_GET['resolution'] : [],
            'sizes' => isset($_GET['size']) ? (array)$_GET['size'] : [],
            'technologies' => isset($_GET['tech']) ? (array)$_GET['tech'] : [],
            'sort' => isset($_GET['sort']) ? $_GET['sort'] : 'newest',
            'page' => isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1,
            'per_page' => 12
        ];
    }

    public function getAll($pdo, $avatar) {
        $result = $this->model->getAll($this->params, $pdo);
        
        // Фільтри
        $allCategories = ['Телевізори', 'Проектори', 'Домашні кінотеатри', 'ТВ-приставки', 'Саундбари'];
        $allBrands = ['Samsung', 'LG', 'Sony', 'Philips', 'TCL', 'Xiaomi', 'Panasonic', 'Hisense'];
        $allResolutions = ['HD (720p)', 'Full HD (1080p)', '4K Ultra HD', '8K Ultra HD'];
        $allSizes = ['До 32"', '32"-43"', '43"-55"', '55"-65"', '65" і більше'];
        $allTechnologies = ['OLED', 'QLED', 'LED', 'Mini-LED', 'Plasma'];

        $templateData = [
            'avatar' => $avatar,
            'products' => $result['products'],
            'error' => $result['error'],
            'categories' => $allCategories,
            'brands' => $allBrands,
            'resolutions' => $allResolutions,
            'sizes' => $allSizes,
            'technologies' => $allTechnologies,
            'price_min' => $this->params['price_min'],
            'price_max' => $this->params['price_max'],
            'sort' => $this->params['sort'],
            'selectedCategories' => $this->params['categories'],
            'selectedBrands' => $this->params['brands'],
            'selectedResolutions' => $this->params['resolutions'],
            'selectedSizes' => $this->params['sizes'],
            'selectedTechnologies' => $this->params['technologies'],
            'total_products' => $result['total_products'],
            'per_page' => $this->params['per_page'],
            'page' => $this->params['page']
        ];

        extract($templateData);
        include __DIR__.'/../views/tv.html';
    }
}