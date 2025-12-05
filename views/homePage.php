<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lexend:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= APP_URL ?>/public/css/homePage.css?v=<?= time() ?>">
    <title>Trang chủ</title>    
</head>

<body>
<header class="nav-wrapper">
    <div class="navbar-v2">
        <!-- LOGO -->
        <div class="nav-left">
            <img src="<?php echo APP_URL;?>/public/images/logohinhanhquan/LogoWebsite.jpg" class="nav-logo">
            <span class="brand-name">LỌ CAKE</span>
        </div>

        <!-- MENU -->
        <nav class="nav-menu">
            <a href="<?php echo APP_URL;?>/Home/">Trang chủ</a>
            <a href="<?php echo APP_URL;?>/Home/menu">Menu Bánh</a>
            <a href="#">Trò truyện</a>
            <a href="#feedback-section">FeedBack</a>
            <a href="<?php echo APP_URL;?>/Home/reviewList">Gửi đánh giá</a>
        </nav>

        <!-- SEARCH + AUTH -->
        <div class="nav-right">
            <!-- ICON GIỎ HÀNG -->
        <a href="<?php echo APP_URL; ?>/Home/order" class="cart-icon">
            🛒
            <span class="cart-count">
                <?php
                    $count = 0;

                    if (isset($_SESSION['cart']) && is_array($_SESSION['cart'])) {
                        foreach ($_SESSION['cart'] as $sizes) {
                            if (!is_array($sizes)) continue;

                            foreach ($sizes as $item) {
                                if (is_array($item) && isset($item['qty'])) {
                                    $count += (int)$item['qty'];
                                }
                            }
                        }
                    }
                    echo $count;
                ?>
            </span>
        </a>

            <div class="auth-group">            
                <?php if (isset($_SESSION['user'])): ?>
                    <span>👤 <?= htmlspecialchars($_SESSION['user']['fullname']) ?></span>
                    <a href="<?php echo APP_URL; ?>/AuthController/logout">Đăng xuất</a>
                    <a href="<?php echo APP_URL; ?>/Home/orderHistory">Lịch sử</a>
                <?php else: ?>
                    <a href="<?php echo APP_URL; ?>/AuthController/ShowLogin">Đăng nhập</a>
                <?php endif; ?>
            </div>
        </div>

        <!-- TOGGLE ICON -->
        <div class="menu-toggle">☰</div>
    </div>
</header>
<script>
window.addEventListener("scroll", () => {
    const nav = document.querySelector(".nav-wrapper");
    if (window.scrollY > 10) {
        nav.classList.add("scrolled");
    } else {
        nav.classList.remove("scrolled");
    }
});
</script>
<!-- BANNER SLIDER - 4 ẢNH TỰ ĐỘNG CHUYỂN -->
<div class="banner-slider">
    <div class="banner-track">
        <div class="banner-slide active">
            <img src="<?php echo APP_URL;?>/public/images/logohinhanhquan/IndexBanner1.jpg" alt="Banner 1">
            <div class="banner-cta">
                <a href="#cake-collection" class="cta-btn cta-order">Đặt ngay</a>
            </div>
        </div>
        <div class="banner-slide">
            <img src="<?php echo APP_URL;?>/public/images/logohinhanhquan/hoptet.jpg" alt="Banner 2">
            <div class="banner-cta">
                <a href="#tet-collection" class="cta-btn cta-buy">Mua ngay</a>
            </div>
        </div>
        <div class="banner-slide">
            <img src="<?php echo APP_URL;?>/public/images/logohinhanhquan/chatbot.avif" alt="Banner 3" style="width : 92%;">
            <div class="banner-cta">
                <button class="cta-btn cta-contact" onclick="alert('Chức năng chatbot đang được phát triển!')">Liên hệ</button>
            </div>
        </div>
        <div class="banner-slide">
            <img src="<?php echo APP_URL;?>/public/images/logohinhanhquan/cake-feedback-voucher-15.jpg" alt="Banner 4" style="width : 95%; height: 95%;">
            <div class="banner-cta">
                <a href="<?= APP_URL ?>/Home/reviewList" class="cta-btn cta-review">Đánh giá</a>
            </div>
        </div>
    </div>
    
    <!-- Navigation Arrows -->
    <button class="banner-nav prev" onclick="changeBannerSlide(-1)">&#10094;</button>
    <button class="banner-nav next" onclick="changeBannerSlide(1)">&#10095;</button>
    
    <!-- Dots -->
    <div class="banner-dots">
        <span class="dot active" onclick="goToBannerSlide(0)"></span>
        <span class="dot" onclick="goToBannerSlide(1)"></span>
        <span class="dot" onclick="goToBannerSlide(2)"></span>
        <span class="dot" onclick="goToBannerSlide(3)"></span>
    </div>
</div>


