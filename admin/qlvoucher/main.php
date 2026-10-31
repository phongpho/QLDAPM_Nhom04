<?php
$title = "Quản lý mã giảm giá - GymBro Admin";
include("../inc/header.php");
?>

<div class="container-fluid p-0">
    <div class="row mb-3 align-items-center">
        <div class="col-md-6">
            <h1 class="h3 mb-0 text-gray-800">
                <i class="align-middle me-1 text-primary" data-feather="tag"></i> Quản lý Mã giảm giá (Voucher)
            </h1>
            <p class="text-muted small mb-0">Thiết lập các chương trình khuyến mãi, voucher giảm giá cho khách mua hàng</p>
        </div>
        <div class="col-md-6 text-md-end mt-3 mt-md-0">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalThemVoucher">
                <i class="align-middle me-1" data-feather="plus-circle"></i> Tạo mã voucher mới
            </button>
        </div>
    </div>

    <!-- Thông báo nếu có -->
    <?php if (isset($_SESSION["thongbao"])): ?>
        <div class="alert alert-<?= htmlspecialchars($_SESSION["thongbao"]["loai"]) ?> alert-dismissible fade show" role="alert">
            <i class="align-middle me-2" data-feather="<?= $_SESSION["thongbao"]["loai"] === 'success' ? 'check-circle' : 'alert-circle' ?>"></i>
            <?= htmlspecialchars($_SESSION["thongbao"]["noidung"]) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php unset($_SESSION["thongbao"]); ?>
    <?php endif; ?>

    <!-- KPI Metric Cards -->
    <div class="row mb-4">
        <div class="col-sm-6 col-xl-3 mb-3 mb-xl-0">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <div class="row">
                        <div class="col mt-0">
                            <h5 class="card-title text-muted">Tổng số Voucher</h5>
                        </div>
                        <div class="col-auto">
                            <div class="stat text-primary bg-primary-subtle rounded-3 p-2">
                                <i class="align-middle" data-feather="tag"></i>
                            </div>
                        </div>
                    </div>
                    <h1 class="mt-1 mb-2 fw-bold text-primary"><?= number_format($thongke['tong'] ?? 0) ?></h1>
                    <div class="mb-0 text-muted small">
                        <span>Đã dùng: <strong><?= number_format($thongke['tongluotdung'] ?? 0) ?></strong> lượt</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-xl-3 mb-3 mb-xl-0">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <div class="row">
                        <div class="col mt-0">
                            <h5 class="card-title text-muted">Đang áp dụng</h5>
                        </div>
                        <div class="col-auto">
                            <div class="stat text-success bg-success-subtle rounded-3 p-2">
                                <i class="align-middle" data-feather="check-circle"></i>
                            </div>
                        </div>
                    </div>
                    <h1 class="mt-1 mb-2 fw-bold text-success"><?= number_format($thongke['dangapdung'] ?? 0) ?></h1>
                    <div class="mb-0 text-muted small">
                        <span>Khách có thể nhập ngay</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-xl-3 mb-3 mb-xl-0">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <div class="row">
                        <div class="col mt-0">
                            <h5 class="card-title text-muted">Đã hết hạn</h5>
                        </div>
                        <div class="col-auto">
                            <div class="stat text-danger bg-danger-subtle rounded-3 p-2">
                                <i class="align-middle" data-feather="clock"></i>
                            </div>
                        </div>
                    </div>
                    <h1 class="mt-1 mb-2 fw-bold text-danger"><?= number_format($thongke['hethan'] ?? 0) ?></h1>
                    <div class="mb-0 text-muted small">
                        <span>Quá thời hạn quy định</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-xl-3 mb-3 mb-xl-0">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <div class="row">
                        <div class="col mt-0">
                            <h5 class="card-title text-muted">Hết lượt dùng</h5>
                        </div>
                        <div class="col-auto">
                            <div class="stat text-warning bg-warning-subtle rounded-3 p-2">
                                <i class="align-middle" data-feather="alert-triangle"></i>
                            </div>
                        </div>
                    </div>
                    <h1 class="mt-1 mb-2 fw-bold text-warning"><?= number_format($thongke['hetluot'] ?? 0) ?></h1>
                    <div class="mb-0 text-muted small">
                        <span>Đạt giới hạn số lượng phát hành</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bảng danh sách & Bộ lọc -->
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white py-3 border-bottom">
            <form method="GET" action="index.php" class="row g-2 align-items-center">
                <input type="hidden" name="action" value="macdinh">
                
                <div class="col-md-4">
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0"><i data-feather="search" class="align-middle text-muted"></i></span>
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
                        <i class="align-middle me-1" data-feather="rotate-ccw"></i> Làm mới
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
                            <th>Mô tả / Hình thức giảm</th>
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
                                    <i data-feather="tag" style="width: 48px; height: 48px; stroke-width: 1.5;" class="mb-2"></i>
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
                                        <span class="badge bg-dark-subtle text-dark border px-2 py-1 font-monospace fs-6">
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
                                            <span class="badge bg-danger-subtle text-danger px-2 py-0 small">Đã hết hạn</span>
                                        <?php elseif ($isOutOfStock): ?>
                                            <span class="badge bg-warning-subtle text-warning px-2 py-0 small">Hết lượt</span>
                                        <?php else: ?>
                                            <span class="text-muted small">Còn <?= ceil(($expireTime - $now) / 86400) ?> ngày</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center">
                                        <?php if ((int)$item["trangthai"] === 1 && !$isExpired && !$isOutOfStock): ?>
                                            <a href="index.php?action=doitrangthai&id=<?= $item['id'] ?>&tt=1" 
                                               class="badge bg-success-subtle text-success text-decoration-none px-2 py-1 rounded"
                                               title="Bấm để tạm dừng">
                                                <i class="align-middle me-1" data-feather="check" style="width: 12px; height: 12px;"></i> Hoạt động
                                            </a>
                                        <?php elseif ((int)$item["trangthai"] === 1): ?>
                                            <span class="badge bg-secondary-subtle text-secondary px-2 py-1 rounded">Không khả dụng</span>
                                        <?php else: ?>
                                            <a href="index.php?action=doitrangthai&id=<?= $item['id'] ?>&tt=0" 
                                               class="badge bg-danger-subtle text-danger text-decoration-none px-2 py-1 rounded"
                                               title="Bấm để kích hoạt lại">
                                                <i class="align-middle me-1" data-feather="pause" style="width: 12px; height: 12px;"></i> Tạm dừng
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
                                                <i class="align-middle" data-feather="edit-2"></i>
                                            </button>
                                            
                                            <?php if ($isAdmin): ?>
                                                <a href="index.php?action=xoa&id=<?= $item['id'] ?>" 
                                                   class="btn btn-outline-danger" 
                                                   onclick="return confirm('Bạn có chắc chắn muốn xóa voucher <?= htmlspecialchars($item['ma']) ?>?');"
                                                   title="Xóa voucher">
                                                    <i class="align-middle" data-feather="trash-2"></i>
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
    </div>
</div>

<!-- Modal Tạo Voucher Mới -->
<div class="modal fade" id="modalThemVoucher" tabindex="-1" aria-labelledby="modalThemVcLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow">
            <form action="index.php?action=xulythem" method="POST">
                <div class="modal-header bg-light">
                    <h5 class="modal-title fw-bold" id="modalThemVcLabel">
                        <i class="align-middle me-1 text-primary" data-feather="plus-circle"></i> Tạo mã giảm giá mới
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
                    <button type="submit" class="btn btn-primary">
                        <i class="align-middle me-1" data-feather="save"></i> Lưu mã voucher
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
                        <i class="align-middle me-1 text-primary" data-feather="edit-2"></i> Chỉnh sửa mã voucher
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
                    <button type="submit" class="btn btn-primary">
                        <i class="align-middle me-1" data-feather="check"></i> Cập nhật thay đổi
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    // 1. Live Filter bảng Voucher
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

    // 2. Click button Sửa -> Populate Modal Sửa
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

<?php include("../inc/footer.php"); ?>
