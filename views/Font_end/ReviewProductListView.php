<?php
$products = $data['products'] ?? [];
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Gửi đánh giá sản phẩm</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; background: #f5f8f4; min-height: 100vh; }
        
        .site-header { background: #6fa05f; height: 70px; display: flex; align-items: center; }
        .header-container { width: 100%; max-width: 1200px; margin: auto; padding: 0 20px; display: flex; align-items: center; justify-content: space-between; }
        .logo img { height: 60px; width: 60px; }
        .main-nav { display: flex; gap: 30px; }
        .main-nav a { color: #fff; text-decoration: none; font-size: 16px; font-weight: 500; }
        .main-nav a:hover { text-decoration: underline; }
        
        .container { max-width: 1200px; margin: 40px auto; padding: 0 20px; }
        
        .page-header { text-align: center; margin-bottom: 40px; }
        .page-header h1 { color: #2b7a37; font-size: 32px; margin-bottom: 10px; }
        .page-header p { color: #666; font-size: 16px; }
        
        .products-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 25px; }
        
        .product-card {
            background: #fff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
            transition: transform 0.3s, box-shadow 0.3s;
        }
        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.12);
        }
        
        .product-card img {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }
        
        .product-info { padding: 15px; }
        .product-info h3 { color: #2b7a37; font-size: 16px; margin-bottom: 8px; }
        .product-info p { color: #666; font-size: 13px; margin-bottom: 12px; }
        
        .review-btn {
            display: block;
            width: 100%;
            padding: 12px;
            background: linear-gradient(135deg, #2b7a37 0%, #4a8c3a 100%);
            color: #fff;
            text-align: center;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            transition: opacity 0.3s;
        }
        .review-btn:hover { opacity: 0.9; }
        
        .review-count {
            display: flex;
            align-items: center;
            gap: 5px;
            color: #f39c12;
            font-size: 14px;
            margin-bottom: 10px;
        }
        
        .alert { padding: 15px; border-radius: 8px; margin-bottom: 20px; }
        .alert-warning { background: #fff3cd; color: #856404; border: 1px solid #ffc107; }
        
        /* Search Box */
        .search-box {
            max-width: 500px;
            margin: 0 auto 30px;
        }
        .search-box input {
            width: 100%;
            padding: 15px 25px;
            border: 2px solid #e5e5e5;
            border-radius: 50px;
            font-size: 16px;
            transition: all 0.3s;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
        .search-box input:focus {
            outline: none;
            border-color: #2b7a37;
            box-shadow: 0 4px 20px rgba(43, 122, 55, 0.15);
        }
        .search-box input::placeholder {
            color: #999;
        }
    </style>
</head>
<body>
    <header class="site-header">
        <div class="header-container">
            <div class="logo">
                <a href="<?= APP_URL ?>">
                    <img src="<?= APP_URL ?>/public/images/logohinhanhquan/LogoWebsite.jpg" alt="Logo">
                </a>
            </div>
            <nav class="main-nav">
                <a href="<?= APP_URL ?>">Trang chủ</a>
                <a href="<?= APP_URL ?>/Home/menu">Menu Bánh sinh nhật</a>
                <a href="<?= APP_URL ?>/Home/orderHistory">Lịch sử đơn hàng</a>
            </nav>
        </div>
    </header>

    <div class="container">
        <div class="page-header">
            <h1>⭐ Gửi đánh giá sản phẩm</h1>
            <p>Chọn sản phẩm bạn muốn đánh giá</p>
        </div>
        
        <!-- Thanh tìm kiếm -->
        <div class="search-box">
            <input type="text" id="searchInput" placeholder="🔍 Tìm kiếm sản phẩm..." onkeyup="searchProducts()">
        </div>
        
        <?php if (!isset($_SESSION['user'])): ?>
            <div class="alert alert-warning">
                ⚠️ Vui lòng <a href="<?= APP_URL ?>/AuthController/ShowLogin">đăng nhập</a> để gửi đánh giá
            </div>
        <?php endif; ?>
        
        <div class="products-grid" id="productsGrid">
            <?php if (!empty($products)): ?>
                <?php foreach ($products as $product): ?>
                <div class="product-card">
                    <img src="<?= APP_URL ?>/public/images/<?= $product['hinhanh'] ?: 'default.png' ?>" alt="<?= htmlspecialchars($product['tensp']) ?>">
                    <div class="product-info">
                        <h3><?= htmlspecialchars($product['tensp']) ?></h3>
                        <p><?= htmlspecialchars($product['maLoaiSP']) ?></p>
                        <?php if (isset($product['avg_rating'])): ?>
                        <div class="review-count">
                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                <?= $i <= round($product['avg_rating']) ? '⭐' : '☆' ?>
                            <?php endfor; ?>
                            <span>(<?= $product['total_reviews'] ?? 0 ?> đánh giá)</span>
                        </div>
                        <?php endif; ?>
                        <a href="<?= APP_URL ?>/Home/reviewProduct/<?= $product['masp'] ?>" class="review-btn">
                            ✍️ Viết đánh giá
                        </a>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p style="text-align:center; color:#666; grid-column: 1/-1;">Chưa có sản phẩm nào</p>
            <?php endif; ?>
        </div>
        
        <div id="noResults" style="display:none; text-align:center; padding:40px; color:#666;">
            <p style="font-size:18px;">😕 Không tìm thấy sản phẩm nào</p>
            <p>Thử tìm kiếm với từ khóa khác</p>
        </div>
    </div>
    
    <script>
    function searchProducts() {
        const input = document.getElementById('searchInput').value.toLowerCase();
        const cards = document.querySelectorAll('.product-card');
        const grid = document.getElementById('productsGrid');
        const noResults = document.getElementById('noResults');
        let found = 0;
        
        cards.forEach(card => {
            const name = card.querySelector('h3').textContent.toLowerCase();
            const category = card.querySelector('p').textContent.toLowerCase();
            
            if (name.includes(input) || category.includes(input)) {
                card.style.display = 'block';
                found++;
            } else {
                card.style.display = 'none';
            }
        });
        
        if (found === 0 && input !== '') {
            noResults.style.display = 'block';
            grid.style.display = 'none';
        } else {
            noResults.style.display = 'none';
            grid.style.display = 'grid';
        }
    }
    </script>
</body>
</html>
