<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Quản lý bài viết & tin tức - GymBro Admin</title>

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
                                <h1 class="gb-title mb-0">Quản lý Bài viết & Tin tức</h1>
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
                                Quản lý các bài viết cẩm nang tập luyện, kiến thức dinh dưỡng, sự kiện thể hình và ưu đãi.
                            </p>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <button type="button" class="btn btn-primary d-inline-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#modalThemTinTuc">
                                <i class="fa fa-plus-circle"></i>
                                <span>Đăng bài viết mới</span>
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
                                        <div class="gb-stat-label">Tổng bài viết</div>
                                        <div class="gb-stat-number"><?= number_format($thongke['tong'] ?? 0) ?></div>
                                        <div class="text-muted small mt-1">Đang xuất bản: <strong><?= number_format($thongke['hienthi'] ?? 0) ?></strong></div>
                                    </div>
                                    <div class="gb-icon-box" style="background-color: #eff6ff; color: #2563eb;">
                                        <i class="fa fa-newspaper"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-6 col-xl-3">
                            <div class="gb-stat-card">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div>
                                        <div class="gb-stat-label">Cẩm nang / Tin tức</div>
                                        <div class="gb-stat-number text-info"><?= number_format($thongke['dem_tintuc'] ?? 0) ?></div>
                                        <div class="text-muted small mt-1">Kiến thức thể hình, dinh dưỡng</div>
                                    </div>
                                    <div class="gb-icon-box" style="background-color: #f0fdfa; color: #0d9488;">
                                        <i class="fa fa-book-open"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-6 col-xl-3">
                            <div class="gb-stat-card">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div>
                                        <div class="gb-stat-label">Sự kiện tập luyện</div>
                                        <div class="gb-stat-number text-warning"><?= number_format($thongke['dem_sukien'] ?? 0) ?></div>
                                        <div class="text-muted small mt-1">Workshop, giải đấu, offline</div>
                                    </div>
                                    <div class="gb-icon-box" style="background-color: #fffbeb; color: #d97706;">
                                        <i class="fa fa-calendar-days"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-6 col-xl-3">
                            <div class="gb-stat-card">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div>
                                        <div class="gb-stat-label">Tin khuyến mãi</div>
                                        <div class="gb-stat-number text-danger"><?= number_format($thongke['dem_khuyenmai'] ?? 0) ?></div>
                                        <div class="text-muted small mt-1">Thông báo ưu đãi mua sắm</div>
                                    </div>
                                    <div class="gb-icon-box" style="background-color: #fef2f2; color: #dc2626;">
                                        <i class="fa fa-percent"></i>
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
                                        <input type="text" name="tukhoa" id="inputFilterLiveTt" class="form-control bg-light border-start-0" 
                                               placeholder="Tìm tiêu đề, nội dung bài viết..." value="<?= htmlspecialchars($_GET["tukhoa"] ?? "") ?>">
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <select name="loai" class="form-select bg-light" onchange="this.form.submit()">
                                        <option value="">-- Tất cả thể loại --</option>
                                        <option value="tintuc" <?= (isset($_GET["loai"]) && $_GET["loai"] === "tintuc") ? "selected" : "" ?>>Cẩm nang / Tin tức</option>
                                        <option value="sukien" <?= (isset($_GET["loai"]) && $_GET["loai"] === "sukien") ? "selected" : "" ?>>Sự kiện tập luyện</option>
                                        <option value="khuyenmai" <?= (isset($_GET["loai"]) && $_GET["loai"] === "khuyenmai") ? "selected" : "" ?>>Tin khuyến mãi</option>
                                    </select>
                                </div>

                                <div class="col-md-2">
                                    <select name="trangthai" class="form-select bg-light" onchange="this.form.submit()">
                                        <option value="">-- Trạng thái --</option>
                                        <option value="1" <?= (isset($_GET["trangthai"]) && $_GET["trangthai"] === "1") ? "selected" : "" ?>>Đang hiển thị</option>
                                        <option value="0" <?= (isset($_GET["trangthai"]) && $_GET["trangthai"] === "0") ? "selected" : "" ?>>Tạm ẩn / Nháp</option>
                                    </select>
                                </div>

                                <div class="col-md-2 text-md-end">
                                    <a href="index.php" class="btn btn-outline-secondary w-100">
                                        <i class="fa fa-rotate-left me-1"></i> Làm mới
                                    </a>
                                </div>
                            </form>
                        </div>

                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0" id="tableTinTuc">
                                    <thead class="table-light">
                                        <tr>
                                            <th class="text-center" style="width: 50px;">STT</th>
                                            <th style="width: 100px;">Hình ảnh</th>
                                            <th>Tiêu đề bài viết</th>
                                            <th style="width: 150px;">Chuyên mục</th>
                                            <th style="width: 140px;">Ngày đăng</th>
                                            <th class="text-center" style="width: 130px;">Trạng thái</th>
                                            <th class="text-center" style="width: 120px;">Thao tác</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (empty($tintucs)): ?>
                                            <tr>
                                                <td colspan="7" class="text-center py-5 text-muted">
                                                    <div class="mb-2"><i class="fa fa-newspaper fs-1 opacity-25"></i></div>
                                                    <p class="mb-0">Chưa có bài viết nào phù hợp.</p>
                                                </td>
                                            </tr>
                                        <?php else: ?>
                                            <?php $stt = 1; foreach ($tintucs as $item): ?>
                                                <tr>
                                                    <td class="text-center fw-bold text-muted"><?= $stt++ ?></td>
                                                    <td>
                                                        <?php if (!empty($item["hinhanh"])): ?>
                                                            <div class="border rounded overflow-hidden shadow-xs" style="width: 74px; height: 50px;">
                                                                <img src="../../assets/images/products/<?= htmlspecialchars($item["hinhanh"]) ?>" 
                                                                     alt="<?= htmlspecialchars($item["tieude"]) ?>" 
                                                                     class="w-100 h-100" style="object-fit: cover;">
                                                            </div>
                                                        <?php else: ?>
                                                            <div class="bg-light border rounded d-flex align-items-center justify-content-center text-muted" style="width: 74px; height: 50px;">
                                                                <i class="fa fa-image"></i>
                                                            </div>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td>
                                                        <div class="fw-bold text-dark fs-6"><?= htmlspecialchars($item["tieude"]) ?></div>
                                                        <div class="text-muted small text-truncate" style="max-width: 450px;">
                                                            <?= htmlspecialchars(strip_tags($item["noidung"])) ?>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <?php if ($item["loai"] === "sukien"): ?>
                                                            <span class="badge bg-warning-subtle text-warning border border-warning-subtle px-2.5 py-1 rounded-pill">
                                                                <i class="fa fa-calendar-days me-1"></i> Sự kiện
                                                            </span>
                                                        <?php elseif ($item["loai"] === "khuyenmai"): ?>
                                                            <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2.5 py-1 rounded-pill">
                                                                <i class="fa fa-percent me-1"></i> Khuyến mãi
                                                            </span>
                                                        <?php else: ?>
                                                            <span class="badge bg-info-subtle text-info border border-info-subtle px-2.5 py-1 rounded-pill">
                                                                <i class="fa fa-book-open me-1"></i> Cẩm nang
                                                            </span>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td>
                                                        <span class="small text-muted"><?= date('d/m/Y H:i', strtotime($item["ngaydang"])) ?></span>
                                                    </td>
                                                    <td class="text-center">
                                                        <?php if ((int)$item["trangthai"] === 1): ?>
                                                            <a href="index.php?action=doitrangthai&id=<?= $item['id'] ?>&tt=1" 
                                                               class="badge bg-success-subtle text-success border border-success-subtle text-decoration-none px-2.5 py-1.5 rounded"
                                                               title="Bấm để ẩn bài viết">
                                                                <i class="fa fa-check-circle me-1"></i> Hiển thị
                                                            </a>
                                                        <?php else: ?>
                                                            <a href="index.php?action=doitrangthai&id=<?= $item['id'] ?>&tt=0" 
                                                               class="badge bg-secondary-subtle text-secondary border text-decoration-none px-2.5 py-1.5 rounded"
                                                               title="Bấm để công khai bài viết">
                                                                <i class="fa fa-file-lines me-1"></i> Bản nháp
                                                            </a>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td class="text-center">
                                                        <div class="btn-group btn-group-sm">
                                                            <button type="button" class="btn btn-outline-primary btn-sua-tt" 
                                                                    data-id="<?= $item['id'] ?>"
                                                                    data-tieude="<?= htmlspecialchars($item['tieude'], ENT_QUOTES) ?>"
                                                                    data-loai="<?= $item['loai'] ?>"
                                                                    data-hinhanh="<?= htmlspecialchars($item['hinhanh'] ?? '', ENT_QUOTES) ?>"
                                                                    data-tt="<?= $item['trangthai'] ?>"
                                                                    data-noidung="<?= htmlspecialchars($item['noidung'], ENT_QUOTES) ?>"
                                                                    title="Chỉnh sửa bài viết">
                                                                <i class="fa fa-pen-to-square"></i>
                                                            </button>
                                                            
                                                            <?php if ($isAdmin): ?>
                                                                <a href="index.php?action=xoa&id=<?= $item['id'] ?>" 
                                                                   class="btn btn-outline-danger" 
                                                                   onclick="return confirm('Bạn có chắc chắn muốn xóa bài viết này?');"
                                                                   title="Xóa bài viết">
                                                                    <i class="fa fa-trash-can"></i>
                                                                </a>
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
                                Hiển thị <strong><?= count($tintucs) ?></strong> bài viết trong danh sách
                            </span>
                            <span class="small text-muted d-none d-md-inline">
                                GymBro Fitness Admin · Quản lý Bài viết & Tin tức
                            </span>
                        </div>
                    </div>

                </div>
            </main>

            <?php include('../inc/footer.php'); ?>
        </div>
    </div>

    <!-- Modal Đăng Bài Viết Mới -->
    <div class="modal fade" id="modalThemTinTuc" tabindex="-1" aria-labelledby="modalThemLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 shadow">
                <form action="index.php?action=xulythem" method="POST" enctype="multipart/form-data">
                    <div class="modal-header bg-light">
                        <h5 class="modal-title fw-bold" id="modalThemLabel">
                            <i class="fa fa-plus-circle text-primary me-2"></i>Đăng bài viết mới
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Tiêu đề bài viết <span class="text-danger">*</span></label>
                            <input type="text" name="tieude" class="form-control" required placeholder="Nhập tiêu đề bài viết...">
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Chuyên mục / Thể loại <span class="text-danger">*</span></label>
                                <select name="loai" class="form-select">
                                    <option value="tintuc">Cẩm nang tập luyện & Dinh dưỡng</option>
                                    <option value="sukien">Sự kiện thể hình & Workshop</option>
                                    <option value="khuyenmai">Thông báo ưu đãi & Khuyến mãi</option>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold">Trạng thái công khai</label>
                                <select name="trangthai" class="form-select">
                                    <option value="1">Hiển thị ngay</option>
                                    <option value="0">Lưu bản nháp</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Ảnh bìa bài viết</label>
                            <input type="file" name="fhinhanh" class="form-control" accept="image/*" onchange="previewNewsAdd(event)">
                            <div class="mt-2 text-center p-2 bg-light rounded border d-none" id="previewContainerNewsAdd">
                                <img id="previewImgNewsAdd" src="" alt="Thumbnail" style="max-height: 120px; max-width: 100%; object-fit: contain;">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Nội dung bài viết <span class="text-danger">*</span></label>
                            <textarea name="noidung" class="form-control" rows="8" required placeholder="Viết nội dung chi tiết bài viết tại đây..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer bg-light">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy bỏ</button>
                        <button type="submit" class="btn btn-primary d-inline-flex align-items-center gap-2">
                            <i class="fa fa-floppy-disk"></i>
                            <span>Đăng bài viết</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Sửa Bài Viết -->
    <div class="modal fade" id="modalSuaTinTuc" tabindex="-1" aria-labelledby="modalSuaLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 shadow">
                <form action="index.php?action=xulysua" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="id" id="edit_tt_id">
                    <div class="modal-header bg-light">
                        <h5 class="modal-title fw-bold" id="modalSuaLabel">
                            <i class="fa fa-pen-to-square text-primary me-2"></i>Chỉnh sửa bài viết
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Tiêu đề bài viết <span class="text-danger">*</span></label>
                            <input type="text" name="tieude" id="edit_tt_tieude" class="form-control" required>
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Chuyên mục / Thể loại <span class="text-danger">*</span></label>
                                <select name="loai" id="edit_tt_loai" class="form-select">
                                    <option value="tintuc">Cẩm nang tập luyện & Dinh dưỡng</option>
                                    <option value="sukien">Sự kiện thể hình & Workshop</option>
                                    <option value="khuyenmai">Thông báo ưu đãi & Khuyến mãi</option>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold">Trạng thái</label>
                                <select name="trangthai" id="edit_tt_trangthai" class="form-select">
                                    <option value="1">Hiển thị</option>
                                    <option value="0">Tạm ẩn / Bản nháp</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Cập nhật ảnh bìa mới</label>
                            <input type="file" name="fhinhanh" class="form-control" accept="image/*" onchange="previewNewsEdit(event)">
                            <div class="form-text">Để trống nếu không muốn đổi ảnh bìa.</div>
                            <div class="mt-2 text-center p-2 bg-light rounded border" id="previewContainerNewsEdit">
                                <img id="previewImgNewsEdit" src="" alt="Thumbnail" style="max-height: 120px; max-width: 100%; object-fit: contain;">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Nội dung bài viết <span class="text-danger">*</span></label>
                            <textarea name="noidung" id="edit_tt_noidung" class="form-control" rows="8" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer bg-light">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                        <button type="submit" class="btn btn-primary d-inline-flex align-items-center gap-2">
                            <i class="fa fa-check"></i>
                            <span>Lưu thay đổi</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
    document.addEventListener("DOMContentLoaded", function() {
        const inputLive = document.getElementById("inputFilterLiveTt");
        const tableBody = document.querySelector("#tableTinTuc tbody");
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

        const modalSuaEl = document.getElementById('modalSuaTinTuc');
        const modalSua = new bootstrap.Modal(modalSuaEl);
        
        document.querySelectorAll(".btn-sua-tt").forEach(btn => {
            btn.addEventListener("click", function() {
                document.getElementById("edit_tt_id").value = this.getAttribute("data-id");
                document.getElementById("edit_tt_tieude").value = this.getAttribute("data-tieude");
                document.getElementById("edit_tt_loai").value = this.getAttribute("data-loai");
                document.getElementById("edit_tt_trangthai").value = this.getAttribute("data-tt");
                document.getElementById("edit_tt_noidung").value = this.getAttribute("data-noidung");

                const hinh = this.getAttribute("data-hinhanh");
                const previewImg = document.getElementById("previewImgNewsEdit");
                if (hinh && hinh.trim() !== "") {
                    previewImg.src = "../../assets/images/products/" + hinh;
                    previewImg.style.display = "inline-block";
                } else {
                    previewImg.src = "../../assets/images/products/product.png";
                }

                modalSua.show();
            });
        });
    });

    function previewNewsAdd(event) {
        const file = event.target.files[0];
        const container = document.getElementById("previewContainerNewsAdd");
        const img = document.getElementById("previewImgNewsAdd");
        if (file) {
            img.src = URL.createObjectURL(file);
            container.classList.remove("d-none");
        }
    }

    function previewNewsEdit(event) {
        const file = event.target.files[0];
        const img = document.getElementById("previewImgNewsEdit");
        if (file) {
            img.src = URL.createObjectURL(file);
        }
    }
    </script>
</body>

</html>
