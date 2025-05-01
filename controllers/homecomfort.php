<?php

require_once __DIR__.'/../models/homecomfort.php';

class HomeAppliancesController
{
    private $model;
    private $params;

    public function __construct()
    {
        $this->model = new HomeAppliancesModel();
        
        // Параметры фильтрации
        $this->params = [
            'categories' => isset($_GET['category']) ? (array)$_GET['category'] : [],
            'price_min' => isset($_GET['price_min']) && $_GET['price_min'] !== '' ? floatval($_GET['price_min']) : null,
            'price_max' => isset($_GET['price_max']) && $_GET['price_max'] !== '' ? floatval($_GET['price_max']) : null,
            'brands' => isset($_GET['brand']) ? (array)$_GET['brand'] : [],
            'powers' => isset($_GET['power']) ? (array)$_GET['power'] : [],
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
        
        // Все возможные фильтры
        $allCategories = ['Пилососи', 'Пральні машини', 'Холодильники', 'Плити', 'Посудомийки', 'Мікрохвильовки'];
        $allBrands = ['Samsung', 'LG', 'Bosch', 'Philips', 'Electrolux', 'Whirlpool', 'Indesit', 'Beko'];
        $allPowers = ['До 1000 Вт', '1000-1500 Вт', '1500-2000 Вт', '2000+ Вт'];

        $templateData = [
            'avatar' => $avatar,
            'products' => $result['products'],
            'error' => $result['error'],
            'categories' => $allCategories,
            'brands' => $allBrands,
            'powers' => $allPowers,
            'price_min' => $this->params['price_min'],
            'price_max' => $this->params['price_max'],
            'sort' => $this->params['sort'],
            'selectedCategories' => $this->params['categories'],
            'selectedBrands' => $this->params['brands'],
            'selectedPowers' => $this->params['powers'],
            'total_products' => $result['total_products'],
            'per_page' => $this->params['per_page'],
            'page' => $this->params['page'],
            'is_ajax' => $this->params['is_ajax']
        ];

        // Если это AJAX запрос, возвращаем JSON
        if ($this->params['is_ajax']) {
            $this->sendAjaxResponse($templateData);
            return;
        }

        // Иначе отображаем полную страницу
        extract($templateData);
        include __DIR__.'/../views/homecomfort.html';
    }

    private function sendAjaxResponse($data)
    {
        ob_start();
        if (empty($data['products'])) {
            include __DIR__.'/../views/partials/no_results.php';
        } else {
            foreach ($data['products'] as $product) {
                include __DIR__.'/../views/partials/product_card.php';
            }
        }
        $products_html = ob_get_clean();

        ob_start();
        if ($data['total_products'] > $data['per_page']) {
            include __DIR__.'/../views/partials/pagination.php';
        }
        $pagination_html = ob_get_clean();

        header('Content-Type: application/json');
        echo json_encode([
            'products_html' => $products_html,
            'pagination_html' => $pagination_html,
            'total_products' => $data['total_products'],
            'page' => $data['page'],
            'total_pages' => ceil($data['total_products'] / $data['per_page'])
        ]);
        exit;
    }
}