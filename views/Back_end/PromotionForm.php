<?php
$method = $data['method'] ?? 'create';
$promo = $data['promotion'] ?? [];
$isEdit = $method === 'edit';
?>

<div class="page-header">
    <h2 class="page-title"><?= $isEdit ? '✏️ Sửa khuyến mãi' : '➕ Thêm khuyến mãi mới' ?></h2>
    <a href="<?= APP_URL ?>/Admin/promotionList" class="btn btn-secondary">← Quay lại</a>
</div>

<div class="card" style="max-width: 700px;">
    <div class="card-header">
        <?= $isEdit ? 'Cập nhật thông tin khuyến mãi' : 'Nhập thông tin khuyến mãi' ?>
    </div>
    <div class="card-body">
        <form action="<?= $isEdit ? APP_URL . '/Admin/promotionUpdate/' . $promo['id'] : APP_URL . '/Admin/promotionStore' ?>" method="POST">
            
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Mã khuyến mãi *</label>
                    <input type="text" name="code" class="form-control" required
                           value="<?= htmlspecialchars($promo['code'] ?? '') ?>"
                           placeholder="VD: SALE50, FREESHIP...">
                </div>
                
                <div class="form-group">
                    <label class="form-label">Loại giảm giá *</label>
                    <select name="type" class="form-control" required>
                        <option value="percent" <?= ($promo['type'] ?? '') == 'percent' ? 'selected' : '' ?>>Phần trăm (%)</option>
                        <option value="fixed" <?= ($promo['type'] ?? '') == 'fixed' ? 'selected' : '' ?>>Số tiền cố định (₫)</option>
                    </select>
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Giá trị *</label>
                    <input type="number" name="value" class="form-control" required min="0"
                           value="<?= htmlspecialchars($promo['value'] ?? '') ?>"
                           placeholder="VD: 10 (%) hoặc 50000 (₫)">
                </div>
                
                <div class="form-group">
                    <label class="form-label">Đơn hàng tối thiểu</label>
                    <input type="number" name="min_order_amount" class="form-control" min="0"
                           value="<?= htmlspecialchars($promo['min_order_amount'] ?? 0) ?>"
                           placeholder="0 = không giới hạn">
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Ngày bắt đầu</label>
                    <input type="datetime-local" name="start_date" class="form-control"
                           value="<?= $promo['start_date'] ? date('Y-m-d\TH:i', strtotime($promo['start_date'])) : '' ?>">
                </div>
                
                <div class="form-group">
                    <label class="form-label">Ngày kết thúc</label>
                    <input type="datetime-local" name="end_date" class="form-control"
                           value="<?= $promo['end_date'] ? date('Y-m-d\TH:i', strtotime($promo['end_date'])) : '' ?>">
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Trạng thái</label>
                    <select name="status" class="form-control">
                        <option value="active" <?= ($promo['status'] ?? 'active') == 'active' ? 'selected' : '' ?>>Hoạt động</option>
                        <option value="inactive" <?= ($promo['status'] ?? '') == 'inactive' ? 'selected' : '' ?>>Tắt</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Giới hạn sử dụng</label>
                    <input type="number" name="usage_limit" class="form-control" min="0"
                           value="<?= htmlspecialchars($promo['usage_limit'] ?? '') ?>"
                           placeholder="Để trống = không giới hạn">
                </div>
            </div>
            
            <div style="display:flex; gap:10px; margin-top:25px;">
                <a href="<?= APP_URL ?>/Admin/promotionList" class="btn btn-secondary">Hủy</a>
                <button type="submit" class="btn btn-primary">💾 <?= $isEdit ? 'Cập nhật' : 'Thêm mới' ?></button>
            </div>
        </form>
    </div>
</div>
