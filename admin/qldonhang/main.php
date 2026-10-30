<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Quản lý đơn hàng - GymBro Admin</title>

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
                                <h1 class="gb-title mb-0">Quản lý đơn hàng</h1>
                                <?php if (isset($_SESSION["nguoidung"]) && (int)$_SESSION["nguoidung"]["loai"] === 1): ?>
                                    <span class="badge bg-purple-subtle text-purple border border-purple-subtle" title="Tài khoản có toàn quyền quản trị và xóa đơn hủy">
                                        <i class="fa fa-shield-halved me-1"></i> Quản trị viên
                                    </span>
                                <?php else: ?>
                                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle" title="Nhân viên có quyền xem, duyệt và cập nhật đơn hàng">
                                        <i class="fa fa-user-check me-1"></i> Nhân viên
                                    </span>
                                <?php endif; ?>
                            </div>
                            <p class="gb-subtitle mb-0">
                                Theo dõi quy trình xử lý đơn hàng, điều phối giao hàng và quản lý doanh số bán lẻ GymBro.
                            </p>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <span class="text-muted small">
                                <i class="fa fa-clock-rotate-left me-1"></i> Cập nhật theo thời gian thực
                            </span>
                        </div>
                    </div>

                    <!-- Thống kê nhanh theo Card Metric (KPIs) -->
                    <div class="row g-3 mb-4">
                        <div class="col-6 col-xl-3">
                            <div class="gb-stat-card">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div>
                                        <div class="gb-stat-label">Tổng đơn hàng</div>
                                        <div class="gb-stat-number"><?= number_format($thongke['tong_don'] ?? 0) ?></div>
                                        <div class="text-muted small mt-1">Toàn bộ đơn trên hệ thống</div>
                                    </div>
                                    <div class="gb-icon-box" style="background-color: #eff6ff; color: #2563eb;">
                                        <i class="fa fa-receipt"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-6 col-xl-3">
                            <div class="gb-stat-card">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div>
                                        <div class="gb-stat-label">Chờ xác nhận</div>
                                        <div class="gb-stat-number text-warning"><?= number_format($thongke['don_moi'] ?? 0) ?></div>
                                        <div class="text-muted small mt-1">Đơn mới cần duyệt</div>
                                    </div>
                                    <div class="gb-icon-box" style="background-color: #fffbeb; color: #d97706;">
                                        <i class="fa fa-clock"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-6 col-xl-3">
                            <div class="gb-stat-card">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div>
                                        <div class="gb-stat-label">Đang vận chuyển</div>
                                        <div class="gb-stat-number text-primary"><?= number_format($thongke['don_vanchuyen'] ?? 0) ?></div>
                                        <div class="text-muted small mt-1">Đóng gói & Đang giao</div>
                                    </div>
                                    <div class="gb-icon-box" style="background-color: #f0fdf4; color: #16a34a;">
                                        <i class="fa fa-truck-fast"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-6 col-xl-3">
                            <div class="gb-stat-card">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div>
                                        <div class="gb-stat-label">Doanh thu hoàn thành</div>
                                        <div class="gb-stat-number text-success" style="font-size: 1.35rem;">
                                            <?= number_format($thongke['doanhthu_hoanthanh'] ?? 0, 0, ',', '.') ?> đ
                                        </div>
                                        <div class="text-muted small mt-1">
                                            <?= number_format($thongke['don_hoanthanh'] ?? 0) ?> đơn đã giao thành công
                                        </div>
                                    </div>
                                    <div class="gb-icon-box" style="background-color: #ecfdf5; color: #059669;">
                                        <i class="fa fa-circle-check"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Thông báo Flash Messages -->
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

                    <!-- Card Bảng Danh sách & Bộ lọc -->
                    <div class="gb-table-card">
                        <!-- Toolbar Bộ lọc chuyên sâu -->
                        <div class="gb-table-header">
                            <form method="GET" action="index.php" class="row g-2 align-items-center">
                                <div class="col-12 col-md-4">
                                    <div class="input-group">
                                        <span class="input-group-text bg-white border-end-0 text-muted"><i class="fa fa-search"></i></span>
                                        <input type="text" name="tukhoa" class="form-control border-start-0 ps-0" placeholder="Mã đơn hàng, tên người nhận, SĐT..." value="<?= htmlspecialchars($tukhoa ?? '') ?>">
                                    </div>
                                </div>

                                <div class="col-6 col-md-2">
                                    <select name="trangthai" class="form-select" onchange="this.form.submit()">
                                        <option value="">-- Trạng thái đơn --</option>
                                        <option value="1" <?= ($trangthai === "1") ? "selected" : "" ?>>Mới (Chờ duyệt)</option>
                                        <option value="2" <?= ($trangthai === "2") ? "selected" : "" ?>>Đã xác nhận</option>
                                        <option value="3" <?= ($trangthai === "3") ? "selected" : "" ?>>Đang đóng gói</option>
                                        <option value="4" <?= ($trangthai === "4") ? "selected" : "" ?>>Đang giao hàng</option>
                                        <option value="5" <?= ($trangthai === "5") ? "selected" : "" ?>>Hoàn thành</option>
                                        <option value="6" <?= ($trangthai === "6") ? "selected" : "" ?>>Đã hủy</option>
                                    </select>
                                </div>

                                <div class="col-6 col-md-2">
                                    <select name="phuongthuc" class="form-select" onchange="this.form.submit()">
                                        <option value="">-- Phương thức TT --</option>
                                        <option value="cod" <?= ($phuongthuc === "cod") ? "selected" : "" ?>>COD (Khi nhận)</option>
                                        <option value="chuyenkhoan" <?= ($phuongthuc === "chuyenkhoan") ? "selected" : "" ?>>Chuyển khoản</option>
                                        <option value="vnpay" <?= ($phuongthuc === "vnpay") ? "selected" : "" ?>>VNPay</option>
                                    </select>
                                </div>

                                <div class="col-6 col-md-2">
                                    <select name="datt" class="form-select" onchange="this.form.submit()">
                                        <option value="">-- Thanh toán --</option>
                                        <option value="1" <?= ($datt === "1") ? "selected" : "" ?>>Đã thanh toán</option>
                                        <option value="0" <?= ($datt === "0") ? "selected" : "" ?>>Chưa thanh toán</option>
                                    </select>
                                </div>

                                <div class="col-6 col-md-2">
                                    <select name="sapxep" class="form-select" onchange="this.form.submit()">
                                        <option value="moinhat" <?= ($sapxep === "moinhat") ? "selected" : "" ?>>Mới nhất trước</option>
                                        <option value="cuNhat" <?= ($sapxep === "cuNhat") ? "selected" : "" ?>>Cũ nhất trước</option>
                                        <option value="giacao" <?= ($sapxep === "giacao") ? "selected" : "" ?>>Giá trị cao nhất</option>
                                        <option value="giathap" <?= ($sapxep === "giathap") ? "selected" : "" ?>>Giá trị thấp nhất</option>
                                    </select>
                                </div>
                            </form>
                        </div>

                        <!-- Bảng dữ liệu đơn hàng -->
                        <div class="table-responsive">
                            <table class="gb-table align-middle">
                                <thead>
                                    <tr>
                                        <th class="text-center" style="width: 50px;">STT</th>
                                        <th style="min-width: 150px;">Mã đơn hàng</th>
                                        <th style="min-width: 180px;">Khách hàng & SĐT</th>
                                        <th style="width: 140px;">Ngày đặt</th>
                                        <th class="text-center" style="width: 90px;">Mặt hàng</th>
                                        <th style="min-width: 140px;">Tổng tiền</th>
                                        <th style="min-width: 160px;">Thanh toán</th>
                                        <th class="text-center" style="min-width: 140px;">Trạng thái</th>
                                        <th class="text-center" style="width: 100px;">Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($danhsach)): ?>
                                        <?php $stt = 1; foreach ($danhsach as $item): ?>
                                            <?php
                                            $badgeTT = $dhModel->laybadgetrangthai($item["trangthai"]);
                                            $isDaThanhToan = ((int)$item["datt"] === 1);
                                            ?>
                                            <tr>
                                                <td class="text-center text-muted fw-semibold"><?= $stt++ ?></td>
                                                <td>
                                                    <a href="index.php?action=chitiet&id=<?= $item["id"] ?>" class="fw-bold text-primary font-monospace text-decoration-none" title="Bấm để xem chi tiết đơn hàng">
                                                        <?= htmlspecialchars($item["madonhang"] ?: ('#' . $item["id"])) ?>
                                                    </a>
                                                    <?php if (!empty($item["ghichu"])): ?>
                                                        <div class="text-truncate text-muted small mt-0.5" style="max-width: 160px;" title="Ghi chú: <?= htmlspecialchars($item["ghichu"]) ?>">
                                                            <i class="fa fa-note-sticky text-warning me-1"></i><?= htmlspecialchars($item["ghichu"]) ?>
                                                        </div>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <div class="fw-semibold text-dark"><?= htmlspecialchars($item["tennguoinhan"]) ?></div>
                                                    <div class="text-muted small">
                                                        <i class="fa fa-phone me-1" style="font-size: 0.75rem;"></i><?= htmlspecialchars($item["sdtnhan"]) ?>
                                                    </div>
                                                </td>
                                                <td class="small text-secondary">
                                                    <?= !empty($item["ngay"]) ? date("d/m/Y H:i", strtotime($item["ngay"])) : "—" ?>
                                                </td>
                                                <td class="text-center">
                                                    <span class="badge bg-slate-100 text-slate-700 border" title="Tổng số lượng mặt hàng">
                                                        <?= (int)$item["sosanpham"] ?> SP (<?= (int)($item["tongsoluong"] ?? $item["sosanpham"]) ?> cái)
                                                    </span>
                                                </td>
                                                <td>
                                                    <span class="fw-bold text-dark fs-6">
                                                        <?= number_format((float)$item["tongtien"], 0, ',', '.') ?> đ
                                                    </span>
                                                    <?php if ((float)$item["giamgia"] > 0): ?>
                                                        <div class="text-success small" style="font-size: 0.75rem;">
                                                            -<?= number_format((float)$item["giamgia"], 0, ',', '.') ?> đ (Voucher)
                                                        </div>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <div class="small fw-semibold text-secondary mb-1">
                                                        <?= htmlspecialchars($dhModel->laytenphuongthuc($item["phuongthuc_tt"])) ?>
                                                    </div>
                                                    <?php if ($isDaThanhToan): ?>
                                                        <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-0.5" style="font-size: 0.725rem;">
                                                            <i class="fa fa-check me-1"></i>Đã thanh toán
                                                        </span>
                                                    <?php else: ?>
                                                        <span class="badge bg-warning-subtle text-warning border border-warning-subtle px-2 py-0.5" style="font-size: 0.725rem;">
                                                            <i class="fa fa-hourglass-half me-1"></i>Chưa thanh toán
                                                        </span>
                                                    <?php endif; ?>
                                                </td>
                                                <td class="text-center">
                                                    <span class="badge <?= $badgeTT['class'] ?> px-2.5 py-1" style="font-size: 0.8rem; font-weight: 600;">
                                                        <i class="fa <?= $badgeTT['icon'] ?> me-1"></i><?= $badgeTT['ten'] ?>
                                                    </span>
                                                </td>
                                                <td class="text-center">
                                                    <div class="d-flex align-items-center justify-content-center gap-1">
                                                        <a href="index.php?action=chitiet&id=<?= $item["id"] ?>" 
                                                           class="btn-action-icon btn-action-edit" 
                                                           title="Xem chi tiết & Xử lý đơn hàng">
                                                            <i class="fa fa-eye"></i>
                                                        </a>
                                                        <?php if (isset($_SESSION["nguoidung"]) && (int)$_SESSION["nguoidung"]["loai"] === 1 && (int)$item["trangthai"] === 6): ?>
                                                            <a href="index.php?action=xoa&id=<?= $item["id"] ?>" 
                                                               class="btn-action-icon btn-action-delete" 
                                                               title="Xóa đơn hàng đã hủy"
                                                               onclick="return confirm('Bạn có chắc chắn muốn xóa đơn hàng đã hủy này khỏi hệ thống?');">
                                                                <i class="fa fa-trash"></i>
                                                            </a>
                                                        <?php endif; ?>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="9">
                                                <div class="gb-empty-state">
                                                    <div class="gb-empty-icon">
                                                        <i class="fa fa-box-open"></i>
                                                    </div>
                                                    <h5 class="fw-bold text-dark">Chưa tìm thấy đơn hàng phù hợp</h5>
                                                    <p class="text-muted small mb-3">
                                                        Không có đơn hàng nào khớp với điều kiện lọc hiện tại.
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

                        <!-- Footer tóm tắt bảng -->
                        <div class="p-3 border-top bg-light bg-opacity-50 d-flex flex-column flex-md-row justify-content-between align-items-center gap-2">
                            <span class="small text-muted">
                                Hiển thị <strong><?= count($danhsach) ?></strong> đơn hàng trong danh sách
                            </span>
                            <span class="small text-muted d-none d-md-inline">
                                GymBro Fitness Admin · Hệ thống xử lý đơn hàng & Vận chuyển
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

