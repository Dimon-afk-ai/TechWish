<?php

require_once __DIR__.'/../models/audio.php';
class AudioController
{
	public function __construct()
	{
		$this->model = new AudioModel();
		// Параметры фильтрации
		$brands = isset($_GET['brand']) ? (array)$_GET['brand'] : [];
		$price_min = isset($_GET['price_min']) ? floatval($_GET['price_min']) : null;
		$price_max = isset($_GET['price_max']) ? floatval($_GET['price_max']) : null;
		$types = isset($_GET['type']) ? (array)$_GET['type'] : [];
		$connections = isset($_GET['connection']) ? (array)$_GET['connection'] : [];
		$sort = isset($_GET['sort']) ? $_GET['sort'] : 'newest';
		$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
		$per_page = 10;
		$this->params = array(
			'brands' => $brands,
			'price_min' => $price_min,
			'price_max' => $price_max,
			'types' => $types,
			'connections' => $connections,
			'sort' => $sort,
			'page' => $page,
			'per_page' => $per_page,
		);
	}

	public function getAll($pdo, $avatar)
	{
		$products = $this->model->getAll($this->params,$pdo);
		extract($this->params);

		$allBrands = ['Sony', 'Bose', 'JBL', 'Sennheiser', 'Apple', 'Samsung', 'Beats', 'Marshall', 'Audio-Technica', 'Logitech'];
		$allTypes = ['Наушники', 'Колонки', 'Микрофоны', 'Усилители', 'Плееры'];
		$allConnections = ['Bluetooth', 'Проводные', 'USB', 'WiFi', 'AUX'];

		$templateData = [
			'avatar' => $avatar,
			'products' => $products['products'],
			'error' => $products['error'],
			'brands' => $allBrands,
			'types' => $allTypes,
			'connections' => $allConnections,
			'price_min' => $price_min,
			'price_max' => $price_max,
			'sort' => $sort,
			'selectedBrands' => $brands,
			'selectedTypes' => $types,
			'selectedConnections' => $connections
		];
		
		$total_products = $products['total_products'];
		extract($templateData);
		include __DIR__.'/../views/audio.html';
	}
}