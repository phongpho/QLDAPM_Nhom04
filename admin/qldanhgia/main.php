<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Quản lý đánh giá & phản hồi - GymBro Admin</title>

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
                                <h1 class="gb-title mb-0">Quản lý Đánh giá & Phản hồi</h1>
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
                                Kiểm duyệt và quản lý các phản hồi, chấm điểm chất lượng sản phẩm từ khách hàng mua sắm.
                            </p>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <a href="index.php" class="btn btn-outline-primary d-inline-flex align-items-center gap-2">
                                <i class="fa fa-rotate-left"></i>
                                <span>Tải lại danh sách</span>
                            </a>
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
                                        <div class="gb-stat-label">Tổng số đánh giá</div>
                                        <div class="gb-stat-number"><?= number_format($thongke['tong'] ?? 0) ?></div>
                                        <div class="text-muted small mt-1">Từ toàn bộ khách mua hàng</div>
                                    </div>
                                    <div class="gb-icon-box" style="background-color: #eff6ff; color: #2563eb;">
                                        <i class="fa fa-comments"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-6 col-xl-3">
                            <div class="gb-stat-card">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div>
                                        <div class="gb-stat-label">Điểm đánh giá TB</div>
                                        <div class="gb-stat-number text-warning"><?= number_format($thongke['diemsao_tb'] ?? 5.0, 1) ?> <span class="fs-4 text-muted">/ 5</span></div>
                                        <div class="text-muted small mt-1">Độ hài lòng toàn shop</div>
                                    </div>
                                    <div class="gb-icon-box" style="background-color: #fffbeb; color: #d97706;">
                                        <i class="fa fa-star"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-6 col-xl-3">
                            <div class="gb-stat-card">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div>
                                        <div class="gb-stat-label">Đánh giá 5 sao</div>
                                        <div class="gb-stat-number text-success"><?= number_format($thongke['danhgia_5sao'] ?? 0) ?></div>
                                        <div class="text-muted small mt-1">Mức hài lòng tuyệt đối</div>
                                    </div>
                                    <div class="gb-icon-box" style="background-color: #ecfdf5; color: #16a34a;">
                                        <i class="fa fa-thumbs-up"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-6 col-xl-3">
                            <div class="gb-stat-card">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div>
                                        <div class="gb-stat-label">Tạm ẩn / Chờ duyệt</div>
                                        <div class="gb-stat-number text-danger"><?= number_format($thongke['an_chotam'] ?? 0) ?></div>
                                        <div class="text-muted small mt-1">Cần kiểm tra nội dung</div>
                                    </div>
                                    <div class="gb-icon-box" style="background-color: #fef2f2; color: #dc2626;">
                                        <i class="fa fa-eye-slash"></i>
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
                                        <input type="text" name="tukhoa" id="inputFilterLiveDg" class="form-control bg-light border-start-0" 
                                               placeholder="Tìm theo tên khách, sản phẩm, nhận xét..." value="<?= htmlspecialchars($_GET["tukhoa"] ?? "") ?>">
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <select name="sao" class="form-select bg-light" onchange="this.form.submit()">
                                        <option value="0">-- Tất cả số sao --</option>
                                        <option value="5" <?= (isset($_GET["sao"]) && (int)$_GET["sao"] === 5) ? "selected" : "" ?>>5 sao ★★★★★</option>
                                        <option value="4" <?= (isset($_GET["sao"]) && (int)$_GET["sao"] === 4) ? "selected" : "" ?>>4 sao ★★★★</option>
                                        <option value="3" <?= (isset($_GET["sao"]) && (int)$_GET["sao"] === 3) ? "selected" : "" ?>>3 sao ★★★</option>
                                        <option value="2" <?= (isset($_GET["sao"]) && (int)$_GET["sao"] === 2) ? "selected" : "" ?>>2 sao ★★</option>
                                        <option value="1" <?= (isset($_GET["sao"]) && (int)$_GET["sao"] === 1) ? "selected" : "" ?>>1 sao ★</option>
                                    </select>
                                </div>

                                <div class="col-md-2">
                                    <select name="trangthai" class="form-select bg-light" onchange="this.form.submit()">
                                        <option value="">-- Trạng thái --</option>
                                        <option value="1" <?= (isset($_GET["trangthai"]) && $_GET["trangthai"] === "1") ? "selected" : "" ?>>Đã hiển thị</option>
                                        <option value="0" <?= (isset($_GET["trangthai"]) && $_GET["trangthai"] === "0") ? "selected" : "" ?>>Đang tạm ẩn</option>
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
                                <table class="table table-hover align-middle mb-0" id="tableDanhGia">
                                    <thead class="table-light">
                                        <tr>
                                            <th class="text-center" style="width: 50px;">STT</th>
                                            <th style="width: 220px;">Khách hàng</th>
                                            <th style="width: 260px;">Sản phẩm</th>
                                            <th style="width: 140px;">Số sao</th>
                                            <th>Nội dung nhận xét</th>
                                            <th class="text-center" style="width: 130px;">Trạng thái</th>
                                            <th class="text-center" style="width: 110px;">Thao tác</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (empty($danhgias)): ?>
                                            <tr>
                                                <td colspan="7" class="text-center py-5 text-muted">
                                                    <div class="mb-2"><i class="fa fa-comment-slash fs-1 opacity-25"></i></div>
                                                    <p class="mb-0">Chưa có phản hồi, đánh giá nào phù hợp với bộ lọc.</p>
                                                </td>
                                            </tr>
                                        <?php else: ?>
                                            <?php $stt = 1; foreach ($danhgias as $item): ?>
                                                <tr>
                                                    <td class="text-center fw-bold text-muted"><?= $stt++ ?></td>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <?php 
                                                            $avatar = !empty($item["anh_khachhang"]) ? '../../assets/images/users/' . $item["anh_khachhang"] : '../../assets/images/users/user.png';
                                                            ?>
                                                            <img src="<?= $avatar ?>" alt="<?= htmlspecialchars($item["ten_khachhang"] ?? 'Khách') ?>" 
                                                                 class="rounded-circle me-2 border shadow-xs" style="width: 38px; height: 38px; object-fit: cover;">
                                                            <div>
                                                                <div class="fw-bold text-dark"><?= htmlspecialchars($item["ten_khachhang"] ?? 'Khách vãng lai') ?></div>
                                                                <div class="text-muted small"><?= htmlspecialchars($item["sdt_khachhang"] ?? $item["email_khachhang"] ?? '') ?></div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <?php 
                                                            $imgSp = !empty($item["anh_sanpham"]) ? '../../assets/images/products/' . $item["anh_sanpham"] : '../../assets/images/products/product.png';
                                                            ?>
                                                            <img src="<?= $imgSp ?>" alt="<?= htmlspecialchars($item["tensanpham"] ?? '') ?>" 
                                                                 class="rounded border me-2" style="width: 40px; height: 40px; object-fit: cover;">
                                                            <div class="text-truncate" style="max-width: 190px;">
                                                                <a href="../qlsanpham/index.php?action=sua&id=<?= $item['sanpham_id'] ?>" 
                                                                   class="text-decoration-none fw-semibold text-dark small" title="<?= htmlspecialchars($item["tensanpham"] ?? '') ?>">
                                                                    <?= htmlspecialchars($item["tensanpham"] ?? 'Sản phẩm đã xóa') ?>
                                                                </a>
                                                                <div class="text-muted small"><?= number_format((float)($item["giaban"] ?? 0), 0, ',', '.') ?>đ</div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="text-warning">
                                                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                                                <i class="fa <?= $i <= (int)$item['sao'] ? 'fa-star' : 'fa-star-o text-muted opacity-50' ?>" style="font-size: 0.85rem;"></i>
                                                            <?php endfor; ?>
                                                        </div>
                                                        <div class="text-muted small mt-1">
                                                            <?= date('d/m/Y H:i', strtotime($item["ngay"])) ?>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="bg-light p-2.5 rounded small text-dark border">
                                                            <?= nl2br(htmlspecialchars($item["noidung"] ?? '(Không có lời bình luận)')) ?>
                                                        </div>
                                                    </td>
                                                    <td class="text-center">
                                                        <?php if ((int)$item["trangthai"] === 1): ?>
                                                            <a href="index.php?action=doitrangthai&id=<?= $item['id'] ?>&tt=1" 
                                                               class="badge bg-success-subtle text-success border border-success-subtle text-decoration-none px-2.5 py-1.5 rounded"
                                                               title="Bấm để ẩn đánh giá">
                                                                <i class="fa fa-check-circle me-1"></i> Đã hiển thị
                                                            </a>
                                                        <?php else: ?>
                                                            <a href="index.php?action=doitrangthai&id=<?= $item['id'] ?>&tt=0" 
                                                               class="badge bg-secondary-subtle text-secondary border text-decoration-none px-2.5 py-1.5 rounded"
                                                               title="Bấm để duyệt hiển thị">
                                                                <i class="fa fa-eye-slash me-1"></i> Tạm ẩn
                                                            </a>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td class="text-center">
                                                        <div class="btn-group btn-group-sm">
                                                            <a href="index.php?action=doitrangthai&id=<?= $item['id'] ?>&tt=<?= $item['trangthai'] ?>" 
                                                               class="btn btn-outline-secondary" 
                                                               title="<?= (int)$item['trangthai'] === 1 ? 'Ẩn đánh giá' : 'Hiển thị đánh giá' ?>">
                                                                <i class="fa <?= (int)$item['trangthai'] === 1 ? 'fa-eye-slash' : 'fa-eye' ?>"></i>
                                                            </a>
                                                            
                                                            <?php if ($isAdmin): ?>
                                                                <a href="index.php?action=xoa&id=<?= $item['id'] ?>" 
                                                                   class="btn btn-outline-danger" 
                                                                   onclick="return confirm('Bạn có chắc chắn muốn xóa phản hồi này vĩnh viễn?');"
                                                                   title="Xóa đánh giá">
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
                                Hiển thị <strong><?= count($danhgias) ?></strong> phản hồi trong danh sách
                            </span>
                            <span class="small text-muted d-none d-md-inline">
                                GymBro Fitness Admin · Kiểm duyệt Đánh giá Khách hàng
                            </span>
                        </div>
                    </div>

                </div>
            </main>

            <?php include('../inc/footer.php'); ?>
        </div>
    </div>

    <script>
    document.addEventListener("DOMContentLoaded", function() {
        const inputLive = document.getElementById("inputFilterLiveDg");
        const tableBody = document.querySelector("#tableDanhGia tbody");
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
    });
    </script>
</body>

</html>