<section class="collection-header" id="tet-collection">
    <div class="wave1"></div>
    <div class="header-content">
        <h1 class="title">Bộ sưu tập Bánh kẹo Tết 2025<br>Savor Cake</h1>
        <div class="underline"></div>
        <p class="description">
            Nhân dịp Tết đến xuân về, Savor Cake phục vụ thêm khách yêu các món bánh kẹo Tết thơm ngon từ nguồn nguyên liệu chất lượng, phù hợp mua ăn gia đình hoặc làm quà biếu tặng.
Quý khách có nhu cầu mua sỉ bánh kẹo Tết, vui lòng liên hệ 0366742331 để được tư vấn mức chiết khấu phù hợp nhất
(*) Savor có nhận ship tỉnh ạ
        </p>
    </div>
</section>

<!-- HIỂN THỊ BÁNH TẾT -->
    <section class="products-section tet-products">
        <div class="container">
            <?php $tetCategories = $data['tetCategories'] ?? []; ?>
            
            <?php if (!empty($tetCategories)): ?>
                <?php foreach ($tetCategories as $categoryKey => $category): ?>
                    <?php 
                        $title = htmlspecialchars($category['title'] ?? 'Sản phẩm');
                        $items = $category['items'] ?? [];
                        if (empty($items)) continue;
                    ?>
                    
                    <h2 class="section-title"><?= $title ?></h2>
                    
                    <div class="carousel-container">
                        <button class="carousel-btn prev" aria-label="Previous">&lsaquo;</button>
                        
                        <div class="carousel-viewport">
                            <div class="carousel-track">
                                <?php foreach ($items as $p): ?>
                                    <?php $sizes = $p['sizes'] ?? []; ?>
                                    
                                    <div class="carousel-item">
                                        <div class="product-card" data-masp="<?= $p['masp'] ?>">
                                            <div class="product-image-wrapper">
                                                <a href="<?= APP_URL ?>/Home/detail/<?= $p['masp'] ?>">
                                                    <img src="<?= APP_URL ?>/public/images/<?= $p['hinhanh'] ?>"
                                                         alt="<?= htmlspecialchars($p['tensp']) ?>">
                                                </a>
                                                <span class="product-badge tet">TẾT 2025</span>
                                            </div>

                                            <div class="product-info">
                                                <h3 class="product-name">
                                                    <?= htmlspecialchars($p['tensp']) ?>
                                                    <span class="name-badge tet-badge">HOT</span>
                                                </h3>

                                                <?php if (!empty($p['moTa'])): ?>
                                                    <p class="product-description">
                                                        <?= mb_substr(strip_tags($p['moTa']), 0, 150) ?>...
                                                    </p>
                                                <?php endif; ?>

                                                <div class="delivery-info">
                                                    <span>Giao được từ: <strong class="delivery-time"><?= rand(1,2) ?> giờ <?= rand(0,5)*10 ?></strong> hôm nay</span>
                                                </div>

                                                <div class="price-sku-row">
                                                    <div class="price-display">
                                                        <span class="price-label">Giá:</span>
                                                        <span class="selected-price">
                                                            <?php if (!empty($sizes)): ?>
                                                                <?= number_format($sizes[0]['giaXuat']) ?> ₫
                                                            <?php else: ?>
                                                                -- ₫
                                                            <?php endif; ?>
                                                        </span>
                                                    </div>
                                                    <div class="sku-info">SKU: V<?= $p['masp'] ?></div>
                                                </div>

                                                <div class="sizes-container">
                                                    <div class="sizes">
                                                        <?php foreach ($sizes as $index => $s): ?>
                                                            <button type="button"
                                                                class="size-btn <?= $index === 0 ? 'active' : '' ?>"
                                                                data-size="<?= htmlspecialchars($s['size']) ?>"
                                                                data-price="<?= $s['giaXuat'] ?>">
                                                                <?= htmlspecialchars($s['size']) ?>
                                                            </button>
                                                        <?php endforeach; ?>
                                                    </div>
                                                </div>

                                                <div class="action-buttons">
                                                    <a href="javascript:void(0)" class="buy-now-btn" data-masp="<?= $p['masp'] ?>">
                                                        Đặt ngay
                                                    </a>
                                                    <button class="cart-btn" data-masp="<?= $p['masp'] ?>" title="Thêm vào giỏ hàng">
                                                        🛒
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        
                        <button class="carousel-btn next" aria-label="Next">&rsaquo;</button>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </section>

