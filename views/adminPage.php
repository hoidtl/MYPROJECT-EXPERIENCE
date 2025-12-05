<!doctype html>
<html lang="vi">
<head>
    <title>Quản trị - Admin Panel</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" href="<?php echo APP_URL;?>/public/css/admin.css">
</head>
<body>

<header>
    <nav class="admin-navbar">
        <div class="navbar-container">
            <a class="navbar-brand" href="<?php echo APP_URL;?>/Admin/">
                🎂 Quản trị Website
            </a>

            <ul class="navbar-menu">
                <li>
                    <a href="<?php echo APP_URL;?>/ProductType/">📁 Loại sản phẩm</a>
                </li>
                <li>
                    <a href="<?php echo APP_URL;?>/Product/">🎂 Sản phẩm</a>
                </li>
                <li>
                    <a href="<?php echo APP_URL;?>/Admin/promotionList">🎁 Khuyến mãi</a>
                </li>
                <li>
                    <a href="<?php echo APP_URL;?>/Admin/orderList">📦 Đơn hàng</a>
                </li>
                <li>
                    <a href="<?php echo APP_URL;?>/Admin/inventory">📊 Kho hàng</a>
                </li>
                <li>
                    <a href="<?php echo APP_URL;?>/Admin/reviewList">⭐ Đánh giá</a>
                </li>
                <li>
                    <a href="<?php echo APP_URL;?>/Admin/userList">👥 Người dùng</a>
                </li>
                <li>
                    <a href="<?php echo APP_URL;?>/Home">🏠 Về trang chủ</a>
                </li>
            </ul>
        </div>
    </nav>
</header>

<main class="admin-container">
    <?php require_once "./views/Back_end/".$data["page"].".php"; ?>
</main>

<footer style="text-align:center; padding:20px; color:#95a5a6; font-size:14px;">
    © 2025 Admin Panel - Website Bán Bánh Kem
</footer>

</body>
</html>
