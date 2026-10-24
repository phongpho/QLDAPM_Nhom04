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
                    <div class="dm-breadcrumb">
                        <a href="index.php"><i class="fa fa-layer-group me-1"></i>Quản lý danh mục</a>
                        <i class="fa fa-chevron-right" style="font-size: 0.65rem;"></i>
                        <span class="text-dark fw-semibold">Cập nhật: <?= htmlspecialchars($d["tendanhmuc"]) ?></span>
                    </div>

                    <div class="row justify-content-center">
                        <div class="col-12 col-xl-8">
                            <div class="dm-edit-card">
                                
                                <!-- Card Header -->
                                <div class="dm-edit-header d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-2">
                                    <div>
                                        <h2 class="dm-edit-title">Chỉnh sửa danh mục</h2>
                                        <p class="text-muted small mb-0">Cập nhật thông tin phân nhóm và cơ chế hiển thị trên website</p>
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

                                    <!-- Thông tin tóm tắt danh mục hiện hành -->
                                    <div class="dm-summary-box d-flex align-items-center justify-content-between gap-3 mb-4">
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="dm-icon-badge">
                                                <?php if ($d["loai"] === "quanao"): ?>
                                                    <i class="fa fa-shirt"></i>
                                                <?php elseif ($d["loai"] === "phukien"): ?>
                                                    <i class="fa fa-dumbbell"></i>
                                                <?php else: ?>
                                                    <i class="fa fa-flask"></i>
                                                <?php endif; ?>
                                            </div>
                                            <div>
                                                <div class="fw-bold text-dark fs-6"><?= htmlspecialchars($d["tendanhmuc"]) ?></div>
                                                <div class="text-muted small">
                                                    Đang liên kết với <strong class="text-primary"><?= (int)($soLuongSP ?? 0) ?> sản phẩm</strong> trong kho hàng.
                                                </div>
                                            </div>
                                        </div>
                                        <div class="text-end d-none d-sm-block">
                                            <?php if ((int)$d["trangthai"] === 1): ?>
                                                <span class="badge" style="background-color: #ecfdf5; color: #047857; border: 1px solid #a7f3d0;">
                                                    <i class="fa fa-eye me-1"></i>Đang hiển thị
                                                </span>
                                            <?php else: ?>
                                                <span class="badge" style="background-color: #fef2f2; color: #b91c1c; border: 1px solid #fecaca;">
                                                    <i class="fa fa-eye-slash me-1"></i>Đang tạm ẩn
                                                </span>
                                            <?php endif; ?>
                                        </div>
                                    </div>

                                    <!-- Form Edit -->
                                    <form action="index.php" method="POST">
                                        <input type="hidden" name="action" value="xlsua">
                                        <input type="hidden" name="txtid" value="<?= $d["id"] ?>">

                                        <!-- Tên danh mục -->
                                        <div class="mb-4">
                                            <label class="form-label">
                                                Tên danh mục <span class="text-danger">*</span>
                                            </label>
                                            <input type="text" name="txtten" class="form-control"
                                                value="<?= htmlspecialchars($d["tendanhmuc"]) ?>" required
                                                placeholder="Nhập tên danh mục...">
                                            <div class="form-text">Tên danh mục là duy nhất và hiển thị trực tiếp đến khách hàng.</div>
                                        </div>

                                        <!-- Nhóm hàng lớn -->
                                        <div class="mb-4">
                                            <label class="form-label">
                                                Nhóm hàng lớn <span class="text-danger">*</span>
                                            </label>
                                            <select name="optloai" class="form-select" required>
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
                                            <div class="form-text">Quyết định cấu trúc phân nhánh trên thanh menu và bộ lọc danh mục.</div>
                                        </div>

                                        <!-- Trạng thái hiển thị -->
                                        <div class="mb-4">
                                            <label class="form-label">
                                                Trạng thái hiển thị <span class="text-danger">*</span>
                                            </label>
                                            <div class="row g-3">
                                                <div class="col-sm-6">
                                                    <label class="dm-radio-card <?= ((int)$d["trangthai"] === 1) ? 'active' : '' ?>">
                                                        <input class="form-check-input mt-0" type="radio" name="opttrangthai" value="1" <?= ((int)$d["trangthai"] === 1) ? 'checked' : '' ?> required>
                                                        <div>
                                                            <div class="fw-semibold text-success small"><i class="fa fa-eye me-1"></i>Hiển thị trên website</div>
                                                            <div class="text-muted" style="font-size: 0.75rem;">Khách hàng có thể tìm kiếm và xem sản phẩm.</div>
                                                        </div>
                                                    </label>
                                                </div>
                                                <div class="col-sm-6">
                                                    <label class="dm-radio-card <?= ((int)$d["trangthai"] === 0) ? 'active' : '' ?>">
                                                        <input class="form-check-input mt-0" type="radio" name="opttrangthai" value="0" <?= ((int)$d["trangthai"] === 0) ? 'checked' : '' ?> required>
                                                        <div>
                                                            <div class="fw-semibold text-danger small"><i class="fa fa-eye-slash me-1"></i>Đang ẩn (Tạm khóa)</div>
                                                            <div class="text-muted" style="font-size: 0.75rem;">Ẩn danh mục và các sản phẩm bên trong khỏi website.</div>
                                                        </div>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Nút thao tác -->
                                        <div class="d-flex justify-content-between align-items-center pt-4 border-top mt-4">
                                            <a href="index.php" class="btn btn-light px-4 border text-secondary">
                                                Hủy bỏ
                                            </a>
                                            <button type="submit" class="btn btn-dm-primary px-4 shadow-sm">
                                                <i class="fa fa-check"></i> Lưu thay đổi
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