<section class="why-savor">
        <div class="container">
            <div class="left">
                <h2>Tại sao bạn nên lựa chọn bánh Savor Cake</h2>
                <p>Hãy cùng tìm hiểu những đặc điểm nổi bật của Savor Cake nhé!</p>
            </div>

            <div class="right">
                <div class="item">
                    <div class="icon">🍇</div>
                    <p>Đa dạng hoa quả tươi nhất HN - 10 loại: nhãn, vải, nho, dâu, bơ, xoài, cherry, kiwi, chanh leo, việt quất</p>
                </div>

                <div class="item">
                    <div class="icon">🛵</div>
                    <p>Làm và ship hoả tốc chỉ 1h từ khi đặt bánh. COD không cần cọc. Freeship từ 350k</p>
                </div>

                <div class="item">
                    <div class="icon">🎂</div>
                    <p>Nhiều kích thước bánh cho 2-20 người. 150+ mẫu bánh sinh nhật, sự kiện, hộp thiếc</p>
                </div>

                <div class="item">
                    <div class="icon">✔️</div>
                    <p>Chứng nhận ISO 22000:2018, đảm bảo VSATTP. Tổng đài xử lý mọi vấn đề 7-23h</p>
                </div>
            </div>
        </div>
    </section>

    <!-- PREMIUM BOX COLLECTION -->
    <section class="collection-header">
        <div class="wave"></div>
        <div class="header-content">
            <h1 class="title">Premium Box Collection<br>Open The Delight</h1>
            <div class="underline"></div>
            <p class="description">
                Khám phá bộ sưu tập bánh hộp cao cấp độc đáo từ Savor Cake với những tuyệt phẩm Tiramisu, Matcha và Chocolate.
                Mỗi chiếc hộp tinh tế là lời mời gọi "open the delight" - mở ra niềm vui với từng tầng hương vị đậm đà,
                nơi rượu rum Captain Morgan hòa quyện cùng các nguyên liệu thượng hạng, mang đến một trải nghiệm ẩm thực
                xa xỉ và đậm chất nghệ thuật.
            </p>
        </div>
    </section>

<!-- PHẦN HIỂN THỊ SẢN PHẨM -->
<section class="products-section">
    <div class="container">

        <?php $productData = $data['productData'] ?? []; ?>

        <?php if (empty($productData)): ?>
            <div class="empty-category">
                <h3>Chưa có sản phẩm nào</h3>
                <p>Vui lòng quay lại sau!</p>
            </div>
        <?php else: ?>

            <?php foreach ($productData as $maLoai => $group): ?>
                <?php
                    $title = htmlspecialchars($group['title'] ?? 'Sản phẩm');
                    $items = $group['items'] ?? [];
                    if (empty($items)) continue;
                ?>

                <!-- ✅ TIÊU ĐỀ LOẠI (Bánh hoa quả) -->
                <h2 class="section-title"><?= $title ?></h2>

                <!-- ---------- CAROUSEL ---------- -->
                <div class="carousel-container">
                    <button class="carousel-btn prev" aria-label="Previous">&lsaquo;</button>

                    <div class="carousel-viewport">
                        <div class="carousel-track">

                            <?php foreach ($items as $p): ?>
                                <?php $sizes = $p['sizes'] ?? []; ?>

                                <div class="carousel-item">
                                    <div class="product-card" data-masp="<?= $p['masp'] ?>">
                                        
                                        <!-- Product Image -->
                                        <div class="product-image-wrapper">
                                            <a href="<?= APP_URL ?>/Home/detail/<?= $p['masp'] ?>">
                                                <img src="<?= APP_URL ?>/public/images/<?= $p['hinhanh'] ?>"
                                                     alt="<?= htmlspecialchars($p['tensp']) ?>">
                                            </a>
                                            <span class="product-badge savor">SAVOR<sub><?= rand(1,2) ?>H</sub></span>
                                        </div>

                                        <!-- Product Info -->
                                        <div class="product-info">
                                            <h3 class="product-name">
                                                <?= htmlspecialchars($p['tensp']) ?>
                                                <span class="name-badge">NEW</span>
                                            </h3>

                                            <?php if (!empty($p['moTa'])): ?>
                                                <p class="product-description">
                                                    <?= mb_substr(strip_tags($p['moTa']), 0, 200) ?>...
                                                </p>
                                            <?php endif; ?>

                                            <!-- Delivery Info -->
                                            <div class="delivery-info">
                                                <span>Giao được từ: <strong class="delivery-time"><?= rand(1,2) ?> giờ <?= rand(0,5)*10 ?></strong> hôm nay</span>
                                            </div>

                                            <!-- Price & SKU -->
                                            <div class="price-sku-row">
                                                <div class="price-display">
                                                    <span class="price-label">Giá:</span>
                                                    <span class="selected-price">
                                                        <?php if (!empty($sizes)): ?>
                                                            <?= number_format($sizes[0]['giaXuat']) ?> ₫
                                                        <?php else: ?>
                                                            -- ₫
                                                        <?php endif; ?>
                                                    </span>
                                                </div>
                                                <div class="sku-info">SKU: V<?= $p['masp'] ?></div>
                                            </div>

                                            <!-- SIZE -->
                                            <div class="sizes-container">
                                                <div class="sizes">
                                                    <?php foreach ($sizes as $index => $s): ?>
                                                        <button
                                                            type="button"
                                                            class="size-btn <?= $index === 0 ? 'active' : '' ?>"
                                                            data-size="<?= htmlspecialchars($s['size']) ?>"
                                                            data-price="<?= $s['giaXuat'] ?>">
                                                            <?= htmlspecialchars($s['size']) ?>
                                                        </button>
                                                    <?php endforeach; ?>
                                                </div>
                                            </div>

                                            <!-- ACTION -->
                                            <div class="action-buttons">
                                                <a href="javascript:void(0)"
                                                   class="buy-now-btn"
                                                   data-masp="<?= $p['masp'] ?>">
                                                    Đặt ngay
                                                </a>

                                                <button class="cart-btn"
                                                        data-masp="<?= $p['masp'] ?>"
                                                        title="Thêm vào giỏ hàng">
                                                    🛒
                                                </button>
                                            </div>
                                        </div>

                                    </div>
                                </div>

                            <?php endforeach; ?>

                        </div>
                    </div>

                    <button class="carousel-btn next" aria-label="Next">&rsaquo;</button>
                </div>
                <!-- ---------- END CAROUSEL ---------- -->

            <?php endforeach; ?>

        <?php endif; ?>

    </div>
