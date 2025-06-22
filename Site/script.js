document.addEventListener('DOMContentLoaded', function() {
    initCheckboxes();
    initLikeButtons();
    initAddToCartButtons();
    updateHeaderCounters();
    initSorting();
    
    const filterForm = document.getElementById('filter-form');
    if (filterForm) {
        filterForm.addEventListener('submit', function(e) {
            e.preventDefault();
            applyFilters();
        });
    }
    
    // Инициализация табов (если есть на странице)
    if (document.querySelector('.tab-link')) {
        initTabs();
    }
    
    // Инициализация галереи (если есть на странице)
    if (document.querySelector('.thumbnail')) {
        initGallery();
    }
});

// Инициализация табов
function initTabs() {
    document.querySelectorAll('.tab-link').forEach(tab => {
        tab.addEventListener('click', function() {
            const tabId = this.getAttribute('data-tab');

            // Убираем активный класс у всех табов и контента
            document.querySelectorAll('.tab-link').forEach(t => t.classList.remove('active'));
            document.querySelectorAll('.tab-pane').forEach(p => p.classList.remove('active'));

            // Добавляем активный класс текущему табу и соответствующему контенту
            this.classList.add('active');
            document.getElementById(tabId).classList.add('active');
        });
    });
}

// Инициализация галереи
function initGallery() {
    document.querySelectorAll('.thumbnail').forEach(thumb => {
        thumb.addEventListener('click', function() {
            const imageSrc = this.getAttribute('data-image');

            // Убираем активный класс у всех миниатюр
            document.querySelectorAll('.thumbnail').forEach(t => t.classList.remove('active'));

            // Добавляем активный класс текущей миниатюре
            this.classList.add('active');

            // Обновляем основное изображение
            document.querySelector('.main-image img').src = `./img/${imageSrc}`;
        });
    });
}

// Обновление счетчиков в шапке
function updateHeaderCounters() {
    // Получаем избранные товары из localStorage
    const favorites = JSON.parse(localStorage.getItem('favorites') || '[]');
    // Получаем товары в корзине из localStorage
    const cart = JSON.parse(localStorage.getItem('cart') || '[]');
    
    // Обновляем счетчики
    document.querySelectorAll('.like-counter .counter-badge').forEach(badge => {
        badge.textContent = favorites.length;
    });
    document.querySelectorAll('.cart-counter .counter-badge').forEach(badge => {
        badge.textContent = cart.length;
    });
}

// Инициализация сортировки
function initSorting() {
    const sortSelect = document.getElementById('sort-select');
    if (sortSelect) {
        sortSelect.addEventListener('change', function() {
            applySorting(this.value);
        });
    }
}

// Применение сортировки
function applySorting(sortValue) {
    // Если выбрана сортировка на клиенте
    if (sortValue) {
        const productsContainer = document.querySelector('.products-flex');
        if (!productsContainer) return;
        
        const products = Array.from(productsContainer.querySelectorAll('.product-card'));
        
        products.sort((a, b) => {
            const priceA = parseInt(a.querySelector('.current-price').textContent.replace(/\D/g,''));
            const priceB = parseInt(b.querySelector('.current-price').textContent.replace(/\D/g,''));
            const nameA = a.querySelector('.product-name').textContent.toLowerCase();
            const nameB = b.querySelector('.product-name').textContent.toLowerCase();
            
            switch(sortValue) {
                case 'price-asc':
                    return priceA - priceB;
                case 'price-desc':
                    return priceB - priceA;
                case 'name-asc':
                    return nameA.localeCompare(nameB);
                case 'name-desc':
                    return nameB.localeCompare(nameA);
                default:
                    return 0;
            }
        });
        
        productsContainer.innerHTML = '';
        products.forEach(product => productsContainer.appendChild(product));
    } else {
        // Если выбрана сортировка по популярности, просто перезагружаем страницу
        window.location.href = 'catalog.php';
    }
}

// Обновленная функция initLikeButtons
function initLikeButtons() {
    document.querySelectorAll('.like-btn').forEach(btn => {
        const productCard = btn.closest('.product-card');
        let productId;
        
        if (productCard) {
            productId = productCard.dataset.id;
        } else {
            // Для страницы товара, где нет .product-card
            productId = btn.dataset.id;
        }
        
        if (!productId) return;
        
        const favorites = JSON.parse(localStorage.getItem('favorites') || '[]');
        if (favorites.includes(productId)) {
            btn.classList.add('active');
        }
        
        btn.addEventListener('click', function(e) {
            e.stopPropagation();
            this.classList.toggle('active');
            toggleFavorite(productId, this.classList.contains('active'));
        });
    });

    // Обработка кликов по карточкам товаров (только для каталога)
    document.querySelectorAll('.product-card').forEach(card => {
        card.addEventListener('click', function(e) {
            // Проверяем, был ли клик по кнопке "лайк" или "в корзину"
            if (e.target.closest('.like-btn') || e.target.closest('.add-to-cart')) {
                return;
            }
            
            const productId = this.dataset.id;
            if (productId) {
                window.location.href = `product.php?id=${productId}`;
            }
        });
    });

    // Добавьте в initLikeButtons() или создайте отдельную функцию
document.querySelectorAll('.cart-counter').forEach(btn => {
    btn.addEventListener('click', function(e) {
        e.preventDefault();
        window.location.href = 'cart.php';
    });
});
}

