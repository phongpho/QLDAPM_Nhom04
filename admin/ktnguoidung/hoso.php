<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION["nguoidung"])) {
    header("Location: index.php?action=dangnhap");
    exit();
}

$user = $_SESSION["nguoidung"];
$loaiNguoiDung = ($user["loai"] == 1) ? "Quản trị viên" : (($user["loai"] == 2) ? "Nhân viên" : "Khách hàng");
$badgeClass = ($user["loai"] == 1) ? "bg-danger" : "bg-primary";

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

    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&display=swap" rel="stylesheet">
    <link href="../inc/css/app.css" rel="stylesheet">
    <script src="../inc/js/app.js"></script>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome 6 -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">
</head>

<body>
    <div class="wrapper">
        <?php include('../inc/sidebar.php'); ?>

        <div class="main">
            <?php include('../inc/header.php'); ?>

            <main class="content">
                <div class="container-fluid p-0">
                    <div class="mb-3 d-flex justify-content-between align-items-center">
                        <h1 class="h3 mb-0 fw-bold text-dark">Hồ sơ cá nhân</h1>
                        <a href="index.php" class="btn btn-sm btn-outline-secondary">
                            <i class="fa fa-arrow-left me-1"></i>Trang quản trị
                        </a>
                    </div>

                    <div class="row g-4">
                        <div class="col-12 col-md-5 col-xl-4">
                            <div class="card shadow-sm border-0 mb-3">
                                <div class="card-header bg-white border-bottom text-center pt-4 pb-0">
                                    <div class="position-relative d-inline-block">
                                        <img id="avatarPreview" src="<?= $imgSrc ?>"
                                            alt="<?= htmlspecialchars($user["hoten"]) ?>"
                                            class="rounded-circle img-thumbnail shadow-sm"
                                            style="width: 130px; height: 130px; object-fit: cover;">
                                        <span class="position-absolute bottom-0 end-0 bg-success border border-white rounded-circle p-2"
                                            title="Tài khoản đang hoạt động" style="transform: translate(-10%, -10%);"></span>
                                    </div>
                                    <h5 class="card-title mt-3 mb-1 fw-bold text-dark"><?= htmlspecialchars($user["hoten"]) ?></h5>
                                    <div class="mb-3">
                                        <span class="badge <?= $badgeClass ?> px-3 py-1"><?= $loaiNguoiDung ?></span>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <h6 class="card-subtitle text-muted mb-3 fw-semibold">Thông tin liên hệ</h6>
                                    <ul class="list-unstyled mb-0">
                                        <li class="mb-2 d-flex align-items-center">
                                            <i class="fa fa-envelope text-primary me-2" style="width: 20px;"></i>
                                            <span class="text-muted small"><?= htmlspecialchars($user["email"]) ?></span>
                                        </li>
                                        <li class="mb-2 d-flex align-items-center">
                                            <i class="fa fa-phone text-primary me-2" style="width: 20px;"></i>
                                            <span class="text-muted small"><?= htmlspecialchars($user["sodienthoai"]) ?></span>
                                        </li>
                                        <li class="mb-2 d-flex align-items-center">
                                            <i class="fa fa-shield-alt text-success me-2" style="width: 20px;"></i>
                                            <span class="text-success small fw-semibold">Trạng thái: Hoạt động</span>
                                        </li>
                                    </ul>
                                    <hr class="my-3">
                                    <div class="d-grid">
                                        <a href="index.php?action=matkhau" class="btn btn-outline-primary btn-sm">
                                            <i class="fa fa-key me-1"></i>Đổi mật khẩu
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 col-md-7 col-xl-8">
                            <div class="card shadow-sm border-0">
                                <div class="card-header bg-white border-bottom py-3">
                                    <h5 class="card-title mb-0 fw-bold text-primary">
                                        <i class="fa fa-user-edit me-2"></i>Cập nhật thông tin tài khoản
                                    </h5>
                                </div>
                                <div class="card-body p-4">
                                    <?php if (isset($msg_error)): ?>
                                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                            <i class="fa fa-exclamation-triangle me-2"></i><?= $msg_error ?>
                                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                        </div>
                                    <?php endif; ?>

                                    <?php if (isset($msg_success)): ?>
                                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                                            <i class="fa fa-check-circle me-2"></i><?= $msg_success ?>
                                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                        </div>
                                    <?php endif; ?>

                                    <form method="post" enctype="multipart/form-data" action="index.php">
                                        <input type="hidden" name="action" value="xlhoso">
                                        <input type="hidden" name="txtid" value="<?= $user["id"] ?>">
                                        <input type="hidden" name="txthinhanh" value="<?= htmlspecialchars($user["hinhanh"] ?? "") ?>">

                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <label class="form-label fw-semibold">Họ và tên <span class="text-danger">*</span></label>
                                                <input class="form-control" type="text" name="txthoten"
                                                    value="<?= htmlspecialchars($user["hoten"]) ?>" required>
                                            </div>

                                            <div class="col-md-6">
                                                <label class="form-label fw-semibold">Vai trò hệ thống</label>
                                                <input class="form-control bg-light" type="text"
                                                    value="<?= $loaiNguoiDung ?>" readonly disabled>
                                            </div>

                                            <div class="col-md-6">
                                                <label class="form-label fw-semibold">Email <span class="text-danger">*</span></label>
                                                <input class="form-control" type="email" name="txtemail"
                                                    value="<?= htmlspecialchars($user["email"]) ?>" required>
                                            </div>

                                            <div class="col-md-6">
                                                <label class="form-label fw-semibold">Số điện thoại <span class="text-danger">*</span></label>
                                                <input class="form-control" type="text" name="txtdienthoai"
                                                    value="<?= htmlspecialchars($user["sodienthoai"]) ?>" required>
                                            </div>

                                            <div class="col-12">
                                                <label class="form-label fw-semibold">Thay đổi ảnh đại diện</label>
                                                <input class="form-control" type="file" name="fhinh" id="inputAvatar"
                                                    accept="image/*" onchange="previewAvatar(event)">
                                                <small class="text-muted">Định dạng hỗ trợ: JPG, PNG, GIF, WEBP. Khi chọn ảnh mới, khung ảnh bên trái sẽ tự động cập nhật xem trước.</small>
                                            </div>
                                        </div>

                                        <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top">
                                            <a href="index.php" class="btn btn-secondary px-4">Hủy bỏ</a>
                                            <button type="submit" class="btn btn-primary px-4">
                                                <i class="fa fa-save me-1"></i>Lưu thay đổi
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