</section>


<!-- SHIPPER TEAM SECTION -->

<section class="shipper-team">
    <div class="container">
        <div class="shipper-header">
            <h2>"Biệt đội" Ship hỏa tốc</h2>
        </div>
        
        <div class="shipper-content">
            <div class="shipper-text">
                <p>Savor Cake xây dựng đội ngũ Shipper chuyên nghiệp & thân thiện, giao hàng nhanh chóng đến tay khách yêu trong vòng 1H</p>
            </div>
            
            <div class="shipper-images">
                <!-- 5 ẢNH SẴN SÀNG ĐỂ BẠN ADD ẢNH VÀO -->
                <div class="shipper-img-container">
                    <img src="<?= APP_URL ?>/public/images/shipper/shipper1.jpg" alt="Shipper 1" class="shipper-img">
                </div>
                <div class="shipper-img-container">
                    <img src="<?= APP_URL ?>/public/images/shipper/shipper2.jpg" alt="Shipper 2" class="shipper-img">
                </div>
                <div class="shipper-img-container">
                    <img src="<?= APP_URL ?>/public/images/shipper/shipper3.jpg" alt="Shipper 3" class="shipper-img">
                </div>
                <div class="shipper-img-container">
                    <img src="<?= APP_URL ?>/public/images/shipper/shipper4.jpg" alt="Shipper 4" class="shipper-img">
                </div>
                <div class="shipper-img-container">
                    <img src="<?= APP_URL ?>/public/images/shipper/shipper5.png" alt="Shipper 5" class="shipper-img">
                </div>
            </div>
        </div>
    </div>
</section>



<section class="collection-header" id="cake-collection">
    <div class="wave1"></div>
    <div class="header-content">
        <h1 class="title">Bộ sưu tập bánh kem, bánh sinh nhật<br>Savor Cake</h1>
        <div class="underline"></div>
        <p class="description">
            Mời bạn xem thêm hơn 99+ mẫu bánh kem, bánh sinh nhật tươi ngon, đa dạng, giá chỉ từ 120k
        </p>
    </div>
</section>

