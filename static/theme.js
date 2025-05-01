/**
 * theme.js - Управління темами оформлення
 */

document.addEventListener('DOMContentLoaded', function() {
    // Елементи вкладок
    const tabButtons = document.querySelectorAll('.tab-btn');
    const tabPanes = document.querySelectorAll('.tab-pane');

    // Налаштування теми
    const themeSelect = document.getElementById('theme');
    const body = document.body;
    
    // Обробка переключення вкладок
    tabButtons.forEach(button => {
        button.addEventListener('click', function() {
            // Видаляємо активний клас з усіх кнопок та панелей
            tabButtons.forEach(btn => btn.classList.remove('active'));
            tabPanes.forEach(pane => pane.classList.remove('active'));
            
            // Додаємо активний клас поточній кнопці
            this.classList.add('active');
            
            // Показуємо відповідну панель
            const tabId = this.getAttribute('data-tab');
            document.getElementById(tabId).classList.add('active');
        });
    });

    // Функція попереднього перегляду аватара
    function previewAvatar(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('avatarPreview').src = e.target.result;
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    // Прикріплюємо обробник до поля завантаження аватара
    const avatarInput = document.getElementById('avatar');
    if (avatarInput) {
        avatarInput.addEventListener('change', function() {
            previewAvatar(this);
        });

        // Додаємо обробник для кнопки зміни аватара
        const avatarChangeBtn = document.querySelector('.avatar-change-btn');
        if (avatarChangeBtn) {
            avatarChangeBtn.addEventListener('click', function(e) {
                e.preventDefault();
                avatarInput.click();
            });
        }
    }

    // Обробка зміни теми
    if (themeSelect) {
        // Застосувати поточну тему при завантаженні сторінки
        applyTheme(themeSelect.value);
        
        // Обробник зміни теми
        themeSelect.addEventListener('change', function() {
            applyTheme(this.value);
            
            // Тут можна зберегти вибір теми в localstorage для попереднього перегляду
            localStorage.setItem('preferredTheme', this.value);
        });
    }
    
    // Застосувати тему
    function applyTheme(theme) {
        // Спочатку видаляємо всі класи тем
        body.classList.remove('light-theme', 'dark-theme', 'cyberpunk');
        
        // Додаємо відповідний клас до body
        switch(theme) {
            case 'light':
                body.classList.add('light-theme');
                break;
            case 'cyberpunk':
                body.classList.add('cyberpunk');
                break;
            default: // 'dark' - за замовчуванням
                body.classList.add('dark-theme');
        }
    }
    
    // Індикатор надійності пароля
    const passwordInput = document.getElementById('new_password');
    const strengthBar = document.querySelector('.strength-bar');
    const strengthText = document.querySelector('.strength-text');
    
    if (passwordInput && strengthBar && strengthText) {
        passwordInput.addEventListener('input', function() {
            const password = this.value;
            let strength = 0;
            
            // Оцінюємо надійність пароля
            if (password.length > 6) strength += 1;
            if (password.length > 10) strength += 1;
            if (/[A-Z]/.test(password)) strength += 1;
            if (/[0-9]/.test(password)) strength += 1;
            if (/[!@#$%^&*]/.test(password)) strength += 1;

            // Оновлюємо індикатор
            const strengthPercent = (strength / 5) * 100;
            strengthBar.style.width = strengthPercent + '%';
            
            // Оновлюємо колір та текст
            if (strength < 2) {
                strengthBar.style.backgroundColor = '#ff3366';
                strengthText.textContent = 'Слабкий пароль';
            } else if (strength < 4) {
                strengthBar.style.backgroundColor = '#ffa500';
                strengthText.textContent = 'Середній пароль';
            } else {
                strengthBar.style.backgroundColor = '#00cc66';
                strengthText.textContent = 'Надійний пароль';
            }
        });
    }
    
    // Валідація форми зміни пароля
    const passwordForm = document.querySelector('form[action="change_password.php"]');
    const confirmPasswordInput = document.getElementById('confirm_password');
    
    if (passwordForm && passwordInput && confirmPasswordInput) {
        passwordForm.addEventListener('submit', function(e) {
            if (passwordInput.value !== confirmPasswordInput.value) {
                e.preventDefault();
                alert('Паролі не співпадають. Будь ласка, перевірте введені дані.');
            }
        });
    }

    // Завантаження теми з localStorage при перезавантаженні сторінки
    // (тільки для попереднього перегляду до збереження)
    const savedTheme = localStorage.getItem('preferredTheme');
    if (savedTheme && themeSelect) {
        themeSelect.value = savedTheme;
        applyTheme(savedTheme);
    }
});