<?php
require_once __DIR__.'/../boot.php';
class ProductModel
{
 	public function getAll($parameters, $pdo)
	{
		$error = '';
		extract($parameters);
		$params = [];
		// Строим SQL запрос для товаров
		$sql = "SELECT * FROM products WHERE category = ?";
		$params[] =  $category;
		

		// Добавляем условия фильтрации
		if (!empty($subcategory)) {
			$placeholders = implode(',', array_fill(0, count($subcategory), '?'));
			$sql .= " AND subcategory IN ($placeholders)";
			$params = array_merge($params, $subcategory);
		}

		if (!empty($brands)) {
			$placeholders = implode(',', array_fill(0, count($brands), '?'));
			$sql .= " AND brand IN ($placeholders)";
			$params = array_merge($params, $brands);
		}

		if ($price_min !== null && !empty($price_min)) {
			$sql .= " AND price >= ?";
			$params[] = $price_min;
		}

		if ($price_max !== null && !empty($price_max)) {
			$sql .= " AND price <= ?";
			$params[] = $price_max;
		}

		if (!empty($rams)) {
			$placeholders = implode(',', array_fill(0, count($rams), '?'));
			$sql .= " AND ram IN ($placeholders)";
			$params = array_merge($params, $rams);
		}

		if (!empty($storages)) {
			$placeholders = implode(',', array_fill(0, count($storages), '?'));
			$sql .= " AND storage IN ($placeholders)";
			$params = array_merge($params, $storages);
		}

// Добавляем сортировку
		switch ($sort) {
			case 'price_asc': $sql .= " ORDER BY price ASC"; break;
			case 'price_desc': $sql .= " ORDER BY price DESC"; break;
			case 'popular': $sql .= " ORDER BY views DESC"; break;
			default: $sql .= " ORDER BY created_at DESC"; // newest
		}

// Добавляем пагинацию
		$sql .= " LIMIT ? OFFSET ?";
		$params[] = $per_page;
		$params[] = ($page - 1) * $per_page;

// Выполняем запрос для получения товаров
		try {
			$stmt = $pdo->prepare($sql);
			$stmt->execute($params);
			$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
			// Получаем общее количество товаров (для пагинации)
			$count_sql = "SELECT COUNT(*) FROM products WHERE category = ?";
			$count_params [] = $category;

			// Те же условия фильтрации, что и для основного запроса
			if (!empty($subcategory)) {
				$placeholders = implode(',', array_fill(0, count($subcategory), '?'));
				$count_sql .= " AND subcategory IN ($placeholders)";
				$count_params = array_merge($count_params, $subcategory);
			}

			if (!empty($brands)) {
				$placeholders = implode(',', array_fill(0, count($brands), '?'));
				$count_sql .= " AND brand IN ($placeholders)";
				$count_params = array_merge($count_params, $brands);
			}

			if ($price_min !== null && !empty($price_min)) {
				$count_sql .= " AND price >= ?";
				$count_params[] = $price_min;
			}

			if ($price_max !== null && !empty($price_max)) {
				$count_sql .= " AND price <= ?";
				$count_params[] = $price_max;
			}

			if (!empty($rams)) {
				$placeholders = implode(',', array_fill(0, count($rams), '?'));
				$count_sql .= " AND ram IN ($placeholders)";
				$count_params = array_merge($count_params, $rams);
			}

			if (!empty($storages)) {
				$placeholders = implode(',', array_fill(0, count($storages), '?'));
				$count_sql .= " AND storage IN ($placeholders)";
				$count_params = array_merge($count_params, $storages);
			}
			$count_stmt = $pdo->prepare($count_sql);
			$count_stmt->execute($count_params);
			$total_products = $count_stmt->fetchColumn();

		} catch (PDOException $e) {
			$error = "Ошибка базы данных: " . $e->getMessage();
			error_log($error);
		}
		// Отладочная информация (можно удалить после тестирования)
		if (empty($products)) {
			error_log("SQL запрос: " . $sql);
			error_log("Параметры: " . print_r($params, true));
			error_log("Всего товаров: " . $total_products);
		}
		return array('products'=>$products,'error'=>$error, 'total_products'=>$total_products);

	}
}