<!-- PHẦN HIỂN THỊ TẤT CẢ CÁC LOẠI BÁNH KHÁC -->
<section class="products-section cake-collection-section">
    <div class="container">
        <?php $otherCategories = $data['otherCategories'] ?? []; ?>
        
        <?php if (!empty($otherCategories)): ?>
            <?php foreach ($otherCategories as $categoryKey => $category): ?>
                <?php 
                    $title = htmlspecialchars($category['title'] ?? 'Sản phẩm');
                    $items = $category['items'] ?? [];
                    if (empty($items)) continue;
                ?>
                
                <h2 class="section-title"><?= $title ?></h2>
                
                <div class="carousel-container">
                    <button class="carousel-btn prev" aria-label="Previous">&lsaquo;</button>
                    
                    <div class="carousel-viewport">
                        <div class="carousel-track">
                            <?php foreach ($items as $p): ?>
                                <?php $sizes = $p['sizes'] ?? []; ?>
                                
                                <div class="carousel-item">
                                    <div class="product-card" data-masp="<?= $p['masp'] ?>">
                                        
                                        <!-- Product Image -->
                                        <div class="product-image-wrapper">
                                            <a href="<?= APP_URL ?>/Home/detail/<?= $p['masp'] ?>">
                                                <img src="<?= APP_URL ?>/public/images/<?= $p['hinhanh'] ?>"
                                                     alt="<?= htmlspecialchars($p['tensp']) ?>">
                                            </a>
                                            <span class="product-badge savor">SAVOR<sub><?= rand(1,2) ?>H</sub></span>
                                        </div>

                                        <!-- Product Info -->
                                        <div class="product-info">
                                            <h3 class="product-name">
                                                <?= htmlspecialchars($p['tensp']) ?>
                                                <span class="name-badge">NEW</span>
                                            </h3>

                                            <?php if (!empty($p['moTa'])): ?>
                                                <p class="product-description">
                                                    <?= mb_substr(strip_tags($p['moTa']), 0, 200) ?>...
                                                </p>
                                            <?php endif; ?>

                                            <!-- Delivery Info -->
                                            <div class="delivery-info">
                                                <span>Giao được từ: <strong class="delivery-time"><?= rand(1,2) ?> giờ <?= rand(0,5)*10 ?></strong> hôm nay</span>
                                            </div>

                                            <!-- Price & SKU -->
                                            <div class="price-sku-row">
                                                <div class="price-display">
                                                    <span class="price-label">Giá:</span>
                                                    <span class="selected-price">
                                                        <?php if (!empty($sizes)): ?>
                                                            <?= number_format($sizes[0]['giaXuat']) ?> ₫
                                                        <?php else: ?>
                                                            -- ₫
                                                        <?php endif; ?>
                                                    </span>
                                                </div>
                                                <div class="sku-info">SKU: V<?= $p['masp'] ?></div>
                                            </div>

                                            <!-- SIZE -->
                                            <div class="sizes-container">
                                                <div class="sizes">
                                                    <?php foreach ($sizes as $index => $s): ?>
                                                        <button
                                                            type="button"
                                                            class="size-btn <?= $index === 0 ? 'active' : '' ?>"
                                                            data-size="<?= htmlspecialchars($s['size']) ?>"
                                                            data-price="<?= $s['giaXuat'] ?>">
                                                            <?= htmlspecialchars($s['size']) ?>
                                                        </button>
                                                    <?php endforeach; ?>
                                                </div>
                                            </div>

                                            <!-- ACTION -->
                                            <div class="action-buttons">
                                                <a href="javascript:void(0)"
                                                   class="buy-now-btn"
                                                   data-masp="<?= $p['masp'] ?>">
                                                    Đặt ngay
                                                </a>

                                                <button class="cart-btn"
                                                        data-masp="<?= $p['masp'] ?>"
                                                        title="Thêm vào giỏ hàng">
                                                    🛒
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    
                    <button class="carousel-btn next" aria-label="Next">&rsaquo;</button>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="empty-category">
                <p>Chưa có sản phẩm trong bộ sưu tập</p>
            </div>
        <?php endif; ?>
    </div>
</section>

<section class="collection-header" id="feedback-section">
    <div class="wave1"></div>
    <div class="header-content">
        <h1 class="title">Feedback tháng 11 & 12/2025</h1>
        <div class="underline"></div>
        <p class="description">
            Savor Cake đã nhận được nhiều phản hồi tích cực từ phía khách hàng khi sử dụng sản phẩm bánh sinh nhật, bánh ngọt của chúng mình ... Cùng xem thử các mẫu bánh được khách yêu tin tưởng ủng hộ dưới đây nhé!
        </p>
    </div>
</section>

<!-- PHẦN HIỂN THỊ ĐÁNH GIÁ - CAROUSEL -->
<section class="feedback-section">
    <div class="feedback-container">
        <?php $reviews = $data['reviews'] ?? []; ?>
        
        <?php if (!empty($reviews)): ?>
        <div class="feedback-carousel">
            <button class="feedback-btn prev" type="button" onclick="slideFeedback(-1)">‹</button>
            
            <div class="feedback-track-wrapper">
                <div class="feedback-track" id="feedbackTrack">
                    <?php foreach ($reviews as $review): ?>
                    <div class="feedback-card">
                        <div class="feedback-image">
                            <?php 
                            $imgSrc = APP_URL . '/public/images/default.png';
                            if (!empty($review['image'])) {
                                $imgSrc = APP_URL . '/public/images/reviews/' . $review['image'];
                            } elseif (!empty($review['product_image'])) {
                                $imgSrc = APP_URL . '/public/images/' . $review['product_image'];
                            }
                            ?>
                            <img src="<?= $imgSrc ?>" alt="Feedback">
                        </div>
                        <div class="feedback-info">
                            <div class="feedback-stars">
                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                    <span class="<?= $i <= $review['rating'] ? 'filled' : '' ?>">★</span>
                                <?php endfor; ?>
                            </div>
                            <div class="feedback-product"><?= htmlspecialchars($review['tensp'] ?? 'Bánh kem') ?></div>
                            <div class="feedback-user">
                                <span class="user-avatar"><?= strtoupper(mb_substr($review['user_name'], 0, 1)) ?></span>
                                <span class="user-name"><?= htmlspecialchars($review['user_name']) ?></span>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            
            <button class="feedback-btn next" type="button" onclick="slideFeedback(1)">›</button>
        </div>
        
        <!-- Dots -->
        <div class="feedback-dots" id="feedbackDots">
            <?php $totalSlides = ceil(count($reviews) / 4); ?>
            <?php for ($i = 0; $i < $totalSlides; $i++): ?>
            <span class="dot <?= $i === 0 ? 'active' : '' ?>" onclick="goToSlide(<?= $i ?>)"></span>
            <?php endfor; ?>
        </div>
        <?php else: ?>
        <div class="no-feedback">
            <p>Chưa có đánh giá nào. Hãy là người đầu tiên!</p>
        </div>
        <?php endif; ?>
        
        <div class="feedback-cta">
            <a href="<?= APP_URL ?>/Home/reviewList" class="btn-feedback">
                ✍️ Gửi đánh giá của bạn
            </a>
        </div>
    </div>
