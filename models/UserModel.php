<?php
require_once __DIR__.'/../boot.php'; // Подключаем boot.php для доступа к функции pdo()

class UserModel {
    private $pdo;

    public function __construct() {
        $this->pdo = pdo(); // Используем функцию pdo() из boot.php
    }
    
    public function getUserById($userId) {
        try {
            $stmt = $this->pdo->prepare("
                SELECT id, username, email, avatar, created_at 
                FROM users 
                WHERE id = ?
            ");
            $stmt->execute([$userId]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error getting user by ID: " . $e->getMessage());
            return false;
        }
    }
    
    // Здесь можно добавить другие методы для работы с пользователями
}