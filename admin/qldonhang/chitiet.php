<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Kiểm tra quyền Admin (1) hoặc Nhân viên (2)
if (!isset($_SESSION["nguoidung"]) || !in_array((int)$_SESSION["nguoidung"]["loai"], [1, 2])) {
    header("Location: ../ktnguoidung/index.php?action=dangnhap");
    exit();
}

$badgeTT = $dhModel->laybadgetrangthai($donhang["trangthai"]);
$isDaThanhToan = ((int)$donhang["datt"] === 1);
$currentStep = (int)$donhang["trangthai"];
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Chi tiết đơn hàng <?= htmlspecialchars($donhang["madonhang"] ?: ('#' . $donhang["id"])) ?> - GymBro Admin</title>

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

    <style>
        /* Timeline Stepper cho quy trình đơn hàng */
        .order-stepper {
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: relative;
            margin: 1.5rem 0 0.5rem;
        }
        .order-stepper::before {
            content: "";
            position: absolute;
            top: 22px;
            left: 5%;
            right: 5%;
            height: 3px;
            background-color: var(--gb-slate-200);
            z-index: 1;
        }
        .order-step {
            position: relative;
            z-index: 2;
            text-align: center;
            flex: 1;
        }
        .order-step-circle {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            background-color: var(--gb-white);
            border: 3px solid var(--gb-slate-300);
            color: var(--gb-slate-400);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            transition: var(--gb-transition);
        }
        .order-step.completed .order-step-circle {
            background-color: var(--gb-success);
            border-color: var(--gb-success);
            color: #ffffff;
        }
        .order-step.active .order-step-circle {
            background-color: var(--gb-primary);
            border-color: var(--gb-primary);
            color: #ffffff;
            box-shadow: 0 0 0 5px rgba(37, 99, 235, 0.2);
        }
        .order-step.cancelled .order-step-circle {
            background-color: var(--gb-danger);
            border-color: var(--gb-danger);
            color: #ffffff;
        }
        .order-step-label {
            font-size: 0.775rem;
            font-weight: 700;
            color: var(--gb-slate-600);
        }
        .order-step.active .order-step-label {
            color: var(--gb-primary);
        }
        .order-step.completed .order-step-label {
            color: var(--gb-success);
        }
        .order-step.cancelled .order-step-label {
            color: var(--gb-danger);
        }

        /* In hóa đơn Clean Print Styles */
        @media print {
            body {
                background: #ffffff !important;
            }
            .sidebar, .navbar, .gb-breadcrumb, .btn, .no-print {
                display: none !important;
            }
            .main, .content {
                padding: 0 !important;
                margin: 0 !important;
            }
            .gb-edit-card, .gb-card {
                border: none !important;
                box-shadow: none !important;
            }
        }
    </style>
</head>

