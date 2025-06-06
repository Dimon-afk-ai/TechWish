<?php

class HomeAppliancesModel
{
    public function getAll($params, $pdo)
    {
        $error = '';
        $products = [];
        $total_products = 0;

        try {
            // Проверяем существование таблицы
            $tableExists = $pdo->query("SHOW TABLES LIKE 'home_appliances'")->rowCount() > 0;
            
            if (!$tableExists) {
                throw new PDOException("Таблица 'home_appliances' не существует");
            }

            // Основной запрос
            $sql = "SELECT * FROM home_appliances WHERE 1=1";
            $count_sql = "SELECT COUNT(*) FROM home_appliances WHERE 1=1";
            $query_params = [];
            $count_params = [];

            // Добавляем условия фильтрации
            $this->addFilterCondition($sql, $count_sql, $query_params, $count_params, 
                'category', $params['categories']);
            $this->addPriceFilter($sql, $count_sql, $query_params, $count_params, 
                $params['price_min'], $params['price_max']);
            $this->addFilterCondition($sql, $count_sql, $query_params, $count_params, 
                'brand', $params['brands']);
            $this->addFilterCondition($sql, $count_sql, $query_params, $count_params, 
                'power', $params['powers']);

            // Получаем общее количество
            $stmt = $pdo->prepare($count_sql);
            $stmt->execute($count_params);
            $total_products = $stmt->fetchColumn();

            // Добавляем сортировку
            switch ($params['sort']) {
                case 'price_asc': $sql .= " ORDER BY price ASC"; break;
                case 'price_desc': $sql .= " ORDER BY price DESC"; break;
                case 'popular': $sql .= " ORDER BY views DESC"; break;
                default: $sql .= " ORDER BY created_at DESC";
            }

            // Добавляем пагинацию
            $sql .= " LIMIT ? OFFSET ?";
            $query_params[] = $params['per_page'];
            $query_params[] = ($params['page'] - 1) * $params['per_page'];

            // Получаем товары
            $stmt = $pdo->prepare($sql);
            $stmt->execute($query_params);
            $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            $error = "Ошибка базы данных: " . $e->getMessage();
            error_log($error);
        }

        return [
            'products' => $products,
            'total_products' => $total_products,
            'error' => $error
        ];
    }

    private function addFilterCondition(&$sql, &$count_sql, &$query_params, &$count_params, $field, $values)
    {
        if (!empty($values)) {
            $placeholders = implode(',', array_fill(0, count($values), '?'));
            $sql .= " AND $field IN ($placeholders)";
            $count_sql .= " AND $field IN ($placeholders)";
            $query_params = array_merge($query_params, $values);
            $count_params = array_merge($count_params, $values);
        }
    }

    private function addPriceFilter(&$sql, &$count_sql, &$query_params, &$count_params, $min, $max)
    {
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