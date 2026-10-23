<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Kiểm tra quyền: Admin (1) hoặc Nhân viên (2)
if (!isset($_SESSION["nguoidung"]) || !in_array($_SESSION["nguoidung"]["loai"], [1, 2])) {
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
    <title>Chỉnh sửa danh mục - GymBro Admin</title>

    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
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
                        <div class="col-12 col-xl-8">
                            <div class="card shadow-sm border-0">
                                <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
                                    <h4 class="card-title text-primary mb-0 fw-bold">
                                        <i class="fa fa-pen-to-square me-2"></i>CẬP NHẬT THÔNG TIN DANH MỤC
                                    </h4>
                                    <a href="index.php" class="btn btn-sm btn-outline-secondary">
                                        <i class="fa fa-arrow-left me-1"></i> Quay lại danh sách
                                    </a>
                                </div>
                                <div class="card-body p-4">

                                    <?php if (isset($_SESSION["loi"])): ?>
                                        <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                                            <i class="fa fa-exclamation-triangle me-2"></i><?= $_SESSION["loi"] ?>
                                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                        </div>
                                        <?php unset($_SESSION["loi"]); ?>
                                    <?php endif; ?>

                                    <!-- Thông tin tóm tắt danh mục -->
                                    <div class="alert alert-info bg-info bg-opacity-10 border-info border-opacity-25 d-flex align-items-center mb-4">
                                        <i class="fa fa-circle-info text-info fa-2x me-3"></i>
                                        <div>
                                            <div class="fw-bold fs-6"><?= htmlspecialchars($d["tendanhmuc"]) ?></div>
                                            <small class="text-muted">
                                                Danh mục này đang liên kết với <strong><?= (int)($soLuongSP ?? 0) ?></strong> sản phẩm trong cửa hàng.
                                            </small>
                                        </div>
                                    </div>

                                    <form action="index.php" method="POST">
                                        <input type="hidden" name="action" value="xlsua">
                                        <input type="hidden" name="txtid" value="<?= $d["id"] ?>">

                                        <div class="mb-3">
                                            <label class="form-label fw-semibold">Tên danh mục <span class="text-danger">*</span></label>
                                            <input type="text" name="txtten" class="form-control form-control-lg"
                                                value="<?= htmlspecialchars($d["tendanhmuc"]) ?>" required
                                                placeholder="Nhập tên danh mục...">
                                            <div class="form-text">Tên danh mục không được trùng với các danh mục khác.</div>
                                        </div>

                                        <div class="row g-3 mb-4">
                                            <div class="col-md-6">
                                                <label class="form-label fw-semibold">Nhóm hàng lớn <span class="text-danger">*</span></label>
                                                <select name="optloai" class="form-select form-select-lg" required>
                                                    <option value="quanao" <?= ($d["loai"] === "quanao") ? "selected" : "" ?>>
                                                        Quần áo thể thao (quanao)
                                                    </option>
                                                    <option value="phukien" <?= ($d["loai"] === "phukien") ? "selected" : "" ?>>
                                                        Phụ kiện tập luyện (phukien)
                                                    </option>
                                                    <option value="tpbs" <?= ($d["loai"] === "tpbs") ? "selected" : "" ?>>
                                                        Thực phẩm bổ sung (tpbs)
                                                    </option>
                                                </select>
                                                <div class="form-text">Quyết định vị trí xuất hiện trên thanh menu.</div>
                                            </div>

                                            <div class="col-md-6">
                                                <label class="form-label fw-semibold">Trạng thái hiển thị <span class="text-danger">*</span></label>
                                                <select name="opttrangthai" class="form-select form-select-lg" required>
                                                    <option value="1" <?= ((int)$d["trangthai"] === 1) ? "selected" : "" ?>>
                                                        Hiển thị trên website
                                                    </option>
                                                    <option value="0" <?= ((int)$d["trangthai"] === 0) ? "selected" : "" ?>>
                                                        Đang ẩn (Tạm khóa)
                                                    </option>
                                                </select>
                                                <div class="form-text">Khi ẩn, khách hàng sẽ không thấy danh mục này ngoài trang chủ.</div>
                                            </div>
                                        </div>

                                        <div class="d-flex justify-content-end gap-2 pt-3 border-top">
                                            <a href="index.php" class="btn btn-light px-4">Hủy bỏ</a>
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
</body>

</html>
