<?php

require_once __DIR__.'/../models/computers.php';

class ComputersController
{
    public function __construct()
    {
        $this->model = new NotebookTabletModel();
        
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
        
        $this->params = array(
            'brands' => $brands,
            'price_min' => $price_min,
            'price_max' => $price_max,
            'rams' => $rams,
            'storages' => $storages,
            'types' => $types,
            'screens' => $screens,
            'sort' => $sort,
            'page' => $page,
            'per_page' => $per_page,
        );
    }

    public function getAll($pdo, $avatar)
    {
        $result = $this->model->getAll($this->params, $pdo);
        extract($this->params);

        $allBrands = ['Apple', 'Samsung', 'Lenovo', 'HP', 'Dell', 'Asus', 'Acer', 'Microsoft', 'Huawei', 'Xiaomi'];
        $allRams = [4, 8, 16, 32, 64];
        $allStorages = [128, 256, 512, 1024, 2048];
        $allTypes = ['Ноутбук', 'Планшет', 'БФП'];
        $allScreens = [10.1, 11.6, 13.3, 14, 15.6, 17.3];

        $templateData = [
            'avatar' => $avatar,
            'products' => $result['products'],
            'error' => $result['error'],
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
        
        $total_products = $result['total_products'];
        extract($templateData);
        include __DIR__.'/../views/computers.html';
    }
}
