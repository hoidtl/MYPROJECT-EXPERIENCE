<?php
$order = $data['order'] ?? [];
$details = $data['details'] ?? [];
?>

<div class="page-header">
    <h2 class="page-title">📋 Chi tiết đơn hàng: <?= htmlspecialchars($order['order_code'] ?? '') ?></h2>
    <a href="<?= APP_URL ?>/Admin/orderList" class="btn btn-secondary">← Quay lại</a>
</div>

<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
    <!-- Order Info -->
    <div class="card">
        <div class="card-header">🛒 Thông tin đơn hàng</div>
        <div class="card-body">
            <table class="table" style="margin:0;">
                <tr>
                    <td style="width:40%; color:#666;">Mã đơn hàng:</td>
                    <td><strong><?= htmlspecialchars($order['order_code'] ?? '') ?></strong></td>
                </tr>
                <tr>
                    <td style="color:#666;">Ngày đặt:</td>
                    <td><?= date('d/m/Y H:i', strtotime($order['created_at'] ?? '')) ?></td>
                </tr>
                <tr>
                    <td style="color:#666;">Trạng thái:</td>
                    <td>
                        <?php
                        $status = $order['transaction_info'] ?? '';
                        if ($status == 'dathanhtoan') {
                            echo '<span class="badge badge-success">Đã thanh toán</span>';
                        } elseif ($status == 'thanhtoanthieu') {
                            echo '<span class="badge badge-warning">Thanh toán thiếu</span>';
                        } else {
                            echo '<span class="badge badge-danger">Chờ thanh toán</span>';
                        }
                        ?>
                    </td>
                </tr>
                <tr>
                    <td style="color:#666;">Phương thức thanh toán:</td>
                    <td>
                        <?php 
                        $pm = $order['payment_method'] ?? '';
                        if ($pm == 'bank_before') echo 'Chuyển khoản trước';
                        elseif ($pm == 'bank_after' || $pm == 'bank') echo 'Chuyển khoản sau';
                        else echo 'Tiền mặt khi nhận hàng';
                        ?>
                    </td>
                </tr>
                <tr>
                    <td style="color:#666;">Hình thức giao hàng:</td>
                    <td>
                        <?= ($order['delivery_method'] ?? '') === 'store' ? 'Lấy tại cửa hàng' : 'Giao hàng tận nơi' ?>
                    </td>
                </tr>
            </table>
        </div>
    </div>
    
    <!-- Customer Info -->
    <div class="card">
        <div class="card-header">👤 Thông tin khách hàng</div>
        <div class="card-body">
            <table class="table" style="margin:0;">
                <tr>
                    <td style="width:40%; color:#666;">Người nhận:</td>
                    <td><strong><?= htmlspecialchars($order['receiver'] ?? '') ?></strong></td>
                </tr>
                <tr>
                    <td style="color:#666;">Số điện thoại:</td>
                    <td><?= htmlspecialchars($order['phone'] ?? '') ?></td>
                </tr>
                <tr>
                    <td style="color:#666;">Địa chỉ:</td>
                    <td>
                        <?= ($order['delivery_method'] ?? '') === 'store' 
                            ? '<span class="badge badge-info">Lấy tại cửa hàng</span>' 
                            : htmlspecialchars($order['address'] ?? '') ?>
                    </td>
                </tr>
                <?php if (!empty($order['note'])): ?>
                <tr>
                    <td style="color:#666;">Ghi chú:</td>
                    <td><?= htmlspecialchars($order['note']) ?></td>
                </tr>
                <?php endif; ?>
            </table>
        </div>
    </div>
</div>

<!-- Order Details -->
<div class="card" style="margin-bottom: 20px;">
    <div class="card-header">🎂 Sản phẩm đã đặt</div>
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th class="text-center">#</th>
                    <th>Sản phẩm</th>
                    <th>Size</th>
                    <th class="text-center">Số lượng</th>
                    <th class="text-right">Đơn giá</th>
                    <th class="text-right">Thành tiền</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($details)): $i = 1; ?>
                    <?php foreach ($details as $item): ?>
                    <?php 
                    // Bỏ qua các dòng addon không hợp lệ
                    $productId = $item['product_id'] ?? '';
                    if (strpos($productId, 'addon_') === 0 && empty($item['product_name'])) continue;
                    
                    $productName = $item['product_name'] ?? $item['tensp'] ?? $productId;
                    $qty = (int)($item['quantity'] ?? $item['qty'] ?? 0);
                    $price = (float)($item['price'] ?? $item['gia'] ?? 0);
                    if ($qty <= 0) continue;
                    ?>
                    <tr>
                        <td class="text-center"><?= $i++ ?></td>
                        <td>
                            <div style="display:flex; align-items:center; gap:10px;">
                                <?php if (!empty($item['product_image'])): ?>
                                <img src="<?= APP_URL ?>/public/images/<?= $item['product_image'] ?>" style="width:40px; height:40px; object-fit:cover; border-radius:6px;">
                                <?php endif; ?>
                                <span><?= htmlspecialchars($productName) ?></span>
                            </div>
                        </td>
                        <td><?= htmlspecialchars($item['size'] ?? '') ?></td>
                        <td class="text-center"><?= $qty ?></td>
                        <td class="text-right"><?= number_format($price, 0, ',', '.') ?>₫</td>
                        <td class="text-right"><?= number_format($price * $qty, 0, ',', '.') ?>₫</td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="6" class="text-center" style="padding:30px; color:#95a5a6;">Không có sản phẩm</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Payment Summary -->
<div class="card" style="max-width: 400px; margin-left: auto;">
    <div class="card-header">💰 Thông tin thanh toán</div>
    <div class="card-body">
        <table class="table" style="margin:0;">
            <tr>
                <td style="color:#666;">Tổng tiền:</td>
                <td class="text-right"><strong><?= number_format($order['total_amount'] ?? 0, 0, ',', '.') ?>₫</strong></td>
            </tr>
            <?php if (!empty($order['discount_amount']) && $order['discount_amount'] > 0): ?>
            <tr>
                <td style="color:#666;">Giảm giá:</td>
                <td class="text-right" style="color:#27ae60;">-<?= number_format($order['discount_amount'], 0, ',', '.') ?>₫</td>
            </tr>
            <?php endif; ?>
            <tr>
                <td style="color:#666;">Đã thanh toán:</td>
                <td class="text-right" style="color:#27ae60;"><?= number_format($order['received_amount'] ?? 0, 0, ',', '.') ?>₫</td>
            </tr>
            <?php 
            $lackAmount = ($order['total_amount'] ?? 0) - ($order['received_amount'] ?? 0);
            if ($lackAmount > 0): 
            ?>
            <tr style="border-top: 2px solid #e9ecef;">
                <td style="color:#e74c3c; font-weight:600;">Còn thiếu:</td>
                <td class="text-right" style="color:#e74c3c; font-weight:600;"><?= number_format($lackAmount, 0, ',', '.') ?>₫</td>
            </tr>
            <?php endif; ?>
        </table>
    </div>
</div>

<div style="margin-top: 20px; display: flex; gap: 10px;">
    <a href="<?= APP_URL ?>/Admin/orderList" class="btn btn-secondary">← Quay lại danh sách</a>
    <a href="<?= APP_URL ?>/Admin/orderUpdateStatus/<?= $order['id'] ?>" class="btn btn-warning">✏️ Cập nhật trạng thái</a>
</div>
