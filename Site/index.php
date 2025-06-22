<?php
session_start();
require_once 'db.php';
?>
<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <title>Timber Soul</title>
    <link rel="stylesheet" href="styles.css">
</head>

<body>
    <div class="container">
        <div class="container_content">
<?php
require_once 'header.php';
?>
        </div>

        <main>
            <section class="hero">
                <div class="hero_container">
                    <h1>Мебель ручного производства из древесного массива</h1>
                    <div class="categories">
                        <img src="./img/s1_1.svg" alt="">
                        <img src="./img/s1_2.svg" alt="">
                        <img src="./img/s1_3.svg" alt="">
                        <img src="./img/s1_4.svg" alt="">
                    </div>
                    <p>Массив дерева — это натуральный материал, вырезанный из ствола дерева, а не спрессованный из опилок или слоёв. Он отличается прочностью и долговечностью, уникальным внешним видом, а также приятными тактильными ощущениями и ремонтопригодностью.</p>
                </div>
            </section>
            <section class="container_content catalog-preview">
                <h2>Мебель по категориям</h2>
                <div class="category-list">
                    <img src="./img/mebel1.svg" alt="">
                    <img src="./img/mebel2.svg" alt="">
                    <img src="./img/mebel3.svg" alt="">
                    <img src="./img/mebel4.svg" alt="">
                    <img src="./img/mebel5.svg" alt="">
                    <img src="./img/mebel6.svg" alt="">
                </div>
                <a href="catalog.php"><button class="to_catalog"><span>Перейти в каталог</span> <img src="./img/arrow_right.svg" alt=""></button></a>
            </section>
            <section class="container_content order-info">
                <div class="order_flex">
                    <h2>Мебель на заказ</h2>
                    <p class="order_descr">Мы не просто делаем мебель.
                        Мы создаём вещи, которые становятся частью дома и семьи.</p>
                    <a href="#!" class="zayavka">Оставить заявку <svg width="35" height="35" viewBox="0 0 35 35" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M15.0322 19.968L22.0995 12.9007M29.329 8.58761L23.3634 27.9755C22.8288 29.713 22.5613 30.5822 22.1002 30.8703C21.7003 31.1202 21.2051 31.162 20.7695 30.9813C20.2672 30.7728 19.8596 29.9589 19.0463 28.3325L15.2678 20.7755C15.1388 20.5174 15.0742 20.3889 14.988 20.2771C14.9115 20.1778 14.8232 20.0884 14.724 20.0119C14.6147 19.9277 14.4883 19.8645 14.2417 19.7412L6.66739 15.954C5.0409 15.1408 4.22758 14.7338 4.01917 14.2315C3.83843 13.7959 3.87965 13.3003 4.12954 12.9003C4.41769 12.4392 5.28679 12.1712 7.02485 11.6365L26.4128 5.67094C27.7792 5.25051 28.4627 5.04046 28.9242 5.2099C29.3262 5.35748 29.6431 5.6741 29.7907 6.07611C29.9601 6.53742 29.7499 7.22055 29.3299 8.58559L29.329 8.58761Z" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </a>
                </div>

                <div class="custom-furniture">
                    <p>Мы изготавливаем мебель на заказ, чтобы она идеально подходила именно вам. Никаких компромиссов — вы выбираете размер, форму, оттенок и функциональность, а мы превращаем идею в изделие, которое прослужит десятилетия.</p>
                    <br>
                    <h2>Почему стоит выбрать мебель на заказ:</h2>
                    <br>
                    <ul>
                        <li><span class="highlight">Индивидуальность.</span><br>
                            Каждый интерьер уникален — и ваша мебель будет такой же функциональность.<br>
                            Учитываем всё: размеры помещения, нужное количество полок, выдвижных ящиков, предпочтения по дизайну.</li>

                        <li><span class="highlight">Материалы.</span><br>
                            Работаем только с отборной древесиной — натуральный массив с выраженной текстурой и характером.</li>

                        <li><span class="highlight">Долговечность.</span><br>
                            Мебель из массива служит годами, сохраняя прочность и красоту.</li>

                        <li><span class="highlight">Контроль на каждом этапе.</span><br>
                            Вы в курсе всего: от первых эскизов до последнего слов масла.</li>
                    </ul>
                </div>

            </section>

            <section class="container_content photo_buyers">
                <h2>Фото наших покупателей</h2>

                <div class="photo_elements">
                    <img src="./img/photo1.png" alt="">
                    <img src="./img/photo2.png" alt="">
                </div>
            </section>
        </main>


    </div>
</body>

</html>
<?php $conn->close(); ?>