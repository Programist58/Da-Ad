<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require 'db.php';

// Получаем баннеры для слайдера
$banner_stmt = $pdo->query("SELECT * FROM banner_slider ORDER BY id DESC");
$banners = $banner_stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Boot's — Магазин обуви</title>
    <link rel="stylesheet" href="assets/style.css">
    <style>
        /* Стили для горизонтального автоматического слайдера */
        .slider-container {
            position: relative;
            max-width: 1200px;
            margin: 20px auto;
            height: 350px;
            overflow: hidden;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        .slider-track {
            display: flex;
            width: 100%;
            height: 100%;
            transition: transform 0.5s ease-in-out;
        }
        .slide {
            min-width: 100%;
            height: 100%;
            position: relative;
            background-size: cover;
            background-position: center;
        }
        .slide-overlay {
            position: absolute;
            bottom: 0; left: 0; right: 0;
            background: linear-gradient(transparent, rgba(0,0,0,0.7));
            padding: 30px;
            color: #fff;
        }
        .slide-title {
            font-size: 24px;
            margin: 0;
            font-weight: bold;
        }
        .slider-btn {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            background: rgba(0,0,0,0.5);
            color: #fff;
            border: none;
            padding: 15px 10px;
            cursor: pointer;
            font-size: 20px;
            z-index: 10;
            border-radius: 5px;
            transition: 0.3s;
        }
        .slider-btn:hover { background: rgba(0,0,0,0.8); }
        .slider-btn.prev { left: 10px; }
        .slider-btn.next { right: 10px; }
    </style>
</head>
<body>

    <?php include('header.php'); ?>

    <main class="container">
        
        <div class="slider-container">
            <div class="slider-track" id="sliderTrack">
                <?php if (count($banners) > 0): ?>
                    <?php foreach ($banners as $banner): ?>
                        <div class="slide" style="background-image: url('assets/img/<?php echo htmlspecialchars($banner['image_path']); ?>');">
                            <?php if (!empty($banner['title'])): ?>
                                <div class="slide-overlay">
                                    <h3 class="slide-title"><?php echo htmlspecialchars($banner['title']); ?></h3>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="slide" style="background-color: #333; display: flex; align-items: center; justify-content: center; color: white;">
                        <div style="text-align: center;">
                            <h3>Добро пожаловать в Boot's!</h3>
                            <p style="color: #ccc;">Добавьте баннеры в панели продавца</p>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
            <?php if (count($banners) > 1): ?>
                <button class="slider-btn prev" onclick="moveSlide(-1)">❮</button>
                <button class="slider-btn next" onclick="moveSlide(1)">❯</button>
            <?php endif; ?>
        </div>

        <?php include('catalog.php'); ?>
   
    </main>

    <script>
        let currentSlideIdx = 0;
        const track = document.getElementById('sliderTrack');
        const totalSlides = <?php echo max(1, count($banners)); ?>;

        function updateSliderPosition() {
            track.style.transform = `translateX(-${currentSlideIdx * 100}%)`;
        }

        function moveSlide(direction) {
            currentSlideIdx = (currentSlideIdx + direction + totalSlides) % totalSlides;
            updateSliderPosition();
        }

        // Автоматическое переключение каждые 10 секунд (10000 мс)
        if (totalSlides > 1) {
            setInterval(() => {
                moveSlide(1);
            }, 10000);
        }
    </script>
</body>
</html>