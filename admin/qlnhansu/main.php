<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Quản lý nhân sự – GymBro Admin</title>
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&display=swap" rel="stylesheet">
    <link href="../inc/css/app.css" rel="stylesheet">
    <script src="../inc/js/app.js"></script>
</head>

<body>
<div class="wrapper">
    <?php include('../inc/sidebar.php'); ?>

    <div class="main">
        <?php include('../inc/header.php'); ?>

        <main class="content">
            <div class="container-fluid p-0">

                <!-- Tiêu đề trang -->
                <div class="mb-3 d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <div>
                        <h1 class="h3 mb-0">Quản lý nhân sự</h1>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb mb-0 small">
                                <li class="breadcrumb-item"><a href="../ktnguoidung/index.php">Trang chủ</a></li>
                                <li class="breadcrumb-item active">Nhân sự</li>
                            </ol>
                        </nav>
                    </div>
                    <a href="index.php?action=them" class="btn btn-primary">
                        <i class="align-middle" data-feather="user-plus"></i>
                        Thêm nhân sự
                    </a>
                </div>

                <!-- Thống kê nhanh -->
                <?php
                $tongSo    = count($nguoidung);
                $soAdmin   = count(array_filter($nguoidung, fn($u) => $u['loai'] == 1));
                $soNhanVien = count(array_filter($nguoidung, fn($u) => $u['loai'] == 2));
                $soKhach   = count(array_filter($nguoidung, fn($u) => $u['loai'] == 3));
                ?>
                <div class="row mb-3 g-3">
                    <div class="col-6 col-md-3">
                        <div class="card text-center py-3 border-0 shadow-sm">
                            <div class="fs-2 fw-bold text-primary"><?= $tongSo ?></div>
                            <div class="small text-muted">Tổng tài khoản</div>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="card text-center py-3 border-0 shadow-sm">
                            <div class="fs-2 fw-bold text-danger"><?= $soAdmin ?></div>
                            <div class="small text-muted">Admin</div>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="card text-center py-3 border-0 shadow-sm">
                            <div class="fs-2 fw-bold text-warning"><?= $soNhanVien ?></div>
                            <div class="small text-muted">Nhân viên</div>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="card text-center py-3 border-0 shadow-sm">
                            <div class="fs-2 fw-bold text-success"><?= $soKhach ?></div>
                            <div class="small text-muted">Khách hàng</div>
                        </div>
                    </div>
                </div>

                <!-- Bảng danh sách -->
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white d-flex align-items-center justify-content-between flex-wrap gap-2">
                        <h5 class="card-title mb-0">Danh sách tài khoản</h5>
                        <!-- Lọc nhanh -->
                        <div class="d-flex gap-2 flex-wrap">
                            <button class="btn btn-sm btn-outline-secondary active" onclick="filterTable('all', this)">Tất cả</button>
                            <button class="btn btn-sm btn-outline-danger"          onclick="filterTable('1', this)">Admin</button>
                            <button class="btn btn-sm btn-outline-warning"         onclick="filterTable('2', this)">Nhân viên</button>
                            <button class="btn btn-sm btn-outline-success"         onclick="filterTable('3', this)">Khách hàng</button>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover table-striped mb-0 align-middle" id="tableNhanSu">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width:50px">#</th>
                                        <th>Họ tên</th>
                                        <th>Email</th>
                                        <th>Số điện thoại</th>
                                        <th style="width:130px">Vai trò</th>
                                        <th style="width:110px">Trạng thái</th>
                                        <th style="width:160px" class="text-center">Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php if (empty($nguoidung)): ?>
                                    <tr>
                                        <td colspan="7" class="text-center py-4 text-muted">Chưa có tài khoản nào.</td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($nguoidung as $i => $u): ?>
                                    <?php
                                        // Badge vai trò
                                        $loaiBadge = match((int)$u['loai']) {
                                            1 => ['danger',  'Admin'],
                                            2 => ['warning', 'Nhân viên'],
                                            3 => ['success', 'Khách hàng'],
                                            default => ['secondary', 'Không rõ'],
                                        };
                                        $isMe = ($u['id'] == $_SESSION['nguoidung']['id']);
                                    ?>
                                    <tr data-loai="<?= $u['loai'] ?>">
                                        <td class="text-muted small"><?= $i + 1 ?></td>
                                        <td>
                                            <div class="fw-semibold"><?= htmlspecialchars($u['hoten']) ?></div>
                                            <?php if ($isMe): ?>
                                                <span class="badge bg-secondary" style="font-size:.65rem">Bạn</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="small"><?= htmlspecialchars($u['email']) ?></td>
                                        <td class="small"><?= htmlspecialchars($u['sodienthoai']) ?></td>

                                        <!-- Đổi vai trò -->
                                        <td>
                                            <?php if ($isMe): ?>
                                                <span class="badge bg-<?= $loaiBadge[0] ?>"><?= $loaiBadge[1] ?></span>
                                            <?php else: ?>
                                                <form method="POST" action="index.php?action=doiloai" class="d-inline">
                                                    <input type="hidden" name="email" value="<?= htmlspecialchars($u['email']) ?>">
                                                    <select name="loai" class="form-select form-select-sm"
                                                            onchange="this.form.submit()" style="min-width:110px">
                                                        <option value="1" <?= $u['loai']==1 ? 'selected':'' ?>>Admin</option>
                                                        <option value="2" <?= $u['loai']==2 ? 'selected':'' ?>>Nhân viên</option>
                                                        <option value="3" <?= $u['loai']==3 ? 'selected':'' ?>>Khách hàng</option>
                                                    </select>
                                                </form>
                                            <?php endif; ?>
                                        </td>

                                        <!-- Trạng thái -->
                                        <td>
                                            <?php if ((int)$u['trangthai'] === 1): ?>
                                                <span class="badge bg-success-subtle text-success border border-success-subtle">Hoạt động</span>
                                            <?php else: ?>
                                                <span class="badge bg-danger-subtle text-danger border border-danger-subtle">Bị khóa</span>
                                            <?php endif; ?>
                                        </td>

                                        <!-- Thao tác -->
                                        <td class="text-center">
                                            <?php if ($isMe): ?>
                                                <span class="text-muted small">–</span>
                                            <?php else: ?>
                                                <?php if ((int)$u['trangthai'] === 1): ?>
                                                    <a href="index.php?action=khoa&id=<?= $u['id'] ?>&trangthai=0"
                                                       class="btn btn-sm btn-outline-danger"
                                                       title="Khóa tài khoản"
                                                       onclick="return confirm('Bạn có chắc muốn KHÓA tài khoản này?')">
                                                        <i data-feather="lock" style="width:14px;height:14px"></i> Khóa
                                                    </a>
                                                <?php else: ?>
                                                    <a href="index.php?action=khoa&id=<?= $u['id'] ?>&trangthai=1"
                                                       class="btn btn-sm btn-outline-success"
                                                       title="Mở khóa tài khoản">
                                                        <i data-feather="unlock" style="width:14px;height:14px"></i> Mở khóa
                                                    </a>
                                                <?php endif; ?>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div><!-- /card -->

            </div>
        </main>

        <?php include('../inc/footer.php'); ?>
    </div>
</div>

<script>
    // Lọc bảng theo vai trò
    function filterTable(loai, btn) {
        document.querySelectorAll('.d-flex.gap-2 button').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');

        document.querySelectorAll('#tableNhanSu tbody tr').forEach(tr => {
            const trLoai = tr.getAttribute('data-loai');
            tr.style.display = (loai === 'all' || trLoai === loai) ? '' : 'none';
        });
    }
</script>
</body>
</html>