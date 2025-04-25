<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__.'/boot.php';

// Получаем данные пользователя если авторизован
$user = null;
$avatar_path = '/images/default-avatar.jpg';

if (isset($_SESSION['user_id'])) {
    try {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$_SESSION['user_id']]);
        $user = $stmt->fetch();
        
        if ($user && !empty($user['avatar'])) {
            $avatar_path = (strpos($user['avatar'], 'http') === 0) 
                ? $user['avatar'] 
                : '/' . ltrim($user['avatar'], '/');
        }
        
        $avatar_path .= (strpos($avatar_path, '?') === false ? '?' : '&') . 't=' . time();
    } catch (PDOException $e) {
        error_log("Ошибка получения данных пользователя: " . $e->getMessage());
    }
}

// Обработка сообщений
$success_message = $_SESSION['success'] ?? null;
$error_message = $_SESSION['error'] ?? null;
unset($_SESSION['success'], $_SESSION['error']);
?>
<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($user) ? htmlspecialchars($user['username']) : 'Профіль' ?> | TechWish</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #0066cc;
            --secondary: #00ccff;
            --accent: #ff3366;
            --dark-bg: rgba(0, 0, 0, 0.8);
        }
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            color: white;
            background: url('https://images.unsplash.com/photo-1620712943543-bcc4688e7485?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1920&q=80') no-repeat center/cover fixed;
        }
        .profile-container {
            max-width: 800px;
            margin: 50px auto;
            background: var(--dark-bg);
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 102, 204, 0.5);
            border: 2px solid var(--primary);
        }
        .profile-header {
            display: flex;
            align-items: center;
            margin-bottom: 30px;
        }
        .profile-avatar {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid var(--secondary);
            margin-right: 30px;
        }
        .profile-info h2 {
            color: var(--secondary);
            margin-bottom: 10px;
        }
        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 4px;
        }
        .alert-success {
            background-color: #d4edda;
            color: #155724;
        }
        .alert-error {
            background-color: #f8d7da;
            color: #721c24;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
            color: var(--secondary);
        }
        .form-group input, 
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 10px;
            background: rgba(51, 51, 51, 0.8);
            border: 1px solid var(--secondary);
            color: white;
            border-radius: 5px;
        }
        .btn {
            background: var(--primary);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            transition: all 0.3s;
            display: inline-block;
            margin-right: 10px;
            text-decoration: none;
            text-align: center;
        }
        .btn:hover {
            background: var(--accent);
        }
        .btn-exit {
            background: var(--accent);
        }
        .nav-buttons {
            display: flex;
            justify-content: space-between;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid var(--secondary);
        }
        .password-section {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid var(--secondary);
        }
    </style>
</head>
<body>
    <div class="profile-container">
        <?php if ($success_message): ?>
            <div class="alert alert-success"><?= htmlspecialchars($success_message) ?></div>
        <?php endif; ?>
        
        <?php if ($error_message): ?>
            <div class="alert alert-error"><?= htmlspecialchars($error_message) ?></div>
        <?php endif; ?>

        <?php if ($user): ?>
            <div class="profile-header">
                <img src="<?= htmlspecialchars($avatar_path) ?>" alt="Аватар" class="profile-avatar" id="avatarPreview">
                <div class="profile-info">
                    <h2><?= htmlspecialchars($user['username']) ?></h2>
                    <p><i class="fas fa-calendar-alt"></i> Зареєстрований з: <?= date('d.m.Y', strtotime($user['created_at'])) ?></p>
                </div>
            </div>

            <form action="update_profile.php" method="post" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="avatar"><i class="fas fa-camera"></i> Аватар:</label>
                    <input type="file" id="avatar" name="avatar" onchange="previewAvatar(this)">
                </div>
                <div class="form-group">
                    <label for="username"><i class="fas fa-user"></i> Ім'я користувача:</label>
                    <input type="text" id="username" name="username" value="<?= htmlspecialchars($user['username']) ?>">
                </div>
                <div class="form-group">
                    <label for="email"><i class="fas fa-envelope"></i> Email:</label>
                    <input type="email" id="email" name="email" value="<?= htmlspecialchars($user['email'] ?? '') ?>">
                </div>
                <button type="submit" class="btn"><i class="fas fa-save"></i> Зберегти зміни</button>
            </form>

            <div class="password-section">
                <h3><i class="fas fa-lock"></i> Зміна пароля</h3>
                <form action="change_password.php" method="post">
                    <div class="form-group">
                        <label for="current_password"><i class="fas fa-key"></i> Поточний пароль:</label>
                        <input type="password" id="current_password" name="current_password" required>
                    </div>
                    <div class="form-group">
                        <label for="new_password"><i class="fas fa-key"></i> Новий пароль:</label>
                        <input type="password" id="new_password" name="new_password" required>
                    </div>
                    <div class="form-group">
                        <label for="confirm_password"><i class="fas fa-key"></i> Підтвердіть пароль:</label>
                        <input type="password" id="confirm_password" name="confirm_password" required>
                    </div>
                    <button type="submit" class="btn"><i class="fas fa-exchange-alt"></i> Змінити пароль</button>
                </form>
            </div>

            <div class="nav-buttons">
                <a href="index.php" class="btn"><i class="fas fa-home"></i> На головну</a>
                <a href="logout.php" class="btn btn-exit"><i class="fas fa-sign-out-alt"></i> Вийти</a>
            </div>
        <?php else: ?>
            <p>Будь ласка, <a href="login.php">увійдіть</a> для перегляду профілю.</p>
        <?php endif; ?>
    </div>

    <script>
        function previewAvatar(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('avatarPreview').src = e.target.result;
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
</body>
</html>