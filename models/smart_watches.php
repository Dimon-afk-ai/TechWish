<?php
class SmartWatchesModel {
    public function getAll($params, $pdo) {
        $error = '';
        $products = [];
        $total_products = 0;

        try {
            $sql = "SELECT * FROM smart_watches WHERE 1=1";
            $count_sql = "SELECT COUNT(*) FROM smart_watches WHERE 1=1";
            $query_params = [];
            $count_params = [];

            // Фільтрація
            $this->addFilterCondition($sql, $count_sql, $query_params, $count_params, 'category', $params['categories']);
            $this->addFilterCondition($sql, $count_sql, $query_params, $count_params, 'subcategory', $params['subcategories']);
            $this->addFilterCondition($sql, $count_sql, $query_params, $count_params, 'brand', $params['brands']);
            $this->addFeaturesFilter($sql, $count_sql, $query_params, $count_params, $params['features']);
            $this->addPriceFilter($sql, $count_sql, $query_params, $count_params, $params['price_min'], $params['price_max']);

            // Кількість товарів
            $stmt = $pdo->prepare($count_sql);
            $stmt->execute($count_params);
            $total_products = $stmt->fetchColumn();

            // Сортування
            switch ($params['sort']) {
                case 'price_asc': $sql .= " ORDER BY price ASC"; break;
                case 'price_desc': $sql .= " ORDER BY price DESC"; break;
                case 'popular': $sql .= " ORDER BY views DESC"; break;
                default: $sql .= " ORDER BY created_at DESC";
            }

            // Пагінація
            $sql .= " LIMIT ? OFFSET ?";
            $query_params[] = $params['per_page'];
            $query_params[] = ($params['page'] - 1) * $params['per_page'];

            // Отримання товарів
            $stmt = $pdo->prepare($sql);
            $stmt->execute($query_params);
            $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            $error = "Помилка бази даних: " . $e->getMessage();
            error_log($error);
        }

        return [
            'products' => $products,
            'total_products' => $total_products,
            'error' => $error
        ];
    }

    private function addFilterCondition(&$sql, &$count_sql, &$query_params, &$count_params, $field, $values) {
        if (!empty($values)) {
            $placeholders = implode(',', array_fill(0, count($values), '?'));
            $sql .= " AND $field IN ($placeholders)";
            $count_sql .= " AND $field IN ($placeholders)";
            $query_params = array_merge($query_params, $values);
            $count_params = array_merge($count_params, $values);
        }
    }

    private function addFeaturesFilter(&$sql, &$count_sql, &$query_params, &$count_params, $features) {
        if (!empty($features)) {
            foreach ($features as $feature) {
                $sql .= " AND features LIKE ?";
                $count_sql .= " AND features LIKE ?";
                $query_params[] = "%$feature%";
                $count_params[] = "%$feature%";
            }
        }
    }

    private function addPriceFilter(&$sql, &$count_sql, &$query_params, &$count_params, $min, $max) {
        if ($min !== null) {
            $sql .= " AND price >= ?";
            $count_sql .= " AND price >= ?";
            $query_params[] = $min;
            $count_params[] = $min;
        }
        if ($max !== null) {
            $sql .= " AND price <= ?";
            $count_sql .= " AND price <= ?";
            $query_params[] = $max;
            $count_params[] = $max;
        }
    }
}