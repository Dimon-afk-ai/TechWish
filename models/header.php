<?php
// Устанавливаем аватар по умолчанию
$avatar = 'default-avatar.jpg';
if (isset($_SESSION['user_id'])) {
	try {
		$stmt = $pdo->prepare("SELECT username, avatar FROM users WHERE id = ?");
		$stmt->execute([$_SESSION['user_id']]);
		$user = $stmt->fetch();

		if ($user) {
			$_SESSION['username'] = $user['username'];
			$_SESSION['user_nickname'] = $user['username'];
			$avatar = !empty($user['avatar']) ? $user['avatar'] : 'default-avatar.jpg';
		}
	} catch (PDOException $e) {
		$error = "Ошибка получения данных пользователя: " . $e->getMessage();
		error_log($error);
	}
}