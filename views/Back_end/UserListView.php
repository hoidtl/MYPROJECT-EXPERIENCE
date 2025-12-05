<?php
$users = $data['users'] ?? [];
$stats = $data['stats'] ?? [];
?>

<!-- Stats Cards -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon blue">👥</div>
        <div class="stat-info">
            <h3><?= $stats['total'] ?? 0 ?></h3>
            <p>Tổng người dùng</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon red">👑</div>
        <div class="stat-info">
            <h3><?= $stats['admins'] ?? 0 ?></h3>
            <p>Quản trị viên</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon green">👤</div>
        <div class="stat-info">
            <h3><?= $stats['users'] ?? 0 ?></h3>
            <p>Người dùng thường</p>
        </div>
    </div>
</div>

<div class="page-header">
    <h2 class="page-title">👥 Quản lý người dùng</h2>
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

<!-- Users Table -->
<div class="card">
    <div class="card-header">Danh sách người dùng (<?= count($users) ?>)</div>
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th class="text-center">#</th>
                    <th>ID</th>
                    <th>Họ tên</th>
                    <th>Email</th>
                    <th class="text-center">Vai trò</th>
                    <th class="text-center">Thao tác</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($users)): $i = 1; ?>
                    <?php foreach ($users as $user): ?>
                    <?php $isCurrentUser = ($user['user_id'] == ($_SESSION['user']['user_id'] ?? 0)); ?>
                    <tr>
                        <td class="text-center"><?= $i++ ?></td>
                        <td><?= $user['user_id'] ?></td>
                        <td>
                            <strong><?= htmlspecialchars($user['fullname'] ?? '') ?></strong>
                            <?php if ($isCurrentUser): ?>
                                <span class="badge badge-info" style="margin-left:5px;">Bạn</span>
                            <?php endif; ?>
                        </td>
                        <td><?= htmlspecialchars($user['email'] ?? '') ?></td>
                        <td class="text-center">
                            <?php if (($user['role'] ?? '') === 'admin'): ?>
                                <span class="badge badge-danger">👑 Admin</span>
                            <?php else: ?>
                                <span class="badge badge-success">👤 User</span>
                            <?php endif; ?>
                        </td>
                        <td class="text-center">
                            <div class="action-btns">
                                <?php if (!$isCurrentUser): ?>
                                    <?php if (($user['role'] ?? '') === 'admin'): ?>
                                        <a href="<?= APP_URL ?>/Admin/userRemoveAdmin/<?= $user['user_id'] ?>" 
                                           class="btn btn-warning btn-sm"
                                           onclick="return confirm('Thu hồi quyền Admin của người dùng này?')"
                                           title="Thu hồi quyền Admin">
                                            ⬇️ Hạ quyền
                                        </a>
                                    <?php else: ?>
                                        <a href="<?= APP_URL ?>/Admin/userSetAdmin/<?= $user['user_id'] ?>" 
                                           class="btn btn-primary btn-sm"
                                           onclick="return confirm('Cấp quyền Admin cho người dùng này?')"
                                           title="Cấp quyền Admin">
                                            👑 Cấp Admin
                                        </a>
                                    <?php endif; ?>
                                    
                                    <a href="<?= APP_URL ?>/Admin/userDelete/<?= $user['user_id'] ?>" 
                                       class="btn btn-danger btn-sm"
                                       onclick="return confirm('Xóa người dùng này? Hành động này không thể hoàn tác!')"
                                       title="Xóa người dùng">
                                        🗑️
                                    </a>
                                <?php else: ?>
                                    <span style="color:#999; font-size:12px;">Không thể thao tác</span>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6">
                            <div class="empty-state">
                                <div class="icon">👥</div>
                                <p>Chưa có người dùng nào</p>
                            </div>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
