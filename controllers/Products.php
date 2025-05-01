<?php

require_once __DIR__.'/../models/products.php';
class ProductController
{
	public function __construct()
	{
		$this->model = new ProductModel();
		// Параметры фильтрации
		$brands = isset($_GET['brand']) ? (array)$_GET['brand'] : [];
		$price_min = isset($_GET['price_min']) ? floatval($_GET['price_min']) : null;
		$price_max = isset($_GET['price_max']) ? floatval($_GET['price_max']) : null;
		$rams = isset($_GET['ram']) ? array_map('intval', (array)$_GET['ram']) : [];
		$storages = isset($_GET['storage']) ? array_map('intval', (array)$_GET['storage']) : [];
		$sort = isset($_GET['sort']) ? $_GET['sort'] : 'newest';
		$category = isset($_GET['cat']) ? $_GET['cat'] : 'smartphones';
		$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
		
		$per_page = 10;
		$this->params = array(
			'brands' => $brands,
			'price_min' => $price_min,
			'price_max' => $price_max,
			'rams' => $rams,
			'storages' => $storages,
			'sort' => $sort,
			'page' => $page,
			'category' => $category,
			'per_page' => $per_page,
		);
	}

	public function getAll($pdo, $avatar)
	{
		$products = $this->model->getAll($this->params,$pdo);
		extract($this->params);

		$allBrands = ['Apple', 'Samsung', 'Xiaomi', 'Huawei', 'OnePlus', 'Realme', 'Oppo', 'Vivo', 'Google', 'Asus'];
		$allCategories = ['Холодильники', 'Плити', 'Посудомийки', 'Мікрохвильовки', 'Кавоварки', 'Блендери', 'Мультиварки', 'Мясорубки'];
		$allTypes = ['Вбудована', 'Окремостояча', 'Компактна', 'Професійна'];
		$allRams = [4, 6, 8, 12, 16];
		$allStorages = [64, 128, 256, 512, 1024];

		$templateData = [
			'avatar' => $avatar,
			'products' => $products['products'],
			'error' => $products['error'],
			'brands' => $allBrands,
			'categories' => $allCategories,
			'selectedCategories' => [],
			'rams' => $allRams,
			'storages' => $allStorages,
			'price_min' => $price_min,
			'price_max' => $price_max,
			'sort' => $sort,
			'selectedBrands' => $brands,
			'selectedRams' => $rams,
			'selectedStorages' => $storages
		];
		
		$total_products = $products['total_products'];
		extract($templateData);
		switch ($category){
			case "smartphones":
				include __DIR__.'/../views/smartphones.html';
				break;
			case "kitchen":
				include __DIR__.'/../views/kitchen-tech.html';
				break;

		}
		
	}
}