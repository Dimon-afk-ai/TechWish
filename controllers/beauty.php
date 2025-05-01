<?php
require_once __DIR__.'/../models/beauty.php';

class BeautyHealthController {
    private $model;
    private $params;

    public function __construct() {
        $this->model = new BeautyHealthModel();
        
        $this->params = [
            'categories' => isset($_GET['category']) ? (array)$_GET['category'] : [],
            'subcategories' => isset($_GET['subcategory']) ? (array)$_GET['subcategory'] : [],
            'brands' => isset($_GET['brand']) ? (array)$_GET['brand'] : [],
            'price_min' => isset($_GET['price_min']) && $_GET['price_min'] !== '' ? floatval($_GET['price_min']) : null,
            'price_max' => isset($_GET['price_max']) && $_GET['price_max'] !== '' ? floatval($_GET['price_max']) : null,
            'purposes' => isset($_GET['purpose']) ? (array)$_GET['purpose'] : [],
            'sort' => isset($_GET['sort']) ? $_GET['sort'] : 'newest',
            'page' => isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1,
            'per_page' => 12
        ];
    }

    public function getAll($pdo, $avatar) {
        $result = $this->model->getAll($this->params, $pdo);
        
        // Всі можливі фільтри
        $allCategories = [
            'Догляд за волоссям' => [
                'Фени', 'Випрямлячі', 'Плойки-щипці', 'Фен-щітка', 
                'Конусні плойки', 'Стайлери', 'Машинки для завивки'
            ],
            'Стрижка волосся' => [
                'Машинки для стрижки', 'Тримери'
            ],
            'Персональний догляд' => [
                'Епілятори', 'Фотоепілятори', 'Жіночі бритви', 'Чоловічі бритви'
            ],
            'Догляд за ротовою порожниною' => [
                'Зубні щітки', 'Іригатори', 'Стерилізатори'
            ],
            'Тренування та релакс' => [
                'Масажери', 'Фітнес-трекери', 'Смарт-ваги'
            ],
            'Турбота про здоров\'я' => [
                'Тонометри', 'Термометри', 'Пульсоксиметри'
            ],
            'Косметологія' => [
                'Масажери для обличчя', 'LED маски', 'Вакуумні очищувачі'
            ]
        ];

        $allBrands = ['Dyson', 'Philips', 'Braun', 'Panasonic', 'Remington', 'Rowenta', 'Geske'];
        $allPurposes = ['Очищення', 'Ліфтинг', 'Розслаблення', 'Догляд за шкірою', 'Стайлінг'];

        $templateData = [
            'avatar' => $avatar,
            'products' => $result['products'],
            'error' => $result['error'],
            'categories' => $allCategories,
            'brands' => $allBrands,
            'purposes' => $allPurposes,
            'price_min' => $this->params['price_min'],
            'price_max' => $this->params['price_max'],
            'sort' => $this->params['sort'],
            'selectedCategories' => $this->params['categories'],
            'selectedSubcategories' => $this->params['subcategories'],
            'selectedBrands' => $this->params['brands'],
            'selectedPurposes' => $this->params['purposes'],
            'total_products' => $result['total_products'],
            'per_page' => $this->params['per_page'],
            'page' => $this->params['page']
        ];

        extract($templateData);
        include __DIR__.'/../views/beauty.html';
    }
}