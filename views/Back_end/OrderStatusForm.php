<?php
$order = $data['order'] ?? [];
?>

<div class="page-header">
    <h2 class="page-title">✏️ Cập nhật trạng thái đơn hàng</h2>
    <a href="<?= APP_URL ?>/Admin/orderList" class="btn btn-secondary">← Quay lại</a>
</div>

<div class="card" style="max-width: 600px;">
    <div class="card-header">
        Đơn hàng: <?= htmlspecialchars($order['order_code'] ?? '') ?>
    </div>
    <div class="card-body">
        <form action="<?= APP_URL ?>/Admin/orderUpdateStatus/<?= $order['id'] ?>" method="POST">
            <div class="form-group">
                <label class="form-label">Mã đơn hàng</label>
                <input type="text" class="form-control" value="<?= htmlspecialchars($order['order_code'] ?? '') ?>" readonly>
            </div>
            
            <div class="form-group">
                <label class="form-label">Khách hàng</label>
                <input type="text" class="form-control" value="<?= htmlspecialchars($order['receiver'] ?? '') ?>" readonly>
            </div>
            
            <div class="form-group">
                <label class="form-label">Tổng tiền</label>
                <input type="text" class="form-control" value="<?= number_format($order['total_amount'] ?? 0, 0, ',', '.') ?>₫" readonly>
            </div>
            
            <div class="form-group">
                <label class="form-label">Trạng thái hiện tại</label>
                <input type="text" class="form-control" value="<?php
                    $status = $order['transaction_info'] ?? '';
                    if ($status == 'dathanhtoan') echo 'Đã thanh toán';
                    elseif ($status == 'thanhtoanthieu') echo 'Thanh toán thiếu';
                    else echo 'Chờ thanh toán';
                ?>" readonly>
            </div>
            
            <div class="form-group">
                <label class="form-label">Trạng thái mới</label>
                <select name="status" class="form-control" required>
                    <option value="chothanhtoan" <?= ($order['transaction_info'] ?? '') == 'chothanhtoan' ? 'selected' : '' ?>>Chờ thanh toán</option>
                    <option value="thanhtoanthieu" <?= ($order['transaction_info'] ?? '') == 'thanhtoanthieu' ? 'selected' : '' ?>>Thanh toán thiếu</option>
                    <option value="dathanhtoan" <?= ($order['transaction_info'] ?? '') == 'dathanhtoan' ? 'selected' : '' ?>>Đã thanh toán</option>
                    <option value="dangxuly">Đang xử lý</option>
                    <option value="danggiao">Đang giao hàng</option>
                    <option value="hoanthanh">Hoàn thành</option>
                    <option value="dahuy">Đã hủy</option>
                </select>
            </div>
            
            <div style="display:flex; gap:10px; margin-top:25px;">
                <a href="<?= APP_URL ?>/Admin/orderList" class="btn btn-secondary">Hủy</a>
                <button type="submit" class="btn btn-primary">💾 Cập nhật</button>
            </div>
        </form>
    </div>
</div>
