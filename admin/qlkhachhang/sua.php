<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Kiểm tra quyền Admin (1) hoặc Nhân viên (2)
if (!isset($_SESSION["nguoidung"]) || !in_array((int)$_SESSION["nguoidung"]["loai"], [1, 2])) {
    header("Location: ../ktnguoidung/index.php?action=dangnhap");
    exit();
}

$khachhang = $k;
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Cập nhật khách hàng - GymBro Admin</title>

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
                    <div class="gb-breadcrumb">
                        <a href="index.php"><i class="fa fa-users me-1"></i>Quản lý khách hàng</a>
                        <i class="fa fa-chevron-right" style="font-size: 0.65rem;"></i>
                        <span class="text-dark fw-semibold">Cập nhật: <?= htmlspecialchars($khachhang["hoten"]) ?></span>
                    </div>

                    <div class="row justify-content-center">
                        <div class="col-12 col-xl-10">
                            <div class="gb-edit-card">
                                
                                <!-- Card Header -->
                                <div class="gb-edit-header d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-2">
                                    <div>
                                        <h2 class="gb-edit-title">Cập nhật thông tin khách hàng</h2>
                                        <p class="text-muted small mb-0">Chỉnh sửa hồ sơ cá nhân, số điện thoại, mật khẩu và trạng thái hoạt động</p>
                                    </div>
                                    <div class="d-flex align-items-center gap-2">
                                        <a href="index.php?action=chitiet&id=<?= $khachhang["id"] ?>" class="btn btn-sm btn-light border text-primary fw-semibold">
                                            <i class="fa fa-id-card me-1"></i> Xem hồ sơ
                                        </a>
                                        <a href="index.php" class="btn btn-sm btn-light border text-secondary fw-semibold">
                                            <i class="fa fa-arrow-left me-1"></i> Danh sách
                                        </a>
                                    </div>
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
                                    $hinhHienTai = (!empty($khachhang["hinhanh"]) && file_exists("../../assets/images/users/" . $khachhang["hinhanh"])) 
                                        ? "../../assets/images/users/" . $khachhang["hinhanh"] 
                                        : "../../assets/images/users/user.png";
                                    ?>

                                    <!-- Summary Box -->
                                    <div class="gb-summary-box d-flex align-items-center justify-content-between gap-3 mb-4">
                                        <div class="d-flex align-items-center gap-3">
                                            <img src="<?= $hinhHienTai ?>" class="ns-avatar-summary-img" alt="<?= htmlspecialchars($khachhang["hoten"]) ?>" style="width: 52px; height: 52px; border-radius: 50%; object-fit: cover; border: 2px solid #e2e8f0;">
                                            <div>
                                                <div class="fw-bold text-dark fs-6 d-flex align-items-center gap-2">
                                                    <?= htmlspecialchars($khachhang["hoten"]) ?>
                                                    <span class="badge" style="background-color: #eff6ff; color: #1e40af; border: 1px solid #bfdbfe; font-size: 0.7rem;">Mã KH: #<?= $khachhang["id"] ?></span>
                                                </div>
                                                <div class="text-muted small">
                                                    <?= htmlspecialchars($khachhang["email"]) ?> · SĐT: <?= htmlspecialchars($khachhang["sodienthoai"]) ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="text-end d-none d-sm-block">
                                            <?php if ((int)$khachhang["trangthai"] === 1): ?>
                                                <span class="badge" style="background-color: #ecfdf5; color: #047857; border: 1px solid #a7f3d0; padding: 0.4rem 0.75rem;">
                                                    <i class="fa fa-check-circle me-1"></i>Đang hoạt động
                                                </span>
                                            <?php else: ?>
                                                <span class="badge" style="background-color: #fef2f2; color: #b91c1c; border: 1px solid #fecaca; padding: 0.4rem 0.75rem;">
                                                    <i class="fa fa-ban me-1"></i>Đang bị khóa
                                                </span>
                                            <?php endif; ?>
                                        </div>
                                    </div>

                                    <!-- Form Edit -->
                                    <form method="post" action="index.php" enctype="multipart/form-data">
                                        <input type="hidden" name="action" value="xlsua">
                                        <input type="hidden" name="txtid" value="<?= $khachhang["id"] ?>">
                                        <input type="hidden" name="txthinhanhcu" value="<?= htmlspecialchars($khachhang["hinhanh"] ?? "") ?>">

                                        <div class="row g-4">
                                            <!-- Cột bên trái: Form thông tin -->
                                            <div class="col-lg-8">
                                                <div class="row g-3">
                                                    <div class="col-md-6">
                                                        <label class="form-label fw-semibold">
                                                            Họ và tên <span class="text-danger">*</span>
                                                        </label>
                                                        <input type="text" class="form-control" name="txthoten" value="<?= htmlspecialchars($khachhang["hoten"]) ?>" required>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <label class="form-label fw-semibold">
                                                            Email tài khoản <span class="text-danger">*</span>
                                                        </label>
                                                        <input type="email" class="form-control" name="txtemail" value="<?= htmlspecialchars($khachhang["email"]) ?>" required>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <label class="form-label fw-semibold">
                                                            Số điện thoại liên hệ <span class="text-danger">*</span>
                                                        </label>
                                                        <input type="text" class="form-control" name="txtsodt" value="<?= htmlspecialchars($khachhang["sodienthoai"]) ?>" required>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <label class="form-label fw-semibold">Đặt lại mật khẩu</label>
                                                        <input type="password" class="form-control" name="txtmatkhaumoi" placeholder="Để trống nếu không đổi">
                                                        <div class="form-text" style="font-size: 0.75rem;">Chỉ nhập khi cần cấp lại mật khẩu đăng nhập cho khách.</div>
                                                    </div>

                                                    <div class="col-12">
                                                        <label class="form-label fw-semibold">
                                                            Trạng thái tài khoản <span class="text-danger">*</span>
                                                        </label>
                                                        <div class="row g-3">
                                                            <div class="col-sm-6">
                                                                <label class="ns-radio-card <?= ((int)$khachhang["trangthai"] === 1) ? 'active' : '' ?>">
                                                                    <input class="form-check-input mt-0" type="radio" name="opttrangthai" value="1" <?= ((int)$khachhang["trangthai"] === 1) ? 'checked' : '' ?> required>
                                                                    <div>
                                                                        <div class="fw-semibold text-success small"><i class="fa fa-check-circle me-1"></i>Hoạt động bình thường</div>
                                                                        <div class="text-muted" style="font-size: 0.75rem;">Khách có thể đăng nhập, đặt hàng và theo dõi thể trạng.</div>
                                                                    </div>
                                                                </label>
                                                            </div>
                                                            <div class="col-sm-6">
                                                                <label class="ns-radio-card <?= ((int)$khachhang["trangthai"] === 0) ? 'active' : '' ?>">
                                                                    <input class="form-check-input mt-0" type="radio" name="opttrangthai" value="0" <?= ((int)$khachhang["trangthai"] === 0) ? 'checked' : '' ?> required>
                                                                    <div>
                                                                        <div class="fw-semibold text-danger small"><i class="fa fa-ban me-1"></i>Tạm khóa tài khoản</div>
                                                                        <div class="text-muted" style="font-size: 0.75rem;">Chặn đăng nhập trên web GymBro để kiểm tra hoặc cảnh báo.</div>
                                                                    </div>
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-12">
                                                        <label class="form-label fw-semibold">Ảnh đại diện mới</label>
                                                        <input type="file" class="form-control" id="fhinh" name="fhinh" accept="image/*" onchange="previewAvatarEdit(event)">
                                                        <div class="form-text" style="font-size: 0.75rem;">Hỗ trợ JPG, PNG, WEBP. Chọn file mới nếu muốn cập nhật ảnh đại diện.</div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Cột bên phải: Xem trước hình ảnh -->
                                            <div class="col-lg-4">
                                                <div class="gb-summary-box h-100 d-flex flex-column align-items-center justify-content-center p-4 text-center">
                                                    <span class="small fw-semibold text-secondary mb-3">Xem trước ảnh đại diện</span>
                                                    <div class="position-relative mb-3">
                                                        <img id="imgPreviewEdit" src="<?= $hinhHienTai ?>" class="rounded-circle shadow-sm" alt="Ảnh đại diện" style="width: 130px; height: 130px; object-fit: cover; border: 4px solid #ffffff;">
                                                    </div>
                                                    <span class="text-muted small" id="fileNamePreviewEdit">Ảnh hiện tại</span>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Nút hành động -->
                                        <div class="d-flex justify-content-between align-items-center pt-4 border-top mt-4">
                                            <a href="index.php" class="btn btn-light px-4 border text-secondary fw-semibold">
                                                Hủy bỏ
                                            </a>
                                            <button type="submit" class="btn btn-gb-primary px-4 shadow-sm">
                                                <i class="fa fa-save me-1"></i> Lưu thay đổi
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
        function previewAvatarEdit(event) {
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
