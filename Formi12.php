<?php 
session_start();

// Проверка, что корзина существует и не пуста
if (!isset($_SESSION['cart']) || !is_array($_SESSION['cart']) || empty($_SESSION['cart'])) {
    echo "<h2>Помилка: кошик порожній.</h2>";
    echo "<p><a href='Formi11.php'>Повернутися до магазину</a></p>";
    exit();
}

$total = 0; // Инициализация переменной для хранения общей суммы заказа
?>

<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Оформлення замовлення</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background: url('https://vignette.wikia.nocookie.net/warhammer40k/images/3/32/Warhammer-40000-Armageddon-Background-Yarricks-Camp.jpg/revision/latest?cb=20180220124218&path-prefix=ru') no-repeat center center/cover;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .container {
            max-width: 900px;
            background: rgba(0, 0, 0, 0.85);
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 15px rgba(255, 255, 0, 0.7);
            color: white;
            display: flex;
            flex-direction: row;
            gap: 20px;
        }
        .left-panel, .right-panel {
            flex: 1;
            padding: 20px;
        }
        .left-panel {
            border-right: 1px solid #ffcc00;
        }
        input, textarea {
            width: calc(100% - 20px);
            padding: 8px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
            background: #333;
            color: white;
        }
        .cart-items {
            margin: 20px 0;
            text-align: left;
        }
        .cart-item {
            padding: 10px;
            border-bottom: 1px solid #ffcc00;
        }
        .cart-item p {
            margin: 5px 0;
        }
        .order-total {
            font-size: 20px;
            margin: 15px 0;
            font-weight: bold;
            color: #ffcc00;
        }
        select, button {
            padding: 10px;
            margin: 10px 0;
            border-radius: 5px;
        }
        button {
            background: #ffcc00;
            color: black;
            cursor: pointer;
        }
        button:hover {
            background: #e0b800;
        }
        .delivery-methods img {
            width: 50px;
            height: auto;
            margin: 5px;
        }
        .right-panel label {
            margin-top: 10px;
        }
        .hidden {
            display: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Левая панель: Замовлення и Форма -->
        <div class="left-panel">
            <h2>Ваше замовлення</h2>
            <div class="cart-items">
                <?php foreach ($_SESSION['cart'] as $item): ?>
                    <div class="cart-item">
                        <p><strong>Товар:</strong> <?= isset($item['name']) ? htmlspecialchars($item['name']) : 'Невідомий товар'; ?></p>
                        <p><strong>Кількість:</strong> <?= isset($item['quantity']) ? $item['quantity'] : '0'; ?></p>
                        <p><strong>Ціна за одиницю:</strong> <?= isset($item['price']) ? $item['price'] . ' грн' : '0 грн'; ?></p>
                        <p><strong>Загальна ціна:</strong> <?= isset($item['price']) ? $item['price'] * $item['quantity'] . ' грн' : '0 грн'; ?></p>
                    </div>
                    <?php $total += isset($item['price']) ? $item['price'] * $item['quantity'] : 0; ?>
                <?php endforeach; ?>
            </div>
            <p class="order-total">Загальна сума: <?= number_format($total, 2); ?> грн</p>
            
            <h3>Інформація про замовлення</h3>
            <form action="submit_order.php" method="post">
                <input type="hidden" name="total" value="<?= $total; ?>">
                
                <label for="name">Ім'я:</label>
                <input type="text" id="name" name="name" required>
                
                <label for="phone">Телефон:</label>
                <input type="tel" id="phone" name="phone" required>
                
                <label for="address">Адреса доставки:</label>
                <input type="text" id="address" name="address" required>
                
                <label for="order_details">Деталі замовлення:</label>
                <textarea id="order_details" name="order_details" rows="4" required></textarea>
            </form>
        </div>

        <!-- Правая панель: Оплата и Доставка -->
        <div class="right-panel">
            <h3>Оплата</h3>
            <label for="payment">Спосіб оплати:</label>
            <select id="payment" name="payment" onchange="togglePaymentFields()">
                <option value="cash">Готівкою</option>
                <option value="card">Картою</option>
            </select>

            <div id="card-details" class="hidden">
                <label for="card_number">Номер картки:</label>
                <input type="text" id="card_number" name="card_number" placeholder="1234 5678 9012 3456">
                
                <label for="card_expiry">Термін дії:</label>
                <input type="text" id="card_expiry" name="card_expiry" placeholder="MM/YY">
                
                <label for="card_cvc">CVC код:</label>
                <input type="text" id="card_cvc" name="card_cvc" placeholder="123">
            </div>

            <h3>Доставка</h3>
            <div class="delivery-methods">
                <label>
                    <input type="radio" name="delivery" value="nova_poshta" required>
                    <img src="https://img.crm-onebox.com//media/97/ff/97ff04254dde5d65aab1c4ca90d17a95.png" alt="Нова Пошта">
                </label>
                <label>
                    <input type="radio" name="delivery" value="ukr_poshta">
                    <img src="https://play-lh.googleusercontent.com/XPU-lVcPPwMTaxZd_85SmiXws0JfvOzuQId8_zVPKZowZ1In8fv1NxNZV-VAx-t-9A" alt="Укрпошта">
                </label>
                <label>
                    <input type="radio" name="delivery" value="meest">
                    <img src="https://somic.com.ua/files/news/9/19/19.png" alt="Meest Express">
                </label>
            </div>
            <br>
            <button type="submit">Підтвердити замовлення</button>
            <a href="Formi11.php">
            <button type="button" style="background: #d9534f; color: white;">⬅ Назад</button>
        </a>
        </div>
    </div>

    <script>
        function togglePaymentFields() {
            var paymentMethod = document.getElementById("payment").value;
            var cardDetails = document.getElementById("card-details");
            cardDetails.style.display = (paymentMethod === "card") ? "block" : "none";
        }

        document.addEventListener("DOMContentLoaded", togglePaymentFields);
    </script>
</body>
</html>
