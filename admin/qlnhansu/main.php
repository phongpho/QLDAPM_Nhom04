<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Quản lý nhân sự - GymBro Admin</title>
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&display=swap" rel="stylesheet">
    <link href="../inc/css/app.css" rel="stylesheet">
    <script src="../inc/js/app.js"></script>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome 6 -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">
    <!-- Design System -->
    <link href="../assets/css/main.css" rel="stylesheet">
</head>

<body>
    <div class="wrapper">
        <?php include('../inc/sidebar.php'); ?>

        <div class="main">
            <?php include('../inc/header.php'); ?>

            <main class="content">
                <div class="container-fluid p-0">
                    <div>
                        <h4>Trang quản trị nhân sự</h4>
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th><a class="text-decoration-none" href="?sort=hinhanh">Hình ảnh</a></th>
                                    <th><a class="text-decoration-none" href="?sort=hoten">Họ và tên</a></th>
                                    <th><a class="text-decoration-none" href="?sort=email">Email</a></th>
                                    <th><a class="text-decoration-none" href="?sort=sodienthoai">Số điện thoại</a></th>
                                    <th><a class="text-decoration-none" href="?sort=trangthai">Trạng thái</a></th>
                                    <th class="text-center">Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($nguoidung as $nd): ?>
                                    <?php if ($nd["loai"] == 2): ?>
                                        <tr>
                                            <td class="d-flex justify-content-center align-items-center" style="height: 70px;">
                                                <?php
                                                if (!empty($nd["hinhanh"]) && file_exists("../../assets/images/users/" . $nd["hinhanh"])) {
                                                    $hinh = "../../assets/images/users/" . $nd["hinhanh"];
                                                } else {
                                                    $hinh = "../../assets/images/users/user.png";
                                                }
                                                ?>
                                                <img src="<?php echo $hinh; ?>" width="45" height="45" class="rounded-circle" style="object-fit: cover;" alt="<?php echo htmlspecialchars($nd["hoten"]); ?>">
                                            </td>
                                            <td><?php echo htmlspecialchars($nd["hoten"]); ?></td>
                                            <td><?php echo htmlspecialchars($nd["email"]); ?></td>
                                            <td><?php echo htmlspecialchars($nd["sodienthoai"]); ?></td>
                                            <td>
                                                <?php if ($nd["trangthai"] == 1): ?>
                                                    <a href="index.php?action=khoa&id=<?= $nd["id"] ?>&trangthai=0"
                                                        class="btn btn-sm btn-outline-success"
                                                        title="Bấm để khóa tài khoản">
                                                        <i class="fa fa-toggle-on text-success me-1"></i>Kích hoạt
                                                    </a>
                                                <?php else: ?>
                                                    <a href="index.php?action=khoa&id=<?= $nd["id"] ?>&trangthai=1"
                                                        class="btn btn-sm btn-outline-danger"
                                                        title="Bấm để mở khóa tài khoản">
                                                        <i class="fa fa-toggle-off text-danger me-1"></i>Đã khóa
                                                    </a>
                                                <?php endif; ?>
                                            </td>
                                            <td class="text-center">
                                                <a href="index.php?action=sua&id=<?= $nd["id"] ?>" class="btn btn-sm btn-warning me-1" title="Sửa thông tin">
                                                    <i class="fa fa-pen me-1"></i>Sửa
                                                </a>
                                                <a href="index.php?action=xoa&id=<?= $nd["id"] ?>" class="btn btn-sm btn-danger" title="Xóa nhân viên"
                                                    onclick="return confirm('Bạn có chắc chắn muốn XÓA nhân viên <?= htmlspecialchars($nd['hoten']) ?> khỏi hệ thống?');">
                                                    <i class="fa fa-trash me-1"></i>Xóa
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </tbody>
                        </table>

                        <button class="btn btn-primary my-3" data-bs-toggle="collapse" data-bs-target="#demo">
                            <i class="fa fa-user-plus me-1"></i> Thêm nhân viên
                        </button>

                        <div id="demo" class="collapse <?php if (isset($msg_error)) echo 'show'; ?> card shadow-sm mb-4">
                            <div class="card-body">
                                <h5 class="card-title fw-bold text-primary mb-3">Thêm mới nhân viên</h5>
                                <?php if (isset($msg_error)): ?>
                                    <div class="alert alert-danger text-center mb-3"><?= $msg_error ?></div>
                                <?php endif; ?>

                                <form method="post" action="index.php" enctype="multipart/form-data" onsubmit="return validateForm();">
                                    <input type="hidden" name="action" value="xlthem">

                                    <div class="row g-4">
                                        <!-- Form nhập liệu (bên trái) -->
                                        <div class="col-md-7 col-lg-8">
                                            <div class="mb-3">
                                                <label class="form-label fw-semibold">Email <span class="text-danger">*</span></label>
                                                <input type="email" class="form-control" id="txtemail" name="txtemail" placeholder="nhapemail@domain.com" required>
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label fw-semibold">Mật khẩu <span class="text-danger">*</span></label>
                                                <input type="password" class="form-control" id="txtmatkhau" name="txtmatkhau" placeholder="Nhập mật khẩu khởi tạo" required>
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label fw-semibold">Số điện thoại <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" id="txtsodt" name="txtsodt" placeholder="Nhập số điện thoại" required>
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label fw-semibold">Họ tên <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" id="txthoten" name="txthoten" placeholder="Nhập họ và tên đầy đủ" required>
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label fw-semibold">Loại quyền <span class="text-danger">*</span></label>
                                                <select class="form-select" name="optloai">
                                                    <option value="2" selected>Nhân viên</option>
                                                    <option value="1">Quản trị viên</option>
                                                </select>
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label fw-semibold">Hình ảnh nhân viên</label>
                                                <input type="file" class="form-control" id="hinhanh" name="hinhanh" accept="image/*" onchange="previewImage(event)">
                                                <small class="text-muted">Hỗ trợ các định dạng: JPG, PNG, GIF, WEBP</small>
                                            </div>
                                        </div>

                                        <!-- Khung xem trước ảnh (bên phải) -->
                                        <div class="col-md-5 col-lg-4 d-flex flex-column align-items-center justify-content-center p-3 border-start text-center">
                                            <label class="form-label fw-semibold mb-3">Xem trước hình ảnh</label>
                                            <div class="border rounded-circle p-1 d-inline-block shadow-sm bg-light mb-2">
                                                <img id="imgPreview" src="../../assets/images/users/user.png"
                                                    class="rounded-circle"
                                                    style="width: 140px; height: 140px; object-fit: cover;"
                                                    alt="Xem trước ảnh">
                                            </div>
                                            <span class="text-muted small mt-2" id="fileNamePreview">Chưa chọn ảnh tải lên</span>
                                        </div>
                                    </div>

                                    <div class="text-center mt-4">
                                        <input type="submit" class="btn btn-primary me-2 px-4" value="Thêm mới">
                                        <a href="index.php" class="btn btn-secondary px-4">Hủy bỏ</a>
                                    </div>
                                </form>
                            </div>
                        </div>

                    </div>
                </div>
            </main>

            <?php include('../inc/footer.php'); ?>
        </div>
    </div>

    <script>
        // Hàm xem trước hình ảnh khi người dùng chọn từ máy tính
        function previewImage(event) {
            const file = event.target.files[0];
            const preview = document.getElementById('imgPreview');
            const fileNameLabel = document.getElementById('fileNamePreview');

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

        function validateForm() {
            return true;
        }
    </script>
</body>

</html>