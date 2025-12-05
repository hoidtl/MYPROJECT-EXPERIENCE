<?php
$products = $data['productList'] ?? [];
$isEdit = isset($data['editItem']);
$edit = $isEdit ? $data['editItem'] : null;
?>

<div class="page-header">
    <h2 class="page-title">📁 Quản lý loại sản phẩm</h2>
</div>

<!-- Form thêm/sửa -->
<div class="card" style="margin-bottom: 25px;">
    <div class="card-header">
        <?= $isEdit ? '✏️ Cập nhật loại sản phẩm' : '➕ Thêm loại sản phẩm mới' ?>
    </div>
    <div class="card-body">
        <form action="<?= $isEdit ? APP_URL . '/ProductType/update/' . $edit['maLoaiSP'] : APP_URL . '/ProductType/create' ?>" method="post">
            <div class="form-row">
                <div class="form-group" style="margin-bottom:0;">
                    <label class="form-label">Mã loại SP</label>
                    <input type="text" name="txt_maloaisp" class="form-control" required
                           value="<?= $isEdit ? htmlspecialchars($edit['maLoaiSP']) : '' ?>"
                           <?= $isEdit ? 'readonly' : '' ?>>
                </div>
                
                <div class="form-group" style="margin-bottom:0;">
                    <label class="form-label">Tên loại SP</label>
                    <input type="text" name="txt_tenloaisp" class="form-control"
                           value="<?= $isEdit ? htmlspecialchars($edit['tenLoaiSP']) : '' ?>">
                </div>
                
                <div class="form-group" style="margin-bottom:0;">
                    <label class="form-label">Mô tả</label>
                    <input type="text" name="txt_motaloaisp" class="form-control"
                           value="<?= $isEdit ? htmlspecialchars($edit['moTaLoaiSP']) : '' ?>">
                </div>
                
                <div class="form-group" style="margin-bottom:0;">
                    <label class="form-label">&nbsp;</label>
                    <div style="display:flex; gap:10px;">
                        <button type="submit" class="btn <?= $isEdit ? 'btn-warning' : 'btn-primary' ?>">
                            💾 <?= $isEdit ? 'Cập nhật' : 'Thêm mới' ?>
                        </button>
                        <?php if ($isEdit): ?>
                            <a href="<?= APP_URL ?>/ProductType" class="btn btn-secondary">🔁 Hủy</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Danh sách -->
<div class="card">
    <div class="card-header">Danh sách loại sản phẩm (<?= count($products) ?>)</div>
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th class="text-center">STT</th>
                    <th>Mã loại SP</th>
                    <th>Tên loại SP</th>
                    <th>Mô tả</th>
                    <th class="text-center">Thao tác</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($products)): $i = 1; ?>
                    <?php foreach ($products as $v): ?>
                    <tr>
                        <td class="text-center"><?= $i++ ?></td>
                        <td><strong><?= htmlspecialchars($v['maLoaiSP']) ?></strong></td>
                        <td><?= htmlspecialchars($v['tenLoaiSP']) ?></td>
                        <td><?= htmlspecialchars($v['moTaLoaiSP']) ?></td>
                        <td class="text-center">
                            <div class="action-btns">
                                <a href="<?= APP_URL ?>/ProductType/edit/<?= $v['maLoaiSP'] ?>" class="btn btn-warning btn-sm">✏️ Sửa</a>
                                <a href="<?= APP_URL ?>/ProductType/delete/<?= $v['maLoaiSP'] ?>" 
                                   onclick="return confirm('Xóa loại sản phẩm này?')"
                                   class="btn btn-danger btn-sm">🗑️ Xóa</a>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5">
                            <div class="empty-state">
                                <div class="icon">📁</div>
                                <p>Chưa có loại sản phẩm nào</p>
                            </div>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
