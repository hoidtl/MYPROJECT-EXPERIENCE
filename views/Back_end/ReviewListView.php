<?php
$reviews = $data['reviews'] ?? [];
$stats = $data['stats'] ?? [];
?>

<!-- Stats Cards -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon blue">📝</div>
        <div class="stat-info">
            <h3><?= $stats['total'] ?? 0 ?></h3>
            <p>Tổng đánh giá</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon orange">⏳</div>
        <div class="stat-info">
            <h3><?= $stats['pending'] ?? 0 ?></h3>
            <p>Chờ duyệt</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon green">✅</div>
        <div class="stat-info">
            <h3><?= $stats['approved'] ?? 0 ?></h3>
            <p>Đã duyệt</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon red">❌</div>
        <div class="stat-info">
            <h3><?= $stats['rejected'] ?? 0 ?></h3>
            <p>Từ chối</p>
        </div>
    </div>
</div>

<div class="page-header">
    <h2 class="page-title">⭐ Quản lý đánh giá</h2>
</div>

<?php if(isset($_SESSION['success'])): ?>
    <div class="alert alert-success">
        <?= $_SESSION['success']; unset($_SESSION['success']); ?>
        <button class="btn-close" onclick="this.parentElement.remove()">×</button>
    </div>
<?php endif; ?>

<!-- Filter -->
<div class="card" style="margin-bottom: 20px;">
    <div class="card-body">
        <form method="GET" action="<?= APP_URL ?>/Admin/reviewList" class="form-row">
            <div class="form-group" style="margin-bottom:0;">
                <label class="form-label">Trạng thái</label>
                <select name="status" class="form-control">
                    <option value="">Tất cả</option>
                    <option value="pending" <?= ($_GET['status'] ?? '') == 'pending' ? 'selected' : '' ?>>Chờ duyệt</option>
                    <option value="approved" <?= ($_GET['status'] ?? '') == 'approved' ? 'selected' : '' ?>>Đã duyệt</option>
                    <option value="rejected" <?= ($_GET['status'] ?? '') == 'rejected' ? 'selected' : '' ?>>Từ chối</option>
                </select>
            </div>
            <div class="form-group" style="margin-bottom:0;">
                <label class="form-label">Số sao</label>
                <select name="rating" class="form-control">
                    <option value="">Tất cả</option>
                    <option value="5" <?= ($_GET['rating'] ?? '') == '5' ? 'selected' : '' ?>>5 sao</option>
                    <option value="4" <?= ($_GET['rating'] ?? '') == '4' ? 'selected' : '' ?>>4 sao</option>
                    <option value="3" <?= ($_GET['rating'] ?? '') == '3' ? 'selected' : '' ?>>3 sao</option>
                    <option value="2" <?= ($_GET['rating'] ?? '') == '2' ? 'selected' : '' ?>>2 sao</option>
                    <option value="1" <?= ($_GET['rating'] ?? '') == '1' ? 'selected' : '' ?>>1 sao</option>
                </select>
            </div>
            <div class="form-group" style="margin-bottom:0;">
                <label class="form-label">&nbsp;</label>
                <button type="submit" class="btn btn-primary">🔍 Lọc</button>
            </div>
        </form>
    </div>
</div>

<!-- Reviews Table -->
<div class="card">
    <div class="card-header">Danh sách đánh giá (<?= count($reviews) ?>)</div>
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th class="text-center">#</th>
                    <th>Sản phẩm</th>
                    <th>Khách hàng</th>
                    <th class="text-center">Đánh giá</th>
                    <th>Nội dung</th>
                    <th>Ảnh</th>
                    <th class="text-center">Trạng thái</th>
                    <th>Ngày gửi</th>
                    <th class="text-center">Thao tác</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($reviews)): $i = 1; ?>
                    <?php foreach ($reviews as $review): ?>
                    <tr>
                        <td class="text-center"><?= $i++ ?></td>
                        <td>
                            <div style="display:flex; align-items:center; gap:10px;">
                                <?php if ($review['product_image']): ?>
                                <img src="<?= APP_URL ?>/public/images/<?= $review['product_image'] ?>" style="width:40px; height:40px; object-fit:cover; border-radius:6px;">
                                <?php endif; ?>
                                <span><?= htmlspecialchars($review['tensp'] ?? $review['product_id']) ?></span>
                            </div>
                        </td>
                        <td>
                            <strong><?= htmlspecialchars($review['user_name']) ?></strong><br>
                            <small style="color:#666;"><?= htmlspecialchars($review['user_email']) ?></small>
                        </td>
                        <td class="text-center" style="color:#f39c12;">
                            <?php for ($s = 1; $s <= 5; $s++): ?>
                                <?= $s <= $review['rating'] ? '★' : '☆' ?>
                            <?php endfor; ?>
                        </td>
                        <td style="max-width:200px;">
                            <?= htmlspecialchars(mb_substr($review['comment'] ?? '', 0, 100)) ?>
                            <?= strlen($review['comment'] ?? '') > 100 ? '...' : '' ?>
                        </td>
                        <td>
                            <?php if ($review['image']): ?>
                            <img src="<?= APP_URL ?>/public/images/reviews/<?= $review['image'] ?>" style="width:50px; height:50px; object-fit:cover; border-radius:6px; cursor:pointer;" onclick="window.open(this.src)">
                            <?php else: ?>
                            <span style="color:#999;">-</span>
                            <?php endif; ?>
                        </td>
                        <td class="text-center">
                            <?php
                            $status = $review['status'];
                            if ($status == 'approved') {
                                echo '<span class="badge badge-success">Đã duyệt</span>';
                            } elseif ($status == 'rejected') {
                                echo '<span class="badge badge-danger">Từ chối</span>';
                            } else {
                                echo '<span class="badge badge-warning">Chờ duyệt</span>';
                            }
                            ?>
                        </td>
                        <td><?= date('d/m/Y H:i', strtotime($review['created_at'])) ?></td>
                        <td class="text-center">
                            <div class="action-btns" style="flex-direction:column; gap:5px;">
                                <?php if ($review['status'] != 'approved'): ?>
                                <a href="<?= APP_URL ?>/Admin/reviewApprove/<?= $review['id'] ?>" class="btn btn-success btn-sm" title="Duyệt">✅</a>
                                <?php endif; ?>
                                <?php if ($review['status'] != 'rejected'): ?>
                                <a href="<?= APP_URL ?>/Admin/reviewReject/<?= $review['id'] ?>" class="btn btn-warning btn-sm" title="Từ chối">🚫</a>
                                <?php endif; ?>
                                <a href="<?= APP_URL ?>/Admin/reviewDelete/<?= $review['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Xóa đánh giá này?')" title="Xóa">🗑️</a>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="9">
                            <div class="empty-state">
                                <div class="icon">⭐</div>
                                <p>Chưa có đánh giá nào</p>
                            </div>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