<body>
    <div class="wrapper">
        <?php include('../inc/sidebar.php'); ?>

        <div class="main">
            <?php include('../inc/header.php'); ?>

            <main class="content">
                <div class="container-fluid p-0">

                    <!-- Breadcrumbs & Navigation -->
                    <div class="gb-breadcrumb justify-content-between no-print">
                        <div class="d-flex align-items-center gap-2">
                            <a href="index.php"><i class="fa fa-truck me-1"></i>Quản lý đơn hàng</a>
                            <i class="fa fa-chevron-right" style="font-size: 0.65rem;"></i>
                            <span class="text-dark fw-semibold">Đơn hàng <?= htmlspecialchars($donhang["madonhang"] ?: ('#' . $donhang["id"])) ?></span>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <button onclick="window.print()" class="btn btn-sm btn-light border text-secondary fw-semibold">
                                <i class="fa fa-print me-1"></i> In đơn hàng
                            </button>
                            <a href="index.php" class="btn btn-sm btn-light border text-secondary fw-semibold">
                                <i class="fa fa-arrow-left me-1"></i> Danh sách
                            </a>
                        </div>
                    </div>

                    <!-- Flash Messages -->
                    <?php if (isset($_SESSION["thongbao"])): ?>
                        <div class="alert alert-success alert-dismissible fade show shadow-sm border-0 d-flex align-items-center gap-2 mb-4 no-print" role="alert" style="background-color: #ecfdf5; color: #065f46; border-left: 4px solid #10b981 !important;">
                            <i class="fa fa-circle-check fs-5"></i>
                            <div><strong>Thành công:</strong> <?= htmlspecialchars($_SESSION["thongbao"]) ?></div>
                            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                        <?php unset($_SESSION["thongbao"]); ?>
                    <?php endif; ?>

                    <?php if (isset($_SESSION["loi"])): ?>
                        <div class="alert alert-danger alert-dismissible fade show shadow-sm border-0 d-flex align-items-center gap-2 mb-4 no-print" role="alert" style="background-color: #fef2f2; color: #991b1b; border-left: 4px solid #ef4444 !important;">
                            <i class="fa fa-triangle-exclamation fs-5"></i>
                            <div><strong>Cảnh báo:</strong> <?= htmlspecialchars($_SESSION["loi"]) ?></div>
                            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                        <?php unset($_SESSION["loi"]); ?>
                    <?php endif; ?>

                    <!-- Header Banner của Đơn hàng -->
                    <div class="gb-card mb-4">
                        <div class="p-4 d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                            <div>
                                <div class="d-flex align-items-center gap-2 flex-wrap mb-1">
                                    <h2 class="gb-title mb-0">Đơn hàng <?= htmlspecialchars($donhang["madonhang"] ?: ('#' . $donhang["id"])) ?></h2>
                                    <span class="badge <?= $badgeTT['class'] ?> px-2.5 py-1" style="font-size: 0.85rem;">
                                        <i class="fa <?= $badgeTT['icon'] ?> me-1"></i><?= $badgeTT['ten'] ?>
                                    </span>
                                    <?php if ($isDaThanhToan): ?>
                                        <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1" style="font-size: 0.8rem;">
                                            <i class="fa fa-check me-1"></i>Đã thanh toán
                                        </span>
                                    <?php else: ?>
                                        <span class="badge bg-warning-subtle text-warning border border-warning-subtle px-2 py-1" style="font-size: 0.8rem;">
                                            <i class="fa fa-hourglass-half me-1"></i>Chưa thanh toán
                                        </span>
                                    <?php endif; ?>
                                </div>
                                <p class="text-muted small mb-0">
                                    Thời gian đặt hàng: <strong><?= !empty($donhang["ngay"]) ? date("d/m/Y H:i:s", strtotime($donhang["ngay"])) : "—" ?></strong>
                                    · Phương thức: <strong><?= htmlspecialchars($dhModel->laytenphuongthuc($donhang["phuongthuc_tt"])) ?></strong>
                                </p>
                            </div>

                            <!-- Thao tác nhanh trạng thái -->
                            <div class="d-flex align-items-center gap-2 no-print flex-wrap">
                                <?php if ($currentStep < 5): ?>
                                    <form method="POST" action="index.php" class="d-inline">
                                        <input type="hidden" name="action" value="capnhattrangthai">
                                        <input type="hidden" name="txtid" value="<?= (int)$donhang["id"] ?>">
                                        <input type="hidden" name="redirect" value="index.php?action=chitiet&id=<?= (int)$donhang["id"] ?>">

                                        <?php if ($currentStep === 1): ?>
                                            <input type="hidden" name="opttrangthai" value="2">
                                            <button type="submit" class="btn btn-gb-primary">
                                                <i class="fa fa-check me-1"></i> Xác nhận đơn này
                                            </button>
                                        <?php elseif ($currentStep === 2): ?>
                                            <input type="hidden" name="opttrangthai" value="3">
                                            <button type="submit" class="btn btn-primary">
                                                <i class="fa fa-box me-1"></i> Bắt đầu đóng gói
                                            </button>
                                        <?php elseif ($currentStep === 3): ?>
                                            <input type="hidden" name="opttrangthai" value="4">
                                            <button type="submit" class="btn btn-warning text-dark fw-semibold">
                                                <i class="fa fa-truck-fast me-1"></i> Bàn giao vận chuyển
                                            </button>
                                        <?php elseif ($currentStep === 4): ?>
                                            <input type="hidden" name="opttrangthai" value="5">
                                            <button type="submit" class="btn btn-success fw-semibold" onclick="return confirm('Xác nhận khách đã nhận hàng và hoàn tất đơn hàng này?');">
                                                <i class="fa fa-circle-check me-1"></i> Hoàn thành đơn
                                            </button>
                                        <?php endif; ?>
                                    </form>
                                <?php endif; ?>

                                <?php if ($currentStep !== 5 && $currentStep !== 6): ?>
                                    <a href="index.php?action=capnhattrangthai&id=<?= $donhang["id"] ?>&trangthai=6&redirect=index.php?action=chitiet&id=<?= $donhang["id"] ?>" 
                                       class="btn btn-outline-danger btn-sm"
                                       onclick="return confirm('Bạn có chắc chắn muốn HỦY đơn hàng này?');">
                                        <i class="fa fa-ban me-1"></i> Hủy đơn
                                    </a>
                                <?php endif; ?>

                                <?php if (!$isDaThanhToan): ?>
                                    <a href="index.php?action=capnhatdatt&id=<?= $donhang["id"] ?>&datt=1&redirect=index.php?action=chitiet&id=<?= $donhang["id"] ?>" 
                                       class="btn btn-outline-success btn-sm"
                                       title="Đánh dấu đã nhận tiền">
                                        <i class="fa fa-dollar-sign me-1"></i> Đã nhận tiền
                                    </a>
                                <?php else: ?>
                                    <a href="index.php?action=capnhatdatt&id=<?= $donhang["id"] ?>&datt=0&redirect=index.php?action=chitiet&id=<?= $donhang["id"] ?>" 
                                       class="btn btn-outline-secondary btn-sm"
                                       title="Chuyển về chưa nhận tiền">
                                        <i class="fa fa-rotate-left me-1"></i> Bỏ thanh toán
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Timeline Stepper -->
                        <div class="px-4 pb-4 border-top pt-3">
                            <div class="small fw-bold text-muted mb-2 text-uppercase" style="letter-spacing: 0.05em;">
                                <i class="fa fa-timeline text-primary me-1"></i>Tiến trình xử lý đơn hàng
                            </div>

                            <?php if ($currentStep === 6): ?>
                                <div class="p-3 bg-danger-subtle text-danger border border-danger-subtle rounded-3 d-flex align-items-center gap-2">
                                    <i class="fa fa-circle-xmark fs-4"></i>
                                    <div>
                                        <strong>Đơn hàng đã bị hủy.</strong> Không thể tiếp tục tiến trình vận chuyển hoặc bàn giao.
                                    </div>
                                </div>
                            <?php else: ?>
                                <div class="order-stepper">
                                    <div class="order-step <?= ($currentStep >= 1) ? (($currentStep > 1) ? 'completed' : 'active') : '' ?>">
                                        <div class="order-step-circle">
                                            <i class="fa <?= ($currentStep > 1) ? 'fa-check' : 'fa-receipt' ?>"></i>
                                        </div>
                                        <div class="order-step-label">1. Mới tạo</div>
                                    </div>

                                    <div class="order-step <?= ($currentStep >= 2) ? (($currentStep > 2) ? 'completed' : 'active') : '' ?>">
                                        <div class="order-step-circle">
                                            <i class="fa <?= ($currentStep > 2) ? 'fa-check' : 'fa-clipboard-check' ?>"></i>
                                        </div>
                                        <div class="order-step-label">2. Đã xác nhận</div>
                                    </div>

                                    <div class="order-step <?= ($currentStep >= 3) ? (($currentStep > 3) ? 'completed' : 'active') : '' ?>">
                                        <div class="order-step-circle">
                                            <i class="fa <?= ($currentStep > 3) ? 'fa-check' : 'fa-box' ?>"></i>
                                        </div>
                                        <div class="order-step-label">3. Đóng gói</div>
                                    </div>

                                    <div class="order-step <?= ($currentStep >= 4) ? (($currentStep > 4) ? 'completed' : 'active') : '' ?>">
                                        <div class="order-step-circle">
                                            <i class="fa <?= ($currentStep > 4) ? 'fa-check' : 'fa-truck-fast' ?>"></i>
                                        </div>
                                        <div class="order-step-label">4. Đang giao</div>
                                    </div>

                                    <div class="order-step <?= ($currentStep === 5) ? 'completed active' : '' ?>">
                                        <div class="order-step-circle">
                                            <i class="fa fa-flag-checkered"></i>
                                        </div>
                                        <div class="order-step-label">5. Hoàn thành</div>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Bố cục 2 Cột chi tiết đơn hàng -->
                    <div class="row g-4">
                        <!-- Cột Trái (2/3): Danh sách sản phẩm & Tổng kết tiền -->
                        <div class="col-12 col-lg-8">
                            <div class="gb-card mb-4">
                                <div class="gb-card-header d-flex justify-content-between align-items-center">
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="gb-icon-box" style="width: 32px; height: 32px; font-size: 0.95rem; background-color: #eff6ff; color: #2563eb;">
                                            <i class="fa fa-box-archive"></i>
                                        </div>
                                        <h5 class="fw-bold mb-0 text-dark" style="font-size: 1rem;">Danh sách sản phẩm trong đơn</h5>
                                    </div>
                                    <span class="badge bg-slate-100 text-slate-700 border">
                                        <?= count($chitiet) ?> mặt hàng
                                    </span>
                                </div>

                                <div class="table-responsive">
                                    <table class="gb-table align-middle">
                                        <thead>
                                            <tr>
                                                <th style="min-width: 250px;">Sản phẩm</th>
                                                <th class="text-center" style="width: 80px;">Size</th>
                                                <th class="text-end" style="width: 120px;">Đơn giá</th>
                                                <th class="text-center" style="width: 80px;">SL</th>
                                                <th class="text-end pe-4" style="width: 140px;">Thành tiền</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($chitiet as $sp): ?>
                                                <?php
                                                $hinhSp = (!empty($sp["hinhanh"]) && file_exists("../../assets/images/products/" . $sp["hinhanh"]))
                                                    ? "../../assets/images/products/" . $sp["hinhanh"]
                                                    : "../../assets/images/products/no-image.png";
                                                ?>
                                                <tr>
                                                    <td>
                                                        <div class="d-flex align-items-center gap-2.5">
                                                            <img src="<?= $hinhSp ?>" alt="<?= htmlspecialchars($sp["tensanpham"]) ?>" 
                                                                 class="rounded border" style="width: 48px; height: 48px; object-fit: cover; background-color: #f8fafc;"
                                                                 onerror="this.src='../../assets/images/products/no-image.png'">
                                                            <div>
                                                                <a href="../qlsanpham/index.php?action=sua&id=<?= $sp['sanpham_id'] ?>" class="fw-bold text-dark text-decoration-none d-block small" title="Xem sản phẩm">
                                                                    <?= htmlspecialchars($sp["tensanpham"]) ?>
                                                                </a>
                                                                <span class="text-muted small" style="font-size: 0.75rem;">Mã SP: #<?= $sp["sanpham_id"] ?></span>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="text-center">
                                                        <?php if (!empty($sp["size"])): ?>
                                                            <span class="badge bg-slate-100 text-slate-800 border font-monospace"><?= htmlspecialchars($sp["size"]) ?></span>
                                                        <?php else: ?>
                                                            <span class="text-muted small">—</span>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td class="text-end small">
                                                        <?= number_format((float)$sp["dongia"], 0, ',', '.') ?> đ
                                                    </td>
                                                    <td class="text-center fw-bold text-dark">
                                                        <?= (int)$sp["soluong"] ?>
                                                    </td>
                                                    <td class="text-end pe-4 fw-bold text-primary">
                                                        <?= number_format((float)$sp["thanhtien"], 0, ',', '.') ?> đ
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>

                                <!-- Bảng tính tiền & Voucher -->
                                <div class="p-4 bg-light border-top">
                                    <div class="row justify-content-end">
                                        <div class="col-12 col-md-7 col-lg-6">
                                            <div class="d-flex justify-content-between mb-2 small">
                                                <span class="text-muted">Tiền tạm tính:</span>
                                                <span class="fw-semibold text-dark"><?= number_format((float)$donhang["tamtinh"], 0, ',', '.') ?> đ</span>
                                            </div>
                                            <div class="d-flex justify-content-between mb-2 small">
                                                <span class="text-muted">Giảm giá Voucher:</span>
                                                <span class="text-success fw-semibold">
                                                    -<?= number_format((float)$donhang["giamgia"], 0, ',', '.') ?> đ
                                                    <?php if (!empty($donhang["ma_voucher"])): ?>
                                                        <span class="badge bg-success-subtle text-success border border-success-subtle ms-1 font-monospace"><?= htmlspecialchars($donhang["ma_voucher"]) ?></span>
                                                    <?php endif; ?>
                                                </span>
                                            </div>
                                            <div class="d-flex justify-content-between mb-3 small">
                                                <span class="text-muted">Phí vận chuyển:</span>
                                                <span class="fw-semibold text-dark"><?= number_format((float)$donhang["phivanchuyen"], 0, ',', '.') ?> đ</span>
                                            </div>
                                            <div class="d-flex justify-content-between pt-2 border-top">
                                                <span class="fw-bold fs-6 text-dark">Tổng cộng thanh toán:</span>
                                                <span class="fw-bold fs-5 text-danger"><?= number_format((float)$donhang["tongtien"], 0, ',', '.') ?> đ</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Cập nhật Đối tác giao hàng & Ghi chú nội bộ -->
                            <div class="gb-card mb-4 no-print">
                                <div class="gb-card-header d-flex align-items-center gap-2">
                                    <div class="gb-icon-box" style="width: 32px; height: 32px; font-size: 0.95rem; background-color: #f0fdf4; color: #16a34a;">
                                        <i class="fa fa-truck-ramp-box"></i>
                                    </div>
                                    <h5 class="fw-bold mb-0 text-dark" style="font-size: 1rem;">Điều phối vận chuyển & Ghi chú</h5>
                                </div>
                                <div class="p-4">
                                    <form method="POST" action="index.php">
                                        <input type="hidden" name="action" value="capnhatvanchuyen">
                                        <input type="hidden" name="txtid" value="<?= (int)$donhang["id"] ?>">

                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <label class="form-label">Đơn vị vận chuyển / Mã vận đơn</label>
                                                <input type="text" name="txtdoitac" class="form-control" 
                                                       value="<?= htmlspecialchars($donhang["doitacgiaohang"] ?? '') ?>"
                                                       placeholder="Ví dụ: GHTK - S1234567, Viettel Post, Shopee Xpress...">
                                                <div class="form-text">Lưu đối tác vận chuyển hoặc mã tra cứu đơn giao hàng.</div>
                                            </div>

                                            <div class="col-md-6">
                                                <label class="form-label">Ghi chú đơn hàng</label>
                                                <input type="text" name="txtghichu" class="form-control" 
                                                       value="<?= htmlspecialchars($donhang["ghichu"] ?? '') ?>"
                                                       placeholder="Ghi chú đóng gói hoặc yêu cầu đặc biệt từ khách...">
                                                <div class="form-text">Hiển thị cho bộ phận kho & giao hàng.</div>
                                            </div>

                                            <div class="col-12 text-end pt-2">
                                                <button type="submit" class="btn btn-gb-primary">
                                                    <i class="fa fa-save me-1"></i> Lưu thông tin vận chuyển
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- Cột Phải (1/3): Thông tin khách hàng & Địa chỉ giao hàng -->
                        <div class="col-12 col-lg-4">
                            <!-- Thẻ Người nhận hàng -->
                            <div class="gb-card mb-4">
                                <div class="gb-card-header d-flex align-items-center gap-2">
                                    <div class="gb-icon-box" style="width: 32px; height: 32px; font-size: 0.95rem; background-color: #eff6ff; color: #2563eb;">
                                        <i class="fa fa-location-dot"></i>
                                    </div>
                                    <h5 class="fw-bold mb-0 text-dark" style="font-size: 1rem;">Địa chỉ nhận hàng</h5>
                                </div>
                                <div class="p-4">
                                    <div class="mb-3">
                                        <span class="text-muted small d-block mb-1">Người nhận:</span>
                                        <div class="fw-bold text-dark fs-6"><?= htmlspecialchars($donhang["tennguoinhan"]) ?></div>
                                    </div>
                                    <div class="mb-3">
                                        <span class="text-muted small d-block mb-1">Số điện thoại liên hệ:</span>
                                        <a href="tel:<?= htmlspecialchars($donhang["sdtnhan"]) ?>" class="fw-bold text-primary text-decoration-none">
                                            <i class="fa fa-phone me-1"></i><?= htmlspecialchars($donhang["sdtnhan"]) ?>
                                        </a>
                                    </div>
                                    <div class="mb-0">
                                        <span class="text-muted small d-block mb-1">Địa chỉ chi tiết:</span>
                                        <div class="text-dark bg-light p-2.5 rounded border small">
                                            <?= nl2br(htmlspecialchars($donhang["diachinhan"])) ?>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Thẻ Tài khoản thành viên đặt hàng -->
                            <div class="gb-card mb-4">
                                <div class="gb-card-header d-flex align-items-center justify-content-between">
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="gb-icon-box" style="width: 32px; height: 32px; font-size: 0.95rem; background-color: #f3e8ff; color: #7e22ce;">
                                            <i class="fa fa-user-check"></i>
                                        </div>
                                        <h5 class="fw-bold mb-0 text-dark" style="font-size: 1rem;">Tài khoản thành viên</h5>
                                    </div>
                                </div>
                                <div class="p-4">
                                    <?php if ($khachhang): ?>
                                        <?php
                                        $hinhKh = (!empty($khachhang["hinhanh"]) && file_exists("../../assets/images/users/" . $khachhang["hinhanh"]))
                                            ? "../../assets/images/users/" . $khachhang["hinhanh"]
                                            : "../../assets/images/users/user.png";
                                        ?>
                                        <div class="d-flex align-items-center gap-3 mb-3">
                                            <img src="<?= $hinhKh ?>" alt="<?= htmlspecialchars($khachhang["hoten"]) ?>" 
                                                 class="rounded-circle border" style="width: 50px; height: 50px; object-fit: cover;">
                                            <div>
                                                <div class="fw-bold text-dark"><?= htmlspecialchars($khachhang["hoten"]) ?></div>
                                                <div class="text-muted small"><?= htmlspecialchars($khachhang["gmail"] ?? $khachhang["sodienthoai"]) ?></div>
                                            </div>
                                        </div>
                                        <div class="pt-2 border-top no-print">
                                            <a href="../qlkhachhang/index.php?action=chitiet&id=<?= $khachhang['id'] ?>" class="btn btn-sm btn-light border text-primary w-100 fw-semibold">
                                                <i class="fa fa-id-card me-1"></i> Xem hồ sơ khách hàng
                                            </a>
                                        </div>
                                    <?php else: ?>
                                        <div class="text-muted small">
                                            <i class="fa fa-info-circle me-1"></i> Đơn hàng đặt bởi khách vãng lai (không đăng nhập)
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <!-- Thẻ Thanh toán & Đối tác -->
                            <div class="gb-card mb-4">
                                <div class="gb-card-header d-flex align-items-center gap-2">
                                    <div class="gb-icon-box" style="width: 32px; height: 32px; font-size: 0.95rem; background-color: #fff7ed; color: #ea580c;">
                                        <i class="fa fa-credit-card"></i>
                                    </div>
                                    <h5 class="fw-bold mb-0 text-dark" style="font-size: 1rem;">Hình thức thanh toán</h5>
                                </div>
                                <div class="p-4">
                                    <div class="mb-3">
                                        <span class="text-muted small d-block mb-1">Phương thức:</span>
                                        <div class="fw-bold text-dark"><?= htmlspecialchars($dhModel->laytenphuongthuc($donhang["phuongthuc_tt"])) ?></div>
                                    </div>
                                    <div class="mb-3">
                                        <span class="text-muted small d-block mb-1">Tình trạng thu tiền:</span>
                                        <?php if ($isDaThanhToan): ?>
                                            <span class="badge bg-success-subtle text-success border border-success-subtle px-2.5 py-1">
                                                <i class="fa fa-check-circle me-1"></i>Đã thanh toán đủ
                                            </span>
                                        <?php else: ?>
                                            <span class="badge bg-warning-subtle text-warning border border-warning-subtle px-2.5 py-1">
                                                <i class="fa fa-clock me-1"></i>Chưa thanh toán (Thu khi giao hàng)
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                    <?php if (!empty($donhang["doitacgiaohang"])): ?>
                                        <div class="mb-0 pt-2 border-top">
                                            <span class="text-muted small d-block mb-1">Mã vận đơn / Đơn vị:</span>
                                            <span class="badge bg-slate-100 text-dark border font-monospace"><?= htmlspecialchars($donhang["doitacgiaohang"]) ?></span>
                                        </div>
                                    <?php endif; ?>
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

