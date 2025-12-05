<?php
$promotions = $data['promotions'] ?? [];
?>

<div class="page-header">
    <h2 class="page-title">🎁 Quản lý khuyến mãi</h2>
    <a href="<?= APP_URL ?>/Admin/promotionCreate" class="btn btn-success">➕ Thêm khuyến mãi</a>
</div>

<div class="card">
    <div class="card-header">Danh sách khuyến mãi (<?= count($promotions) ?>)</div>
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th class="text-center">#</th>
                    <th>Mã</th>
                    <th>Loại</th>
                    <th class="text-right">Giá trị</th>
                    <th class="text-right">Đơn tối thiểu</th>
                    <th>Thời gian</th>
                    <th class="text-center">Trạng thái</th>
                    <th class="text-center">Sử dụng/Tối đa</th>
                    <th class="text-center">Thao tác</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($promotions)): $i = 1; ?>
                    <?php foreach ($promotions as $promo): ?>
                    <tr>
                        <td class="text-center"><?= $i++ ?></td>
                        <td><strong><?= htmlspecialchars($promo['code']) ?></strong></td>
                        <td>
                            <?php if ($promo['type'] == 'percent'): ?>
                                <span class="badge badge-info">Phần trăm</span>
                            <?php else: ?>
                                <span class="badge badge-primary">Cố định</span>
                            <?php endif; ?>
                        </td>
                        <td class="text-right">
                            <?php if ($promo['type'] == 'percent'): ?>
                                <strong><?= htmlspecialchars($promo['value']) ?>%</strong>
                            <?php else: ?>
                                <strong><?= number_format($promo['value'], 0, ',', '.') ?>₫</strong>
                            <?php endif; ?>
                        </td>
                        <td class="text-right"><?= number_format($promo['min_order_amount'] ?? 0, 0, ',', '.') ?>₫</td>
                        <td style="font-size:13px;">
                            <?= $promo['start_date'] ? date('d/m/Y', strtotime($promo['start_date'])) : 'N/A' ?>
                            <br>
                            <?= $promo['end_date'] ? date('d/m/Y', strtotime($promo['end_date'])) : 'N/A' ?>
                        </td>
                        <td class="text-center">
                            <?php if ($promo['status'] == 'active'): ?>
                                <span class="badge badge-success">Hoạt động</span>
                            <?php else: ?>
                                <span class="badge badge-danger">Tắt</span>
                            <?php endif; ?>
                        </td>
                        <td class="text-center">
                            <?= $promo['usage_count'] ?? 0 ?> / <?= $promo['usage_limit'] ?? '∞' ?>
                        </td>
                        <td class="text-center">
                            <div class="action-btns">
                                <a href="<?= APP_URL ?>/Admin/promotionEdit/<?= $promo['id'] ?>" class="btn btn-warning btn-sm">✏️</a>
                                <a href="<?= APP_URL ?>/Admin/promotionDelete/<?= $promo['id'] ?>" 
                                   onclick="return confirm('Xóa khuyến mãi này?')"
                                   class="btn btn-danger btn-sm">🗑️</a>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="9">
                            <div class="empty-state">
                                <div class="icon">🎁</div>
                                <p>Chưa có khuyến mãi nào</p>
                            </div>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
