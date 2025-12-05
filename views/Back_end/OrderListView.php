<?php
$orders = $data['orders'] ?? [];
$stats = $data['stats'] ?? [];
?>

<!-- Stats Cards -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon blue">📦</div>
        <div class="stat-info">
            <h3><?= $stats['total'] ?? count($orders) ?></h3>
            <p>Tổng đơn hàng</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon orange">⏳</div>
        <div class="stat-info">
            <h3><?= $stats['pending'] ?? 0 ?></h3>
            <p>Chờ xử lý</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon green">✅</div>
        <div class="stat-info">
            <h3><?= $stats['completed'] ?? 0 ?></h3>
            <p>Đã hoàn thành</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon red">💰</div>
        <div class="stat-info">
            <h3><?= number_format($stats['revenue'] ?? 0, 0, ',', '.') ?>₫</h3>
            <p>Doanh thu</p>
        </div>
    </div>
</div>

<!-- Page Header -->
<div class="page-header">
    <h2 class="page-title">📦 Quản lý đơn hàng</h2>
</div>

<?php if(isset($_SESSION['success'])): ?>
    <div class="alert alert-success">
        <?= $_SESSION['success']; unset($_SESSION['success']); ?>
        <button class="btn-close" onclick="this.parentElement.remove()">×</button>
    </div>
<?php endif; ?>

<?php if(isset($_SESSION['error'])): ?>
    <div class="alert alert-danger">
        <?= $_SESSION['error']; unset($_SESSION['error']); ?>
        <button class="btn-close" onclick="this.parentElement.remove()">×</button>
    </div>
<?php endif; ?>

<!-- Filter -->
<div class="card" style="margin-bottom: 20px;">
    <div class="card-body">
        <form method="GET" action="<?= APP_URL ?>/Admin/orderList" class="form-row">
            <div class="form-group" style="margin-bottom:0;">
                <label class="form-label">Trạng thái</label>
                <select name="status" class="form-control">
                    <option value="">Tất cả</option>
                    <option value="chothanhtoan" <?= ($_GET['status'] ?? '') == 'chothanhtoan' ? 'selected' : '' ?>>Chờ thanh toán</option>
                    <option value="dathanhtoan" <?= ($_GET['status'] ?? '') == 'dathanhtoan' ? 'selected' : '' ?>>Đã thanh toán</option>
                    <option value="thanhtoanthieu" <?= ($_GET['status'] ?? '') == 'thanhtoanthieu' ? 'selected' : '' ?>>Thanh toán thiếu</option>
                </select>
            </div>
            <div class="form-group" style="margin-bottom:0;">
                <label class="form-label">Từ ngày</label>
                <input type="date" name="from_date" class="form-control" value="<?= $_GET['from_date'] ?? '' ?>">
            </div>
            <div class="form-group" style="margin-bottom:0;">
                <label class="form-label">Đến ngày</label>
                <input type="date" name="to_date" class="form-control" value="<?= $_GET['to_date'] ?? '' ?>">
            </div>
            <div class="form-group" style="margin-bottom:0;">
                <label class="form-label">&nbsp;</label>
                <button type="submit" class="btn btn-primary">🔍 Lọc</button>
            </div>
        </form>
    </div>
</div>

<!-- Orders Table -->
<div class="card">
    <div class="card-header">
        Danh sách đơn hàng (<?= count($orders) ?> đơn)
    </div>
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th class="text-center">#</th>
                    <th>Mã đơn</th>
                    <th>Khách hàng</th>
                    <th>SĐT</th>
                    <th>Địa chỉ</th>
                    <th class="text-right">Tổng tiền</th>
                    <th class="text-right">Đã thanh toán</th>
                    <th class="text-center">Trạng thái</th>
                    <th>Ngày đặt</th>
                    <th class="text-center">Thao tác</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($orders)): $i = 1; ?>
                    <?php foreach ($orders as $order): ?>
                    <tr>
                        <td class="text-center"><?= $i++ ?></td>
                        <td><strong><?= htmlspecialchars($order['order_code']) ?></strong></td>
                        <td><?= htmlspecialchars($order['receiver']) ?></td>
                        <td><?= htmlspecialchars($order['phone']) ?></td>
                        <td>
                            <?= ($order['delivery_method'] ?? '') === 'store' 
                                ? '<span class="badge badge-info">Lấy tại cửa hàng</span>' 
                                : htmlspecialchars(mb_substr($order['address'] ?? '', 0, 30)) . (strlen($order['address'] ?? '') > 30 ? '...' : '') ?>
                        </td>
                        <td class="text-right"><?= number_format($order['total_amount'] ?? 0, 0, ',', '.') ?>₫</td>
                        <td class="text-right">
                            <?php if (($order['received_amount'] ?? 0) > 0): ?>
                                <span style="color:#27ae60;"><?= number_format($order['received_amount'], 0, ',', '.') ?>₫</span>
                            <?php else: ?>
                                <span style="color:#95a5a6;">0₫</span>
                            <?php endif; ?>
                        </td>
                        <td class="text-center">
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
                        <td><?= date('d/m/Y H:i', strtotime($order['created_at'])) ?></td>
                        <td class="text-center">
                            <div class="action-btns">
                                <a href="<?= APP_URL ?>/Admin/orderDetail/<?= $order['id'] ?>" class="btn btn-primary btn-sm">👁️</a>
                                <a href="<?= APP_URL ?>/Admin/orderUpdateStatus/<?= $order['id'] ?>" class="btn btn-warning btn-sm">✏️</a>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="10">
                            <div class="empty-state">
                                <div class="icon">📭</div>
                                <p>Chưa có đơn hàng nào</p>
                            </div>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
