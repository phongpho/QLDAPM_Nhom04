<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION["nguoidung"])) {
    header("Location: index.php?action=dangnhap");
    exit();
}

$user = $_SESSION["nguoidung"];
$loaiNguoiDung = ((int)$user["loai"] === 1) ? "Quản trị viên" : (((int)$user["loai"] === 2) ? "Nhân viên vận hành" : "Khách hàng");
$badgeClass = ((int)$user["loai"] === 1) ? "bg-purple-subtle text-purple border border-purple-subtle" : "bg-primary-subtle text-primary border border-primary-subtle";

$hinhanh = $user["hinhanh"] ?? '';
if (!empty($hinhanh) && file_exists("../../assets/images/users/" . $hinhanh)) {
    $imgSrc = "../../assets/images/users/" . $hinhanh;
} else {
    $imgSrc = "../../assets/images/users/user.png";
}
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Hồ sơ cá nhân - GymBro Admin</title>

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

                    <!-- Breadcrumbs & Tiêu đề -->
                    <div class="gb-breadcrumb">
                        <a href="index.php"><i class="fa fa-sliders me-1"></i>Bảng điều khiển</a>
                        <i class="fa fa-chevron-right" style="font-size: 0.65rem;"></i>
                        <span class="text-dark fw-semibold">Hồ sơ cá nhân</span>
                    </div>

                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
                        <div>
                            <h1 class="gb-title mb-1">Hồ sơ cá nhân</h1>
                            <p class="gb-subtitle mb-0">Quản lý và cập nhật thông tin cá nhân và ảnh đại diện tài khoản.</p>
                        </div>
                        <div>
                            <a href="index.php" class="btn btn-outline-secondary">
                                <i class="fa fa-arrow-left me-1"></i> Quay lại bảng điều khiển
                            </a>
                        </div>
                    </div>

                    <div class="row g-4">
                        <!-- Profile Card Cột trái -->
                        <div class="col-12 col-md-5 col-xl-4">
                            <div class="card border-0 shadow-sm rounded-3 mb-4">
                                <div class="card-header bg-white border-bottom text-center pt-4 pb-3">
                                    <div class="position-relative d-inline-block">
                                        <img id="avatarPreview" src="<?= $imgSrc ?>"
                                            alt="<?= htmlspecialchars($user["hoten"]) ?>"
                                            class="gb-profile-avatar">
                                        <span class="position-absolute bottom-0 end-0 bg-success border border-2 border-white rounded-circle p-2"
                                            title="Tài khoản đang hoạt động" style="transform: translate(-10%, -10%);"></span>
                                    </div>
                                    <h5 class="fw-bold text-dark mt-3 mb-1"><?= htmlspecialchars($user["hoten"]) ?></h5>
                                    <div>
                                        <span class="badge <?= $badgeClass ?> px-3 py-1 fw-semibold"><?= $loaiNguoiDung ?></span>
                                    </div>
                                </div>
                                <div class="card-body p-4">
                                    <h6 class="text-muted text-uppercase fw-bold small mb-3" style="letter-spacing: 0.05em;">Thông tin tài khoản</h6>
                                    <ul class="gb-profile-list mb-4">
                                        <li>
                                            <div class="gb-profile-icon"><i class="fa fa-envelope text-primary"></i></div>
                                            <div class="text-truncate">
                                                <small class="text-muted d-block">Địa chỉ email</small>
                                                <span class="fw-medium text-dark"><?= htmlspecialchars($user["email"]) ?></span>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="gb-profile-icon"><i class="fa fa-phone text-success"></i></div>
                                            <div>
                                                <small class="text-muted d-block">Số điện thoại</small>
                                                <span class="fw-medium text-dark font-monospace"><?= htmlspecialchars($user["sodienthoai"]) ?></span>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="gb-profile-icon"><i class="fa fa-shield-halved text-info"></i></div>
                                            <div>
                                                <small class="text-muted d-block">Trạng thái hệ thống</small>
                                                <span class="text-success fw-semibold">Đang hoạt động</span>
                                            </div>
                                        </li>
                                    </ul>

                                    <div class="d-grid">
                                        <a href="index.php?action=matkhau" class="btn btn-outline-primary">
                                            <i class="fa fa-key me-1"></i> Đổi mật khẩu đăng nhập
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Form Cập nhật Cột phải -->
                        <div class="col-12 col-md-7 col-xl-8">
                            <div class="gb-edit-card">
                                <div class="gb-edit-header">
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="gb-icon-badge">
                                            <i class="fa fa-user-pen"></i>
                                        </div>
                                        <div>
                                            <h5 class="gb-edit-title mb-0">Cập nhật thông tin</h5>
                                            <small class="text-muted">Thay đổi họ tên, thông tin liên hệ và ảnh đại diện</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body p-4">
                                    <?php if (isset($msg_error)): ?>
                                        <div class="alert alert-danger alert-dismissible fade show border-0 d-flex align-items-center gap-2 mb-3" role="alert" style="background-color: #fef2f2; color: #991b1b; border-left: 4px solid #ef4444 !important;">
                                            <i class="fa fa-circle-exclamation fs-5"></i>
                                            <div><?= htmlspecialchars($msg_error) ?></div>
                                            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
                                        </div>
                                    <?php endif; ?>

                                    <?php if (isset($msg_success)): ?>
                                        <div class="alert alert-success alert-dismissible fade show border-0 d-flex align-items-center gap-2 mb-3" role="alert" style="background-color: #ecfdf5; color: #065f46; border-left: 4px solid #10b981 !important;">
                                            <i class="fa fa-circle-check fs-5"></i>
                                            <div><?= htmlspecialchars($msg_success) ?></div>
                                            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
                                        </div>
                                    <?php endif; ?>

                                    <form method="post" enctype="multipart/form-data" action="index.php">
                                        <input type="hidden" name="action" value="xlhoso">
                                        <input type="hidden" name="txtid" value="<?= $user["id"] ?>">
                                        <input type="hidden" name="txthinhanh" value="<?= htmlspecialchars($user["hinhanh"] ?? "") ?>">

                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <label class="form-label">Họ và tên <span class="text-danger">*</span></label>
                                                <input class="form-control" type="text" name="txthoten"
                                                    value="<?= htmlspecialchars($user["hoten"]) ?>" required>
                                            </div>

                                            <div class="col-md-6">
                                                <label class="form-label">Vai trò hệ thống</label>
                                                <input class="form-control bg-light" type="text"
                                                    value="<?= $loaiNguoiDung ?>" readonly disabled>
                                            </div>

                                            <div class="col-md-6">
                                                <label class="form-label">Địa chỉ email <span class="text-danger">*</span></label>
                                                <input class="form-control" type="email" name="txtemail"
                                                    value="<?= htmlspecialchars($user["email"]) ?>" required>
                                            </div>

                                            <div class="col-md-6">
                                                <label class="form-label">Số điện thoại <span class="text-danger">*</span></label>
                                                <input class="form-control" type="text" name="txtdienthoai"
                                                    value="<?= htmlspecialchars($user["sodienthoai"]) ?>" required>
                                            </div>

                                            <div class="col-12">
                                                <label class="form-label">Thay đổi ảnh đại diện</label>
                                                <input class="form-control" type="file" name="fhinh" id="inputAvatar"
                                                    accept="image/*" onchange="previewAvatar(event)">
                                                <div class="form-text">Hỗ trợ các định dạng: JPG, PNG, GIF, WEBP. Ảnh xem trước bên trái sẽ tự động phản ánh khi bạn chọn file mới.</div>
                                            </div>
                                        </div>

                                        <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top">
                                            <a href="index.php" class="btn btn-light px-3">Hủy bỏ</a>
                                            <button type="submit" class="btn btn-primary px-4 fw-semibold">
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
        function previewAvatar(event) {
            const file = event.target.files[0];
            const preview = document.getElementById('avatarPreview');
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        }
    </script>
</body>

</html>