<?php

class AccessoriesModel 
{
    public function getAll($params, $pdo) {
        $error = '';
        $products = [];
        $total_products = 0;

        try {
            $sql = "SELECT * FROM gadget_accessories WHERE 1=1";
            $count_sql = "SELECT COUNT(*) FROM gadget_accessories WHERE 1=1";
            $query_params = [];
            $count_params = [];

            // Фільтрація
            $this->addFilterCondition($sql, $count_sql, $query_params, $count_params, 'category', $params['categories']);
            $this->addPriceFilter($sql, $count_sql, $query_params, $count_params, $params['price_min'], $params['price_max']);
            $this->addFilterCondition($sql, $count_sql, $query_params, $count_params, 'brand', $params['brands']);
            $this->addFilterCondition($sql, $count_sql, $query_params, $count_params, 'type', $params['types']);
            $this->addFilterCondition($sql, $count_sql, $query_params, $count_params, 'connection_type', $params['connections']);
            $this->addCapacityFilter($sql, $count_sql, $query_params, $count_params, $params['capacities']);

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

    private function addCapacityFilter(&$sql, &$count_sql, &$query_params, &$count_params, $capacities) {
        if (!empty($capacities)) {
            $conditions = [];
            foreach ($capacities as $capacity) {
                switch ($capacity) {
                    case 'До 64GB':
                        $conditions[] = "(capacity <= 64 OR capacity LIKE '%64GB%')";
                        break;
                    case '64GB-256GB':
                        $conditions[] = "((capacity > 64 AND capacity <= 256) OR capacity LIKE '%128GB%' OR capacity LIKE '%256GB%')";
                        break;
                    case '256GB-1TB':
                        $conditions[] = "((capacity > 256 AND capacity <= 1024) OR capacity LIKE '%512GB%' OR capacity LIKE '%1TB%')";
                        break;
                    case '1TB і більше':
                        $conditions[] = "(capacity > 1024 OR capacity LIKE '%2TB%' OR capacity LIKE '%4TB%')";
                        break;
                }
            }
            if (!empty($conditions)) {
                $sql .= " AND (" . implode(' OR ', $conditions) . ")";
                $count_sql .= " AND (" . implode(' OR ', $conditions) . ")";
            }
        }
    }
}