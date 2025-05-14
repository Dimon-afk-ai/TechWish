<?php
require_once __DIR__.'/../boot.php'; // Подключаем boot.php для доступа к функции pdo()

class WishlistModel {
    private $pdo;

    public function __construct() {
        $this->pdo = pdo(); // Используем функцию pdo() из boot.php
    }

    public function createWishlist($userId, $title, $description, $isPublic) {
        try {
            $stmt = $this->pdo->prepare("
                INSERT INTO wishlists (user_id, title, description, is_public)
                VALUES (?, ?, ?, ?)
            ");
            $stmt->execute([$userId, $title, $description, $isPublic ? 1 : 0]);
            return $this->pdo->lastInsertId();
        } catch (PDOException $e) {
            error_log("Error creating wishlist: " . $e->getMessage());
            return false;
        }
    }

    public function addItem($wishlistId, $productData) {
        try {
            $stmt = $this->pdo->prepare("
                INSERT INTO wishlist_items 
                (wishlist_id, title, description, price, image_url, product_url)
                VALUES (?, ?, ?, ?, ?, ?)
            ");
            $stmt->execute([
                $wishlistId,
                $productData['title'],
                $productData['description'],
                $productData['price'],
                $productData['image_url'],
                $productData['product_url']
            ]);
            return $this->pdo->lastInsertId();
        } catch (PDOException $e) {
            error_log("Error adding wishlist item: " . $productData['title'] . " - " . $e->getMessage());
            return false;
        }
    }

    public function reserveItem($itemId, $userId) {
        try {
            $stmt = $this->pdo->prepare("
                UPDATE wishlist_items 
                SET is_reserved = 1, reserved_by = ?
                WHERE id = ? AND is_reserved = 0 AND is_purchased = 0
            ");
            return $stmt->execute([$userId, $itemId]);
        } catch (PDOException $e) {
            error_log("Error reserving item: " . $e->getMessage());
            return false;
        }
    }

    public function markAsPurchased($itemId) {
        try {
            $stmt = $this->pdo->prepare("
                UPDATE wishlist_items 
                SET is_purchased = 1 
                WHERE id = ? AND is_reserved = 1
            ");
            return $stmt->execute([$itemId]);
        } catch (PDOException $e) {
            error_log("Error marking item as purchased: " . $e->getMessage());
            return false;
        }
    }

    public function getUserWishlists($userId) {
        try {
            $stmt = $this->pdo->prepare("
                SELECT * FROM wishlists 
                WHERE user_id = ?
                ORDER BY created_at DESC
            ");
            $stmt->execute([$userId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error getting user wishlists: " . $e->getMessage());
            return [];
        }
    }

    public function getPublicWishlists($page = 1, $perPage = 10) {
        $offset = ($page - 1) * $perPage;
        
        try {
            // Отримати вішлісти
            $stmt = $this->pdo->prepare("
                SELECT w.*, u.username, u.avatar 
                FROM wishlists w
                JOIN users u ON w.user_id = u.id
                WHERE w.is_public = 1
                ORDER BY w.created_at DESC
                LIMIT ? OFFSET ?
            ");
            $stmt->bindValue(1, $perPage, PDO::PARAM_INT);
            $stmt->bindValue(2, $offset, PDO::PARAM_INT);
            $stmt->execute();
            $wishlists = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Отримати кількість для пагінації
            $countStmt = $this->pdo->query("
                SELECT COUNT(*) FROM wishlists 
                WHERE is_public = 1
            ");
            $total = $countStmt->fetchColumn();

            return [
                'wishlists' => $wishlists,
                'total' => $total,
                'page' => $page,
                'per_page' => $perPage
            ];
        } catch (PDOException $e) {
            error_log("Error getting public wishlists: " . $e->getMessage());
            return ['wishlists' => [], 'total' => 0];
        }
    }

    public function getWishlistById($wishlistId) {
        try {
            $stmt = $this->pdo->prepare("
                SELECT w.*, u.username, u.avatar 
                FROM wishlists w
                JOIN users u ON w.user_id = u.id
                WHERE w.id = ?
            ");
            $stmt->execute([$wishlistId]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error getting wishlist: " . $e->getMessage());
            return false;
        }
    }

    public function getWishlistItems($wishlistId) {
        try {
            $stmt = $this->pdo->prepare("
                SELECT wi.*, u.username as reserved_by_username
                FROM wishlist_items wi
                LEFT JOIN users u ON wi.reserved_by = u.id
                WHERE wi.wishlist_id = ?
                ORDER BY wi.created_at DESC
            ");
            $stmt->execute([$wishlistId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error getting wishlist items: " . $e->getMessage());
            return [];
        }
    }
}