</section>

<script>
let currentSlide = 0;
const track = document.getElementById('feedbackTrack');
const dots = document.querySelectorAll('.feedback-dots .dot');

function getItemsPerView() {
    if (window.innerWidth >= 1200) return 4;
    if (window.innerWidth >= 900) return 3;
    if (window.innerWidth >= 600) return 2;
    return 1;
}

function slideFeedback(direction) {
    const items = document.querySelectorAll('.feedback-card');
    const perView = getItemsPerView();
    const maxSlide = Math.ceil(items.length / perView) - 1;
    
    currentSlide += direction;
    if (currentSlide < 0) currentSlide = maxSlide;
    if (currentSlide > maxSlide) currentSlide = 0;
    
    updateSlide();
}

function goToSlide(index) {
    currentSlide = index;
    updateSlide();
}

function updateSlide() {
    const items = document.querySelectorAll('.feedback-card');
    if (items.length === 0) return;
    
    const perView = getItemsPerView();
    const itemWidth = items[0].offsetWidth + 20; // gap
    track.style.transform = `translateX(-${currentSlide * perView * itemWidth}px)`;
    
    dots.forEach((dot, i) => {
        dot.classList.toggle('active', i === currentSlide);
    });
}

window.addEventListener('resize', () => {
    currentSlide = 0;
    updateSlide();
});
</script>

<!-- CHÍNH SÁCH SHIP & BÁN HÀNG -->
<section class="policy-section">
    <div class="policy-container">
        <h2 class="policy-title">Chính sách ship & bán hàng</h2>
        <div class="policy-underline"></div>
        <p class="policy-subtitle">Bấm để xem thêm chi tiết <a href="#">TẠI ĐÂY</a></p>
        
        <div class="policy-grid">
            <!-- COD Card -->
            <div class="policy-card cod-card">
                <div class="policy-card-header">
                    <span class="cod-badge">ĐẶT HÀNG</span>
                    <span class="cod-highlight">COD</span>
                    <span class="cod-badge">SIÊU TIỆN LỢI</span>
                </div>
                <div class="policy-card-body">
                    <ul class="policy-list">
                        <li><span class="check">✓</span> KHÔNG CẦN ĐẶT CỌC TRƯỚC</li>
                        <li><span class="check">✓</span> TRẢ TIỀN KHI NHẬN HÀNG</li>
                        <li><span class="dot">●</span> HỖ TRỢ 12 QUẬN NỘI THÀNH</li>
                        <li><span class="dot">●</span> FREESHIP từ 350k</li>
                        <li><span class="dot">●</span> NHẬN HÀNG TẠI CỬA HÀNG</li>
                        <li><span class="dot">●</span> Đơn dưới 350k: 30k ship</li>
                    </ul>
                </div>
                <!-- Placeholder cho ảnh shipper -->
            </div>
            
            <!-- Voucher Card -->
            <div class="policy-card voucher-card">
                <div class="policy-card-header">
                    <span class="voucher-title">VOUCHER FEEDBACK</span>
                </div>
                <div class="policy-card-body">
                    <p class="voucher-text">TẶNG VOUCHER</p>
                    <div class="voucher-badge">
                        <span class="voucher-label">Giảm ngay</span>
                        <span class="voucher-percent">15%</span>
                    </div>
                    <p class="voucher-note">Khi gửi feedback đánh giá sản phẩm</p>
                </div>
            </div>
            
            <!-- Chiết khấu Card -->
            <div class="policy-card discount-card">
                <div class="policy-card-header">
                    <span class="discount-title">CHÍNH SÁCH CHIẾT KHẤU</span>
                </div>
                <div class="policy-card-body">
                    <p class="discount-desc">Khách hàng sẽ được giảm giá trực tiếp trên hóa đơn. Hóa đơn đã áp dụng chiết khấu sẽ giữ nguyên giá.</p>
                    <table class="discount-table">
                        <thead>
                            <tr>
                                <th>GIÁ TRỊ HÓA ĐƠN<br><small>Tổng tiền</small></th>
                                <th>CHIẾT KHẤU</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>2.000.000đ</td>
                                <td><span class="discount-badge">10%</span></td>
                            </tr>
                            <tr>
                                <td>5.000.000đ</td>
                                <td><span class="discount-badge">15%</span></td>
                            </tr>
                            <tr>
                                <td>10.000.000đ</td>
                                <td><span class="discount-badge">20%</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- FOOTER -->
