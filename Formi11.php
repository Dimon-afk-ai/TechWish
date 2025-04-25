<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Корзина покупок</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #1a1a1a;
            color: #e0e0e0;
            margin: 0;
            padding: 0;
        }
        header {
            background-color: #000;
            padding: 20px;
            text-align: center;
        }
        header h1 {
            margin: 0;
            color: #ffcc00;
        }
        .container {
            padding: 20px;
        }
        .product {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
            padding: 10px;
            border: 1px solid #333;
            border-radius: 8px;
            background-color: #2a2a2a;
        }
        .product img {
            width: 120px;
            height: 120px;
            margin-right: 20px;
            border-radius: 8px;
        }
        .product-details {
            flex-grow: 1;
        }
        .product h2 {
            margin: 0;
            color: #ffcc00;
        }
        .product p {
            margin: 5px 0;
        }
        .product .price {
            font-size: 1.2em;
            color: #ffcc00;
            font-weight: bold;
        }
        .summary {
            margin-top: 20px;
            text-align: center;
            padding: 10px;
            background-color: #2a2a2a;
            border-radius: 8px;
        }
        .summary p {
            margin: 5px 0;
        }
        .checkout {
            text-align: center;
            margin-top: 20px;
        }
        .checkout a {
            display: inline-block;
            padding: 10px 20px;
            background-color: #ffcc00;
            color: #1a1a1a;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }
        .checkout a:hover {
            background-color: #e0b800;
        }
        button {
            background-color: #ffcc00;
            border: none;
            padding: 10px;
            border-radius: 5px;
            cursor: pointer;
            color: #1a1a1a;
            transition: background-color 0.3s ease;
        }
        button:hover {
            background-color: #e0b800;
        }
    </style>
</head>
<body>
    <header>
        <h1>Корзина покупок</h1>
    </header>
    <div class="container">
        <div class="product" id="product1">
            <img src="https://ihrova-maysternya.com/content/images/11/155x160l80nn0/peredzamovlennia-nabir-miniatiur-warhammer-40000-captain-in-terminator-armour-12178670394069.jpg" alt="Space Marine">
            <div class="product-details">
                <h2>Космічний Десантник</h2>
                <p>Елітний воїн Імперіуму Людства, готовий до будь-якої битви.</p>
                <p class="price">500 грн</p>
                <button onclick="addToCart(500)">Додати в корзину</button>
            </div>
        </div>
        <div class="product" id="product2">
            <img src="https://ihrova-maysternya.com/content/images/49/387x400l85nn0/43859090726840.webp" alt="Хаос">
            <div class="product-details">
                <h2>Воїн Хаосу</h2>
                <p>Служитель темних богів, несущий хаос і руйнування.</p>
                <p class="price">450 грн</p>
                <button onclick="addToCart(450)">Додати в корзину</button>
            </div>
        </div>
        <div class="product" id="product3">
            <img src="https://domigr.com.ua/image/cache/data/10_warhammer/warhammer40000/orks-boss-snikrot/Orks_Boss_Snikrot_01-376x376.jpg" alt="Орки">
            <div class="product-details">
                <h2>Орк Воїн</h2>
                <p>Дикий та могутній воїн, що живе заради війни та грабунку.</p>
                <p class="price">400 грн</p>
                <button onclick="addToCart(400)">Додати в корзину</button>
            </div>
        </div>
        <div class="product" id="product4">
            <img src="https://domigr.com.ua/image/cache/data/10_warhammer/warhammer40000/ael-warlocks/AEL_Warlocks_01-376x376.jpg" alt="Ельдар">
            <div class="product-details">
                <h2>Ельдар Провидець</h2>
                <p>Могутній психік, здатний передбачити майбутнє та керувати магією.</p>
                <p class="price">550 грн</p>
                <button onclick="addToCart(550)">Додати в корзину</button>
            </div>
        </div>
        <div class="summary">
            <h2>Сумарна вартість:</h2>
            <p id="total">0 грн</p>
        </div>
        <div class="checkout">
            <a href="checkout.html">Оформити замовлення</a>
        </div>
    </div>

    <script>
        let totalCost = 0;

        function addToCart(price) {
            totalCost += price;
            document.getElementById('total').textContent = totalCost + ' грн';
        }
    </script>
</body>
</html>



<script>
    let totalCost = 0;

    function addToCart(price) {
        totalCost += price;
        document.getElementById('total').textContent = totalCost + ' грн';
    }

    document.getElementById('checkout-link').addEventListener('click', function(event) {
        event.preventDefault();
        window.location.href = "Formi12.php?total=" + totalCost;
    });
</script>
<script>
    let cart = [];

    function addToCart(price, name) {
        cart.push({ name: name, price: price, quantity: 1 });
        updateTotal();
        sessionStorage.setItem('cart', JSON.stringify(cart));
    }

    function updateTotal() {
        let total = cart.reduce((sum, item) => sum + item.price * item.quantity, 0);
        document.getElementById('total').textContent = total + ' грн';
    }

    function sendCartToPHP() {
        const cartData = sessionStorage.getItem('cart');
        if (cartData) {
            fetch('save_cart.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: cartData
            }).then(() => {
                window.location.href = 'Formi12.php';
            });
        } else {
            alert("Корзина порожня!");
        }
    }

    document.querySelector('.checkout a').addEventListener('click', function (event) {
        event.preventDefault(); // Останавливаем стандартное действие ссылки
        sendCartToPHP();
    });
</script>
<br>
        <a href="index.php">
            <button type="button" style="background: #d9534f; color: white;">⬅ Назад</button>
        </a>
    </div>
</body>
</html>