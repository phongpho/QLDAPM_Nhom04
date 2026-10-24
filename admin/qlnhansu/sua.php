<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Kiểm tra quyền Admin (loai == 1)
if (!isset($_SESSION["nguoidung"]) || $_SESSION["nguoidung"]["loai"] != 1) {
    header("Location: ../ktnguoidung/index.php?action=dangnhap");
    exit();
}
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Cập nhật nhân sự - GymBro Admin</title>

    <!-- Google Fonts: Plus Jakarta Sans -->
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

                    <!-- Breadcrumbs & Navigation -->
                    <div class="ns-breadcrumb">
                        <a href="index.php"><i class="fa fa-users-gear me-1"></i>Quản lý nhân sự</a>
                        <i class="fa fa-chevron-right" style="font-size: 0.65rem;"></i>
                        <span class="text-dark fw-semibold">Cập nhật: <?= htmlspecialchars($nhanvien["hoten"]) ?></span>
                    </div>

                    <div class="row justify-content-center">
                        <div class="col-12 col-xl-10">
                            <div class="ns-edit-card">
                                
                                <!-- Card Header -->
                                <div class="ns-edit-header d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-2">
                                    <div>
                                        <h2 class="ns-edit-title">Cập nhật thông tin nhân sự</h2>
                                        <p class="text-muted small mb-0">Chỉnh sửa hồ sơ, phân quyền, ảnh đại diện và trạng thái hoạt động</p>
                                    </div>
                                    <a href="index.php" class="btn btn-sm btn-light border text-secondary fw-semibold">
                                        <i class="fa fa-arrow-left me-1"></i> Quay lại
                                    </a>
                                </div>

                                <div class="p-4 p-md-5">

                                    <!-- Flash Alert if error -->
                                    <?php if (isset($_SESSION["loi"])): ?>
                                        <div class="alert alert-danger alert-dismissible fade show shadow-sm border-0 d-flex align-items-center gap-2 mb-4" role="alert" style="background-color: #fef2f2; color: #991b1b; border-left: 4px solid #ef4444 !important;">
                                            <i class="fa fa-triangle-exclamation fs-5"></i>
                                            <div><strong>Lỗi:</strong> <?= htmlspecialchars($_SESSION["loi"]) ?></div>
                                            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
                                        </div>
                                        <?php unset($_SESSION["loi"]); ?>
                                    <?php endif; ?>

                                    <?php
                                    $hinhHienTai = (!empty($nhanvien["hinhanh"]) && file_exists("../../assets/images/users/" . $nhanvien["hinhanh"])) 
                                        ? "../../assets/images/users/" . $nhanvien["hinhanh"] 
                                        : "../../assets/images/users/user.png";
                                    $isSelf = (isset($_SESSION["nguoidung"]) && (int)$nhanvien["id"] === (int)$_SESSION["nguoidung"]["id"]);
                                    ?>

                                    <!-- Summary Box -->
                                    <div class="ns-summary-box d-flex align-items-center justify-content-between gap-3 mb-4">
                                        <div class="d-flex align-items-center gap-3">
                                            <img src="<?= $hinhHienTai ?>" class="ns-avatar-summary-img" alt="<?= htmlspecialchars($nhanvien["hoten"]) ?>">
                                            <div>
                                                <div class="fw-bold text-dark fs-6 d-flex align-items-center gap-2">
                                                    <?= htmlspecialchars($nhanvien["hoten"]) ?>
                                                    <?php if ($isSelf): ?>
                                                        <span class="badge" style="background-color: #fef3c7; color: #92400e; border: 1px solid #fde68a; font-size: 0.7rem;">Bạn</span>
                                                    <?php endif; ?>
                                                </div>
                                                <div class="text-muted small">
                                                    <?= htmlspecialchars($nhanvien["email"]) ?> · Mã nhân sự: #<?= $nhanvien["id"] ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="text-end d-none d-sm-block">
                                            <?php if ((int)$nhanvien["trangthai"] === 1): ?>
                                                <span class="badge" style="background-color: #ecfdf5; color: #047857; border: 1px solid #a7f3d0;">
                                                    <i class="fa fa-check-circle me-1"></i>Đang hoạt động
                                                </span>
                                            <?php else: ?>
                                                <span class="badge" style="background-color: #fef2f2; color: #b91c1c; border: 1px solid #fecaca;">
                                                    <i class="fa fa-ban me-1"></i>Đang bị khóa
                                                </span>
                                            <?php endif; ?>
                                        </div>
                                    </div>

                                    <!-- Form Edit -->
                                    <form method="post" action="index.php" enctype="multipart/form-data">
                                        <input type="hidden" name="action" value="xlsua">
                                        <input type="hidden" name="txtid" value="<?= $nhanvien["id"] ?>">
                                        <input type="hidden" name="txthinhanhcu" value="<?= htmlspecialchars($nhanvien["hinhanh"] ?? "") ?>">

                                        <div class="row g-4">
                                            <!-- Cột bên trái: Form thông tin -->
                                            <div class="col-lg-8">
                                                <div class="row g-3">
                                                    <div class="col-md-6">
                                                        <label class="form-label">
                                                            Họ và tên <span class="text-danger">*</span>
                                                        </label>
                                                        <input type="text" class="form-control" name="txthoten" value="<?= htmlspecialchars($nhanvien["hoten"]) ?>" required>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <label class="form-label">
                                                            Email đăng nhập <span class="text-danger">*</span>
                                                        </label>
                                                        <input type="email" class="form-control" name="txtemail" value="<?= htmlspecialchars($nhanvien["email"]) ?>" required>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <label class="form-label">
                                                            Số điện thoại <span class="text-danger">*</span>
                                                        </label>
                                                        <input type="text" class="form-control" name="txtsodt" value="<?= htmlspecialchars($nhanvien["sodienthoai"]) ?>" required>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <label class="form-label">Đổi mật khẩu mới</label>
                                                        <input type="password" class="form-control" name="txtmatkhaumoi" placeholder="Để trống nếu giữ nguyên mật khẩu cũ">
                                                        <div class="form-text">Chỉ nhập khi bạn muốn thiết lập lại mật khẩu mới cho nhân sự này.</div>
                                                    </div>

                                                    <div class="col-12">
                                                        <label class="form-label">
                                                            Trạng thái tài khoản <span class="text-danger">*</span>
                                                        </label>
                                                        <div class="row g-3">
                                                            <div class="col-sm-6">
                                                                <label class="ns-radio-card <?= ((int)$nhanvien["trangthai"] === 1) ? 'active' : '' ?>">
                                                                    <input class="form-check-input mt-0" type="radio" name="opttrangthai" value="1" <?= ((int)$nhanvien["trangthai"] === 1) ? 'checked' : '' ?> required>
                                                                    <div>
                                                                        <div class="fw-semibold text-success small"><i class="fa fa-check-circle me-1"></i>Kích hoạt (Hoạt động)</div>
                                                                        <div class="text-muted" style="font-size: 0.75rem;">Nhân sự có thể đăng nhập và xử lý công việc.</div>
                                                                    </div>
                                                                </label>
                                                            </div>
                                                            <div class="col-sm-6">
                                                                <label class="ns-radio-card <?= ((int)$nhanvien["trangthai"] === 0) ? 'active' : '' ?>">
                                                                    <input class="form-check-input mt-0" type="radio" name="opttrangthai" value="0" <?= ((int)$nhanvien["trangthai"] === 0) ? 'checked' : '' ?> required>
                                                                    <div>
                                                                        <div class="fw-semibold text-danger small"><i class="fa fa-ban me-1"></i>Khóa tài khoản</div>
                                                                        <div class="text-muted" style="font-size: 0.75rem;">Tạm dừng mọi quyền truy cập vào hệ thống.</div>
                                                                    </div>
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-12">
                                                        <label class="form-label">Tải lên hình ảnh mới</label>
                                                        <input type="file" class="form-control" id="hinhanh" name="hinhanh" accept="image/*" onchange="previewImageEdit(event)">
                                                        <div class="form-text">Hỗ trợ JPG, PNG, WEBP. Chọn tệp mới nếu muốn thay đổi ảnh đại diện.</div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Cột bên phải: Xem trước hình ảnh -->
                                            <div class="col-lg-4">
                                                <div class="ns-avatar-preview-box h-100 d-flex flex-column align-items-center justify-content-center">
                                                    <span class="small fw-semibold text-secondary mb-2">Ảnh đại diện nhân sự</span>
                                                    <img id="imgPreviewEdit" src="<?= $hinhHienTai ?>" class="ns-avatar-preview-img" alt="Ảnh đại diện">
                                                    <span class="text-muted small" id="fileNamePreviewEdit">Ảnh hiện tại</span>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Nút hành động -->
                                        <div class="d-flex justify-content-between align-items-center pt-4 border-top mt-4">
                                            <a href="index.php" class="btn btn-light px-4 border text-secondary">
                                                Hủy bỏ
                                            </a>
                                            <button type="submit" class="btn btn-ns-primary px-4 shadow-sm">
                                                <i class="fa fa-save"></i> Lưu thay đổi
                                            </button>
                                        </div>
                                    </form>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>

            <?php include('../inc/footer.php'); ?>
        </div>
    </div>

    <script>
        function previewImageEdit(event) {
            const file = event.target.files[0];
            const preview = document.getElementById('imgPreviewEdit');
            const fileNameLabel = document.getElementById('fileNamePreviewEdit');

            if (file) {
                fileNameLabel.innerText = 'Ảnh mới: ' + file.name;
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                };
                reader.readAsDataURL(file);
            } else {
                preview.src = '<?= $hinhHienTai ?>';
                fileNameLabel.innerText = 'Ảnh hiện tại';
            }
        }
    </script>
</body>

</html>