<footer class="site-footer">
    <div class="footer-main">
        <div class="footer-container">
            <!-- Thông tin liên hệ -->
            <div class="footer-col">
                <h3>Thông tin liên hệ</h3>
                <ul>
                    <li>Hotline tư vấn: <a href="tel:1900779907">1900 779 907</a></li>
                    <li>Hotline khiếu nại: <a href="tel:0364907773">0364907773</a></li>
                    <li>Tài trợ thiện nguyện: <a href="tel:0934664262">093 466 4262</a></li>
                    <li>Email: <a href="mailto:support@savor.vn">support@savor.vn</a></li>
                    <li><a href="#">Tuyển dụng</a></li>
                </ul>
            </div>
            
            <!-- Our brands -->
            <div class="footer-col brands">
                <h3>Our brands</h3>
                <div class="brand-logos">
                    <img src="<?= APP_URL ?>/public/images/brands/abby-logo.png" alt="Abby" onerror="this.style.display='none'">
                    <img src="<?= APP_URL ?>/public/images/brands/savor-bread-logo.png" alt="Savor Bread" onerror="this.style.display='none'">
                </div>
            </div>
            
            <!-- Liên kết -->
            <div class="footer-col links">
                <h3>Liên kết</h3>
                <div class="social-links">
                    <a href="#" class="social-icon facebook">
                        <svg viewBox="0 0 24 24" fill="currentColor"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                    </a>
                    <a href="#" class="social-icon tiktok">
                        <svg viewBox="0 0 24 24" fill="currentColor"><path d="M12.525.02c1.31-.02 2.61-.01 3.91-.02.08 1.53.63 3.09 1.75 4.17 1.12 1.11 2.7 1.62 4.24 1.79v4.03c-1.44-.05-2.89-.35-4.2-.97-.57-.26-1.1-.59-1.62-.93-.01 2.92.01 5.84-.02 8.75-.08 1.4-.54 2.79-1.35 3.94-1.31 1.92-3.58 3.17-5.91 3.21-1.43.08-2.86-.31-4.08-1.03-2.02-1.19-3.44-3.37-3.65-5.71-.02-.5-.03-1-.01-1.49.18-1.9 1.12-3.72 2.58-4.96 1.66-1.44 3.98-2.13 6.15-1.72.02 1.48-.04 2.96-.04 4.44-.99-.32-2.15-.23-3.02.37-.63.41-1.11 1.04-1.36 1.75-.21.51-.15 1.07-.14 1.61.24 1.64 1.82 3.02 3.5 2.87 1.12-.01 2.19-.66 2.77-1.61.19-.33.4-.67.41-1.06.1-1.79.06-3.57.07-5.36.01-4.03-.01-8.05.02-12.07z"/></svg>
                    </a>
                </div>
                <div class="certification">
                    <img src="<?= APP_URL ?>/public/images/brands/bo-cong-thuong.png" alt="Đã thông báo Bộ Công Thương" onerror="this.style.display='none'">
                </div>
            </div>
        </div>
    </div>
    
    <div class="footer-bottom">
        <p>© 2025 Lọ Cake - Website Bán Bánh Kem</p>
    </div>
</footer>

<!-- JAVASCRIPT CHO TẤT CẢ CAROUSEL VÀ PRODUCT CARDS -->
<script>
// ========== BANNER SLIDER - TRƯỢT NGANG ==========
let bannerIndex = 0;
const bannerTrack = document.querySelector('.banner-track');
const bannerSlides = document.querySelectorAll('.banner-slide');
const bannerDots = document.querySelectorAll('.banner-dots .dot');
const totalSlides = bannerSlides.length;

function showBannerSlide(index) {
    if (totalSlides === 0) return;
    
    // Reset index nếu vượt quá
    if (index >= totalSlides) bannerIndex = 0;
    if (index < 0) bannerIndex = totalSlides - 1;
    
    // Di chuyển track sang trái
    const offset = -bannerIndex * 100;
    bannerTrack.style.transform = `translateX(${offset}%)`;
    
    // Cập nhật dots
    bannerDots.forEach((dot, i) => {
        dot.classList.toggle('active', i === bannerIndex);
    });
}

