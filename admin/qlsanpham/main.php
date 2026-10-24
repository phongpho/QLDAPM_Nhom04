<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Quản lý sản phẩm - GymBro Admin</title>

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
                                <h1 class="gb-title mb-0">Quản lý hàng hóa</h1>
                                <?php if (isset($_SESSION["nguoidung"]) && (int)$_SESSION["nguoidung"]["loai"] === 1): ?>
                                    <span class="badge bg-purple-subtle text-purple border border-purple-subtle" title="Tài khoản có toàn quyền quản trị và xóa sản phẩm">
                                        <i class="fa fa-shield-halved me-1"></i> Quản trị viên
                                    </span>
                                <?php else: ?>
                                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle" title="Nhân viên có quyền thêm, sửa và ẩn/hiện sản phẩm">
                                        <i class="fa fa-user-check me-1"></i> Nhân viên
                                    </span>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <button class="btn btn-gb-primary shadow-sm" type="button" data-bs-toggle="collapse" data-bs-target="#formThemSanPham" aria-expanded="false" aria-controls="formThemSanPham">
                                <i class="fa fa-plus me-1"></i> Thêm sản phẩm mới
                            </button>
                        </div>
                    </div>

                    <!-- Thống kê nhanh theo Card Metric -->
                    <div class="row g-3 mb-4">
                        <div class="col-6 col-xl-3">
                            <div class="gb-stat-card">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div>
                                        <div class="gb-stat-label">Tổng mặt hàng</div>
                                        <div class="gb-stat-number"><?= $tongSanPham ?? 0 ?></div>
                                        <div class="text-muted small mt-1">Sản phẩm trong hệ thống</div>
                                    </div>
                                    <div class="gb-icon-box" style="background-color: #eff6ff; color: #2563eb;">
                                        <i class="fa fa-boxes-stacked"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-6 col-xl-3">
                            <div class="gb-stat-card">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div>
                                        <div class="gb-stat-label">Cảnh báo tồn kho</div>
                                        <div class="gb-stat-number <?= ($demSapHetHang > 0) ? 'text-danger' : 'text-dark' ?>">
                                            <?= $demSapHetHang ?? 0 ?>
                                        </div>
                                        <div class="text-muted small mt-1">Tồn kho &le; 5 đơn vị</div>
                                    </div>
                                    <div class="gb-icon-box" style="background-color: #fffbeb; color: #d97706;">
                                        <i class="fa fa-triangle-exclamation"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-6 col-xl-3">
                            <div class="gb-stat-card">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div>
                                        <div class="gb-stat-label">Sản phẩm nổi bật</div>
                                        <div class="gb-stat-number"><?= $demNoiBat ?? 0 ?></div>
                                        <div class="text-muted small mt-1">Ghim trên trang chủ</div>
                                    </div>
                                    <div class="gb-icon-box" style="background-color: #f3e8ff; color: #7e22ce;">
                                        <i class="fa fa-star"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-6 col-xl-3">
                            <div class="gb-stat-card">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div>
                                        <div class="gb-stat-label">Đang tạm ẩn</div>
                                        <div class="gb-stat-number"><?= $demTamAn ?? 0 ?></div>
                                        <div class="text-muted small mt-1">Ngừng hiển thị bán</div>
                                    </div>
                                    <div class="gb-icon-box" style="background-color: #f8fafc; color: #64748b;">
                                        <i class="fa fa-eye-slash"></i>
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

                    <!-- Form Thêm mới sản phẩm (Dạng Collapse) -->
                    <div id="formThemSanPham" class="collapse gb-form-card mb-4">
                        <div class="gb-form-header d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center gap-2">
                                <div class="gb-icon-box" style="width: 32px; height: 32px; font-size: 0.95rem; background-color: #eff6ff; color: #2563eb;">
                                    <i class="fa fa-plus"></i>
                                </div>
                                <h5 class="fw-bold mb-0 text-dark" style="font-size: 1rem;">Khởi tạo sản phẩm mới</h5>
                            </div>
                            <button type="button" class="btn btn-sm btn-light border" data-bs-toggle="collapse" data-bs-target="#formThemSanPham" aria-label="Đóng form">
                                <i class="fa fa-times"></i>
                            </button>
                        </div>
                        <div class="p-4">
                            <form action="index.php" method="POST" enctype="multipart/form-data">
                                <input type="hidden" name="action" value="xlthem">

                                <div class="row g-4">
                                    <!-- Cột trái: Thông tin cơ bản & Giá -->
                                    <div class="col-12 col-lg-7">
                                        <div class="row g-3">
                                            <div class="col-12">
                                                <label class="form-label fw-semibold small text-secondary">
                                                    Tên sản phẩm <span class="text-danger">*</span>
                                                </label>
                                                <input type="text" name="txtten" class="form-control" placeholder="Ví dụ: Áo thun Oversize GymBro Pro, Dây kháng lực PowerBand..." required>
                                            </div>

                                            <div class="col-md-6">
                                                <label class="form-label fw-semibold small text-secondary">
                                                    Danh mục hàng hóa <span class="text-danger">*</span>
                                                </label>
                                                <select name="optdanhmuc" class="form-select" required>
                                                    <option value="">Chọn danh mục</option>
                                                    <?php if (!empty($danhmuc)): ?>
                                                        <?php foreach ($danhmuc as $dmItem): ?>
                                                            <option value="<?= $dmItem["id"] ?>">
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
                                                    <?php if (!empty($thuonghieu)): ?>
                                                        <?php foreach ($thuonghieu as $thItem): ?>
                                                            <option value="<?= $thItem["id"] ?>">
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
                                                    <input type="number" name="txtgiagoc" class="form-control" placeholder="0" min="0" step="1000">
                                                    <span class="input-group-text bg-light text-muted small">đ</span>
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <label class="form-label fw-semibold small text-secondary">
                                                    Giá bán thực tế <span class="text-danger">*</span>
                                                </label>
                                                <div class="input-group">
                                                    <input type="number" name="txtgiaban" class="form-control" placeholder="0" min="0" step="1000" required>
                                                    <span class="input-group-text bg-light text-muted small">đ</span>
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <label class="form-label fw-semibold small text-secondary">
                                                    Số lượng tồn kho <span class="text-danger">*</span>
                                                </label>
                                                <input type="number" name="txtsoluongton" class="form-control" value="10" min="0" required>
                                            </div>

                                            <div class="col-12">
                                                <label class="form-label fw-semibold small text-secondary">
                                                    Mô tả sản phẩm
                                                </label>
                                                <textarea name="txtmota" class="form-control" rows="3" placeholder="Mô tả công năng, chất liệu, hướng dẫn chọn kích cỡ..."></textarea>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Cột phải: Thuộc tính & Ảnh -->
                                    <div class="col-12 col-lg-5">
                                        <div class="p-3 bg-light rounded-3 border">
                                            <!-- Khung tải ảnh & preview -->
                                            <div class="mb-3 text-center">
                                                <label class="form-label fw-semibold small text-secondary d-block text-start">
                                                    Ảnh đại diện sản phẩm
                                                </label>
                                                <div class="d-inline-block position-relative mb-2">
                                                    <img id="newProductThumb" src="../../assets/images/products/product.png"
                                                        alt="Xem trước ảnh"
                                                        class="rounded-3 border shadow-sm"
                                                        style="width: 120px; height: 120px; object-fit: cover; background: #ffffff;"
                                                        onerror="this.src='../../assets/images/users/user.png'">
                                                </div>
                                                <input type="file" name="fhinh" id="inputProductImg" class="form-control form-control-sm" accept="image/*" onchange="previewThumb(event)">
                                                <div class="form-text small text-muted text-start mt-1">Định dạng hỗ trợ: JPG, PNG, WEBP.</div>
                                            </div>

                                            <div class="row g-2 mb-3">
                                                <div class="col-6">
                                                    <label class="form-label fw-semibold small text-secondary">Đối tượng</label>
                                                    <select name="optdoituong" class="form-select form-select-sm">
                                                        <option value="unisex" selected>Unisex (Cả nam & nữ)</option>
                                                        <option value="nam">Nam</option>
                                                        <option value="nu">Nữ</option>
                                                    </select>
                                                </div>
                                                <div class="col-6">
                                                    <label class="form-label fw-semibold small text-secondary">Kích cỡ (Sizes)</label>
                                                    <input type="text" name="txtsizes" class="form-control form-control-sm" placeholder="S,M,L,XL">
                                                </div>
                                                <div class="col-6">
                                                    <label class="form-label fw-semibold small text-secondary">Màu sắc</label>
                                                    <input type="text" name="txtmausac" class="form-control form-control-sm" placeholder="Đen, Xám, Xanh">
                                                </div>
                                                <div class="col-6">
                                                    <label class="form-label fw-semibold small text-secondary">Hạn sử dụng</label>
                                                    <input type="date" name="txthansudung" class="form-control form-control-sm">
                                                </div>
                                                <div class="col-6">
                                                    <label class="form-label fw-semibold small text-secondary">Chất liệu</label>
                                                    <input type="text" name="txtchatlieu" class="form-control form-control-sm" placeholder="Cotton, Spandex...">
                                                </div>
                                                <div class="col-6">
                                                    <label class="form-label fw-semibold small text-secondary">Thành phần</label>
                                                    <input type="text" name="txtthanhphan" class="form-control form-control-sm" placeholder="Whey Isolate, BCAA...">
                                                </div>
                                            </div>

                                            <!-- Mục tiêu tập luyện -->
                                            <div class="mb-3">
                                                <label class="form-label fw-semibold small text-secondary d-block">Mục tiêu tập luyện</label>
                                                <div class="d-flex flex-wrap gap-3">
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="checkbox" name="chkmuctieu[]" value="giamcan" id="mtGiamCan" checked>
                                                        <label class="form-check-label small" for="mtGiamCan">Giảm cân</label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="checkbox" name="chkmuctieu[]" value="tangcan" id="mtTangCan" checked>
                                                        <label class="form-check-label small" for="mtTangCan">Tăng cơ</label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="checkbox" name="chkmuctieu[]" value="duytri" id="mtDuyTri" checked>
                                                        <label class="form-check-label small" for="mtDuyTri">Duy trì</label>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Bật/tắt trạng thái & nổi bật -->
                                            <div class="d-flex align-items-center justify-content-between pt-2 border-top">
                                                <div class="form-check form-switch mb-0">
                                                    <input class="form-check-input" type="checkbox" name="chknoibat" id="chkNoiBat" value="1">
                                                    <label class="form-check-label small fw-semibold" for="chkNoiBat">Ghim nổi bật</label>
                                                </div>
                                                <div class="form-check form-switch mb-0">
                                                    <input class="form-check-input" type="checkbox" name="chktrangthai" id="chkTrangThai" value="1" checked>
                                                    <label class="form-check-label small fw-semibold text-success" for="chkTrangThai">Mở bán ngay</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top">
                                    <button type="button" class="btn btn-light px-3" data-bs-toggle="collapse" data-bs-target="#formThemSanPham">
                                        Đóng lại
                                    </button>
                                    <button type="submit" class="btn btn-gb-primary px-4">
                                        <i class="fa fa-save me-1"></i> Lưu sản phẩm
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Card Bảng Danh sách & Bộ lọc -->
                    <div class="gb-table-card">
                        <!-- Toolbar Bộ lọc chuyên sâu -->
                        <div class="gb-table-header">
                            <form method="GET" action="index.php" class="row g-2 align-items-center">
                                <div class="col-12 col-md-4">
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0 text-muted">
                                            <i class="fa fa-search"></i>
                                        </span>
                                        <input type="text" name="tukhoa" class="form-control border-start-0 ps-0" placeholder="Tìm theo tên sản phẩm hoặc mã ID..." value="<?= htmlspecialchars($filter_tukhoa ?? '') ?>">
                                    </div>
                                </div>

                                <div class="col-6 col-md-3">
                                    <select name="danhmuc" class="form-select" onchange="this.form.submit()">
                                        <option value="">Tất cả danh mục</option>
                                        <?php if (!empty($danhmuc)): ?>
                                            <?php foreach ($danhmuc as $dItem): ?>
                                                <option value="<?= $dItem["id"] ?>" <?= ((string)($filter_danhmuc ?? '') === (string)$dItem["id"]) ? 'selected' : '' ?>>
                                                    <?= htmlspecialchars($dItem["tendanhmuc"]) ?>
                                                </option>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </select>
                                </div>

                                <div class="col-6 col-md-2">
                                    <select name="tonkho" class="form-select" onchange="this.form.submit()">
                                        <option value="">Tất cả kho</option>
                                        <option value="conhang" <?= (($filter_tonkho ?? '') === 'conhang') ? 'selected' : '' ?>>Còn hàng (>5)</option>
                                        <option value="saphethang" <?= (($filter_tonkho ?? '') === 'saphethang') ? 'selected' : '' ?>>Sắp hết (&le;5)</option>
                                        <option value="hethang" <?= (($filter_tonkho ?? '') === 'hethang') ? 'selected' : '' ?>>Hết hàng (0)</option>
                                    </select>
                                </div>

                                <div class="col-6 col-md-2">
                                    <select name="trangthai" class="form-select" onchange="this.form.submit()">
                                        <option value="">Tất cả trạng thái</option>
                                        <option value="1" <?= (($filter_trangthai ?? '') === '1') ? 'selected' : '' ?>>Đang bán</option>
                                        <option value="0" <?= (($filter_trangthai ?? '') === '0') ? 'selected' : '' ?>>Đang tạm ẩn</option>
                                    </select>
                                </div>

                                <div class="col-6 col-md-1 text-end">
                                    <?php if (!empty($filter_tukhoa) || ($filter_danhmuc ?? '') !== '' || !empty($filter_tonkho) || ($filter_trangthai ?? '') !== '' || ($filter_noibat ?? '') !== ''): ?>
                                        <a href="index.php" class="btn btn-outline-secondary w-100" title="Đặt lại bộ lọc về mặc định">
                                            <i class="fa fa-rotate-left"></i>
                                        </a>
                                    <?php else: ?>
                                        <button type="submit" class="btn btn-light border w-100 fw-semibold text-secondary" title="Áp dụng bộ lọc">
                                            <i class="fa fa-filter"></i>
                                        </button>
                                    <?php endif; ?>
                                </div>
                            </form>
                        </div>

                        <!-- Data Table -->
                        <div class="table-responsive">
                            <table class="gb-table">
                                <thead>
                                    <tr>
                                        <th style="width: 50px;" class="text-center">STT</th>
                                        <th>Sản phẩm</th>
                                        <th>Phân loại danh mục</th>
                                        <th>Giá bán / Giá gốc</th>
                                        <th class="text-center">Tồn kho</th>
                                        <th class="text-center" style="width: 80px;">Nổi bật</th>
                                        <th class="text-center" style="width: 140px;">Trạng thái</th>
                                        <th class="text-center" style="width: 110px;">Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $danhsach = $danhsachHienThi ?? [];
                                    ?>
                                    <?php if (!empty($danhsach)): ?>
                                        <?php $stt = 1;
                                        foreach ($danhsach as $p): ?>
                                            <?php
                                            $thumbImg = (!empty($p["hinhanh"]) && file_exists("../../assets/images/products/" . $p["hinhanh"]))
                                                ? "../../assets/images/products/" . $p["hinhanh"]
                                                : "../../assets/images/products/product.png";

                                            $loaiDm = $p["loai_danhmuc"] ?? "quanao";
                                            $badgeLoaiClass = "dm-badge-quanao";
                                            if ($loaiDm === "phukien") $badgeLoaiClass = "dm-badge-phukien";
                                            elseif ($loaiDm === "tpbs") $badgeLoaiClass = "dm-badge-tpbs";

                                            $tonKho = (int)$p["soluongton"];
                                            $tonKhoClass = "text-success bg-success-subtle border-success-subtle";
                                            if ($tonKho <= 0) {
                                                $tonKhoClass = "text-danger bg-danger-subtle border-danger-subtle";
                                            } elseif ($tonKho <= 5) {
                                                $tonKhoClass = "text-warning-emphasis bg-warning-subtle border-warning-subtle";
                                            }
                                            ?>
                                            <tr>
                                                <td class="text-center text-muted fw-semibold small">
                                                    <?= $stt++ ?>
                                                </td>

                                                <td>
                                                    <div class="d-flex align-items-center gap-3">
                                                        <img src="<?= $thumbImg ?>" class="gb-product-thumb" alt="<?= htmlspecialchars($p["tensanpham"]) ?>" onerror="this.src='../../assets/images/users/user.png'">
                                                        <div>
                                                            <div class="fw-bold text-dark fs-6">
                                                                <?= htmlspecialchars($p["tensanpham"]) ?>
                                                            </div>
                                                            <div class="small text-muted mt-1 d-flex align-items-center gap-2">
                                                                <span>Mã: #<?= $p["id"] ?></span>
                                                                <?php if (!empty($p["tenthuonghieu"])): ?>
                                                                    <span>&bull;</span>
                                                                    <span class="text-secondary"><?= htmlspecialchars($p["tenthuonghieu"]) ?></span>
                                                                <?php endif; ?>
                                                                <?php if (!empty($p["doituong"])): ?>
                                                                    <span>&bull;</span>
                                                                    <span class="text-uppercase" style="font-size: 0.7rem;"><?= htmlspecialchars($p["doituong"]) ?></span>
                                                                <?php endif; ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>

                                                <td>
                                                    <span class="dm-badge <?= $badgeLoaiClass ?>">
                                                        <?= htmlspecialchars($p["tendanhmuc"] ?? "Chưa phân loại") ?>
                                                    </span>
                                                </td>

                                                <td>
                                                    <div class="fw-bold text-dark">
                                                        <?= number_format($p["giaban"], 0, ',', '.') ?> đ
                                                    </div>
                                                    <?php if ((float)$p["giagoc"] > (float)$p["giaban"]): ?>
                                                        <div class="small text-muted text-decoration-line-through">
                                                            <?= number_format($p["giagoc"], 0, ',', '.') ?> đ
                                                        </div>
                                                    <?php endif; ?>
                                                </td>

                                                <td class="text-center">
                                                    <span class="badge border px-2 py-1 <?= $tonKhoClass ?>">
                                                        <?= $tonKho ?>
                                                    </span>
                                                </td>

                                                <td class="text-center">
                                                    <?php if ((int)$p["noibat"] === 1): ?>
                                                        <a href="index.php?action=doinoibat&id=<?= $p["id"] ?>&noibat=0"
                                                            class="btn-toggle-featured featured-active"
                                                            title="Bấm để bỏ ghim nổi bật">
                                                            <i class="fa fa-star"></i>
                                                        </a>
                                                    <?php else: ?>
                                                        <a href="index.php?action=doinoibat&id=<?= $p["id"] ?>&noibat=1"
                                                            class="btn-toggle-featured featured-inactive"
                                                            title="Bấm để ghim vào mục nổi bật">
                                                            <i class="fa fa-star"></i>
                                                        </a>
                                                    <?php endif; ?>
                                                </td>

                                                <td class="text-center">
                                                    <?php if ((int)$p["trangthai"] === 1): ?>
                                                        <a href="index.php?action=doitrangthai&id=<?= $p["id"] ?>&trangthai=0"
                                                            class="btn-toggle-status status-active"
                                                            title="Bấm để tạm ẩn khỏi website bán hàng">
                                                            <i class="fa fa-eye"></i> Đang bán
                                                        </a>
                                                    <?php else: ?>
                                                        <a href="index.php?action=doitrangthai&id=<?= $p["id"] ?>&trangthai=1"
                                                            class="btn-toggle-status status-inactive"
                                                            title="Bấm để mở bán lại trên website">
                                                            <i class="fa fa-eye-slash"></i> Tạm ẩn
                                                        </a>
                                                    <?php endif; ?>
                                                </td>

                                                <td class="text-center">
                                                    <div class="d-inline-flex align-items-center gap-1">
                                                        <!-- Nút Sửa -->
                                                        <a href="index.php?action=sua&id=<?= $p["id"] ?>"
                                                            class="btn-action-icon btn-action-edit"
                                                            title="Chỉnh sửa chi tiết sản phẩm">
                                                            <i class="fa fa-pen"></i>
                                                        </a>

                                                        <!-- Nút Xóa: Chỉ dành cho Admin -->
                                                        <?php if (isset($_SESSION["nguoidung"]) && (int)$_SESSION["nguoidung"]["loai"] === 1): ?>
                                                            <a href="index.php?action=xoa&id=<?= $p["id"] ?>"
                                                                class="btn-action-icon btn-action-delete"
                                                                title="Xóa sản phẩm khỏi hệ thống"
                                                                onclick="return confirm('Bạn có chắc chắn muốn XÓA sản phẩm <?= htmlspecialchars($p['tensanpham']) ?>? Thao tác này sẽ xóa vĩnh viễn nếu chưa phát sinh đơn hàng.');">
                                                                <i class="fa fa-trash"></i>
                                                            </a>
                                                        <?php else: ?>
                                                            <span class="btn-action-icon btn-action-disabled" title="Nhân viên không có quyền xóa sản phẩm. Hãy dùng nút Tạm ẩn">
                                                                <i class="fa fa-lock"></i>
                                                            </span>
                                                        <?php endif; ?>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="8" class="text-center py-5">
                                                <div class="dm-empty-state">
                                                    <div class="dm-empty-icon">
                                                        <i class="fa fa-box-open"></i>
                                                    </div>
                                                    <h5 class="fw-bold text-dark">Chưa tìm thấy sản phẩm phù hợp</h5>
                                                    <p class="text-muted small mb-3">
                                                        Không có sản phẩm nào khớp với bộ lọc tìm kiếm hiện tại.
                                                    </p>
                                                    <a href="index.php" class="btn btn-outline-secondary btn-sm">
                                                        <i class="fa fa-rotate-left me-1"></i> Bỏ bộ lọc và xem tất cả
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
            </main>

            <?php include('../inc/footer.php'); ?>
        </div>
    </div>

    <script>
        function previewThumb(event) {
            const file = event.target.files[0];
            const preview = document.getElementById('newProductThumb');
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