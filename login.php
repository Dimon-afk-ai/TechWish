<?php
require_once __DIR__.'/boot.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    
    if (empty($username) || empty($password)) {
        flash('Логин и пароль обязательны');
        header('Location: login.php');
        exit;
    }
    
    try {
        $pdo = pdo();
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch();
        
        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_nickname'] = $user['username'];
            header('Location: index.php');
            exit;
        }
        
        flash('Неверные учетные данные');
    } catch (PDOException $e) {
        flash('Ошибка базы данных');
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Вход в Империум</title>
    <style>
        body {
            background: url('https://i.pinimg.com/736x/23/2f/b0/232fb0f2c9d89634b4bd81344f7c62de.jpg') no-repeat center center fixed;
            background-size: cover;
            font-family: 'Cinzel', serif;
            color: #FFD700;
            text-align: center;
        }
        .container {
            background: rgba(0, 0, 0, 0.8);
            padding: 20px;
            border: 2px solid #FFD700;
            width: 320px;
            margin: auto;
            margin-top: 100px;
            border-radius: 10px;
            box-shadow: 0px 0px 20px rgba(255, 215, 0, 0.5);
        }
        input {
            width: calc(100% - 22px);
            padding: 10px;
            margin: 10px 0;
            border: 2px solid #FFD700;
            background: #333;
            color: #FFD700;
            font-size: 16px;
            border-radius: 5px;
            text-align: center;
        }
        button {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 2px solid #FFD700;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            border-radius: 5px;
            background: #8B0000;
            color: white;
        }
        button:hover {
            background: #FFD700;
            color: black;
        }
        .register-link {
            margin-top: 15px;
            color: #FFD700;
            text-decoration: none;
            display: inline-block;
            padding: 8px 15px;
            border: 1px solid #FFD700;
            border-radius: 5px;
            transition: all 0.3s;
        }
        .register-link:hover {
            background: rgba(255, 215, 0, 0.2);
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Вход в Империум</h1>

        <?php if (isset($_SESSION['flash'])): ?>
            <div class="alert alert-danger mb-3">
                <?php echo $_SESSION['flash']; ?>
                <?php unset($_SESSION['flash']); ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="login.php">
            <input type="text" name="username" placeholder="Логин Акколита" required>
            <input type="password" name="password" placeholder="Код Генетического Наследия" required>
            <button type="submit" name="login">Войти в Орден</button>
        </form>

        <a href="register.php" class="register-link">Еще не зарегистрированы?</a>
    </div>
</body>
</html>