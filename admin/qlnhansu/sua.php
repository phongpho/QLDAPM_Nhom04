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
    <title>Sửa nhân viên - GymBro Admin</title>

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
                    <div class="row justify-content-center">
                        <div class="col-12 col-xl-10">
                            <div class="card shadow-sm border-0">
                                <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
                                    <h4 class="card-title text-primary mb-0 fw-bold">
                                        <i class="fa fa-user-edit me-2"></i>CẬP NHẬT THÔNG TIN NHÂN VIÊN
                                    </h4>
                                    <a href="index.php" class="btn btn-sm btn-outline-secondary">
                                        <i class="fa fa-arrow-left me-1"></i>Danh sách nhân sự
                                    </a>
                                </div>
                                <div class="card-body p-4">
                                    <?php if (isset($msg_error)): ?>
                                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                            <i class="fa fa-exclamation-triangle me-2"></i><?= $msg_error ?>
                                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                        </div>
                                    <?php endif; ?>

                                    <?php
                                    $hinhHienTai = (!empty($nhanvien["hinhanh"]) && file_exists("../../assets/images/users/" . $nhanvien["hinhanh"])) 
                                        ? "../../assets/images/users/" . $nhanvien["hinhanh"] 
                                        : "../../assets/images/users/user.png";
                                    ?>

                                    <form method="post" action="index.php" enctype="multipart/form-data">
                                        <input type="hidden" name="action" value="xlsua">
                                        <input type="hidden" name="txtid" value="<?= $nhanvien["id"] ?>">
                                        <input type="hidden" name="txthinhanhcu" value="<?= htmlspecialchars($nhanvien["hinhanh"] ?? "") ?>">

                                        <div class="row g-4">
                                            <!-- Cột bên trái: Form thông tin -->
                                            <div class="col-md-7 col-lg-8">
                                                <div class="mb-3">
                                                    <label class="form-label fw-semibold">Họ và tên <span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control" name="txthoten" value="<?= htmlspecialchars($nhanvien["hoten"]) ?>" required>
                                                </div>

                                                <div class="mb-3">
                                                    <label class="form-label fw-semibold">Email <span class="text-danger">*</span></label>
                                                    <input type="email" class="form-control" name="txtemail" value="<?= htmlspecialchars($nhanvien["email"]) ?>" required>
                                                </div>

                                                <div class="mb-3">
                                                    <label class="form-label fw-semibold">Số điện thoại <span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control" name="txtsodt" value="<?= htmlspecialchars($nhanvien["sodienthoai"]) ?>" required>
                                                </div>

                                                <div class="mb-3">
                                                    <label class="form-label fw-semibold">Trạng thái tài khoản</label>
                                                    <select class="form-select" name="opttrangthai">
                                                        <option value="1" <?= ($nhanvien["trangthai"] == 1) ? "selected" : "" ?>>Kích hoạt (Hoạt động)</option>
                                                        <option value="0" <?= ($nhanvien["trangthai"] == 0) ? "selected" : "" ?>>Khóa tài khoản</option>
                                                    </select>
                                                </div>

                                                <div class="mb-3">
                                                    <label class="form-label fw-semibold">Đổi mật khẩu mới</label>
                                                    <input type="password" class="form-control" name="txtmatkhaumoi" placeholder="Để trống nếu giữ nguyên mật khẩu cũ">
                                                    <small class="text-muted">Chỉ nhập nếu bạn muốn đặt lại mật khẩu cho nhân viên này.</small>
                                                </div>

                                                <div class="mb-3">
                                                    <label class="form-label fw-semibold">Chọn hình ảnh mới</label>
                                                    <input type="file" class="form-control" id="hinhanh" name="hinhanh" accept="image/*" onchange="previewImage(event)">
                                                    <small class="text-muted">Chọn ảnh mới nếu muốn thay đổi ảnh đại diện hiện tại.</small>
                                                </div>
                                            </div>

                                            <!-- Cột bên phải: Xem trước hình ảnh -->
                                            <div class="col-md-5 col-lg-4 d-flex flex-column align-items-center justify-content-center p-3 border-start text-center">
                                                <label class="form-label fw-semibold mb-3">Ảnh đại diện</label>
                                                <div class="border rounded-circle p-1 d-inline-block shadow-sm bg-light mb-2">
                                                    <img id="imgPreview" src="<?= $hinhHienTai ?>" 
                                                         class="rounded-circle" 
                                                         style="width: 150px; height: 150px; object-fit: cover;" 
                                                         alt="Ảnh đại diện">
                                                </div>
                                                <span class="text-muted small mt-2" id="fileNamePreview">Ảnh hiện tại</span>
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
    function previewImage(event) {
        const file = event.target.files[0];
        const preview = document.getElementById('imgPreview');
        const fileNameLabel = document.getElementById('fileNamePreview');

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
