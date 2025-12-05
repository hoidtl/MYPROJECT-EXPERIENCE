<?php
$order = $data['order'] ?? [];
$details = $data['details'] ?? [];
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Chi tiết đơn hàng - <?= htmlspecialchars($order['order_code'] ?? '') ?></title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: Arial, sans-serif;
            background: #f5f8f4;
            min-height: 100vh;
        }
        
        /* Header */
        .site-header {
            background: #6fa05f;
            height: 70px;
            display: flex;
            align-items: center;
        }
        .header-container {
            width: 100%;
            max-width: 1200px;
            margin: auto;
            padding: 0 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .logo img {
            height: 60px;
            width: 60px;
        }
        .main-nav {
            display: flex;
            gap: 30px;
        }
        .main-nav a {
            color: #fff;
            text-decoration: none;
            font-size: 16px;
            font-weight: 500;
        }
        .main-nav a:hover {
            text-decoration: underline;
        }
        .header-right {
            position: relative;
        }
        .cart-link {
            color: #fff;
            font-size: 22px;
            text-decoration: none;
        }
        .cart-count {
            position: absolute;
            top: -6px;
            right: -10px;
            background: #ff4757;
            color: #fff;
            font-size: 12px;
            padding: 2px 6px;
            border-radius: 50%;
        }
        
        /* Container */
        .container {
            width: 92%;
            max-width: 1000px;
            margin: 40px auto;
            background: white;
            padding: 30px;
            border-radius: 14px;
            box-shadow: 0 4px 16px rgba(0,0,0,0.08);
        }
        
        h2 {
            color: #2b7a37;
            font-weight: 700;
            margin-bottom: 25px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        /* Info Grid */
        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 25px;
            margin-bottom: 30px;
        }
        
        .info-box {
            background: #f0f7ef;
            border-radius: 10px;
            padding: 20px;
        }
        .info-box h3 {
            color: #2b7a37;
            font-size: 16px;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 2px solid #2b7a37;
        }
        .info-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px dashed #ccc;
        }
        .info-row:last-child {
            border-bottom: none;
        }
        .info-row span:first-child {
            color: #666;
        }
        .info-row strong {
            color: #333;
        }
        
        /* Status Badge */
        .badge {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 13px;
            font-weight: 600;
            color: white;
        }
        .bg-success { background: #2b7a37; }
        .bg-danger { background: #e74c3c; }
        .bg-warning { background: #f0ad4e; color: #333; }
        
        /* Products Table */
        .products-section {
            margin-top: 30px;
        }
        .products-section h3 {
            color: #2b7a37;
            margin-bottom: 15px;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            border-radius: 10px;
            overflow: hidden;
        }
        thead {
            background: #2b7a37;
            color: white;
        }
        th, td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #e5e5e5;
        }
        tr:hover {
            background: #f0f7ef;
        }
        .product-img {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 8px;
        }
        
        /* Payment Summary */
        .payment-summary {
            background: #f0f7ef;
            border-radius: 10px;
            padding: 20px;
            margin-top: 25px;
        }
        .payment-summary h3 {
            color: #2b7a37;
            margin-bottom: 15px;
        }
        .summary-row {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px dashed #ccc;
        }
        .summary-row:last-child {
            border-bottom: none;
        }
        .summary-row.total {
            font-size: 18px;
            font-weight: bold;
            color: #2b7a37;
            border-top: 2px solid #2b7a37;
            margin-top: 10px;
            padding-top: 15px;
        }
        .text-success { color: #2b7a37; }
        .text-danger { color: #e74c3c; }
        
        /* Buttons */
        .btn-group {
            display: flex;
            gap: 15px;
            margin-top: 25px;
        }
        .btn {
            padding: 12px 25px;
            border-radius: 8px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            border: none;
            transition: all 0.3s;
        }
        .btn-primary {
            background: #2b7a37;
            color: white;
        }
        .btn-primary:hover {
            background: #236b2e;
        }
        .btn-secondary {
            background: #e5e5e5;
            color: #333;
        }
        .btn-secondary:hover {
            background: #d5d5d5;
        }
        .btn-warning {
            background: #f0ad4e;
            color: #333;
        }
        .btn-warning:hover {
            background: #ec971f;
        }
        
        @media (max-width: 768px) {
            .info-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
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
                <a href="<?= APP_URL ?>/Home/advice">Tư vấn</a>
                <a href="<?= APP_URL ?>/Home/contact">Liên hệ</a>
            </nav>
            <div class="header-right">
                <a href="<?= APP_URL ?>/Home/order" class="cart-link">
                    🛒
                    <span class="cart-count">
                        <?= isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0 ?>
                    </span>
                </a>
            </div>
        </div>
    </header>

    <!-- Container -->
    <div class="container">
        <h2>📋 Chi tiết đơn hàng: <?= htmlspecialchars($order['order_code'] ?? '') ?></h2>
        
        <!-- Info Grid -->
        <div class="info-grid">
            <!-- Order Info -->
            <div class="info-box">
                <h3>🛒 Thông tin đơn hàng</h3>
                <div class="info-row">
                    <span>Mã đơn hàng:</span>
                    <strong><?= htmlspecialchars($order['order_code'] ?? '') ?></strong>
                </div>
                <div class="info-row">
                    <span>Ngày đặt:</span>
                    <strong><?= htmlspecialchars($order['created_at'] ?? '') ?></strong>
                </div>
                <div class="info-row">
                    <span>Trạng thái:</span>
                    <?php
                    $status = $order['transaction_info'] ?? '';
                    if ($status == 'dathanhtoan') {
                        echo '<span class="badge bg-success">Đã thanh toán</span>';
                    } elseif ($status == 'thanhtoanthieu') {
                        echo '<span class="badge bg-warning">Thanh toán thiếu</span>';
                    } else {
                        echo '<span class="badge bg-danger">Chưa thanh toán</span>';
                    }
                    ?>
                </div>
                <div class="info-row">
                    <span>Phương thức thanh toán:</span>
                    <strong>
                        <?php 
                        $pm = $order['payment_method'] ?? '';
                        if ($pm == 'bank_before') {
                            echo 'Chuyển khoản trước';
                        } elseif ($pm == 'bank_after' || $pm == 'bank') {
                            echo 'Chuyển khoản sau';
                        } else {
                            echo 'Tiền mặt khi nhận hàng';
                        }
                        ?>
                    </strong>
                </div>
                <div class="info-row">
                    <span>Hình thức giao hàng:</span>
                    <strong>
                        <?= ($order['delivery_method'] ?? '') === 'store' ? 'Lấy tại cửa hàng' : 'Giao hàng tận nơi' ?>
                    </strong>
                </div>
            </div>
            
            <!-- Receiver Info -->
            <div class="info-box">
                <h3>👤 Thông tin người nhận</h3>
                <div class="info-row">
                    <span>Người nhận:</span>
                    <strong><?= htmlspecialchars($order['receiver'] ?? '') ?></strong>
                </div>
                <div class="info-row">
                    <span>Số điện thoại:</span>
                    <strong><?= htmlspecialchars($order['phone'] ?? '') ?></strong>
                </div>
                <div class="info-row">
                    <span>Địa chỉ:</span>
                    <strong>
                        <?= ($order['delivery_method'] ?? '') === 'store' 
                            ? 'Lấy tại cửa hàng' 
                            : htmlspecialchars($order['address'] ?? '') ?>
                    </strong>
                </div>
                <?php if (!empty($order['note'])): ?>
                <div class="info-row">
                    <span>Ghi chú:</span>
                    <strong><?= htmlspecialchars($order['note']) ?></strong>
                </div>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Products -->
        <div class="products-section">
            <h3>🎂 Sản phẩm đã đặt</h3>
            <table>
                <thead>
                    <tr>
                        <th>STT</th>
                        <th>Sản phẩm</th>
                        <th>Size</th>
                        <th>Số lượng</th>
                        <th>Đơn giá</th>
                        <th>Thành tiền</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($details)): ?>
                        <?php 
                        $stt = 1; 
                        // Kết nối DB để lấy giá từ tbl_sanpham_size
                        $pdo = new PDO('mysql:host=localhost;dbname=website', 'root', '');
                        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
                        foreach ($details as $item): 
                        ?>
                        <?php 
                        // Bỏ qua addon không hợp lệ
                        $productId = $item['product_id'] ?? '';
                        if (strpos($productId, 'addon_') === 0 && empty($item['product_name'])) continue;
                        
                        $productName = $item['product_name'] ?? $item['tensp'] ?? $productId;
                        $qty = (int)($item['quantity'] ?? $item['qty'] ?? 0);
                        $size = $item['size'] ?? '';
                        
                        // Lấy giá từ tbl_sanpham_size nếu price = 0
                        $price = (float)($item['price'] ?? 0);
                        if ($price <= 0 && !empty($productId) && !empty($size)) {
                            $stmt = $pdo->prepare("SELECT giaXuat FROM tbl_sanpham_size WHERE masp = ? AND size = ?");
                            $stmt->execute([$productId, $size]);
                            $sizeInfo = $stmt->fetch();
                            $price = $sizeInfo['giaXuat'] ?? 0;
                        }
                        
                        if ($qty <= 0) continue;
                        ?>
                        <tr>
                            <td><?= $stt++ ?></td>
                            <td><?= htmlspecialchars($productName) ?></td>
                            <td><?= htmlspecialchars($size) ?></td>
                            <td><?= $qty ?></td>
                            <td><?= number_format($price, 0, ',', '.') ?> ₫</td>
                            <td><?= number_format($price * $qty, 0, ',', '.') ?> ₫</td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="6" style="text-align:center;">Không có sản phẩm</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        
        <!-- Payment Summary -->
        <div class="payment-summary">
            <h3>💰 Thông tin thanh toán</h3>
            <div class="summary-row">
                <span>Tổng tiền đơn hàng:</span>
                <strong><?= number_format($order['total_amount'] ?? 0, 0, ',', '.') ?> ₫</strong>
            </div>
            <?php if (!empty($order['discount_amount']) && $order['discount_amount'] > 0): ?>
            <div class="summary-row">
                <span>Giảm giá (Mã: <?= htmlspecialchars($order['coupon_code'] ?? '') ?>):</span>
                <strong class="text-success">-<?= number_format($order['discount_amount'], 0, ',', '.') ?> ₫</strong>
            </div>
            <?php endif; ?>
            <?php if (isset($order['received_amount']) && $order['received_amount'] > 0): ?>
            <div class="summary-row">
                <span>Đã thanh toán:</span>
                <strong class="text-success"><?= number_format($order['received_amount'], 0, ',', '.') ?> ₫</strong>
            </div>
            <?php endif; ?>
            <?php 
            $lackAmount = $order['lack_amount'] ?? (($order['total_amount'] ?? 0) - ($order['received_amount'] ?? 0));
            if ($lackAmount > 0): 
            ?>
            <div class="summary-row total">
                <span>Còn phải thanh toán:</span>
                <strong class="text-danger"><?= number_format($lackAmount, 0, ',', '.') ?> ₫</strong>
            </div>
            <?php else: ?>
            <div class="summary-row total">
                <span>Trạng thái:</span>
                <strong class="text-success">✓ Đã thanh toán đủ</strong>
            </div>
            <?php endif; ?>
        </div>
        
        <!-- Buttons -->
        <div class="btn-group">
            <a href="<?= APP_URL ?>/Home/orderHistory" class="btn btn-secondary">← Quay lại lịch sử đơn hàng</a>
            <?php 
            $pm = $order['payment_method'] ?? '';
            $isBank = in_array($pm, ['bank', 'bank_before', 'bank_after']);
            $status = $order['transaction_info'] ?? '';
            $needsPayment = empty($status) || $status == 'chothanhtoan' || $status == 'thanhtoanthieu';
            
            if ($isBank && $needsPayment && $lackAmount > 0): 
            ?>
            <form action="<?= APP_URL ?>/Home/vnpayPay" method="POST" style="display:inline;">
                <input type="hidden" name="order_code" value="<?= htmlspecialchars($order['order_code'] ?? '') ?>">
                <input type="hidden" name="amount" value="<?= $lackAmount ?>">
                <button type="submit" class="btn btn-warning">
                    Thanh toán <?= number_format($lackAmount, 0, ',', '.') ?> ₫
                </button>
            </form>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
