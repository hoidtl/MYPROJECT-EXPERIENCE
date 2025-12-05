<?php
$product = $data['product'] ?? [];
$reviews = $data['reviews'] ?? [];
$stats = $data['stats'] ?? [];
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Đánh giá - <?= htmlspecialchars($product['tensp'] ?? '') ?></title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; background: #f5f8f4; min-height: 100vh; }
        
        .site-header { background: #6fa05f; height: 70px; display: flex; align-items: center; }
        .header-container { width: 100%; max-width: 1200px; margin: auto; padding: 0 20px; display: flex; align-items: center; justify-content: space-between; }
        .logo img { height: 60px; width: 60px; }
        .main-nav { display: flex; gap: 30px; }
        .main-nav a { color: #fff; text-decoration: none; font-size: 16px; font-weight: 500; }
        
        .container { max-width: 900px; margin: 40px auto; padding: 0 20px; }
        
        /* Product Info */
        .product-header {
            background: #fff;
            border-radius: 12px;
            padding: 25px;
            display: flex;
            gap: 25px;
            margin-bottom: 30px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        }
        .product-header img { width: 150px; height: 150px; object-fit: cover; border-radius: 10px; }
        .product-header-info h1 { color: #2b7a37; font-size: 24px; margin-bottom: 10px; }
        .product-header-info p { color: #666; margin-bottom: 8px; }
        
        /* Rating Stats */
        .rating-stats {
            display: flex;
            align-items: center;
            gap: 20px;
            margin-top: 15px;
        }
        .avg-rating { text-align: center; }
        .avg-rating .number { font-size: 48px; font-weight: bold; color: #f39c12; }
        .avg-rating .stars { color: #f39c12; font-size: 20px; }
        .avg-rating .count { color: #666; font-size: 14px; }
        
        /* Review Form */
        .review-form-card {
            background: #fff;
            border-radius: 12px;
            padding: 30px;
            margin-bottom: 30px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        }
        .review-form-card h2 { color: #2b7a37; margin-bottom: 20px; }
        
        .form-group { margin-bottom: 20px; }
        .form-label { display: block; margin-bottom: 8px; font-weight: 600; color: #333; }
        .form-control {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e5e5e5;
            border-radius: 8px;
            font-size: 14px;
        }
        .form-control:focus { outline: none; border-color: #2b7a37; }
        textarea.form-control { min-height: 120px; resize: vertical; }
        
        /* Star Rating Input */
        .star-rating { display: flex; gap: 5px; flex-direction: row-reverse; justify-content: flex-end; }
        .star-rating input { display: none; }
        .star-rating label {
            font-size: 35px;
            color: #ddd;
            cursor: pointer;
            transition: color 0.2s;
        }
        .star-rating label:hover,
        .star-rating label:hover ~ label,
        .star-rating input:checked ~ label { color: #f39c12; }
        
        /* Image Upload */
        .image-upload { position: relative; }
        .image-upload input[type="file"] { display: none; }
        .upload-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 12px 20px;
            background: #f0f7ef;
            border: 2px dashed #2b7a37;
            border-radius: 8px;
            cursor: pointer;
            color: #2b7a37;
            font-weight: 500;
        }
        .upload-btn:hover { background: #e5f2e5; }
        .preview-img { max-width: 200px; margin-top: 10px; border-radius: 8px; display: none; }
        
        .btn {
            padding: 14px 30px;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            border: none;
            transition: all 0.3s;
        }
        .btn-primary { background: linear-gradient(135deg, #2b7a37 0%, #4a8c3a 100%); color: #fff; }
        .btn-primary:hover { opacity: 0.9; }
        .btn-secondary { background: #e5e5e5; color: #333; }
        
        /* Reviews List */
        .reviews-list { margin-top: 30px; }
        .reviews-list h2 { color: #2b7a37; margin-bottom: 20px; }
        
        .review-item {
            background: #fff;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 15px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
        .review-header { display: flex; justify-content: space-between; margin-bottom: 10px; }
        .review-user { font-weight: 600; color: #333; }
        .review-date { color: #999; font-size: 13px; }
        .review-stars { color: #f39c12; margin-bottom: 10px; }
        .review-comment { color: #555; line-height: 1.6; }
        .review-image { max-width: 200px; margin-top: 10px; border-radius: 8px; }
        
        .alert { padding: 15px; border-radius: 8px; margin-bottom: 20px; }
        .alert-success { background: #d4edda; color: #155724; }
        .alert-danger { background: #f8d7da; color: #721c24; }
        .alert-warning { background: #fff3cd; color: #856404; }
        
        .back-link { display: inline-flex; align-items: center; gap: 5px; color: #2b7a37; text-decoration: none; margin-bottom: 20px; }
        .back-link:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <header class="site-header">
        <div class="header-container">
            <div class="logo">
                <a href="<?= APP_URL ?>"><img src="<?= APP_URL ?>/public/images/logohinhanhquan/LogoWebsite.jpg" alt="Logo"></a>
            </div>
            <nav class="main-nav">
                <a href="<?= APP_URL ?>">Trang chủ</a>
                <a href="<?= APP_URL ?>/Home/reviewList">Gửi đánh giá</a>
                <a href="<?= APP_URL ?>/Home/orderHistory">Lịch sử đơn hàng</a>
            </nav>
        </div>
    </header>

    <div class="container">
        <a href="<?= APP_URL ?>/Home/reviewList" class="back-link">← Quay lại danh sách sản phẩm</a>
        
        <?php if(isset($_SESSION['review_success'])): ?>
            <div class="alert alert-success"><?= $_SESSION['review_success']; unset($_SESSION['review_success']); ?></div>
        <?php endif; ?>
        
        <?php if(isset($_SESSION['review_error'])): ?>
            <div class="alert alert-danger"><?= $_SESSION['review_error']; unset($_SESSION['review_error']); ?></div>
        <?php endif; ?>
        
        <!-- Product Header -->
        <div class="product-header">
            <img src="<?= APP_URL ?>/public/images/<?= $product['hinhanh'] ?: 'default.png' ?>" alt="">
            <div class="product-header-info">
                <h1><?= htmlspecialchars($product['tensp'] ?? '') ?></h1>
                <p>Loại: <?= htmlspecialchars($product['maLoaiSP'] ?? '') ?></p>
                
                <?php if ($stats && $stats['total_reviews'] > 0): ?>
                <div class="rating-stats">
                    <div class="avg-rating">
                        <div class="number"><?= number_format($stats['avg_rating'], 1) ?></div>
                        <div class="stars">
                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                <?= $i <= round($stats['avg_rating']) ? '⭐' : '☆' ?>
                            <?php endfor; ?>
                        </div>
                        <div class="count"><?= $stats['total_reviews'] ?> đánh giá</div>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Review Form -->
        <?php if (isset($_SESSION['user'])): ?>
        <div class="review-form-card">
            <h2>✍️ Viết đánh giá của bạn</h2>
            <form action="<?= APP_URL ?>/Home/submitReview" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="product_id" value="<?= htmlspecialchars($product['masp'] ?? '') ?>">
                
                <div class="form-group">
                    <label class="form-label">Đánh giá của bạn *</label>
                    <div class="star-rating">
                        <input type="radio" name="rating" value="5" id="star5" required><label for="star5">★</label>
                        <input type="radio" name="rating" value="4" id="star4"><label for="star4">★</label>
                        <input type="radio" name="rating" value="3" id="star3"><label for="star3">★</label>
                        <input type="radio" name="rating" value="2" id="star2"><label for="star2">★</label>
                        <input type="radio" name="rating" value="1" id="star1"><label for="star1">★</label>
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Nhận xét của bạn</label>
                    <textarea name="comment" class="form-control" placeholder="Chia sẻ trải nghiệm của bạn về sản phẩm này..."></textarea>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Thêm hình ảnh (tùy chọn)</label>
                    <div class="image-upload">
                        <label class="upload-btn">
                            📷 Chọn ảnh
                            <input type="file" name="review_image" accept="image/*" onchange="previewImage(this)">
                        </label>
                        <img id="preview" class="preview-img">
                    </div>
                </div>
                
                <button type="submit" class="btn btn-primary">📤 Gửi đánh giá</button>
            </form>
        </div>
        <?php else: ?>
        <div class="alert alert-warning">
            ⚠️ Vui lòng <a href="<?= APP_URL ?>/AuthController/ShowLogin">đăng nhập</a> để gửi đánh giá
        </div>
        <?php endif; ?>
        
        <!-- Reviews List -->
        <div class="reviews-list">
            <h2>📝 Đánh giá từ khách hàng (<?= count($reviews) ?>)</h2>
            
            <?php if (!empty($reviews)): ?>
                <?php foreach ($reviews as $review): ?>
                <div class="review-item">
                    <div class="review-header">
                        <span class="review-user">👤 <?= htmlspecialchars($review['user_name']) ?></span>
                        <span class="review-date"><?= date('d/m/Y H:i', strtotime($review['created_at'])) ?></span>
                    </div>
                    <div class="review-stars">
                        <?php for ($i = 1; $i <= 5; $i++): ?>
                            <?= $i <= $review['rating'] ? '⭐' : '☆' ?>
                        <?php endfor; ?>
                    </div>
                    <?php if ($review['comment']): ?>
                    <div class="review-comment"><?= nl2br(htmlspecialchars($review['comment'])) ?></div>
                    <?php endif; ?>
                    <?php if ($review['image']): ?>
                    <img src="<?= APP_URL ?>/public/images/reviews/<?= $review['image'] ?>" class="review-image" alt="">
                    <?php endif; ?>
                </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p style="color:#666; text-align:center; padding:30px;">Chưa có đánh giá nào cho sản phẩm này. Hãy là người đầu tiên!</p>
            <?php endif; ?>
        </div>
    </div>
    
    <script>
    function previewImage(input) {
        const preview = document.getElementById('preview');
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.style.display = 'block';
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
    </script>
</body>
</html>
