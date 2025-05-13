<?php

require_once __DIR__.'/../models/kitchen-tech.php';

class KitchenController
{
    public function __construct()
    {
        $this->model = new KitchenModel();
        
        // Параметры фильтрации
        $categories = isset($_GET['category']) ? (array)$_GET['category'] : [];
        $price_min = isset($_GET['price_min']) ? floatval($_GET['price_min']) : null;
        $price_max = isset($_GET['price_max']) ? floatval($_GET['price_max']) : null;
        $brands = isset($_GET['brand']) ? (array)$_GET['brand'] : [];
        $types = isset($_GET['type']) ? (array)$_GET['type'] : [];
        $sort = isset($_GET['sort']) ? $_GET['sort'] : 'newest';
        $page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
        $per_page = 12;
        
        $this->params = [
            'categories' => $categories,
            'price_min' => $price_min,
            'price_max' => $price_max,
            'brands' => $brands,
            'types' => $types,
            'sort' => $sort,
            'page' => $page,
            'per_page' => $per_page,
        ];
    }

    public function getAll($pdo, $avatar)
    {
        $products = $this->model->getAll($this->params, $pdo);
        extract($this->params);

        $allCategories = ['Холодильники', 'Плити', 'Посудомийки', 'Мікрохвильовки', 'Кавоварки', 'Блендери', 'Мультиварки', 'Мясорубки'];
        $allBrands = ['Samsung', 'Bosch', 'Philips', 'Electrolux', 'Moulinex', 'Tefal', 'Gorenje', 'Zanussi'];
        $allTypes = ['Вбудована', 'Окремостояча', 'Компактна', 'Професійна'];

        $templateData = [
            'avatar' => $avatar,
            'products' => $products['products'],
            'error' => $products['error'],
            'categories' => $allCategories,
            'brands' => $allBrands,
            'types' => $allTypes,
            'price_min' => $price_min,
            'price_max' => $price_max,
            'sort' => $sort,
            'selectedCategories' => $categories,
            'selectedBrands' => $brands,
            'selectedTypes' => $types,
            'total_products' => $products['total_products'],
            'page' => $page,
            'per_page' => $per_page
        ];
        
        extract($templateData);
        include __DIR__.'/../views/kitchen-tech.html';
    }
}
// Функція для додавання товару у вішліст
function addToWishlist(productId, productType) {
    // Показати модальне вікно з вибором вішліста
    fetch('get_user_wishlists.php')
        .then(response => response.json())
        .then(wishlists => {
            let html = '<h3>Оберіть вішліст</h3>';
            
            if (wishlists.length === 0) {
                html += '<p>У вас немає вішлістів. Створити новий?</p>';
                html += '<button onclick="createNewWishlist(' + productId + ', \'' + productType + '\')">Створити вішліст</button>';
            } else {
                html += '<select id="wishlistSelect">';
                wishlists.forEach(wishlist => {
                    html += `<option value="${wishlist.id}">${wishlist.title}</option>`;
                });
                html += '</select>';
                html += '<button onclick="addToSelectedWishlist(' + productId + ', \'' + productType + '\')">Додати</button>';
                html += '<button onclick="createNewWishlist(' + productId + ', \'' + productType + '\')">Новий вішліст</button>';
            }
            
            showModal(html);
        });
}

function addToSelectedWishlist(productId, productType) {
    const wishlistId = document.getElementById('wishlistSelect').value;
    
    fetch('add_to_wishlist.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            product_id: productId,
            product_type: productType,
            wishlist_id: wishlistId
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Товар успішно додано до вішліста!');
            closeModal();
        } else {
            alert('Помилка: ' + (data.message || 'Не вдалося додати товар'));
        }
    });
}

function createNewWishlist(productId, productType) {
    const title = prompt('Введіть назву нового вішліста:');
    if (title) {
        fetch('create_wishlist.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ title })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                addToSelectedWishlist(productId, productType, data.wishlist_id);
            } else {
                alert('Помилка при створенні вішліста');
            }
        });
    }
}

// Додайте ці кнопки на сторінку товару
<button class="btn btn-primary" onclick="addToWishlist(<?= $product['id'] ?>, 'smart_watch')">
    <i class="fas fa-heart"></i> Додати до вішліста
</button>