function changeBannerSlide(direction) {
    bannerIndex += direction;
    showBannerSlide(bannerIndex);
}

function goToBannerSlide(index) {
    bannerIndex = index;
    showBannerSlide(bannerIndex);
}

// Tự động chuyển slide mỗi 5 giây
setInterval(function() {
    bannerIndex++;
    showBannerSlide(bannerIndex);
}, 5000);

// Khởi tạo
showBannerSlide(0);

// Smooth scroll cho CTA buttons
document.querySelectorAll('.banner-cta a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
            target.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        }
    });
});

document.addEventListener('DOMContentLoaded', function() {
    // ========== CAROUSEL LOGIC ==========
    document.querySelectorAll('.carousel-container').forEach(car => {
        const track = car.querySelector('.carousel-track');
        const items = Array.from(car.querySelectorAll('.carousel-item'));
        const prevBtn = car.querySelector('.carousel-btn.prev');
        const nextBtn = car.querySelector('.carousel-btn.next');

        let groupIndex = 0;

        function getPerView() {
            const w = window.innerWidth;
            if (w >= 1200) return 4;
            if (w >= 900) return 3;
            if (w >= 600) return 2;
            return 1;
        }

        function updateControls() {
            const perView = getPerView();
            const maxGroup = Math.max(0, Math.ceil(items.length / perView) - 1);
            if (prevBtn) prevBtn.disabled = groupIndex <= 0;
            if (nextBtn) nextBtn.disabled = groupIndex >= maxGroup;
        }

        function updatePosition() {
            const perView = getPerView();
            if (items.length === 0) return;
            const itemRect = items[0].getBoundingClientRect();
            const gap = 24;
            const itemWidth = itemRect.width + gap;
            const shift = groupIndex * perView * itemWidth;
            track.style.transform = `translateX(${-shift}px)`;
            updateControls();
        }

        if (prevBtn) {
            prevBtn.addEventListener('click', function() {
                groupIndex = Math.max(0, groupIndex - 1);
                updatePosition();
            });
        }

        if (nextBtn) {
            nextBtn.addEventListener('click', function() {
                const perView = getPerView();
                const maxGroup = Math.max(0, Math.ceil(items.length / perView) - 1);
                groupIndex = Math.min(maxGroup, groupIndex + 1);
                updatePosition();
            });
        }

        let resizeTimer;
        window.addEventListener('resize', () => {
            clearTimeout(resizeTimer);
            resizeTimer = setTimeout(() => {
                const perView = getPerView();
                const maxGroup = Math.max(0, Math.ceil(items.length / perView) - 1);
                if (groupIndex > maxGroup) groupIndex = maxGroup;
                updatePosition();
            }, 120);
        });

        setTimeout(updatePosition, 100);
    });

    // ========== SIZE SELECTION ==========
    document.querySelectorAll('.size-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const productCard = this.closest('.product-card');
            const sizeButtons = productCard.querySelectorAll('.size-btn');
            const priceDisplay = productCard.querySelector('.selected-price');

            sizeButtons.forEach(b => b.classList.remove('active'));
            this.classList.add('active');

            const price = this.dataset.price;
            if (priceDisplay) {
                priceDisplay.textContent = Number(price).toLocaleString('vi-VN') + ' ₫';
            }
        });
    });

    // ========== BUY NOW BUTTON ==========
    document.querySelectorAll('.buy-now-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const masp = this.dataset.masp;
            const productCard = this.closest('.product-card');
            const activeSizeBtn = productCard.querySelector('.size-btn.active');

            if (!activeSizeBtn) {
                alert("Vui lòng chọn size bánh!");
                return;
            }

            const size = activeSizeBtn.dataset.size;
            window.location.href = "<?= APP_URL ?>/Home/addtocard/" + masp + "?size=" + encodeURIComponent(size);
        });
    });

    // ========== CART BUTTON ==========
    document.querySelectorAll('.cart-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const masp = this.dataset.masp;
            const productCard = this.closest('.product-card');
            const activeSizeBtn = productCard.querySelector('.size-btn.active');

            if (!activeSizeBtn) {
                alert('Vui lòng chọn size');
                return;
            }

            const size = activeSizeBtn.dataset.size;

            fetch(`<?= APP_URL ?>/Home/addToCartAjax/${masp}?size=${encodeURIComponent(size)}`)
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        const countEl = document.querySelector('.cart-count');
                        if (countEl) {
                            countEl.textContent = data.totalQty;
                            countEl.classList.add('bump');
                            setTimeout(() => countEl.classList.remove('bump'), 200);
                        }
                    }
                });
        });
    });
});
</script>

</body>
</html>