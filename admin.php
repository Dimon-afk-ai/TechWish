<?php include 'db.php'; ?>

<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <title>Адмін-панель</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Додати HTML-контент</h1>
    <form method="post">
        <textarea name="content" rows="10" cols="50" placeholder="Введіть HTML-код тут"></textarea><br>
        <button type="submit">Зберегти</button>
    </form>

    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $content = $_POST['content'];
        $stmt = $pdo->prepare("INSERT INTO pages (content) VALUES (:content)");
        $stmt->execute(['content' => $content]);
        echo "<p>Контент збережено!</p>";
    }
    ?>
</body>
</html>
