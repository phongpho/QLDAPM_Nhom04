<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Quản lý danh mục - GymBro Admin</title>

    <!-- Google Fonts: Plus Jakarta Sans for modern administrative craft -->
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
                                <h1 class="dm-title mb-0">Quản lý danh mục</h1>
                                <?php if (isset($_SESSION["nguoidung"]) && $_SESSION["nguoidung"]["loai"] == 1): ?>
                                    <span class="dm-role-pill dm-role-admin" title="Tài khoản có toàn quyền quản trị và xóa danh mục">
                                        <i class="fa fa-shield-halved"></i> Quản trị viên
                                    </span>
                                <?php else: ?>
                                    <span class="dm-role-pill dm-role-staff" title="Nhân viên có quyền thêm, sửa và ẩn/hiện danh mục">
                                        <i class="fa fa-user-check"></i> Nhân viên
                                    </span>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <button class="btn btn-dm-primary shadow-sm" type="button" data-bs-toggle="collapse" data-bs-target="#formThemDanhMuc" aria-expanded="false" aria-controls="formThemDanhMuc">
                                <i class="fa fa-plus-circle"></i> Thêm danh mục mới
                            </button>
                        </div>
                    </div>

                    <!-- Thống kê nhanh theo Card Metric (Rhythm & Scannable density) -->
                    <div class="row g-3 mb-4">
                        <div class="col-6 col-xl-3">
                            <div class="dm-stat-card">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div>
                                        <div class="dm-stat-label">Tổng danh mục</div>
                                        <div class="dm-stat-number"><?= $tongDanhmuc ?></div>
                                        <div class="text-muted small mt-1">
                                            <span class="text-success fw-semibold"><?= $demHien ?> hiển thị</span> · <span class="text-secondary"><?= $demAn ?> đã ẩn</span>
                                        </div>
                                    </div>
                                    <div class="dm-icon-box" style="background-color: #eff6ff; color: #2563eb;">
                                        <i class="fa fa-layer-group"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-6 col-xl-3">
                            <div class="dm-stat-card">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div>
                                        <div class="dm-stat-label">Quần áo thể thao</div>
                                        <div class="dm-stat-number"><?= $demQuanao ?></div>
                                        <div class="text-muted small mt-1">Nhóm mã: <code>quanao</code></div>
                                    </div>
                                    <div class="dm-icon-box" style="background-color: #f0fdf4; color: #16a34a;">
                                        <i class="fa fa-shirt"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-6 col-xl-3">
                            <div class="dm-stat-card">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div>
                                        <div class="dm-stat-label">Phụ kiện tập luyện</div>
                                        <div class="dm-stat-number"><?= $demPhukien ?></div>
                                        <div class="text-muted small mt-1">Nhóm mã: <code>phukien</code></div>
                                    </div>
                                    <div class="dm-icon-box" style="background-color: #fff7ed; color: #ea580c;">
                                        <i class="fa fa-dumbbell"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-6 col-xl-3">
                            <div class="dm-stat-card">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div>
                                        <div class="dm-stat-label">Thực phẩm bổ sung</div>
                                        <div class="dm-stat-number"><?= $demTpbs ?></div>
                                        <div class="text-muted small mt-1">Nhóm mã: <code>tpbs</code></div>
                                    </div>
                                    <div class="dm-icon-box" style="background-color: #fdf2f8; color: #db2777;">
                                        <i class="fa fa-flask"></i>
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

                    <!-- Form Thêm mới danh mục (Dạng Collapse tinh chỉnh) -->
                    <div id="formThemDanhMuc" class="collapse dm-form-card mb-4">
                        <div class="dm-form-header d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center gap-2">
                                <div class="dm-icon-box" style="width: 32px; height: 32px; font-size: 0.95rem; background-color: #eff6ff; color: #2563eb;">
                                    <i class="fa fa-plus"></i>
                                </div>
                                <h5 class="fw-bold mb-0 text-dark" style="font-size: 1rem;">Khởi tạo danh mục mới</h5>
                            </div>
                            <button type="button" class="btn btn-sm btn-light border" data-bs-toggle="collapse" data-bs-target="#formThemDanhMuc" aria-label="Đóng form">
                                <i class="fa fa-times"></i>
                            </button>
                        </div>
                        <div class="p-4">
                            <form action="index.php" method="POST" class="row g-3">
                                <input type="hidden" name="action" value="xlthem">

                                <div class="col-md-5">
                                    <label class="form-label fw-semibold small text-secondary">
                                        Tên danh mục hàng hóa <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" name="txtten" class="form-control" placeholder="Ví dụ: Áo khoác Windbreaker, Dây quấn cổ tay..." required>
                                    <div class="form-text small text-muted">Tên danh mục duy nhất, không được trùng lặp trong hệ thống.</div>
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label fw-semibold small text-secondary">
                                        Thuộc nhóm lớn <span class="text-danger">*</span>
                                    </label>
                                    <select name="optloai" class="form-select" required>
                                        <option value="quanao">Quần áo thể thao (quanao)</option>
                                        <option value="phukien">Phụ kiện tập luyện (phukien)</option>
                                        <option value="tpbs">Thực phẩm bổ sung (tpbs)</option>
                                    </select>
                                    <div class="form-text small text-muted">Quyết định vị trí xuất hiện trên thanh điều hướng website.</div>
                                </div>

                                <div class="col-md-3">
                                    <label class="form-label fw-semibold small text-secondary">
                                        Trạng thái hiển thị
                                    </label>
                                    <select name="opttrangthai" class="form-select">
                                        <option value="1" selected>Hiển thị ngay</option>
                                        <option value="0">Tạm ẩn (Khóa hiển thị)</option>
                                    </select>
                                    <div class="form-text small text-muted">Có thể bật/tắt nhanh bất kỳ lúc nào.</div>
                                </div>

                                <div class="col-12 pt-3 border-top d-flex justify-content-between align-items-center">
                                    <div class="small text-muted d-none d-md-block">
                                        <i class="fa fa-info-circle text-primary me-1"></i> Khi danh mục bị ẩn, toàn bộ sản phẩm bên trong sẽ tạm thời ẩn ngoài trang người dùng.
                                    </div>
                                    <div class="d-flex gap-2 ms-auto">
                                        <button type="button" class="btn btn-light px-3" data-bs-toggle="collapse" data-bs-target="#formThemDanhMuc">
                                            Đóng lại
                                        </button>
                                        <button type="submit" class="btn btn-dm-primary px-4">
                                            <i class="fa fa-save"></i> Lưu danh mục
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Card Bảng Danh sách & Bộ lọc Tinh tế -->
                    <div class="dm-table-card">
                        <!-- Toolbar Bộ lọc -->
                        <div class="dm-table-header">
                            <form method="GET" action="index.php" class="row g-2 align-items-center">
                                <div class="col-12 col-md-4">
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0 text-muted">
                                            <i class="fa fa-search"></i>
                                        </span>
                                        <input type="text" name="tukhoa" class="form-control border-start-0 ps-0" placeholder="Tìm theo tên danh mục..." value="<?= htmlspecialchars($filter_tukhoa ?? '') ?>">
                                    </div>
                                </div>

                                <div class="col-6 col-md-3">
                                    <select name="loai" class="form-select" onchange="this.form.submit()">
                                        <option value="">Tất cả nhóm hàng</option>
                                        <option value="quanao" <?= (($filter_loai ?? '') === 'quanao') ? 'selected' : '' ?>>Quần áo thể thao</option>
                                        <option value="phukien" <?= (($filter_loai ?? '') === 'phukien') ? 'selected' : '' ?>>Phụ kiện tập luyện</option>
                                        <option value="tpbs" <?= (($filter_loai ?? '') === 'tpbs') ? 'selected' : '' ?>>Thực phẩm bổ sung</option>
                                    </select>
                                </div>

                                <div class="col-6 col-md-3">
                                    <select name="trangthai" class="form-select" onchange="this.form.submit()">
                                        <option value="">Tất cả trạng thái</option>
                                        <option value="1" <?= (($filter_trangthai ?? '') === '1') ? 'selected' : '' ?>>Đang hiển thị</option>
                                        <option value="0" <?= (($filter_trangthai ?? '') === '0') ? 'selected' : '' ?>>Đang ẩn</option>
                                    </select>
                                </div>

                                <div class="col-12 col-md-2 text-end">
                                    <?php if (!empty($filter_loai) || ($filter_trangthai ?? '') !== '' || !empty($filter_tukhoa)): ?>
                                        <a href="index.php" class="btn btn-outline-secondary w-100" title="Đặt lại bộ lọc về mặc định">
                                            <i class="fa fa-rotate-left me-1"></i> Bỏ lọc
                                        </a>
                                    <?php else: ?>
                                        <button type="submit" class="btn btn-light border w-100 fw-semibold text-secondary">
                                            <i class="fa fa-filter me-1"></i> Lọc dữ liệu
                                        </button>
                                    <?php endif; ?>
                                </div>
                            </form>
                        </div>

                        <!-- Data Table -->
                        <div class="table-responsive">
                            <table class="dm-table">
                                <thead>
                                    <tr>
                                        <th style="width: 60px;" class="text-center">STT</th>
                                        <th>Tên danh mục</th>
                                        <th>Phân nhóm chính</th>
                                        <th class="text-center">Sản phẩm liên kết</th>
                                        <th class="text-center" style="width: 160px;">Trạng thái</th>
                                        <th class="text-center" style="width: 140px;">Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($danhmuc)): ?>
                                        <?php $stt = 1;
                                        foreach ($danhmuc as $d): ?>
                                            <tr>
                                                <td class="text-center text-muted fw-semibold small">
                                                    <?= $stt++ ?>
                                                </td>

                                                <td>
                                                    <div class="fw-bold text-dark fs-6 mb-0">
                                                        <?= htmlspecialchars($d["tendanhmuc"]) ?>
                                                    </div>
                                                </td>

                                                <td>
                                                    <?php if ($d["loai"] === "quanao"): ?>
                                                        <span class="dm-badge dm-badge-quanao">
                                                            <i class="fa fa-shirt"></i> Quần áo thể thao
                                                        </span>
                                                    <?php elseif ($d["loai"] === "phukien"): ?>
                                                        <span class="dm-badge dm-badge-phukien">
                                                            <i class="fa fa-dumbbell"></i> Phụ kiện tập luyện
                                                        </span>
                                                    <?php elseif ($d["loai"] === "tpbs"): ?>
                                                        <span class="dm-badge dm-badge-tpbs">
                                                            <i class="fa fa-flask"></i> Thực phẩm bổ sung
                                                        </span>
                                                    <?php else: ?>
                                                        <span class="dm-badge bg-light text-secondary border">
                                                            <?= htmlspecialchars($d["loai"]) ?>
                                                        </span>
                                                    <?php endif; ?>
                                                </td>

                                                <td class="text-center">
                                                    <?php $soSP = (int)($d["soluongsanpham"] ?? 0); ?>
                                                    <?php if ($soSP > 0): ?>
                                                        <span class="dm-badge dm-badge-count" title="Danh mục đang gắn với <?= $soSP ?> sản phẩm">
                                                            <i class="fa fa-box text-primary"></i> <?= $soSP ?> sản phẩm
                                                        </span>
                                                    <?php else: ?>
                                                        <span class="dm-badge dm-badge-count-zero" title="Chưa có sản phẩm nào - Danh mục này có thể xóa an toàn">
                                                            <i class="fa fa-check text-muted"></i> 0 sản phẩm (Trống)
                                                        </span>
                                                    <?php endif; ?>
                                                </td>

                                                <td class="text-center">
                                                    <!-- Nút Toggle Ẩn/Hiện: Cả Nhân viên và Admin đều thao tác được -->
                                                    <?php if ((int)$d["trangthai"] === 1): ?>
                                                        <a href="index.php?action=doitrangthai&id=<?= $d["id"] ?>&trangthai=0"
                                                            class="btn-toggle-status status-active"
                                                            title="Bấm để ẨN danh mục khỏi website người dùng">
                                                            <i class="fa fa-eye"></i> Hiển thị
                                                        </a>
                                                    <?php else: ?>
                                                        <a href="index.php?action=doitrangthai&id=<?= $d["id"] ?>&trangthai=1"
                                                            class="btn-toggle-status status-inactive"
                                                            title="Bấm để HIỂN THỊ lại danh mục trên website">
                                                            <i class="fa fa-eye-slash"></i> Đang ẩn
                                                        </a>
                                                    <?php endif; ?>
                                                </td>

                                                <td class="text-center">
                                                    <div class="d-inline-flex align-items-center gap-1">
                                                        <!-- Nút Sửa: Cả Admin và Nhân viên đều thấy -->
                                                        <a href="index.php?action=sua&id=<?= $d["id"] ?>"
                                                            class="btn-action-icon btn-action-edit"
                                                            title="Chỉnh sửa thông tin danh mục">
                                                            <i class="fa fa-pen"></i>
                                                        </a>

                                                        <!-- Nút Xóa: CHỈ ADMIN (loai == 1) MỚI CÓ -->
                                                        <?php if (isset($_SESSION["nguoidung"]) && $_SESSION["nguoidung"]["loai"] == 1): ?>
                                                            <?php if ($soSP > 0): ?>
                                                                <button type="button"
                                                                    class="btn-action-icon btn-action-delete opacity-75"
                                                                    title="Danh mục đang có <?= $soSP ?> sản phẩm. Nhấn để xem hướng dẫn"
                                                                    onclick="alert('Không thể xóa! Danh mục này đang chứa <?= $soSP ?> sản phẩm. Để bảo vệ dữ liệu đơn hàng và lịch sử kinh doanh, vui lòng chọn chuyển trạng thái sang ẨN.');">
                                                                    <i class="fa fa-trash"></i>
                                                                </button>
                                                            <?php else: ?>
                                                                <a href="index.php?action=xoa&id=<?= $d["id"] ?>"
                                                                    class="btn-action-icon btn-action-delete"
                                                                    title="Xóa danh mục trống khỏi hệ thống"
                                                                    onclick="return confirm('Bạn có chắc chắn muốn XÓA danh mục <?= htmlspecialchars($d['tendanhmuc']) ?> khỏi hệ thống? Thao tác này không thể hoàn tác.');">
                                                                    <i class="fa fa-trash"></i>
                                                                </a>
                                                            <?php endif; ?>
                                                        <?php else: ?>
                                                            <span class="btn-action-icon btn-action-disabled"
                                                                title="Nhân viên không có quyền xóa. Bạn có thể sử dụng nút Ẩn danh mục">
                                                                <i class="fa fa-lock"></i>
                                                            </span>
                                                        <?php endif; ?>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="6" class="p-0">
                                                <div class="dm-empty-state">
                                                    <div class="dm-empty-icon">
                                                        <i class="fa fa-folder-open"></i>
                                                    </div>
                                                    <h5 class="fw-bold text-dark mb-1">Không tìm thấy danh mục nào</h5>
                                                    <p class="text-muted small mb-3">Không có bản ghi nào phù hợp với điều kiện tìm kiếm hoặc bộ lọc hiện tại.</p>
                                                    <?php if (!empty($filter_loai) || ($filter_trangthai ?? '') !== '' || !empty($filter_tukhoa)): ?>
                                                        <a href="index.php" class="btn btn-sm btn-outline-secondary">
                                                            <i class="fa fa-rotate-left me-1"></i> Xóa tất cả bộ lọc
                                                        </a>
                                                    <?php else: ?>
                                                        <button class="btn btn-sm btn-dm-primary" type="button" data-bs-toggle="collapse" data-bs-target="#formThemDanhMuc">
                                                            <i class="fa fa-plus me-1"></i> Thêm danh mục mới ngay
                                                        </button>
                                                    <?php endif; ?>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>

                        <!-- Footer tóm tắt bảng -->
                        <div class="p-3 border-top bg-light bg-opacity-50 d-flex justify-content-between align-items-center">
                            <span class="small text-muted">
                                Hiển thị <strong><?= count($danhmuc) ?></strong> danh mục trong danh sách
                            </span>
                            <span class="small text-muted d-none d-md-inline">
                                GymBro Fitness Admin · Hệ thống phân nhóm sản phẩm
                            </span>
                        </div>
                    </div>

                </div>
            </main>

            <?php include('../inc/footer.php'); ?>
        </div>
    </div>
</body>

</html>