<?php
require_once __DIR__.'/../models/WishlistModel.php';
require_once __DIR__.'/../models/UserModel.php';
// Убираем импорт ProductController.php, чтобы избежать циклической зависимости

class WishlistController {
    private $wishlistModel;
    private $userModel;
    
    public function __construct() {
        $this->wishlistModel = new WishlistModel();
        $this->userModel = new UserModel();
    }
    
    // Метод getAll для отображения всех доступных вишлистов на странице
    public function getAll($pdo, $avatar) {
        try {
            // Получаем публичные вишлисты 
            $publicWishlists = $this->getPublicWishlists();
            
            // Получаем пользовательские вишлисты, если пользователь авторизован
            $userWishlists = [];
            if (isset($_SESSION['user_id'])) {
                $userWishlists = $this->getUserWishlists($_SESSION['user_id']);
            }
            
            // Отображаем страницу с вишлистами
            include __DIR__ . '/../views/wishlist_view.html';
            
        } catch (Exception $e) {
            echo "Ошибка при получении вишлистов: " . $e->getMessage();
        }
    }
    
    // Створити новий вішліст
    public function createWishlist($userId, $title, $description, $isPublic) {
        return $this->wishlistModel->createWishlist($userId, $title, $description, $isPublic);
    }
    
    // Додати товар до вішліста
    public function addItem($wishlistId, $productData) {
        return $this->wishlistModel->addItem($wishlistId, $productData);
    }
    
    // Зарезервувати товар
    public function reserveItem($itemId, $userId) {
        return $this->wishlistModel->reserveItem($itemId, $userId);
    }
    
    // Позначити як куплений
    public function markAsPurchased($itemId) {
        return $this->wishlistModel->markAsPurchased($itemId);
    }
    
    // Отримати вішлісти користувача
    public function getUserWishlists($userId) {
        return $this->wishlistModel->getUserWishlists($userId);
    }
    
    // Отримати публічні вішлісти
    public function getPublicWishlists($page = 1, $perPage = 10) {
        return $this->wishlistModel->getPublicWishlists($page, $perPage);
    }
    
    // Отримати деталі вішліста
    public function getWishlistDetails($wishlistId, $currentUserId = null) {
        $wishlist = $this->wishlistModel->getWishlistById($wishlistId);
        if (!$wishlist) {
            return ['error' => 'Wishlist not found'];
        }
        
        // Перевірка доступу
        if (!$wishlist['is_public'] && $wishlist['user_id'] != $currentUserId) {
            return ['error' => 'Access denied'];
        }
        
        $items = $this->wishlistModel->getWishlistItems($wishlistId);
        $owner = $this->userModel->getUserById($wishlist['user_id']);
        
        return [
            'wishlist' => $wishlist,
            'items' => $items,
            'owner' => $owner
        ];
    }
}