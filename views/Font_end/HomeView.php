<style>
.section-title {
    font-size: 26px;
    font-weight: 700;
    margin: 40px 0 10px;
    color: #333;
    border-left: 4px solid #5B8A32;
    padding-left: 15px;
}

.product-row {
    display: flex;
    gap: 25px;
    overflow-x: auto;
    padding: 15px 5px 25px 5px;
    scrollbar-width: thin;
    scrollbar-color: #5B8A32 #f1f1f1;
}

.product-row::-webkit-scrollbar {
    height: 8px;
}

.product-row::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 10px;
}

.product-row::-webkit-scrollbar-thumb {
    background: #5B8A32;
    border-radius: 10px;
}

.product-card {
    min-width: 250px;
    background: #fff;
    border-radius: 16px;
    padding: 18px;
    border: 1px solid #e6e6e6;
    transition: 0.25s;
    display: flex;
    flex-direction: column;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.product-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 5px 15px rgba(0,0,0,0.15);
}

.product-card img {
    width: 100%;
    height: 200px;
    object-fit: cover;
    border-radius: 12px;
    margin-bottom: 15px;
}

.product-name {
    font-size: 18px;
    font-weight: 700;
    min-height: 45px;
    color: #333;
    line-height: 1.3;
}

.product-price {
    font-size: 18px;
    font-weight: bold;
    color: #5B8A32;
    margin: 8px 0 18px;
}

.discount-badge {
    background: #e74c3c;
    color: white;
    padding: 2px 8px;
    border-radius: 4px;
    font-size: 0.8em;
    margin-left: 8px;
}

.original-price {
    text-decoration: line-through;
    color: #999;
    font-size: 14px;
    margin-right: 8px;
}

.add-cart-btn {
    background: #5B8A32;
    color: white;
    padding: 12px;
    border-radius: 10px;
    text-align: center;
    text-decoration: none;
    margin-top: auto;
    font-weight: 600;
    transition: background 0.3s;
}

.add-cart-btn:hover {
    background: #4a7328;
    color: white;
}

.empty-category {
    text-align: center;
    padding: 40px;
    color: #666;
    font-style: italic;
}
</style>

<?php 
// $productData dạng: 
// [
//    "BanhInHinh" => [...],
//    "BanhKem" => [...],
//    ...
// ]
?>

<?php if(empty($productData)): ?>
    <div class="empty-category">
        <h3>Chưa có sản phẩm nào</h3>
        <p>Vui lòng quay lại sau!</p>
    </div>
<?php else: ?>

    <?php foreach($productData as $categoryName => $products): ?>

        <?php if(empty($products)) continue; ?>

        <!-- Tiêu đề danh mục -->
        <h2 class="section-title"><?= htmlspecialchars($categoryName) ?></h2>

        <div class="product-row">

            <?php foreach($products as $p): ?>
                <div class="product-card">

                    <a href="<?= APP_URL ?>/Home/detail/<?= $p['masp'] ?>">
                        <img src="<?= APP_URL ?>/uploads/<?= $p['hinhanh'] ?>" alt="<?= $p['tensp'] ?>">
                    </a>

                    <div class="product-name"><?= $p['tensp'] ?></div>

                    <div class="product-price">
                        <?php if(isset($p['khuyenmai']) && $p['khuyenmai'] > 0): ?>
                            <?php 
                                $giaGoc = $p['giaXuat'];
                                $giaKM = $giaGoc - ($giaGoc * $p['khuyenmai'] / 100);
                            ?>
                            <span class="original-price"><?= number_format($giaGoc) ?>₫</span>
                            <?= number_format($giaKM) ?>₫
                            <span class="discount-badge">-<?= $p['khuyenmai'] ?>%</span>
                        <?php else: ?>
                            <?= number_format($p['giaXuat']) ?>₫
                        <?php endif; ?>
                    </div>

                    <a class="add-cart-btn" href="<?= APP_URL ?>/Home/addtocard/<?= $p['masp'] ?>">
                        Đặt ngay
                    </a>

                </div>
            <?php endforeach; ?>

        </div>

    <?php endforeach; ?>

<?php endif; ?>
