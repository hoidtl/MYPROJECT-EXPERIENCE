<?php
$products = $data['productList'] ?? [];
?>

<div class="page-header">
    <h2 class="page-title">🎂 Quản lý sản phẩm</h2>
    <a href="<?= APP_URL ?>/Product/create" class="btn btn-success">➕ Thêm sản phẩm</a>
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

<div class="alert alert-info">
    📊 Tổng số sản phẩm: <strong><?= count($products) ?></strong>
</div>

<div class="card">
    <div class="card-header">Danh sách sản phẩm</div>
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th class="text-center">#</th>
                    <th>Ảnh</th>
                    <th>Mã SP</th>
                    <th>Tên SP</th>
                    <th>Loại</th>
                    <th>Kích thước</th>
                    <th>Giá nhập</th>
                    <th>Giá xuất</th>
                    <th>Mô tả</th>
                    <th>Ngày tạo</th>
                    <th class="text-center">Thao tác</th>
                </tr>
            </thead>
            <tbody>
                <?php if(!empty($products)): $i = 1; ?>
                    <?php foreach($products as $v): ?>
                    <tr>
                        <td class="text-center"><?= $i++ ?></td>
                        <td>
                            <img src="<?= APP_URL ?>/public/images/<?= $v['hinhanh'] ?: 'default.png' ?>" class="product-img" alt="">
                        </td>
                        <td><strong><?= htmlspecialchars($v['masp']) ?></strong></td>
                        <td><?= htmlspecialchars($v['tensp']) ?></td>
                        <td><?= htmlspecialchars($v['maLoaiSP']) ?></td>
                        <td style="font-size:13px; white-space:pre-line;">
                            <?= !empty($v['sizes']) 
                                ? nl2br(htmlspecialchars(str_replace(",", "\n", $v["sizes"]))) 
                                : '<span style="color:#95a5a6;">Không có</span>' ?>
                        </td>
                        <td style="font-size:13px; white-space:pre-line;">
                            <?= !empty($v['giaNhapList']) 
                                ? nl2br(htmlspecialchars(str_replace(" | ", "\n", $v["giaNhapList"]))) 
                                : '<span style="color:#95a5a6;">Không có</span>' ?>
                        </td>
                        <td style="font-size:13px; white-space:pre-line;">
                            <?= !empty($v['giaXuatList']) 
                                ? nl2br(htmlspecialchars(str_replace(" | ", "\n", $v["giaXuatList"]))) 
                                : '<span style="color:#95a5a6;">Không có</span>' ?>
                        </td>
                        <td style="max-width:150px;">
                            <?php 
                                $m = $v['mota'] ?? '';
                                echo strlen($m) > 40 ? htmlspecialchars(substr($m,0,40))."…" : htmlspecialchars($m);
                            ?>
                        </td>
                        <td><?= htmlspecialchars($v['createDate'] ?? '') ?></td>
                        <td class="text-center">
                            <div class="action-btns">
                                <a href="<?= APP_URL ?>/Product/edit/<?= $v['masp'] ?>" class="btn btn-warning btn-sm">✏️</a>
                                <a href="<?= APP_URL ?>/Product/delete/<?= $v['masp'] ?>" 
                                   onclick="return confirm('Xóa sản phẩm này?')" 
                                   class="btn btn-danger btn-sm">🗑️</a>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="11">
                            <div class="empty-state">
                                <div class="icon">📦</div>
                                <p>Không có sản phẩm nào</p>
                            </div>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
