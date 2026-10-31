<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Quản lý mã giảm giá - GymBro Admin</title>

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
                                <h1 class="gb-title mb-0">Quản lý mã giảm giá (Voucher)</h1>
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
                                Thiết lập các chương trình khuyến mãi, voucher giảm giá theo % hoặc tiền mặt cho khách hàng.
                            </p>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <button type="button" class="btn btn-primary d-inline-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#modalThemVoucher">
                                <i class="fa fa-plus-circle"></i>
                                <span>Tạo mã voucher mới</span>
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
                                        <div class="gb-stat-label">Tổng số Voucher</div>
                                        <div class="gb-stat-number"><?= number_format($thongke['tong'] ?? 0) ?></div>
                                        <div class="text-muted small mt-1">Đã dùng: <strong><?= number_format($thongke['tongluotdung'] ?? 0) ?></strong> lượt</div>
                                    </div>
                                    <div class="gb-icon-box" style="background-color: #eff6ff; color: #2563eb;">
                                        <i class="fa fa-ticket"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-6 col-xl-3">
                            <div class="gb-stat-card">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div>
                                        <div class="gb-stat-label">Đang áp dụng</div>
                                        <div class="gb-stat-number text-success"><?= number_format($thongke['dangapdung'] ?? 0) ?></div>
                                        <div class="text-muted small mt-1">Khách có thể nhập ngay</div>
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
                                        <div class="gb-stat-label">Đã hết hạn</div>
                                        <div class="gb-stat-number text-danger"><?= number_format($thongke['hethan'] ?? 0) ?></div>
                                        <div class="text-muted small mt-1">Quá thời hạn quy định</div>
                                    </div>
                                    <div class="gb-icon-box" style="background-color: #fef2f2; color: #dc2626;">
                                        <i class="fa fa-clock"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-6 col-xl-3">
                            <div class="gb-stat-card">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div>
                                        <div class="gb-stat-label">Hết lượt dùng</div>
                                        <div class="gb-stat-number text-warning"><?= number_format($thongke['hetluot'] ?? 0) ?></div>
                                        <div class="text-muted small mt-1">Đạt giới hạn phát hành</div>
                                    </div>
                                    <div class="gb-icon-box" style="background-color: #fffbeb; color: #d97706;">
                                        <i class="fa fa-triangle-exclamation"></i>
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
                                
                                <div class="col-md-4">
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0 text-muted"><i class="fa fa-search"></i></span>
                                        <input type="text" name="tukhoa" id="inputFilterLiveVc" class="form-control bg-light border-start-0" 
                                               placeholder="Tìm mã voucher, mô tả..." value="<?= htmlspecialchars($_GET["tukhoa"] ?? "") ?>">
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <select name="loai" class="form-select bg-light" onchange="this.form.submit()">
                                        <option value="">-- Tất cả hình thức giảm --</option>
                                        <option value="phantram" <?= (isset($_GET["loai"]) && $_GET["loai"] === "phantram") ? "selected" : "" ?>>Giảm theo phần trăm (%)</option>
                                        <option value="sotien" <?= (isset($_GET["loai"]) && $_GET["loai"] === "sotien") ? "selected" : "" ?>>Giảm theo số tiền (VNĐ)</option>
                                    </select>
                                </div>

                                <div class="col-md-3">
                                    <select name="trangthai" class="form-select bg-light" onchange="this.form.submit()">
                                        <option value="">-- Tất cả trạng thái --</option>
                                        <option value="active" <?= (isset($_GET["trangthai"]) && $_GET["trangthai"] === "active") ? "selected" : "" ?>>Đang áp dụng</option>
                                        <option value="expired" <?= (isset($_GET["trangthai"]) && $_GET["trangthai"] === "expired") ? "selected" : "" ?>>Đã hết hạn</option>
                                        <option value="outofstock" <?= (isset($_GET["trangthai"]) && $_GET["trangthai"] === "outofstock") ? "selected" : "" ?>>Hết lượt dùng</option>
                                        <option value="disabled" <?= (isset($_GET["trangthai"]) && $_GET["trangthai"] === "disabled") ? "selected" : "" ?>>Tạm dừng/Khóa</option>
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
                                <table class="table table-hover align-middle mb-0" id="tableVoucher">
                                    <thead class="table-light">
                                        <tr>
                                            <th class="text-center" style="width: 50px;">STT</th>
                                            <th style="width: 140px;">Mã Voucher</th>
                                            <th>Mô tả / Mức giảm</th>
                                            <th>Đơn tối thiểu</th>
                                            <th style="width: 160px;">Tiến độ sử dụng</th>
                                            <th style="width: 150px;">Hạn sử dụng</th>
                                            <th class="text-center" style="width: 130px;">Trạng thái</th>
                                            <th class="text-center" style="width: 130px;">Thao tác</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (empty($vouchers)): ?>
                                            <tr>
                                                <td colspan="8" class="text-center py-5 text-muted">
                                                    <div class="mb-2"><i class="fa fa-ticket fs-1 opacity-25"></i></div>
                                                    <p class="mb-0">Không tìm thấy mã giảm giá nào phù hợp.</p>
                                                </td>
                                            </tr>
                                        <?php else: ?>
                                            <?php 
                                            $stt = 1; 
                                            $now = time();
                                            foreach ($vouchers as $item): 
                                                $expireTime = strtotime($item["ngayhethan"]);
                                                $isExpired = $expireTime <= $now;
                                                $isOutOfStock = (int)$item["dasudung"] >= (int)$item["soluong"];
                                                $percentUsed = ((int)$item["soluong"] > 0) ? round(((int)$item["dasudung"] / (int)$item["soluong"]) * 100) : 100;
                                            ?>
                                                <tr>
                                                    <td class="text-center fw-bold text-muted"><?= $stt++ ?></td>
                                                    <td>
                                                        <span class="badge bg-dark-subtle text-dark border px-2.5 py-1.5 font-monospace fs-6">
                                                            <?= htmlspecialchars($item["ma"]) ?>
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <div class="fw-bold text-dark">
                                                            <?php if ($item["loai"] === "phantram"): ?>
                                                                <span class="text-danger fw-bold">Giảm <?= (float)$item["giatri"] ?>%</span>
                                                                <?php if (!empty($item["giamtoida"]) && (float)$item["giamtoida"] > 0): ?>
                                                                    <span class="text-muted small">(Tối đa <?= number_format((float)$item["giamtoida"], 0, ',', '.') ?>đ)</span>
                                                                <?php endif; ?>
                                                            <?php else: ?>
                                                                <span class="text-success fw-bold">Giảm <?= number_format((float)$item["giatri"], 0, ',', '.') ?>đ</span>
                                                            <?php endif; ?>
                                                        </div>
                                                        <span class="text-muted small"><?= htmlspecialchars($item["mota"] ?? 'Ưu đãi mua sắm') ?></span>
                                                    </td>
                                                    <td>
                                                        <?php if ((float)$item["donhang_toithieu"] > 0): ?>
                                                            <span class="fw-semibold text-secondary">Từ <?= number_format((float)$item["donhang_toithieu"], 0, ',', '.') ?>đ</span>
                                                        <?php else: ?>
                                                            <span class="badge bg-light text-muted border">Không giới hạn</span>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td>
                                                        <div class="d-flex justify-content-between small mb-1">
                                                            <span class="text-muted">Đã dùng: <strong><?= number_format($item["dasudung"]) ?></strong></span>
                                                            <span class="text-muted">Tổng: <strong><?= number_format($item["soluong"]) ?></strong></span>
                                                        </div>
                                                        <div class="progress" style="height: 6px;">
                                                            <div class="progress-bar <?= $percentUsed >= 100 ? 'bg-danger' : ($percentUsed >= 80 ? 'bg-warning' : 'bg-success') ?>" 
                                                                 role="progressbar" style="width: <?= min($percentUsed, 100) ?>%"></div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="small fw-semibold <?= $isExpired ? 'text-danger' : 'text-dark' ?>">
                                                            <?= date('d/m/Y H:i', $expireTime) ?>
                                                        </div>
                                                        <?php if ($isExpired): ?>
                                                            <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2 py-0 small">Đã hết hạn</span>
                                                        <?php elseif ($isOutOfStock): ?>
                                                            <span class="badge bg-warning-subtle text-warning border border-warning-subtle px-2 py-0 small">Hết lượt</span>
                                                        <?php else: ?>
                                                            <span class="text-muted small">Còn <?= ceil(($expireTime - $now) / 86400) ?> ngày</span>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td class="text-center">
                                                        <?php if ((int)$item["trangthai"] === 1 && !$isExpired && !$isOutOfStock): ?>
                                                            <a href="index.php?action=doitrangthai&id=<?= $item['id'] ?>&tt=1" 
                                                               class="badge bg-success-subtle text-success border border-success-subtle text-decoration-none px-2.5 py-1.5 rounded"
                                                               title="Bấm để tạm dừng">
                                                                <i class="fa fa-check-circle me-1"></i> Hoạt động
                                                            </a>
                                                        <?php elseif ((int)$item["trangthai"] === 1): ?>
                                                            <span class="badge bg-secondary-subtle text-secondary border px-2.5 py-1.5 rounded">Không khả dụng</span>
                                                        <?php else: ?>
                                                            <a href="index.php?action=doitrangthai&id=<?= $item['id'] ?>&tt=0" 
                                                               class="badge bg-danger-subtle text-danger border border-danger-subtle text-decoration-none px-2.5 py-1.5 rounded"
                                                               title="Bấm để kích hoạt lại">
                                                                <i class="fa fa-pause me-1"></i> Tạm dừng
                                                            </a>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td class="text-center">
                                                        <div class="btn-group btn-group-sm">
                                                            <button type="button" class="btn btn-outline-primary btn-sua-vc" 
                                                                    data-id="<?= $item['id'] ?>"
                                                                    data-ma="<?= htmlspecialchars($item['ma'], ENT_QUOTES) ?>"
                                                                    data-mota="<?= htmlspecialchars($item['mota'] ?? '', ENT_QUOTES) ?>"
                                                                    data-loai="<?= $item['loai'] ?>"
                                                                    data-giatri="<?= $item['giatri'] ?>"
                                                                    data-donhang_toithieu="<?= $item['donhang_toithieu'] ?>"
                                                                    data-giamtoida="<?= $item['giamtoida'] ?? '' ?>"
                                                                    data-ngayhethan="<?= date('Y-m-d\TH:i', $expireTime) ?>"
                                                                    data-soluong="<?= $item['soluong'] ?>"
                                                                    data-tt="<?= $item['trangthai'] ?>"
                                                                    title="Chỉnh sửa voucher">
                                                                <i class="fa fa-pen-to-square"></i>
                                                            </button>
                                                            
                                                            <?php if ($isAdmin): ?>
                                                                <a href="index.php?action=xoa&id=<?= $item['id'] ?>" 
                                                                   class="btn btn-outline-danger" 
                                                                   onclick="return confirm('Bạn có chắc chắn muốn xóa voucher <?= htmlspecialchars($item['ma']) ?>?');"
                                                                   title="Xóa voucher">
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
                                Hiển thị <strong><?= count($vouchers) ?></strong> mã giảm giá trong danh sách
                            </span>
                            <span class="small text-muted d-none d-md-inline">
                                GymBro Fitness Admin · Quản lý Chương trình Khuyến mãi
                            </span>
                        </div>
                    </div>

                </div>
            </main>

            <?php include('../inc/footer.php'); ?>
        </div>
    </div>

    <!-- Modal Tạo Voucher Mới -->
    <div class="modal fade" id="modalThemVoucher" tabindex="-1" aria-labelledby="modalThemVcLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 shadow">
                <form action="index.php?action=xulythem" method="POST">
                    <div class="modal-header bg-light">
                        <h5 class="modal-title fw-bold" id="modalThemVcLabel">
                            <i class="fa fa-plus-circle text-primary me-2"></i>Tạo mã giảm giá mới
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-4">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Mã Voucher <span class="text-danger">*</span></label>
                                <input type="text" name="ma" class="form-control text-uppercase font-monospace" required placeholder="VD: GYM10, FREESHIP50..." style="letter-spacing: 1px;">
                                <div class="form-text">Mã viết liền không dấu, hệ thống tự động in hoa.</div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold">Hình thức giảm giá <span class="text-danger">*</span></label>
                                <select name="loai" id="selectLoaiAdd" class="form-select" onchange="toggleGiamToiDaAdd()">
                                    <option value="phantram">Giảm theo phần trăm (%)</option>
                                    <option value="sotien">Giảm theo số tiền (VNĐ)</option>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold" id="labelGiaTriAdd">Mức giảm (%) <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="number" name="giatri" id="inputGiaTriAdd" class="form-control" required min="1" step="any" placeholder="Nhập % giảm (1-100)">
                                    <span class="input-group-text" id="unitGiaTriAdd">%</span>
                                </div>
                            </div>

                            <div class="col-md-6" id="wrapperGiamToiDaAdd">
                                <label class="form-label fw-bold">Mức giảm tối đa (VNĐ)</label>
                                <div class="input-group">
                                    <input type="number" name="giamtoida" class="form-control" min="0" step="1000" placeholder="VD: 100000 (Để trống nếu không giới hạn)">
                                    <span class="input-group-text">đ</span>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold">Đơn hàng tối thiểu (VNĐ)</label>
                                <div class="input-group">
                                    <input type="number" name="donhang_toithieu" class="form-control" value="0" min="0" step="1000">
                                    <span class="input-group-text">đ</span>
                                </div>
                                <div class="form-text">Giá trị đơn tạm tính tối thiểu để áp dụng mã.</div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold">Tổng số lượng phát hành <span class="text-danger">*</span></label>
                                <input type="number" name="soluong" class="form-control" required min="1" value="100" placeholder="VD: 100">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold">Ngày & giờ hết hạn <span class="text-danger">*</span></label>
                                <input type="datetime-local" name="ngayhethan" class="form-control" required value="<?= date('Y-m-d\T23:59', strtotime('+30 days')) ?>">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold">Trạng thái phát hành</label>
                                <select name="trangthai" class="form-select">
                                    <option value="1">Kích hoạt ngay</option>
                                    <option value="0">Tạm dừng (Lưu nháp)</option>
                                </select>
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-bold">Mô tả / Thể lệ chương trình</label>
                                <textarea name="mota" class="form-control" rows="2" placeholder="Ví dụ: Giảm 10% tối đa 100k cho đơn từ 300k..."></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer bg-light">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy bỏ</button>
                        <button type="submit" class="btn btn-primary d-inline-flex align-items-center gap-2">
                            <i class="fa fa-floppy-disk"></i>
                            <span>Lưu mã voucher</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Sửa Voucher -->
    <div class="modal fade" id="modalSuaVoucher" tabindex="-1" aria-labelledby="modalSuaVcLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 shadow">
                <form action="index.php?action=xulysua" method="POST">
                    <input type="hidden" name="id" id="edit_vc_id">
                    <div class="modal-header bg-light">
                        <h5 class="modal-title fw-bold" id="modalSuaVcLabel">
                            <i class="fa fa-pen-to-square text-primary me-2"></i>Chỉnh sửa mã voucher
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-4">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Mã Voucher <span class="text-danger">*</span></label>
                                <input type="text" name="ma" id="edit_vc_ma" class="form-control text-uppercase font-monospace" required style="letter-spacing: 1px;">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold">Hình thức giảm giá <span class="text-danger">*</span></label>
                                <select name="loai" id="edit_vc_loai" class="form-select" onchange="toggleGiamToiDaEdit()">
                                    <option value="phantram">Giảm theo phần trăm (%)</option>
                                    <option value="sotien">Giảm theo số tiền (VNĐ)</option>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold" id="labelGiaTriEdit">Mức giảm <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="number" name="giatri" id="edit_vc_giatri" class="form-control" required min="1" step="any">
                                    <span class="input-group-text" id="unitGiaTriEdit">%</span>
                                </div>
                            </div>

                            <div class="col-md-6" id="wrapperGiamToiDaEdit">
                                <label class="form-label fw-bold">Mức giảm tối đa (VNĐ)</label>
                                <div class="input-group">
                                    <input type="number" name="giamtoida" id="edit_vc_giamtoida" class="form-control" min="0" step="1000">
                                    <span class="input-group-text">đ</span>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold">Đơn hàng tối thiểu (VNĐ)</label>
                                <div class="input-group">
                                    <input type="number" name="donhang_toithieu" id="edit_vc_donhang_toithieu" class="form-control" min="0" step="1000">
                                    <span class="input-group-text">đ</span>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold">Tổng số lượng phát hành <span class="text-danger">*</span></label>
                                <input type="number" name="soluong" id="edit_vc_soluong" class="form-control" required min="1">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold">Ngày & giờ hết hạn <span class="text-danger">*</span></label>
                                <input type="datetime-local" name="ngayhethan" id="edit_vc_ngayhethan" class="form-control" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold">Trạng thái phát hành</label>
                                <select name="trangthai" id="edit_vc_trangthai" class="form-select">
                                    <option value="1">Kích hoạt áp dụng</option>
                                    <option value="0">Tạm dừng phát hành</option>
                                </select>
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-bold">Mô tả / Thể lệ chương trình</label>
                                <textarea name="mota" id="edit_vc_mota" class="form-control" rows="2"></textarea>
                            </div>
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
        const inputLive = document.getElementById("inputFilterLiveVc");
        const tableBody = document.querySelector("#tableVoucher tbody");
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

        const modalSuaEl = document.getElementById('modalSuaVoucher');
        const modalSua = new bootstrap.Modal(modalSuaEl);
        
        document.querySelectorAll(".btn-sua-vc").forEach(btn => {
            btn.addEventListener("click", function() {
                document.getElementById("edit_vc_id").value = this.getAttribute("data-id");
                document.getElementById("edit_vc_ma").value = this.getAttribute("data-ma");
                document.getElementById("edit_vc_mota").value = this.getAttribute("data-mota");
                document.getElementById("edit_vc_loai").value = this.getAttribute("data-loai");
                document.getElementById("edit_vc_giatri").value = this.getAttribute("data-giatri");
                document.getElementById("edit_vc_donhang_toithieu").value = this.getAttribute("data-donhang_toithieu");
                document.getElementById("edit_vc_giamtoida").value = this.getAttribute("data-giamtoida");
                document.getElementById("edit_vc_ngayhethan").value = this.getAttribute("data-ngayhethan");
                document.getElementById("edit_vc_soluong").value = this.getAttribute("data-soluong");
                document.getElementById("edit_vc_trangthai").value = this.getAttribute("data-tt");

                toggleGiamToiDaEdit();
                modalSua.show();
            });
        });
    });

    function toggleGiamToiDaAdd() {
        const loai = document.getElementById("selectLoaiAdd").value;
        const wrapper = document.getElementById("wrapperGiamToiDaAdd");
        const unit = document.getElementById("unitGiaTriAdd");
        const label = document.getElementById("labelGiaTriAdd");
        if (loai === 'phantram') {
            wrapper.style.display = "block";
            unit.textContent = "%";
            label.innerHTML = 'Mức giảm (%) <span class="text-danger">*</span>';
        } else {
            wrapper.style.display = "none";
            unit.textContent = "đ";
            label.innerHTML = 'Mức giảm (VNĐ) <span class="text-danger">*</span>';
        }
    }

    function toggleGiamToiDaEdit() {
        const loai = document.getElementById("edit_vc_loai").value;
        const wrapper = document.getElementById("wrapperGiamToiDaEdit");
        const unit = document.getElementById("unitGiaTriEdit");
        const label = document.getElementById("labelGiaTriEdit");
        if (loai === 'phantram') {
            wrapper.style.display = "block";
            unit.textContent = "%";
            label.innerHTML = 'Mức giảm (%) <span class="text-danger">*</span>';
        } else {
            wrapper.style.display = "none";
            unit.textContent = "đ";
            label.innerHTML = 'Mức giảm (VNĐ) <span class="text-danger">*</span>';
        }
    }
    </script>
</body>

</html>
