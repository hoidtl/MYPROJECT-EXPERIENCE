<?php
$products = $data['products'] ?? [];
$stats = $data['stats'] ?? [];
$categories = $data['categories'] ?? [];
?>

<!-- Stats Cards -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon blue">🎂</div>
        <div class="stat-info">
            <h3><?= $stats['total_products'] ?? 0 ?></h3>
            <p>Tổng sản phẩm</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon green">✅</div>
        <div class="stat-info">
            <h3><?= $stats['in_stock'] ?? 0 ?></h3>
            <p>Còn hàng</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon orange">⚠️</div>
        <div class="stat-info">
            <h3><?= $stats['low_stock'] ?? 0 ?></h3>
            <p>Sắp hết hàng (≤10)</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon red">❌</div>
        <div class="stat-info">
            <h3><?= $stats['out_of_stock'] ?? 0 ?></h3>
            <p>Hết hàng</p>
        </div>
    </div>
</div>

<!-- Page Header -->
<div class="page-header">
    <h2 class="page-title">📊 Quản lý kho hàng</h2>
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
        <form method="GET" action="<?= APP_URL ?>/Admin/inventory" class="form-row">
            <div class="form-group" style="margin-bottom:0;">
                <label class="form-label">Trạng thái kho</label>
                <select name="stock_status" class="form-control">
                    <option value="">Tất cả</option>
                    <option value="in_stock" <?= ($_GET['stock_status'] ?? '') == 'in_stock' ? 'selected' : '' ?>>Còn hàng (>10)</option>
                    <option value="low_stock" <?= ($_GET['stock_status'] ?? '') == 'low_stock' ? 'selected' : '' ?>>Sắp hết (≤10)</option>
                    <option value="out_of_stock" <?= ($_GET['stock_status'] ?? '') == 'out_of_stock' ? 'selected' : '' ?>>Hết hàng (0)</option>
                </select>
            </div>
            <div class="form-group" style="margin-bottom:0;">
                <label class="form-label">Loại sản phẩm</label>
                <select name="category" class="form-control">
                    <option value="">Tất cả</option>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?= htmlspecialchars($cat['maLoaiSP']) ?>" <?= ($_GET['category'] ?? '') == $cat['maLoaiSP'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($cat['tenLoaiSP'] ?? $cat['maLoaiSP']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group" style="margin-bottom:0;">
                <label class="form-label">Tìm kiếm</label>
                <input type="text" name="search" class="form-control" placeholder="Tên sản phẩm..." value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
            </div>
            <div class="form-group" style="margin-bottom:0;">
                <label class="form-label">&nbsp;</label>
                <button type="submit" class="btn btn-primary">🔍 Lọc</button>
            </div>
        </form>
    </div>
</div>

<!-- Inventory Table -->
<div class="card">
    <div class="card-header">
        Danh sách tồn kho (<?= count($products) ?> sản phẩm)
    </div>
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th class="text-center">#</th>
                    <th>Ảnh</th>
                    <th>Mã SP</th>
                    <th>Tên sản phẩm</th>
                    <th>Loại</th>
                    <th class="text-center">Tồn kho</th>
                    <th class="text-center">Trạng thái</th>
                    <th class="text-center">Thao tác</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($products)): $i = 1; ?>
                    <?php foreach ($products as $product): ?>
                    <?php $stock = (int)($product['soluong'] ?? 0); ?>
                    <tr>
                        <td class="text-center"><?= $i++ ?></td>
                        <td>
                            <img src="<?= APP_URL ?>/public/images/<?= $product['hinhanh'] ?: 'default.png' ?>" class="product-img" alt="">
                        </td>
                        <td><strong><?= htmlspecialchars($product['masp']) ?></strong></td>
                        <td><?= htmlspecialchars($product['tensp']) ?></td>
                        <td><?= htmlspecialchars($product['maLoaiSP']) ?></td>
                        <td class="text-center">
                            <?php 
                            if ($stock <= 0) {
                                echo '<span class="stock-out">0</span>';
                            } elseif ($stock <= 10) {
                                echo '<span class="stock-low">' . $stock . '</span>';
                            } else {
                                echo '<span class="stock-ok">' . $stock . '</span>';
                            }
                            ?>
                        </td>
                        <td class="text-center">
                            <?php 
                            if ($stock <= 0) {
                                echo '<span class="badge badge-danger">Hết hàng</span>';
                            } elseif ($stock <= 10) {
                                echo '<span class="badge badge-warning">Sắp hết</span>';
                            } else {
                                echo '<span class="badge badge-success">Còn hàng</span>';
                            }
                            ?>
                        </td>
                        <td class="text-center">
                            <button type="button" class="btn btn-primary btn-sm" 
                                    onclick="openStockModal('<?= htmlspecialchars($product['masp']) ?>', '<?= htmlspecialchars($product['tensp']) ?>', <?= $stock ?>)">
                                📦 Cập nhật
                            </button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="8">
                            <div class="empty-state">
                                <div class="icon">📦</div>
                                <p>Chưa có sản phẩm nào trong kho</p>
                            </div>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Stock Update Modal -->
<div id="stockModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:1000; align-items:center; justify-content:center;">
    <div style="background:#fff; padding:30px; border-radius:12px; width:450px; max-width:90%;">
        <h3 style="margin-bottom:20px; color:#2c3e50;">📦 Cập nhật tồn kho</h3>
        <form action="<?= APP_URL ?>/Admin/updateStock" method="POST">
            <input type="hidden" name="masp" id="modal_masp">
            
            <div class="form-group">
                <label class="form-label">Sản phẩm</label>
                <input type="text" id="modal_tensp" class="form-control" readonly>
            </div>
            
            <div class="form-group">
                <label class="form-label">Số lượng hiện tại</label>
                <input type="number" id="modal_current" class="form-control" readonly>
            </div>
            
            <div class="form-group">
                <label class="form-label">Thao tác</label>
                <select name="action" class="form-control" required>
                    <option value="set">Đặt số lượng mới</option>
                    <option value="add">Nhập thêm hàng (+)</option>
                    <option value="subtract">Xuất hàng (-)</option>
                </select>
            </div>
            
            <div class="form-group">
                <label class="form-label">Số lượng</label>
                <input type="number" name="quantity" class="form-control" min="0" required placeholder="Nhập số lượng...">
            </div>
            
            <div style="display:flex; gap:10px; margin-top:25px;">
                <button type="button" class="btn btn-secondary" onclick="closeStockModal()">Hủy</button>
                <button type="submit" class="btn btn-primary">💾 Lưu thay đổi</button>
            </div>
        </form>
    </div>
</div>

<script>
function openStockModal(masp, tensp, currentStock) {
    document.getElementById('modal_masp').value = masp;
    document.getElementById('modal_tensp').value = tensp;
    document.getElementById('modal_current').value = currentStock;
    document.getElementById('stockModal').style.display = 'flex';
}

function closeStockModal() {
    document.getElementById('stockModal').style.display = 'none';
}

// Close modal when clicking outside
document.getElementById('stockModal').addEventListener('click', function(e) {
    if (e.target === this) closeStockModal();
});
</script>
