<?php
require_once __DIR__.'/../boot.php';

class ProductController {
    private $pdo;
    
    public function __construct() {
        $this->pdo = pdo(); // Используем функцию pdo() из boot.php
    }
    
    public function addToWishlist($productId, $productType, $wishlistId) {
        // Отримати дані товару з відповідної таблиці
        $product = $this->getProductData($productId, $productType);
        
        if (!$product) {
            return ['error' => 'Product not found'];
        }

        // Подключаем WishlistController только когда он нужен
        require_once __DIR__.'/../controllers/WishlistController.php';
        
        // Додати до вішліста
        $wishlistController = new WishlistController();
        $result = $wishlistController->addItem($wishlistId, [
            'product_id' => $productId,
            'product_type' => $productType,
            'title' => $product['name'],
            'description' => $product['description'] ?? '',
            'price' => $product['price'],
            'image_url' => $product['image_url'],
            'product_url' => $this->generateProductUrl($productId, $productType)
        ]);

        return $result;
    }

    private function getProductData($productId, $productType) {
        try {
            // В зависимости от типа продукта выбираем нужную таблицу
            $table = $this->getTableNameByProductType($productType);
            if (!$table) {
                return null;
            }
            
            $stmt = $this->pdo->prepare("SELECT * FROM $table WHERE id = ?");
            $stmt->execute([$productId]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error getting product data: " . $e->getMessage());
            return null;
        }
    }
    
    private function getTableNameByProductType($productType) {
        switch ($productType) {
            case 'smart_watch': return 'smart_watches';
            // Добавьте другие типы товаров при необходимости
            default: return null;
        }
    }

    private function generateProductUrl($productId, $productType) {
        // Генеруємо URL для товару
        switch ($productType) {
            case 'smart_watch': return "smart_watches.php?id=$productId";
            // Додати інші типи товарів
            default: return "#";
        }
    }
}