// Переключение избранного
function toggleFavorite(productId, isFavorite) {
    let favorites = JSON.parse(localStorage.getItem('favorites') || '[]');
    
    if (isFavorite) {
        if (!favorites.includes(productId)) {
            favorites.push(productId);
        }
    } else {
        favorites = favorites.filter(id => id !== productId);
    }
    
    localStorage.setItem('favorites', JSON.stringify(favorites));
    updateHeaderCounters();
}

// Добавление в корзину
function initAddToCartButtons() {
    document.querySelectorAll('.add-to-cart').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.stopPropagation();
            const productCard = this.closest('.product-card');
            let productId;
            
            if (productCard) {
                productId = productCard.dataset.id;
            } else {
                // Для страницы товара, где нет .product-card
                productId = new URLSearchParams(window.location.search).get('id');
            }
            
            if (!productId) return;
            
            addToCart(productId, this);
        });
    });
}

function addToCart(productId, button) {
    let cart = JSON.parse(localStorage.getItem('cart')) || [];
    
    if (!cart.includes(productId)) {
        cart.push(productId);
        localStorage.setItem('cart', JSON.stringify(cart));
        
        // Устанавливаем куки на 30 дней
        document.cookie = `cart=${JSON.stringify(cart)}; path=/; max-age=${86400 * 30}`;
        
        updateHeaderCounters();
        
        if (button) {
            button.textContent = 'Добавлено!';
            setTimeout(() => {
                button.textContent = 'В корзину';
            }, 2000);
        }
    }
}

function applyFilters() {
    const form = document.getElementById('filter-form');
    const formData = new FormData(form);
    const params = new URLSearchParams();
    
    // Добавляем параметры
    formData.forEach((value, key) => {
        if (Array.isArray(value)) {
            value.forEach(v => params.append(key, v));
        } else {
            params.append(key, value);
        }
    });
    
    // Обновляем контент через AJAX
    fetch(`catalog.php?${params.toString()}`)
        .then(response => response.text())
        .then(html => {
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');
            
            // Обновляем товары
            const productsContainer = document.querySelector('.products-flex');
            if (productsContainer) {
                const newProducts = doc.querySelector('.products-flex');
                if (newProducts) {
                    productsContainer.innerHTML = newProducts.innerHTML;
                }
            }
            
            // Обновляем счетчики
            updateHeaderCounters();
            
            // Инициализируем кнопки
            initLikeButtons();
            initAddToCartButtons();
        })
        .catch(error => {
            console.error('Error applying filters:', error);
            // В случае ошибки просто перезагружаем страницу
            window.location.href = `catalog.php?${params.toString()}`;
        });
}

function clearFilters() {
    // Очищаем форму
    const form = document.getElementById('filter-form');
    form.reset();
    
    // Применяем пустые фильтры
    fetch('catalog.php')
        .then(response => response.text())
        .then(html => {
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');
            const productsGrid = doc.querySelector('.products-grid');
            document.querySelector('.products-grid').innerHTML = productsGrid.innerHTML;
            
            // Повторно инициализируем кнопки
            initLikeButtons();
            initAddToCartButtons();
        });
}

function initCheckboxes() {
    // Обработка кликов по заголовкам фильтров
    document.querySelectorAll('.filter-group h3').forEach(header => {
        header.addEventListener('click', function() {
            const group = this.parentElement;
            group.classList.toggle('active');
        });
    });

    // Стилизация чекбоксов
    document.querySelectorAll('.filter-group input[type="checkbox"]').forEach(checkbox => {
        const label = checkbox.parentElement;
        label.classList.add('custom-checkbox');
        
        const customCheckbox = document.createElement('span');
        customCheckbox.className = 'checkmark';
        label.appendChild(customCheckbox);
    });
    
    // Убедитесь, что все группы фильтров имеют класс active при загрузке
    document.querySelectorAll('.filter-group').forEach(group => {
        group.classList.add('active');
    });
}