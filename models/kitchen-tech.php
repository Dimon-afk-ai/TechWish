<?php
require_once __DIR__.'/../boot.php';

class KitchenModel
{
    public function getAll($parameters, $pdo)
    {
        $error = '';
        extract($parameters);
        
        // Строим SQL запрос для товаров
        $sql = "SELECT * FROM kitchen_appliances WHERE 1=1";
        $params = [];

        // Добавляем условия фильтрации
        if (!empty($categories)) {
            $placeholders = implode(',', array_fill(0, count($categories), '?'));
            $sql .= " AND category IN ($placeholders)";
            $params = array_merge($params, $categories);
        }

        if ($price_min !== null) {
            $sql .= " AND price >= ?";
            $params[] = $price_min;
        }

        if ($price_max !== null) {
            $sql .= " AND price <= ?";
            $params[] = $price_max;
        }

        if (!empty($brands)) {
            $placeholders = implode(',', array_fill(0, count($brands), '?'));
            $sql .= " AND brand IN ($placeholders)";
            $params = array_merge($params, $brands);
        }

        if (!empty($types)) {
            $placeholders = implode(',', array_fill(0, count($types), '?'));
            $sql .= " AND type IN ($placeholders)";
            $params = array_merge($params, $types);
        }

        // Добавляем сортировку
        switch ($sort) {
            case 'price_asc': $sql .= " ORDER BY price ASC"; break;
            case 'price_desc': $sql .= " ORDER BY price DESC"; break;
            case 'popular': $sql .= " ORDER BY views DESC"; break;
            default: $sql .= " ORDER BY id DESC"; // используем id вместо created_at
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
            
            // Получаем общее количество товаров
            $count_sql = "SELECT COUNT(*) FROM kitchen_appliances WHERE 1=1";
            $count_params = [];
            
            if (!empty($categories)) {
                $placeholders = implode(',', array_fill(0, count($categories), '?'));
                $count_sql .= " AND category IN ($placeholders)";
                $count_params = array_merge($count_params, $categories);
            }

            if ($price_min !== null) {
                $count_sql .= " AND price >= ?";
                $count_params[] = $price_min;
            }

            if ($price_max !== null) {
                $count_sql .= " AND price <= ?";
                $count_params[] = $price_max;
            }

            if (!empty($brands)) {
                $placeholders = implode(',', array_fill(0, count($brands), '?'));
                $count_sql .= " AND brand IN ($placeholders)";
                $count_params = array_merge($count_params, $brands);
            }

            if (!empty($types)) {
                $placeholders = implode(',', array_fill(0, count($types), '?'));
                $count_sql .= " AND type IN ($placeholders)";
                $count_params = array_merge($count_params, $types);
            }

            $count_stmt = $pdo->prepare($count_sql);
            $count_stmt->execute($count_params);
            $total_products = $count_stmt->fetchColumn();

        } catch (PDOException $e) {
            $error = "Ошибка базы данных: " . $e->getMessage();
            error_log($error);
        }

        return [
            'products' => $products ?? [],
            'error' => $error,
            'total_products' => $total_products ?? 0
        ];
    }
}