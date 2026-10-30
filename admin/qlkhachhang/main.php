<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Quản lý khách hàng - GymBro Admin</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- AdminKit base -->
    <link href="../inc/css/app.css" rel="stylesheet">
    <script src="../inc/js/app.js"></script>

    <!-- Bootstrap 5 & FontAwesome 6 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">

    <!-- Unified Admin Design System -->
    <link href="../inc/css/main.css" rel="stylesheet">
</head>

<body>
    <div class="wrapper">
        <?php include('../inc/sidebar.php'); ?>

        <div class="main">
            <?php include('../inc/header.php'); ?>

            <main class="content">
                <div class="container-fluid p-0">

                    <!-- Header & Tiêu đề -->
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
                        <div>
                            <div class="d-flex align-items-center gap-2 mb-1">
                                <h1 class="gb-title mb-0">Quản lý khách hàng</h1>
                                <?php if (isset($_SESSION["nguoidung"]) && (int)$_SESSION["nguoidung"]["loai"] === 1): ?>
                                    <span class="badge bg-purple-subtle text-purple border border-purple-subtle" title="Tài khoản có toàn quyền quản trị và xóa khách hàng">
                                        <i class="fa fa-shield-halved me-1"></i> Quản trị viên
                                    </span>
                                <?php else: ?>
                                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle" title="Nhân viên có quyền xem và hỗ trợ khách hàng">
                                        <i class="fa fa-user-check me-1"></i> Nhân viên
                                    </span>
                                <?php endif; ?>
                            </div>
                            <p class="gb-subtitle mb-0">
                                Quản lý danh sách thành viên, theo dõi lịch sử mua sắm và hỗ trợ tài khoản khách hàng GymBro.
                            </p>
                        </div>
                    </div>

                    <!-- Thống kê nhanh theo Card Metric -->
                    <div class="row g-3 mb-4">
                        <div class="col-6 col-xl-3">
                            <div class="gb-stat-card">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div>
                                        <div class="gb-stat-label">Tổng khách hàng</div>
                                        <div class="gb-stat-number"><?= $tongKhachHang ?? 0 ?></div>
                                        <div class="text-muted small mt-1">Thành viên đăng ký</div>
                                    </div>
                                    <div class="gb-icon-box" style="background-color: #eff6ff; color: #2563eb;">
                                        <i class="fa fa-users"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-6 col-xl-3">
                            <div class="gb-stat-card">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div>
                                        <div class="gb-stat-label">Đang hoạt động</div>
                                        <div class="gb-stat-number text-success">
                                            <?= $demHoatDong ?? 0 ?>
                                        </div>
                                        <div class="text-muted small mt-1">Truy cập bình thường</div>
                                    </div>
                                    <div class="gb-icon-box" style="background-color: #ecfdf5; color: #059669;">
                                        <i class="fa fa-user-check"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-6 col-xl-3">
                            <div class="gb-stat-card">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div>
                                        <div class="gb-stat-label">Tài khoản bị khóa</div>
                                        <div class="gb-stat-number <?= ($demKhoa > 0) ? 'text-danger' : 'text-dark' ?>">
                                            <?= $demKhoa ?? 0 ?>
                                        </div>
                                        <div class="text-muted small mt-1">Tạm dừng truy cập</div>
                                    </div>
                                    <div class="gb-icon-box" style="background-color: #fef2f2; color: #dc2626;">
                                        <i class="fa fa-user-lock"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-6 col-xl-3">
                            <div class="gb-stat-card">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div>
                                        <div class="gb-stat-label">Đã phát sinh đơn</div>
                                        <div class="gb-stat-number text-primary"><?= $demCoDonHang ?? 0 ?></div>
                                        <div class="text-muted small mt-1">Khách hàng có lịch sử mua</div>
                                    </div>
                                    <div class="gb-icon-box" style="background-color: #f3e8ff; color: #7e22ce;">
                                        <i class="fa fa-bag-shopping"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Flash Messages -->
                    <?php if (isset($_SESSION["thongbao"])): ?>
                        <div class="alert alert-success alert-dismissible fade show shadow-sm border-0 d-flex align-items-center gap-2 mb-4" role="alert" style="background-color: #ecfdf5; color: #065f46; border-left: 4px solid #10b981 !important;">
                            <i class="fa fa-circle-check fs-5"></i>
                            <div><strong>Thành công:</strong> <?= htmlspecialchars($_SESSION["thongbao"]) ?></div>
                            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                        <?php unset($_SESSION["thongbao"]); ?>
                    <?php endif; ?>

                    <?php if (isset($_SESSION["loi"])): ?>
                        <div class="alert alert-danger alert-dismissible fade show shadow-sm border-0 d-flex align-items-center gap-2 mb-4" role="alert" style="background-color: #fef2f2; color: #991b1b; border-left: 4px solid #ef4444 !important;">
                            <i class="fa fa-triangle-exclamation fs-5"></i>
                            <div><strong>Cảnh báo:</strong> <?= htmlspecialchars($_SESSION["loi"]) ?></div>
                            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                        <?php unset($_SESSION["loi"]); ?>
                    <?php endif; ?>

                    <!-- Card Bảng Danh sách & Bộ lọc -->
                    <div class="gb-table-card">
                        <!-- Toolbar Bộ lọc chuyên sâu -->
                        <div class="gb-table-header">
                            <form method="GET" action="index.php" class="row g-2 align-items-center">
                                <div class="col-12 col-md-5">
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0 text-muted">
                                            <i class="fa fa-search"></i>
                                        </span>
                                        <input type="text" name="tukhoa" class="form-control border-start-0 ps-0" placeholder="Tìm theo tên, email, số điện thoại hoặc mã ID..." value="<?= htmlspecialchars($filter_tukhoa ?? '') ?>">
                                    </div>
                                </div>

                                <div class="col-6 col-md-3">
                                    <select name="trangthai" class="form-select" onchange="this.form.submit()">
                                        <option value="">Tất cả trạng thái</option>
                                        <option value="1" <?= (($filter_trangthai ?? '') === '1') ? 'selected' : '' ?>>Đang hoạt động</option>
                                        <option value="0" <?= (($filter_trangthai ?? '') === '0') ? 'selected' : '' ?>>Đang bị khóa</option>
                                    </select>
                                </div>

                                <div class="col-6 col-md-3">
                                    <select name="donhang" class="form-select" onchange="this.form.submit()">
                                        <option value="">Tất cả lịch sử mua hàng</option>
                                        <option value="codon" <?= (($filter_donhang ?? '') === 'codon') ? 'selected' : '' ?>>Đã từng mua hàng</option>
                                        <option value="chuadon" <?= (($filter_donhang ?? '') === 'chuadon') ? 'selected' : '' ?>>Chưa có đơn hàng</option>
                                    </select>
                                </div>

                                <div class="col-12 col-md-1 text-end">
                                    <?php if (!empty($filter_tukhoa) || ($filter_trangthai ?? '') !== '' || ($filter_donhang ?? '') !== ''): ?>
                                        <a href="index.php" class="btn btn-outline-secondary w-100" title="Đặt lại bộ lọc về mặc định">
                                            <i class="fa fa-rotate-left"></i>
                                        </a>
                                    <?php else: ?>
                                        <button type="submit" class="btn btn-light border w-100 fw-semibold text-secondary" title="Áp dụng bộ lọc">
                                            <i class="fa fa-filter"></i>
                                        </button>
                                    <?php endif; ?>
                                </div>
                            </form>
                        </div>

                        <!-- Data Table -->
                        <div class="table-responsive">
                            <table class="gb-table">
                                <thead>
                                    <tr>
                                        <th style="width: 50px;" class="text-center">STT</th>
                                        <th>Khách hàng</th>
                                        <th>Thông tin liên hệ</th>
                                        <th class="text-center">Lịch sử đơn hàng</th>
                                        <th class="text-center">Tổng chi tiêu</th>
                                        <th class="text-center">Ngày đăng ký</th>
                                        <th class="text-center" style="width: 140px;">Trạng thái</th>
                                        <th class="text-center" style="width: 120px;">Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    $danhsach = $danhsachHienThi ?? [];
                                    ?>
                                    <?php if (!empty($danhsach)): ?>
                                        <?php $stt = 1;
                                        foreach ($danhsach as $u): ?>
                                            <?php 
                                            $userImg = (!empty($u["hinhanh"]) && file_exists("../../assets/images/users/" . $u["hinhanh"]))
                                                ? "../../assets/images/users/" . $u["hinhanh"]
                                                : "../../assets/images/users/user.png";

                                            $tongDon = (int)($u["tong_donhang"] ?? 0);
                                            $tongTien = (float)($u["tong_chitieu"] ?? 0);
                                            ?>
                                            <tr>
                                                <td class="text-center text-muted fw-semibold small">
                                                    <?= $stt++ ?>
                                                </td>

                                                <td>
                                                    <div class="d-flex align-items-center gap-3">
                                                        <div class="ns-avatar-wrapper">
                                                            <img src="<?= $userImg ?>" class="ns-avatar-img" alt="<?= htmlspecialchars($u["hoten"]) ?>" onerror="this.src='../../assets/images/users/user.png'">
                                                            <span class="ns-status-indicator <?= ((int)$u["trangthai"] === 1) ? 'ns-status-online' : 'ns-status-offline' ?>"></span>
                                                        </div>
                                                        <div>
                                                            <div class="fw-bold text-dark fs-6">
                                                                <?= htmlspecialchars($u["hoten"]) ?>
                                                            </div>
                                                            <small class="text-muted">Mã khách: #<?= $u["id"] ?></small>
                                                        </div>
                                                    </div>
                                                </td>

                                                <td>
                                                    <div>
                                                        <i class="fa fa-envelope text-muted me-1" style="font-size: 0.8rem;"></i>
                                                        <span class="text-dark"><?= htmlspecialchars($u["email"]) ?></span>
                                                    </div>
                                                    <div class="small text-muted mt-1">
                                                        <i class="fa fa-phone text-muted me-1" style="font-size: 0.8rem;"></i>
                                                        <span class="font-monospace"><?= htmlspecialchars($u["sodienthoai"] ?? "Chưa có") ?></span>
                                                    </div>
                                                </td>

                                                <td class="text-center">
                                                    <?php if ($tongDon > 0): ?>
                                                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-1">
                                                            <?= $tongDon ?> đơn hàng
                                                        </span>
                                                    <?php else: ?>
                                                        <span class="badge bg-light text-secondary border px-2 py-1">
                                                            Chưa có đơn
                                                        </span>
                                                    <?php endif; ?>
                                                </td>

                                                <td class="text-center fw-bold text-dark">
                                                    <?= number_format($tongTien, 0, ',', '.') ?> đ
                                                </td>

                                                <td class="text-center text-muted small">
                                                    <?= !empty($u["ngaytao"]) ? date('d/m/Y', strtotime($u["ngaytao"])) : "Không rõ" ?>
                                                </td>

                                                <td class="text-center">
                                                    <?php if ((int)$u["trangthai"] === 1): ?>
                                                        <a href="index.php?action=doitrangthai&id=<?= $u["id"] ?>&trangthai=0"
                                                           class="btn-toggle-status status-active"
                                                           title="Bấm để tạm khóa tài khoản khách hàng này">
                                                            <i class="fa fa-toggle-on text-success"></i> Hoạt động
                                                        </a>
                                                    <?php else: ?>
                                                        <a href="index.php?action=doitrangthai&id=<?= $u["id"] ?>&trangthai=1"
                                                           class="btn-toggle-status status-inactive"
                                                           title="Bấm để kích hoạt lại tài khoản khách hàng này">
                                                            <i class="fa fa-toggle-off text-danger"></i> Đã khóa
                                                        </a>
                                                    <?php endif; ?>
                                                </td>

                                                <td class="text-center">
                                                    <div class="d-inline-flex align-items-center gap-1">
                                                        <!-- Nút Chi tiết hồ sơ -->
                                                        <a href="index.php?action=chitiet&id=<?= $u["id"] ?>"
                                                           class="btn-action-icon" style="background-color: #eff6ff; color: #2563eb; border-color: #bfdbfe;"
                                                           title="Xem chi tiết hồ sơ, thể trạng và đơn hàng">
                                                            <i class="fa fa-id-card"></i>
                                                        </a>

                                                        <!-- Nút Sửa -->
                                                        <a href="index.php?action=sua&id=<?= $u["id"] ?>"
                                                           class="btn-action-icon btn-action-edit"
                                                           title="Chỉnh sửa thông tin khách hàng">
                                                            <i class="fa fa-pen"></i>
                                                        </a>

                                                        <!-- Nút Xóa: Chỉ dành cho Admin -->
                                                        <?php if (isset($_SESSION["nguoidung"]) && (int)$_SESSION["nguoidung"]["loai"] === 1): ?>
                                                            <a href="index.php?action=xoa&id=<?= $u["id"] ?>"
                                                               class="btn-action-icon btn-action-delete"
                                                               title="Xóa tài khoản khách hàng khỏi hệ thống"
                                                               onclick="return confirm('Bạn có chắc chắn muốn XÓA khách hàng <?= htmlspecialchars($u['hoten']) ?>? Thao tác này chỉ thực hiện được nếu khách hàng chưa có đơn hàng.');">
                                                                <i class="fa fa-trash"></i>
                                                            </a>
                                                        <?php else: ?>
                                                            <span class="btn-action-icon btn-action-disabled" title="Nhân viên không có quyền xóa. Hãy dùng nút Khóa tài khoản">
                                                                <i class="fa fa-lock"></i>
                                                            </span>
                                                        <?php endif; ?>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="8" class="text-center py-5">
                                                <div class="dm-empty-state">
                                                    <div class="dm-empty-icon">
                                                        <i class="fa fa-users-slash"></i>
                                                    </div>
                                                    <h5 class="fw-bold text-dark">Chưa tìm thấy khách hàng phù hợp</h5>
                                                    <p class="text-muted small mb-3">
                                                        Không có tài khoản khách hàng nào khớp với điều kiện tìm kiếm hiện tại.
                                                    </p>
                                                    <a href="index.php" class="btn btn-outline-secondary btn-sm">
                                                        <i class="fa fa-rotate-left me-1"></i> Bỏ bộ lọc và xem tất cả
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
            </main>

            <?php include('../inc/footer.php'); ?>
        </div>
    </div>

</body>

</html>
