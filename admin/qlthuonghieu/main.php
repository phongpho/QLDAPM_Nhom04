<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Quản lý thương hiệu - GymBro Admin</title>

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
                                <h1 class="gb-title mb-0">Quản lý thương hiệu</h1>
                                <?php if (isset($_SESSION["nguoidung"]) && (int)$_SESSION["nguoidung"]["loai"] === 1): ?>
                                    <span class="badge bg-purple-subtle text-purple border border-purple-subtle">
                                        <i class="fa fa-shield-halved me-1"></i> Quản trị viên
                                    </span>
                                <?php else: ?>
                                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle">
                                        <i class="fa fa-user-check me-1"></i> Nhân viên
                                    </span>
                                <?php endif; ?>
                            </div>
                            <p class="gb-subtitle mb-0">
                                Quản lý danh sách các nhãn hàng, thương hiệu đối tác cung cấp thiết bị và thực phẩm bổ sung.
                            </p>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <button type="button" class="btn btn-primary d-inline-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#modalThemThuongHieu">
                                <i class="fa fa-plus-circle"></i>
                                <span>Thêm thương hiệu mới</span>
                            </button>
                        </div>
                    </div>

                    <!-- Flash Message Thông báo -->
                    <?php if (isset($_SESSION["thongbao"])): ?>
                        <div class="alert alert-<?= htmlspecialchars($_SESSION["thongbao"]["loai"]) ?> alert-dismissible fade show d-flex align-items-center gap-2 shadow-xs mb-4" role="alert">
                            <i class="fa <?= $_SESSION["thongbao"]["loai"] === 'success' ? 'fa-circle-check text-success' : 'fa-circle-exclamation text-danger' ?> fs-5"></i>
                            <div><?= htmlspecialchars($_SESSION["thongbao"]["noidung"]) ?></div>
                            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                        <?php unset($_SESSION["thongbao"]); ?>
                    <?php endif; ?>

                    <!-- Metric Cards (KPIs) -->
                    <div class="row g-3 mb-4">
                        <div class="col-6 col-xl-3">
                            <div class="gb-stat-card">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div>
                                        <div class="gb-stat-label">Tổng thương hiệu</div>
                                        <div class="gb-stat-number"><?= number_format($thongke['tong'] ?? 0) ?></div>
                                        <div class="text-muted small mt-1">Hãng đối tác hệ thống</div>
                                    </div>
                                    <div class="gb-icon-box" style="background-color: #eff6ff; color: #2563eb;">
                                        <i class="fa fa-award"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-6 col-xl-3">
                            <div class="gb-stat-card">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div>
                                        <div class="gb-stat-label">Đang hiển thị</div>
                                        <div class="gb-stat-number text-success"><?= number_format($thongke['hoatdong'] ?? 0) ?></div>
                                        <div class="text-muted small mt-1">Hoạt động ngoài website</div>
                                    </div>
                                    <div class="gb-icon-box" style="background-color: #ecfdf5; color: #16a34a;">
                                        <i class="fa fa-circle-check"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-6 col-xl-3">
                            <div class="gb-stat-card">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div>
                                        <div class="gb-stat-label">Đang tạm ẩn</div>
                                        <div class="gb-stat-number text-warning"><?= number_format($thongke['an'] ?? 0) ?></div>
                                        <div class="text-muted small mt-1">Chỉ lưu trữ nội bộ</div>
                                    </div>
                                    <div class="gb-icon-box" style="background-color: #fffbeb; color: #d97706;">
                                        <i class="fa fa-eye-slash"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-6 col-xl-3">
                            <div class="gb-stat-card">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div>
                                        <div class="gb-stat-label">Hàng hóa theo hãng</div>
                                        <div class="gb-stat-number text-info"><?= number_format($thongke['tongsanpham'] ?? 0) ?></div>
                                        <div class="text-muted small mt-1">Sản phẩm đã gán thương hiệu</div>
                                    </div>
                                    <div class="gb-icon-box" style="background-color: #f0fdfa; color: #0d9488;">
                                        <i class="fa fa-box-open"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Bảng dữ liệu & Bộ lọc -->
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-white py-3 border-bottom">
                            <form method="GET" action="index.php" class="row g-2 align-items-center">
                                <input type="hidden" name="action" value="macdinh">
                                
                                <div class="col-md-5">
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0 text-muted"><i class="fa fa-search"></i></span>
                                        <input type="text" name="tukhoa" id="inputFilterLive" class="form-control bg-light border-start-0" 
                                               placeholder="Tìm nhanh tên thương hiệu..." value="<?= htmlspecialchars($_GET["tukhoa"] ?? "") ?>">
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <select name="trangthai" class="form-select bg-light" onchange="this.form.submit()">
                                        <option value="">-- Tất cả trạng thái --</option>
                                        <option value="1" <?= (isset($_GET["trangthai"]) && $_GET["trangthai"] === "1") ? "selected" : "" ?>>Đang hiển thị</option>
                                        <option value="0" <?= (isset($_GET["trangthai"]) && $_GET["trangthai"] === "0") ? "selected" : "" ?>>Đang tạm ẩn</option>
                                    </select>
                                </div>

                                <div class="col-md-4 text-md-end">
                                    <a href="index.php" class="btn btn-outline-secondary">
                                        <i class="fa fa-rotate-left me-1"></i> Làm mới
                                    </a>
                                </div>
                            </form>
                        </div>

                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0" id="tableThuongHieu">
                                    <thead class="table-light">
                                        <tr>
                                            <th class="text-center" style="width: 60px;">STT</th>
                                            <th style="width: 110px;">Logo</th>
                                            <th>Tên thương hiệu</th>
                                            <th class="text-center" style="width: 180px;">Sản phẩm liên kết</th>
                                            <th class="text-center" style="width: 150px;">Trạng thái</th>
                                            <th class="text-center" style="width: 140px;">Thao tác</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (empty($thuonghieus)): ?>
                                            <tr>
                                                <td colspan="6" class="text-center py-5 text-muted">
                                                    <div class="mb-2"><i class="fa fa-inbox fs-1 opacity-25"></i></div>
                                                    <p class="mb-0">Không tìm thấy thương hiệu nào phù hợp.</p>
                                                </td>
                                            </tr>
                                        <?php else: ?>
                                            <?php $stt = 1; foreach ($thuonghieus as $item): ?>
                                                <tr>
                                                    <td class="text-center fw-bold text-muted"><?= $stt++ ?></td>
                                                    <td>
                                                        <?php if (!empty($item["logo"])): ?>
                                                            <div class="bg-white border rounded p-1 d-inline-flex align-items-center justify-content-center shadow-xs" style="width: 70px; height: 46px;">
                                                                <img src="../../assets/images/products/<?= htmlspecialchars($item["logo"]) ?>" 
                                                                     alt="<?= htmlspecialchars($item["tenthuonghieu"]) ?>" 
                                                                     style="max-height: 100%; max-width: 100%; object-fit: contain;">
                                                            </div>
                                                        <?php else: ?>
                                                            <div class="bg-light border rounded d-inline-flex align-items-center justify-content-center text-muted" style="width: 70px; height: 46px;">
                                                                <i class="fa fa-image"></i>
                                                            </div>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td>
                                                        <div class="fw-bold text-dark fs-6"><?= htmlspecialchars($item["tenthuonghieu"]) ?></div>
                                                        <span class="text-muted small">Cập nhật hệ thống</span>
                                                    </td>
                                                    <td class="text-center">
                                                        <?php if ((int)$item["soluong_sanpham"] > 0): ?>
                                                            <a href="../qlsanpham/index.php?thuonghieu_id=<?= $item['id'] ?>" class="badge bg-primary-subtle text-primary border border-primary-subtle text-decoration-none px-3 py-2 rounded-pill">
                                                                <i class="fa fa-box me-1"></i>
                                                                <?= number_format($item["soluong_sanpham"]) ?> sản phẩm
                                                            </a>
                                                        <?php else: ?>
                                                            <span class="badge bg-light text-muted border px-3 py-2 rounded-pill">0 sản phẩm</span>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td class="text-center">
                                                        <?php if ((int)$item["trangthai"] === 1): ?>
                                                            <a href="index.php?action=doitrangthai&id=<?= $item['id'] ?>&tt=1" 
                                                               class="badge bg-success-subtle text-success border border-success-subtle text-decoration-none px-2.5 py-1.5 rounded"
                                                               title="Bấm để ẩn thương hiệu">
                                                                <i class="fa fa-check-circle me-1"></i> Hiển thị
                                                            </a>
                                                        <?php else: ?>
                                                            <a href="index.php?action=doitrangthai&id=<?= $item['id'] ?>&tt=0" 
                                                               class="badge bg-secondary-subtle text-secondary border text-decoration-none px-2.5 py-1.5 rounded"
                                                               title="Bấm để kích hoạt hiển thị">
                                                                <i class="fa fa-eye-slash me-1"></i> Tạm ẩn
                                                            </a>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td class="text-center">
                                                        <div class="btn-group btn-group-sm">
                                                            <button type="button" class="btn btn-outline-primary btn-sua-th" 
                                                                    data-id="<?= $item['id'] ?>"
                                                                    data-ten="<?= htmlspecialchars($item['tenthuonghieu'], ENT_QUOTES) ?>"
                                                                    data-logo="<?= htmlspecialchars($item['logo'] ?? '', ENT_QUOTES) ?>"
                                                                    data-tt="<?= $item['trangthai'] ?>"
                                                                    title="Chỉnh sửa thương hiệu">
                                                                <i class="fa fa-pen-to-square"></i>
                                                            </button>
                                                            
                                                            <?php if ($isAdmin): ?>
                                                                <?php if ((int)$item["soluong_sanpham"] > 0): ?>
                                                                    <button type="button" class="btn btn-outline-secondary" 
                                                                            title="Đã có sản phẩm liên kết, không thể xóa" 
                                                                            onclick="alert('Thương hiệu <?= htmlspecialchars($item['tenthuonghieu']) ?> đang có <?= $item['soluong_sanpham'] ?> sản phẩm liên kết! Bạn chỉ có thể chuyển sang trạng thái Ẩn.')">
                                                                        <i class="fa fa-trash-can text-muted"></i>
                                                                    </button>
                                                                <?php else: ?>
                                                                    <a href="index.php?action=xoa&id=<?= $item['id'] ?>" 
                                                                       class="btn btn-outline-danger" 
                                                                       onclick="return confirm('Bạn có chắc chắn muốn xóa vĩnh viễn thương hiệu <?= htmlspecialchars($item['tenthuonghieu']) ?>?');"
                                                                       title="Xóa thương hiệu">
                                                                        <i class="fa fa-trash-can"></i>
                                                                    </a>
                                                                <?php endif; ?>
                                                            <?php endif; ?>
                                                        </div>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Footer bảng -->
                        <div class="p-3 border-top bg-light bg-opacity-50 d-flex flex-column flex-md-row justify-content-between align-items-center gap-2">
                            <span class="small text-muted">
                                Hiển thị <strong><?= count($thuonghieus) ?></strong> thương hiệu trong danh sách
                            </span>
                            <span class="small text-muted d-none d-md-inline">
                                GymBro Fitness Admin · Quản lý Thương hiệu & Nhãn hàng
                            </span>
                        </div>
                    </div>

                </div>
            </main>

            <?php include('../inc/footer.php'); ?>
        </div>
    </div>

    <!-- Modal Thêm Thương Hiệu -->
    <div class="modal fade" id="modalThemThuongHieu" tabindex="-1" aria-labelledby="modalThemLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <form action="index.php?action=xulythem" method="POST" enctype="multipart/form-data">
                    <div class="modal-header bg-light">
                        <h5 class="modal-title fw-bold" id="modalThemLabel">
                            <i class="fa fa-plus-circle text-primary me-2"></i>Thêm thương hiệu mới
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Tên thương hiệu <span class="text-danger">*</span></label>
                            <input type="text" name="tenthuonghieu" class="form-control" required placeholder="Ví dụ: Gymshark, Nike Pro, Puma...">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Logo thương hiệu</label>
                            <input type="file" name="flogo" class="form-control" accept="image/*" onchange="previewLogoAdd(event)">
                            <div class="form-text">Hỗ trợ JPG, PNG, WEBP, SVG. Nên dùng ảnh tỉ lệ ngang nền trong suốt (PNG).</div>
                            <div class="mt-2 text-center p-2 bg-light rounded border d-none" id="previewContainerAdd">
                                <img id="previewImgAdd" src="" alt="Logo xem trước" style="max-height: 60px; max-width: 100%; object-fit: contain;">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Trạng thái ban đầu</label>
                            <select name="trangthai" class="form-select">
                                <option value="1">Hiển thị (Khách hàng thấy thương hiệu)</option>
                                <option value="0">Tạm ẩn (Chỉ lưu nội bộ)</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer bg-light">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy bỏ</button>
                        <button type="submit" class="btn btn-primary d-inline-flex align-items-center gap-2">
                            <i class="fa fa-floppy-disk"></i>
                            <span>Lưu thương hiệu</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Sửa Thương Hiệu -->
    <div class="modal fade" id="modalSuaThuongHieu" tabindex="-1" aria-labelledby="modalSuaLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <form action="index.php?action=xulysua" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="id" id="edit_th_id">
                    <div class="modal-header bg-light">
                        <h5 class="modal-title fw-bold" id="modalSuaLabel">
                            <i class="fa fa-pen-to-square text-primary me-2"></i>Chỉnh sửa thương hiệu
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Tên thương hiệu <span class="text-danger">*</span></label>
                            <input type="text" name="tenthuonghieu" id="edit_th_ten" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Cập nhật Logo mới</label>
                            <input type="file" name="flogo" class="form-control" accept="image/*" onchange="previewLogoEdit(event)">
                            <div class="form-text">Để trống nếu muốn giữ nguyên logo hiện tại.</div>
                            <div class="mt-2 text-center p-2 bg-light rounded border" id="previewContainerEdit">
                                <span class="text-muted small d-block mb-1">Logo hiện tại / xem trước:</span>
                                <img id="previewImgEdit" src="" alt="Logo" style="max-height: 60px; max-width: 100%; object-fit: contain;">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Trạng thái</label>
                            <select name="trangthai" id="edit_th_tt" class="form-select">
                                <option value="1">Hiển thị</option>
                                <option value="0">Tạm ẩn</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer bg-light">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                        <button type="submit" class="btn btn-primary d-inline-flex align-items-center gap-2">
                            <i class="fa fa-check"></i>
                            <span>Cập nhật thay đổi</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
    document.addEventListener("DOMContentLoaded", function() {
        // Live Filter
        const inputLive = document.getElementById("inputFilterLive");
        const tableBody = document.querySelector("#tableThuongHieu tbody");
        if (inputLive && tableBody) {
            inputLive.addEventListener("input", function() {
                const query = this.value.toLowerCase().trim();
                const rows = tableBody.querySelectorAll("tr");
                rows.forEach(row => {
                    const text = row.textContent.toLowerCase();
                    if (text.includes(query)) {
                        row.style.display = "";
                    } else {
                        row.style.display = "none";
                    }
                });
            });
        }

        // Modal Populate
        const modalSuaEl = document.getElementById('modalSuaThuongHieu');
        const modalSua = new bootstrap.Modal(modalSuaEl);
        
        document.querySelectorAll(".btn-sua-th").forEach(btn => {
            btn.addEventListener("click", function() {
                const id = this.getAttribute("data-id");
                const ten = this.getAttribute("data-ten");
                const logo = this.getAttribute("data-logo");
                const tt = this.getAttribute("data-tt");

                document.getElementById("edit_th_id").value = id;
                document.getElementById("edit_th_ten").value = ten;
                document.getElementById("edit_th_tt").value = tt;

                const previewImg = document.getElementById("previewImgEdit");
                if (logo && logo.trim() !== "") {
                    previewImg.src = "../../assets/images/products/" + logo;
                    previewImg.style.display = "inline-block";
                } else {
                    previewImg.src = "../../assets/images/products/product.png";
                }

                modalSua.show();
            });
        });
    });

    function previewLogoAdd(event) {
        const file = event.target.files[0];
        const container = document.getElementById("previewContainerAdd");
        const img = document.getElementById("previewImgAdd");
        if (file) {
            img.src = URL.createObjectURL(file);
            container.classList.remove("d-none");
        }
    }

    function previewLogoEdit(event) {
        const file = event.target.files[0];
        const img = document.getElementById("previewImgEdit");
        if (file) {
            img.src = URL.createObjectURL(file);
        }
    }
    </script>
</body>

</html>
