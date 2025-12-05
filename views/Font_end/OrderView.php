<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Giỏ hàng</title>
    <link rel="stylesheet" href="<?= APP_URL ?>/public/css/orderView.css">
</head>
<body>

<header class="site-header">
    <div class="header-container">

        <!-- LOGO -->
        <div class="logo">
            <a href="<?= APP_URL ?>">
                <img src="<?= APP_URL ?>/public/images/logohinhanhquan/LogoWebsite.jpg" alt="Savor Cake">
            </a>
        </div>

        <!-- MENU -->
        <nav class="main-nav">
            <a href="<?= APP_URL ?>">Trang chủ</a>
            <a href="<?= APP_URL ?>/Home/menu">Menu Bánh sinh nhật</a>
            <a href="<?= APP_URL ?>/Home/advice">Tư vấn</a>
            <a href="<?= APP_URL ?>/Home/contact">Liên hệ</a>
        </nav>

        

    </div>
</header>


<div class="cart-page">

    <h1 class="cart-title">Giỏ hàng</h1>

    <p class="cart-count">
        Bạn có <strong><?= count($listProductOrder) ?></strong> sản phẩm trong giỏ hàng
    </p>

    <div class="cart-header">
        <div>Sản phẩm</div>
        <div>Giá</div>
        <div>Số lượng</div>
        <div>Tạm tính</div>
    </div>

    <form action="<?= APP_URL ?>/Home/update" method="post">

        <!-- ================= SẢN PHẨM CHÍNH ================= -->
        <?php foreach ($listProductOrder as $v): ?>
            <?php
            if (($v['type'] ?? 'product') === 'addon') continue;
            $thanhTien = $v['gia'] * $v['qty'];
            ?>

            <div class="cart-item">
                <div class="product-box">
                    <img src="<?= APP_URL ?>/public/images/<?= htmlspecialchars($v['hinhanh']) ?>" class="cart-img">
                    <div>
                        <div class="cart-name"><?= htmlspecialchars($v['tensp']) ?></div>
                        <div class="cart-meta">
                            SKU: <?= $v['masp'] ?> | Size: <?= htmlspecialchars($v['size']) ?>
                        </div>

                        <label class="addon-label">Chữ trên đế</label>
                        <input type="text"
                               class="addon-input"
                               name="addon[<?= $v['masp'] ?>][<?= $v['size'] ?>]"
                               placeholder="Nhập chữ (không bắt buộc)">
                    </div>
                </div>

                <div class="price">
                    <?= number_format($v['gia'], 0, ',', '.') ?> ₫
                </div>

                <div class="qty">
                    <button type="button" class="qty-btn minus">−</button>
                    <input type="number"
                           name="qty[<?= $v['masp'] ?>][<?= $v['size'] ?>]"
                           value="<?= $v['qty'] ?>"
                           min="1">
                    <button type="button" class="qty-btn plus">+</button>
                </div>

                <div class="total">
                    <?= number_format($thanhTien, 0, ',', '.') ?> ₫
                    <a class="delete"
                       href="<?= APP_URL ?>/Home/delete/<?= $v['masp'] ?>/<?= urlencode($v['size']) ?>"
                       onclick="return confirm('Xoá sản phẩm?')">Xoá</a>
                </div>
            </div>
        <?php endforeach; ?>


        <!-- ================= PHỤ KIỆN ĐÃ THÊM ================= -->
            <?php foreach ($listProductOrder as $v): ?>
            <?php if (($v['type'] ?? '') === 'addon'): ?>
            <div class="cart-item addon-item">

                <div class="product-box">
                    <img src="<?= APP_URL ?>/public/images/<?= htmlspecialchars($v['hinhanh']) ?>" class="cart-img">
                    <div>
                        <div class="cart-name">
                            <?= htmlspecialchars($v['tensp']) ?>
                            <small style="color:#e67e22">(Phụ kiện)</small>
                        </div>
                    </div>
                </div>

                <div class="price">
                    <?= number_format($v['gia'], 0, ',', '.') ?> ₫
                </div>

                <!-- ✅ INPUT CHO UPDATE -->
                <div class="qty">
                    <button type="button" class="qty-btn minus">−</button>

                    <input
                        type="number"
                        min="1"
                        name="addon_qty[<?= $v['masp'] ?>]"
                        value="<?= $v['qty'] ?>"
                    >

                    <button type="button" class="qty-btn plus">+</button>
                </div>


                <div class="total">
                    <?= number_format($v['gia'] * $v['qty'], 0, ',', '.') ?> ₫
                    <br>
                    <a 
                        href="<?= APP_URL ?>/Home/removeAddon/<?= $v['masp'] ?>" 
                        style="color:red"
                        onclick="return confirm('Xoá phụ kiện?')"
                    >
                        Xoá
                    </a>
                </div>

            </div>
            <?php endif; ?>
            <?php endforeach; ?>



        <div class="cart-actions">
            <button class="btn-update">Cập nhật</button>

            <?php if (isset($_SESSION['user'])): ?>
                <a href="<?= APP_URL ?>/Home/checkoutInfo" class="btn-order">Đặt hàng</a>
            <?php else: ?>
                <a href="<?= APP_URL ?>/AuthController/ShowLogin" class="btn-order">Đăng nhập</a>
            <?php endif; ?>
        </div>

    </form>
</div>


<!-- ================= DANH SÁCH PHỤ KIỆN ================= -->
<?php if (!empty($phuKien)): ?>
<div class="addon-section">
    <h2>🎁 Phụ kiện đi kèm</h2>

    <div class="addon-grid">
        <?php foreach ($phuKien as $pk): ?>
            <div class="addon-card">
                <img src="<?= APP_URL ?>/public/images/<?= htmlspecialchars($pk['hinhanh']) ?>">
                <div class="addon-name"><?= htmlspecialchars($pk['tensp']) ?></div>
                <div class="addon-price">
                    <?= number_format($pk['display_price'], 0, ',', '.') ?> ₫
                </div>
                <button
                    type="button"
                    class="btn-addon"
                    data-id="<?= $pk['masp'] ?>">
                    Thêm
                </button>
            </div>
        <?php endforeach; ?>
    </div>
</div>
<?php endif; ?>


<script>
document.addEventListener('click', e => {
    if (e.target.classList.contains('plus')) {
        e.target.previousElementSibling.value++;
    }
    if (e.target.classList.contains('minus')) {
        const i = e.target.nextElementSibling;
        i.value = Math.max(1, i.value - 1);
    }
});
</script>

<script>
document.querySelectorAll('.btn-addon').forEach(btn => {
    btn.addEventListener('click', function () {
        const masp = this.dataset.id;

        fetch('<?= APP_URL ?>/Home/addAddon', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: 'masp=' + masp
        })
        .then(res => res.text())
        .then(data => {
            console.log(data);
            location.reload();
        });
    });
});
</script>


</body>
</html>
