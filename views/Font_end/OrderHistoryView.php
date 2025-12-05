<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Lịch sử đơn hàng</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="<?php echo APP_URL; ?>/public/css/orderHistory.css">
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

        <!-- ICON -->
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
<div class="container mt-5">
    <h2>Lịch sử đơn hàng của bạn</h2>
    <table class="table table-bordered table-hover">
        <thead class="table-dark">
            <tr>
                <th>Mã hóa đơn</th>
                <th>Ngày đặt</th>
                <th>Tổng tiền</th>
                <th>Người nhận</th>
                <th>Địa chỉ giao hàng</th>
                <th>Số điện thoại</th>
                <th>Trạng thái</th>
                <th>Phương thức thanh toán</th>
                <th>Thao tác</th>
            </tr>
        </thead>
        <tbody>
        <?php if (!empty($data['orders'])): foreach ($data['orders'] as $order): ?>
            <tr>
                <td><?= htmlspecialchars($order['order_code']) ?></td>
                <td><?= htmlspecialchars($order['created_at']) ?></td>
                <td>
                    <div>Tổng đơn: <?= number_format($order['total_amount'], 0, ',', '.') ?> ₫</div>
                    <?php 
                    $status = $order['transaction_info'];
                    if (!empty($order['discount_amount']) && $order['discount_amount'] > 0): ?>
                        <div class="text-primary">Giảm: <?= number_format($order['discount_amount'], 0, ',', '.') ?> ₫ (Mã: <?= htmlspecialchars($order['coupon_code']) ?>)</div>
                    <?php endif; ?>
                    <?php if ($status == 'dathanhtoan'): ?>
                        <div class="text-success">Đã thanh toán đủ</div>
                    <?php elseif (isset($order['received_amount']) && $order['received_amount'] > 0): ?>
                        <div class="text-success">Đã thanh toán: <?= number_format($order['received_amount'], 0, ',', '.') ?> ₫</div>
                        <div class="text-danger">Còn thiếu: <?= number_format($order['lack_amount'], 0, ',', '.') ?> ₫</div>
                    <?php endif; ?>
                </td>
                <td><?= htmlspecialchars($order['receiver']) ?></td>
                <td>
    <?= htmlspecialchars(
        ($order['delivery_method'] ?? '') === 'store'
            ? 'Lấy tại cửa hàng'
            : ($order['address'] ?? '')
    ) ?>
</td>


                <td><?= htmlspecialchars($order['phone']) ?></td>
                <td>
                    <?php
                    $status = $order['transaction_info'];
                    if ($status == 'dathanhtoan') {
                        echo '<span class="badge bg-success">Đã thanh toán</span>';
                    } elseif ($status == 'thanhtoanthieu') {
                        echo '<span class="badge bg-warning">Thanh toán thiếu</span>';
                    } else {
                        echo '<span class="badge bg-danger">Chưa thanh toán</span>';
                    }
                    ?>
                </td>
                <td>
                <?php 
                    $pm = $order['payment_method'] ?? '';
                    if ($pm == 'bank_before') {
                        echo 'Chuyển khoản trước';
                    } elseif ($pm == 'bank_after' || $pm == 'bank') {
                        echo 'Chuyển khoản sau khi nhận hàng';
                    } else {
                        echo 'Thanh toán tiền mặt khi nhận hàng';
                    }
                ?>
            </td>
                <td>
                    <a href="<?php echo APP_URL; ?>/Home/orderDetail/<?= $order['id'] ?>" class="btn btn-info btn-sm">Xem chi tiết</a>
                    <?php 
                    // Chỉ hiện nút thanh toán khi:
                    // 1. Phương thức là chuyển khoản (bank_before, bank_after, bank)
                    // 2. Chưa thanh toán đủ (chothanhtoan hoặc thanhtoanthieu)
                    $pm = $order['payment_method'] ?? '';
                    $isBank = in_array($pm, ['bank', 'bank_before', 'bank_after']);
                    $needsPayment = empty($status) || $status == 'chothanhtoan' || $status == 'thanhtoanthieu';
                    
                    if ($isBank && $needsPayment): 
                    ?>
                        <form action="<?php echo APP_URL; ?>/Home/vnpayPay" method="POST" class="d-inline">
                            <input type="hidden" name="order_code" value="<?= htmlspecialchars($order['order_code']) ?>">
                            <input type="hidden" name="amount" value="<?= isset($order['lack_amount']) && $order['lack_amount'] > 0 ? $order['lack_amount'] : $order['total_amount'] ?>">
                            <button type="submit" class="btn btn-primary btn-sm">
                                <?php echo isset($order['lack_amount']) && $order['lack_amount'] > 0 ? 
                                    'Thanh toán số tiền còn thiếu' : 'Thanh toán'; ?>
                            </button>
                        </form>
                    <?php endif; ?>
            </tr>
        <?php endforeach; else: ?>
            <tr><td colspan="6" class="text-center">Bạn chưa có đơn hàng nào.</td></tr>
        <?php endif; ?>
        <?php if (isset($_SESSION['success'])): ?>
    <div class="alert alert-success">
        <?= $_SESSION['success']; unset($_SESSION['success']); ?>
    </div>
<?php endif; ?>
        </tbody>
    </table>
</div>
</body>
</html>
