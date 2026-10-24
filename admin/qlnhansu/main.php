<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Quản lý nhân sự - GymBro Admin</title>

    <!-- Google Fonts: Plus Jakarta Sans for modern administrative craft -->
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
                                <h1 class="ns-title mb-0">Quản trị nhân sự</h1>
                                <span class="badge" style="background-color: #f3e8ff; color: #7e22ce; border: 1px solid #e9d5ff; font-size: 0.75rem;">
                                    <i class="fa fa-shield-halved me-1"></i>Khu vực quản trị cấp cao
                                </span>
                            </div>
                            <p class="ns-subtitle mb-0">
                                Quản lý phân quyền, tài khoản nhân viên vận hành và đội ngũ quản trị viên hệ thống GymBro.
                            </p>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <button class="btn btn-ns-primary shadow-sm" type="button" data-bs-toggle="collapse" data-bs-target="#formThemNhanVien" aria-expanded="false" aria-controls="formThemNhanVien">
                                <i class="fa fa-user-plus"></i> Thêm nhân viên mới
                            </button>
                        </div>
                    </div>

                    <!-- Thống kê nhanh theo Card Metric -->
                    <div class="row g-3 mb-4">
                        <div class="col-6 col-xl-3">
                            <div class="ns-stat-card">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div>
                                        <div class="ns-stat-label">Tổng nhân sự nội bộ</div>
                                        <div class="ns-stat-number"><?= $tongNhansu ?? 0 ?></div>
                                        <div class="text-muted small mt-1">
                                            <span class="text-success fw-semibold"><?= $demHoatdong ?? 0 ?> hoạt động</span> · <span class="text-secondary"><?= $demKhoa ?? 0 ?> khóa</span>
                                        </div>
                                    </div>
                                    <div class="ns-icon-box" style="background-color: #eff6ff; color: #2563eb;">
                                        <i class="fa fa-users"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-6 col-xl-3">
                            <div class="ns-stat-card">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div>
                                        <div class="ns-stat-label">Nhân viên vận hành</div>
                                        <div class="ns-stat-number"><?= $demNhanvien ?? 0 ?></div>
                                        <div class="text-muted small mt-1">Quyền: <code>Nhân viên (2)</code></div>
                                    </div>
                                    <div class="ns-icon-box" style="background-color: #e0f2fe; color: #0284c7;">
                                        <i class="fa fa-clipboard-user"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-6 col-xl-3">
                            <div class="ns-stat-card">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div>
                                        <div class="ns-stat-label">Quản trị viên</div>
                                        <div class="ns-stat-number"><?= $demAdmin ?? 0 ?></div>
                                        <div class="text-muted small mt-1">Quyền: <code>Quản trị (1)</code></div>
                                    </div>
                                    <div class="ns-icon-box" style="background-color: #f3e8ff; color: #7e22ce;">
                                        <i class="fa fa-shield-halved"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-6 col-xl-3">
                            <div class="ns-stat-card">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div>
                                        <div class="ns-stat-label">Tài khoản bị khóa</div>
                                        <div class="ns-stat-number"><?= $demKhoa ?? 0 ?></div>
                                        <div class="text-muted small mt-1">Tạm dừng truy cập</div>
                                    </div>
                                    <div class="ns-icon-box" style="background-color: #fef2f2; color: #dc2626;">
                                        <i class="fa fa-user-slash"></i>
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

                    <!-- Form Thêm mới nhân viên (Collapse Drawer) -->
                    <div id="formThemNhanVien" class="collapse ns-form-card mb-4">
                        <div class="ns-form-header d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center gap-2">
                                <div class="ns-icon-box" style="width: 32px; height: 32px; font-size: 0.95rem; background-color: #eff6ff; color: #2563eb;">
                                    <i class="fa fa-user-plus"></i>
                                </div>
                                <h5 class="fw-bold mb-0 text-dark" style="font-size: 1rem;">Khởi tạo tài khoản nhân sự mới</h5>
                            </div>
                            <button type="button" class="btn btn-sm btn-light border" data-bs-toggle="collapse" data-bs-target="#formThemNhanVien" aria-label="Đóng form">
                                <i class="fa fa-times"></i>
                            </button>
                        </div>
                        <div class="p-4">
                            <form method="post" action="index.php" enctype="multipart/form-data">
                                <input type="hidden" name="action" value="xlthem">

                                <div class="row g-4">
                                    <!-- Cột form nhập liệu -->
                                    <div class="col-lg-8">
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <label class="form-label fw-semibold small text-secondary">
                                                    Họ và tên nhân sự <span class="text-danger">*</span>
                                                </label>
                                                <input type="text" class="form-control" name="txthoten" placeholder="Ví dụ: Nguyễn Văn Hùng" required>
                                            </div>

                                            <div class="col-md-6">
                                                <label class="form-label fw-semibold small text-secondary">
                                                    Địa chỉ Email <span class="text-danger">*</span>
                                                </label>
                                                <input type="email" class="form-control" name="txtemail" placeholder="tennhanvien@gymbro.vn" required>
                                                <div class="form-text small text-muted">Dùng để đăng nhập vào trang quản trị.</div>
                                            </div>

                                            <div class="col-md-6">
                                                <label class="form-label fw-semibold small text-secondary">
                                                    Số điện thoại <span class="text-danger">*</span>
                                                </label>
                                                <input type="text" class="form-control" name="txtsodt" placeholder="Ví dụ: 0912345678" required>
                                            </div>

                                            <div class="col-md-6">
                                                <label class="form-label fw-semibold small text-secondary">
                                                    Mật khẩu khởi tạo <span class="text-danger">*</span>
                                                </label>
                                                <input type="password" class="form-control" name="txtmatkhau" placeholder="Nhập mật khẩu..." required>
                                            </div>

                                            <div class="col-md-6">
                                                <label class="form-label fw-semibold small text-secondary">
                                                    Phân quyền truy cập <span class="text-danger">*</span>
                                                </label>
                                                <select class="form-select" name="optloai" required>
                                                    <option value="2" selected>Nhân viên vận hành (Quản lý hàng hóa, đơn hàng)</option>
                                                    <option value="1">Quản trị viên cấp cao (Toàn quyền hệ thống)</option>
                                                </select>
                                            </div>

                                            <div class="col-md-6">
                                                <label class="form-label fw-semibold small text-secondary">
                                                    Ảnh đại diện nhân sự
                                                </label>
                                                <input type="file" class="form-control" id="hinhanh" name="hinhanh" accept="image/*" onchange="previewImageAdd(event)">
                                                <div class="form-text small text-muted">Hỗ trợ JPG, PNG, WEBP. Tối đa 5MB.</div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Cột xem trước ảnh đại diện -->
                                    <div class="col-lg-4">
                                        <div class="ns-avatar-preview-box h-100 d-flex flex-column align-items-center justify-content-center">
                                            <span class="small fw-semibold text-secondary mb-2">Xem trước ảnh đại diện</span>
                                            <img id="imgPreviewAdd" src="../../assets/images/users/user.png" class="ns-avatar-preview-img" alt="Xem trước ảnh">
                                            <span class="text-muted small" id="fileNamePreviewAdd">Chưa chọn ảnh tải lên</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12 pt-3 border-top mt-4 d-flex justify-content-between align-items-center">
                                    <div class="small text-muted d-none d-md-block">
                                        <i class="fa fa-lock text-primary me-1"></i> Mật khẩu sẽ được mã hóa an toàn qua thuật toán bcrypt trước khi lưu trữ.
                                    </div>
                                    <div class="d-flex gap-2 ms-auto">
                                        <button type="button" class="btn btn-light px-3" data-bs-toggle="collapse" data-bs-target="#formThemNhanVien">
                                            Đóng lại
                                        </button>
                                        <button type="submit" class="btn btn-ns-primary px-4">
                                            <i class="fa fa-save"></i> Khởi tạo nhân sự
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Card Bảng Danh sách & Bộ lọc -->
                    <div class="ns-table-card">
                        <!-- Toolbar Bộ lọc -->
                        <div class="ns-table-header">
                            <form method="GET" action="index.php" class="row g-2 align-items-center">
                                <div class="col-12 col-md-7">
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0 text-muted">
                                            <i class="fa fa-search"></i>
                                        </span>
                                        <input type="text" name="tukhoa" class="form-control border-start-0 ps-0" placeholder="Tìm theo tên nhân viên, email hoặc số điện thoại..." value="<?= htmlspecialchars($filter_tukhoa ?? '') ?>">
                                    </div>
                                </div>

                                <div class="col-6 col-md-3">
                                    <select name="trangthai" class="form-select" onchange="this.form.submit()">
                                        <option value="">Tất cả trạng thái</option>
                                        <option value="1" <?= (($filter_trangthai ?? '') === '1') ? 'selected' : '' ?>>Đang hoạt động</option>
                                        <option value="0" <?= (($filter_trangthai ?? '') === '0') ? 'selected' : '' ?>>Đang bị khóa</option>
                                    </select>
                                </div>

                                <div class="col-6 col-md-2 text-end">
                                    <?php if (($filter_trangthai ?? '') !== '' || !empty($filter_tukhoa)): ?>
                                        <a href="index.php" class="btn btn-outline-secondary w-100" title="Đặt lại bộ lọc về mặc định">
                                            <i class="fa fa-rotate-left me-1"></i> Bỏ lọc
                                        </a>
                                    <?php else: ?>
                                        <button type="submit" class="btn btn-light border w-100 fw-semibold text-secondary">
                                            <i class="fa fa-filter me-1"></i> Lọc
                                        </button>
                                    <?php endif; ?>
                                </div>
                            </form>
                        </div>

                        <!-- Data Table -->
                        <div class="table-responsive">
                            <table class="ns-table">
                                <thead>
                                    <tr>
                                        <th style="width: 60px;" class="text-center">STT</th>
                                        <th>Nhân sự</th>
                                        <th>Thông tin liên hệ</th>
                                        <th>Vai trò quyền hạn</th>
                                        <th class="text-center" style="width: 160px;">Trạng thái</th>
                                        <th class="text-center" style="width: 140px;">Thao tác</th>
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
                                            // Tuyệt đối không liệt kê Quản trị viên trong danh sách nhân viên
                                            if ((int)$u["loai"] === 1) continue;
                                            $isSelf = (isset($_SESSION["nguoidung"]) && (int)$u["id"] === (int)$_SESSION["nguoidung"]["id"]);
                                            $userImg = (!empty($u["hinhanh"]) && file_exists("../../assets/images/users/" . $u["hinhanh"]))
                                                ? "../../assets/images/users/" . $u["hinhanh"]
                                                : "../../assets/images/users/user.png";
                                            ?>
                                            <tr>
                                                <td class="text-center text-muted fw-semibold small">
                                                    <?= $stt++ ?>
                                                </td>

                                                <td>
                                                    <div class="d-flex align-items-center gap-3">
                                                        <div class="ns-avatar-wrapper">
                                                            <img src="<?= $userImg ?>" class="ns-avatar-img" alt="<?= htmlspecialchars($u["hoten"]) ?>">
                                                            <span class="ns-status-indicator <?= ((int)$u["trangthai"] === 1) ? 'ns-status-online' : 'ns-status-offline' ?>"></span>
                                                        </div>
                                                        <div>
                                                            <div class="fw-bold text-dark fs-6 d-flex align-items-center gap-2">
                                                                <?= htmlspecialchars($u["hoten"]) ?>
                                                                <?php if ($isSelf): ?>
                                                                    <span class="ns-badge-self" title="Tài khoản đang đăng nhập hiện tại">Bạn</span>
                                                                <?php endif; ?>
                                                            </div>
                                                            <small class="text-muted">Mã tài khoản: #<?= $u["id"] ?></small>
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
                                                        <span class="font-monospace"><?= htmlspecialchars($u["sodienthoai"] ?? "Chưa cập nhật") ?></span>
                                                    </div>
                                                </td>

                                                <td>
                                                    <?php if ((int)$u["loai"] === 1): ?>
                                                        <span class="ns-badge ns-badge-admin">
                                                            <i class="fa fa-shield-halved"></i> Quản trị viên
                                                        </span>
                                                    <?php elseif ((int)$u["loai"] === 2): ?>
                                                        <span class="ns-badge ns-badge-staff">
                                                            <i class="fa fa-clipboard-user"></i> Nhân viên
                                                        </span>
                                                    <?php else: ?>
                                                        <span class="ns-badge ns-badge-customer">
                                                            <i class="fa fa-user"></i> Khách hàng
                                                        </span>
                                                    <?php endif; ?>
                                                </td>

                                                <td class="text-center">
                                                    <?php if ($isSelf): ?>
                                                        <span class="btn-toggle-status status-active opacity-75" title="Tài khoản của bạn (Không thể tự khóa)">
                                                            <i class="fa fa-check-circle"></i> Đang hoạt động
                                                        </span>
                                                    <?php else: ?>
                                                        <?php if ((int)$u["trangthai"] === 1): ?>
                                                            <a href="index.php?action=khoa&id=<?= $u["id"] ?>&trangthai=0"
                                                               class="btn-toggle-status status-active"
                                                               title="Bấm để tạm khóa tài khoản này">
                                                                <i class="fa fa-toggle-on text-success"></i> Hoạt động
                                                            </a>
                                                        <?php else: ?>
                                                            <a href="index.php?action=khoa&id=<?= $u["id"] ?>&trangthai=1"
                                                               class="btn-toggle-status status-inactive"
                                                               title="Bấm để kích hoạt lại tài khoản này">
                                                                <i class="fa fa-toggle-off text-danger"></i> Đã khóa
                                                            </a>
                                                        <?php endif; ?>
                                                    <?php endif; ?>
                                                </td>

                                                <td class="text-center">
                                                    <div class="d-inline-flex align-items-center gap-1">
                                                        <!-- Nút Sửa -->
                                                        <a href="index.php?action=sua&id=<?= $u["id"] ?>"
                                                           class="btn-action-icon btn-action-edit"
                                                           title="Chỉnh sửa thông tin nhân sự">
                                                            <i class="fa fa-pen"></i>
                                                        </a>

                                                        <!-- Nút Xóa (Bảo vệ tài khoản chính mình) -->
                                                        <?php if ($isSelf): ?>
                                                            <span class="btn-action-icon btn-action-disabled"
                                                                  title="Không thể tự xóa tài khoản của chính mình">
                                                                <i class="fa fa-lock"></i>
                                                            </span>
                                                        <?php else: ?>
                                                            <a href="index.php?action=xoa&id=<?= $u["id"] ?>"
                                                               class="btn-action-icon btn-action-delete"
                                                               title="Xóa tài khoản nhân sự khỏi hệ thống"
                                                               onclick="return confirm('Bạn có chắc chắn muốn XÓA nhân sự <?= htmlspecialchars($u['hoten']) ?> khỏi hệ thống?');">
                                                                <i class="fa fa-trash"></i>
                                                            </a>
                                                        <?php endif; ?>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="6" class="p-0">
                                                <div class="ns-empty-state">
                                                    <div class="ns-empty-icon">
                                                        <i class="fa fa-users-slash"></i>
                                                    </div>
                                                    <h5 class="fw-bold text-dark mb-1">Không tìm thấy nhân sự phù hợp</h5>
                                                    <p class="text-muted small mb-3">Không có bản ghi nhân sự nào khớp với điều kiện tìm kiếm hoặc bộ lọc hiện tại.</p>
                                                    <?php if (!empty($filter_loai) || ($filter_trangthai ?? '') !== '' || !empty($filter_tukhoa)): ?>
                                                        <a href="index.php" class="btn btn-sm btn-outline-secondary">
                                                            <i class="fa fa-rotate-left me-1"></i> Xóa tất cả bộ lọc
                                                        </a>
                                                    <?php else: ?>
                                                        <button class="btn btn-sm btn-ns-primary" type="button" data-bs-toggle="collapse" data-bs-target="#formThemNhanVien">
                                                            <i class="fa fa-user-plus me-1"></i> Thêm nhân viên mới ngay
                                                        </button>
                                                    <?php endif; ?>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>

                        <!-- Footer tóm tắt bảng -->
                        <div class="p-3 border-top bg-light bg-opacity-50 d-flex justify-content-between align-items-center">
                            <span class="small text-muted">
                                Hiển thị <strong><?= count($danhsach) ?></strong> nhân sự trong danh sách
                            </span>
                            <span class="small text-muted d-none d-md-inline">
                                GymBro Fitness Admin · Quản lý tài khoản và phân quyền nhân sự
                            </span>
                        </div>
                    </div>

                </div>
            </main>

            <?php include('../inc/footer.php'); ?>
        </div>
    </div>

    <script>
        function previewImageAdd(event) {
            const file = event.target.files[0];
            const preview = document.getElementById('imgPreviewAdd');
            const fileNameLabel = document.getElementById('fileNamePreviewAdd');

            if (file) {
                fileNameLabel.innerText = file.name;
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                };
                reader.readAsDataURL(file);
            } else {
                preview.src = '../../assets/images/users/user.png';
                fileNameLabel.innerText = 'Chưa chọn ảnh tải lên';
            }
        }
    </script>
</body>

</html>