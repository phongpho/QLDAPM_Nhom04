<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION["nguoidung"]) || !in_array((int)$_SESSION["nguoidung"]["loai"], [1, 2])) {
    header("Location: ../ktnguoidung/index.php?action=dangnhap");
    exit();
}

$thumbCurrent = (!empty($s["hinhanh"]) && file_exists("../../assets/images/products/" . $s["hinhanh"]))
    ? "../../assets/images/products/" . $s["hinhanh"]
    : "../../assets/images/products/product.png";

$muctieuList = !empty($s["muctieu"]) ? explode(',', $s["muctieu"]) : [];
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Cập nhật sản phẩm: <?= htmlspecialchars($s["tensanpham"]) ?> - GymBro Admin</title>

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
                        <a href="index.php"><i class="fa fa-boxes-stacked me-1"></i>Quản lý hàng hóa</a>
                        <i class="fa fa-chevron-right" style="font-size: 0.65rem;"></i>
                        <span class="text-dark fw-semibold">Cập nhật: <?= htmlspecialchars($s["tensanpham"]) ?></span>
                    </div>

                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
                        <div>
                            <h1 class="gb-title mb-1">Cập nhật sản phẩm #<?= $s["id"] ?></h1>
                            <p class="gb-subtitle mb-0">Chỉnh sửa giá niêm yết, số lượng tồn kho, hình ảnh và thuộc tính sản phẩm.</p>
                        </div>
                        <div>
                            <a href="index.php" class="btn btn-outline-secondary">
                                <i class="fa fa-arrow-left me-1"></i> Quay lại danh sách
                            </a>
                        </div>
                    </div>

                    <div class="gb-edit-card shadow-sm mb-4">
                        <div class="gb-edit-header">
                            <div class="d-flex align-items-center gap-2">
                                <div class="gb-icon-badge">
                                    <i class="fa fa-box-open"></i>
                                </div>
                                <div>
                                    <h5 class="gb-edit-title mb-0"><?= htmlspecialchars($s["tensanpham"]) ?></h5>
                                    <small class="text-muted">Mã định danh: #<?= $s["id"] ?> &bull; Ngày khởi tạo: <?= htmlspecialchars($s["ngaytao"] ?? "Mới") ?></small>
                                </div>
                            </div>
                        </div>

                        <div class="card-body p-4">
                            <!-- Summary Box thống kê chỉ số của sản phẩm -->
                            <div class="gb-summary-box mb-4">
                                <div class="row g-3 align-items-center">
                                    <div class="col-6 col-md-3">
                                        <div class="small text-muted">Lượt xem sản phẩm</div>
                                        <div class="fw-bold text-dark fs-5 mt-1">
                                            <i class="fa fa-eye text-primary me-1"></i> <?= number_format($s["luotxem"] ?? 0) ?>
                                        </div>
                                    </div>
                                    <div class="col-6 col-md-3">
                                        <div class="small text-muted">Lượt bán thành công</div>
                                        <div class="fw-bold text-dark fs-5 mt-1">
                                            <i class="fa fa-bag-shopping text-success me-1"></i> <?= number_format($s["luotmua"] ?? 0) ?>
                                        </div>
                                    </div>
                                    <div class="col-6 col-md-3">
                                        <div class="small text-muted">Tồn kho hiện tại</div>
                                        <div class="fw-bold fs-5 mt-1 <?= ((int)$s["soluongton"] <= 5) ? 'text-danger' : 'text-dark' ?>">
                                            <i class="fa fa-warehouse text-warning me-1"></i> <?= (int)$s["soluongton"] ?> đơn vị
                                        </div>
                                    </div>
                                    <div class="col-6 col-md-3">
                                        <div class="small text-muted">Trạng thái bán</div>
                                        <div class="mt-1">
                                            <?php if ((int)$s["trangthai"] === 1): ?>
                                                <span class="badge bg-success-subtle text-success border border-success-subtle">
                                                    <i class="fa fa-check-circle me-1"></i> Đang bán
                                                </span>
                                            <?php else: ?>
                                                <span class="badge bg-danger-subtle text-danger border border-danger-subtle">
                                                    <i class="fa fa-eye-slash me-1"></i> Tạm ẩn
                                                </span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <form action="index.php" method="POST" enctype="multipart/form-data">
                                <input type="hidden" name="action" value="xlsua">
                                <input type="hidden" name="txtid" value="<?= $s["id"] ?>">
                                <input type="hidden" name="txthinhanhcu" value="<?= htmlspecialchars($s["hinhanh"] ?? "") ?>">

                                <div class="row g-4">
                                    <!-- Cột trái: Thông tin thương mại -->
                                    <div class="col-12 col-lg-7">
                                        <div class="row g-3">
                                            <div class="col-12">
                                                <label class="form-label fw-semibold small text-secondary">
                                                    Tên sản phẩm <span class="text-danger">*</span>
                                                </label>
                                                <input type="text" name="txtten" class="form-control" value="<?= htmlspecialchars($s["tensanpham"]) ?>" required>
                                            </div>

                                            <div class="col-md-6">
                                                <label class="form-label fw-semibold small text-secondary">
                                                    Danh mục hàng hóa <span class="text-danger">*</span>
                                                </label>
                                                <select name="optdanhmuc" class="form-select" required>
                                                    <option value="">Chọn danh mục</option>
                                                    <?php if (!empty($danhmuc)): ?>
                                                        <?php foreach ($danhmuc as $dmItem): ?>
                                                            <option value="<?= $dmItem["id"] ?>" <?= ((int)$s["danhmuc_id"] === (int)$dmItem["id"]) ? 'selected' : '' ?>>
                                                                <?= htmlspecialchars($dmItem["tendanhmuc"]) ?> (<?= htmlspecialchars($dmItem["loai"]) ?>)
                                                            </option>
                                                        <?php endforeach; ?>
                                                    <?php endif; ?>
                                                </select>
                                            </div>

                                            <div class="col-md-6">
                                                <label class="form-label fw-semibold small text-secondary">
                                                    Thương hiệu
                                                </label>
                                                <select name="optthuonghieu" class="form-select">
                                                    <option value="">Không phân thương hiệu</option>
                                                    <?php if (!empty($thuonghieu)): ?>
                                                        <?php foreach ($thuonghieu as $thItem): ?>
                                                            <option value="<?= $thItem["id"] ?>" <?= ((int)($s["thuonghieu_id"] ?? 0) === (int)$thItem["id"]) ? 'selected' : '' ?>>
                                                                <?= htmlspecialchars($thItem["tenthuonghieu"]) ?>
                                                            </option>
                                                        <?php endforeach; ?>
                                                    <?php endif; ?>
                                                </select>
                                            </div>

                                            <div class="col-md-4">
                                                <label class="form-label fw-semibold small text-secondary">
                                                    Giá niêm yết (Giá gốc)
                                                </label>
                                                <div class="input-group">
                                                    <input type="number" name="txtgiagoc" class="form-control" value="<?= (float)$s["giagoc"] ?>" min="0" step="1000">
                                                    <span class="input-group-text bg-light text-muted small">đ</span>
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <label class="form-label fw-semibold small text-secondary">
                                                    Giá bán thực tế <span class="text-danger">*</span>
                                                </label>
                                                <div class="input-group">
                                                    <input type="number" name="txtgiaban" class="form-control" value="<?= (float)$s["giaban"] ?>" min="0" step="1000" required>
                                                    <span class="input-group-text bg-light text-muted small">đ</span>
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <label class="form-label fw-semibold small text-secondary">
                                                    Số lượng tồn kho <span class="text-danger">*</span>
                                                </label>
                                                <input type="number" name="txtsoluongton" class="form-control" value="<?= (int)$s["soluongton"] ?>" min="0" required>
                                            </div>

                                            <div class="col-12">
                                                <label class="form-label fw-semibold small text-secondary">
                                                    Mô tả sản phẩm
                                                </label>
                                                <textarea name="txtmota" class="form-control" rows="4"><?= htmlspecialchars($s["mota"] ?? "") ?></textarea>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Cột phải: Thuộc tính & Ảnh -->
                                    <div class="col-12 col-lg-5">
                                        <div class="p-3 bg-light rounded-3 border">
                                            <!-- Khung xem trước và đổi ảnh -->
                                            <div class="mb-3 text-center">
                                                <label class="form-label fw-semibold small text-secondary d-block text-start">
                                                    Ảnh đại diện sản phẩm
                                                </label>
                                                <div class="d-inline-block position-relative mb-2">
                                                    <img id="editProductThumb" src="<?= $thumbCurrent ?>" 
                                                         alt="<?= htmlspecialchars($s["tensanpham"]) ?>" 
                                                         class="rounded-3 border shadow-sm"
                                                         style="width: 140px; height: 140px; object-fit: cover; background: #ffffff;"
                                                         onerror="this.src='../../assets/images/users/user.png'">
                                                </div>
                                                <input type="file" name="fhinh" id="inputProductImgEdit" class="form-control form-control-sm" accept="image/*" onchange="previewEditThumb(event)">
                                                <div class="form-text small text-muted text-start mt-1">Để trống nếu muốn giữ nguyên ảnh hiện tại.</div>
                                            </div>

                                            <div class="row g-2 mb-3">
                                                <div class="col-6">
                                                    <label class="form-label fw-semibold small text-secondary">Đối tượng</label>
                                                    <select name="optdoituong" class="form-select form-select-sm">
                                                        <option value="unisex" <?= (($s["doituong"] ?? "") === "unisex") ? "selected" : "" ?>>Unisex</option>
                                                        <option value="nam" <?= (($s["doituong"] ?? "") === "nam") ? "selected" : "" ?>>Nam</option>
                                                        <option value="nu" <?= (($s["doituong"] ?? "") === "nu") ? "selected" : "" ?>>Nữ</option>
                                                    </select>
                                                </div>
                                                <div class="col-6">
                                                    <label class="form-label fw-semibold small text-secondary">Kích cỡ (Sizes)</label>
                                                    <input type="text" name="txtsizes" class="form-control form-control-sm" value="<?= htmlspecialchars($s["sizes"] ?? "") ?>" placeholder="S,M,L,XL">
                                                </div>
                                                <div class="col-6">
                                                    <label class="form-label fw-semibold small text-secondary">Màu sắc</label>
                                                    <input type="text" name="txtmausac" class="form-control form-control-sm" value="<?= htmlspecialchars($s["mausac"] ?? "") ?>" placeholder="Đen, Xám, Xanh">
                                                </div>
                                                <div class="col-6">
                                                    <label class="form-label fw-semibold small text-secondary">Hạn sử dụng</label>
                                                    <input type="date" name="txthansudung" class="form-control form-control-sm" value="<?= htmlspecialchars($s["hansudung"] ?? "") ?>">
                                                </div>
                                                <div class="col-6">
                                                    <label class="form-label fw-semibold small text-secondary">Chất liệu</label>
                                                    <input type="text" name="txtchatlieu" class="form-control form-control-sm" value="<?= htmlspecialchars($s["chatlieu"] ?? "") ?>" placeholder="Cotton, Spandex...">
                                                </div>
                                                <div class="col-6">
                                                    <label class="form-label fw-semibold small text-secondary">Thành phần</label>
                                                    <input type="text" name="txtthanhphan" class="form-control form-control-sm" value="<?= htmlspecialchars($s["thanhphan"] ?? "") ?>" placeholder="Whey Isolate, BCAA...">
                                                </div>
                                            </div>

                                            <!-- Mục tiêu tập luyện -->
                                            <div class="mb-3">
                                                <label class="form-label fw-semibold small text-secondary d-block">Mục tiêu tập luyện</label>
                                                <div class="d-flex flex-wrap gap-3">
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="checkbox" name="chkmuctieu[]" value="giamcan" id="editMtGiamCan" <?= in_array('giamcan', $muctieuList) ? 'checked' : '' ?>>
                                                        <label class="form-check-label small" for="editMtGiamCan">Giảm cân</label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="checkbox" name="chkmuctieu[]" value="tangcan" id="editMtTangCan" <?= in_array('tangcan', $muctieuList) ? 'checked' : '' ?>>
                                                        <label class="form-check-label small" for="editMtTangCan">Tăng cơ</label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="checkbox" name="chkmuctieu[]" value="duytri" id="editMtDuyTri" <?= in_array('duytri', $muctieuList) ? 'checked' : '' ?>>
                                                        <label class="form-check-label small" for="editMtDuyTri">Duy trì</label>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Bật/tắt trạng thái & nổi bật -->
                                            <div class="d-flex align-items-center justify-content-between pt-2 border-top">
                                                <div class="form-check form-switch mb-0">
                                                    <input class="form-check-input" type="checkbox" name="chknoibat" id="editChkNoiBat" value="1" <?= ((int)$s["noibat"] === 1) ? 'checked' : '' ?>>
                                                    <label class="form-check-label small fw-semibold" for="editChkNoiBat">Ghim nổi bật</label>
                                                </div>
                                                <div class="form-check form-switch mb-0">
                                                    <input class="form-check-input" type="checkbox" name="chktrangthai" id="editChkTrangThai" value="1" <?= ((int)$s["trangthai"] === 1) ? 'checked' : '' ?>>
                                                    <label class="form-check-label small fw-semibold text-success" for="editChkTrangThai">Mở bán trên website</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top">
                                    <a href="index.php" class="btn btn-light px-3">Hủy bỏ</a>
                                    <button type="submit" class="btn btn-gb-primary px-4">
                                        <i class="fa fa-save me-1"></i> Lưu thay đổi
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                </div>
            </main>

            <?php include('../inc/footer.php'); ?>
        </div>
    </div>

    <script>
        function previewEditThumb(event) {
            const file = event.target.files[0];
            const preview = document.getElementById('editProductThumb');
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
