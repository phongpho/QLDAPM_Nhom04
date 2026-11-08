<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION["nguoidung"])) {
    header("Location: index.php?action=dangnhap");
    exit();
}

$user = $_SESSION["nguoidung"];
$isAdmin = ((int)$user["loai"] === 1);
$roleName = $isAdmin ? "Quản trị viên" : "Nhân viên vận hành";

// Khởi tạo các biến thống kê an toàn
$countDanhMuc = 0;
$countSanPham = 0;
$countKhachHang = 0;
$countNhanSu = 0;

$tkDonHang = [
    'tong_don' => 0,
    'don_moi' => 0,
    'don_xacnhan' => 0,
    'don_vanchuyen' => 0,
    'don_hoanthanh' => 0,
    'don_huy' => 0,
    'doanhthu_hoanthanh' => 0,
    'tong_doanhso' => 0
];
$doanhThuHomNay = 0;
$doanhThuThangNay = 0;
$spSapHet = [];
$donHangMoiNhat = [];
$danhGiaMoiNhat = [];

try {
    $dbcon = DATABASE::connect();

    // 1. Thống kê đơn hàng & doanh thu
    if (file_exists("../../model/donhang.php")) {
        require_once("../../model/donhang.php");
        $donhangModel = new DONHANG();
        $tkDonHang = $donhangModel->thongkedonhangquantri();
    }

    // Doanh thu hôm nay
    $sqlToday = "SELECT COALESCE(SUM(tongtien), 0) FROM donhang WHERE trangthai = 5 AND DATE(ngay) = CURDATE()";
    $doanhThuHomNay = (float)$dbcon->query($sqlToday)->fetchColumn();

    // Doanh thu tháng này
    $sqlMonth = "SELECT COALESCE(SUM(tongtien), 0) FROM donhang WHERE trangthai = 5 AND MONTH(ngay) = MONTH(CURDATE()) AND YEAR(ngay) = YEAR(CURDATE())";
    $doanhThuThangNay = (float)$dbcon->query($sqlMonth)->fetchColumn();

    // 2. Thống kê sản phẩm & danh mục
    $countSanPham = (int)$dbcon->query("SELECT COUNT(*) FROM sanpham")->fetchColumn();
    $countDanhMuc = (int)$dbcon->query("SELECT COUNT(*) FROM danhmuc")->fetchColumn();

    // 3. Thống kê người dùng
    $countKhachHang = (int)$dbcon->query("SELECT COUNT(*) FROM nguoidung WHERE loai = 3")->fetchColumn();
    $countNhanSu = (int)$dbcon->query("SELECT COUNT(*) FROM nguoidung WHERE loai = 2")->fetchColumn();

    // 4. Sản phẩm cảnh báo tồn kho (soluongton <= 5)
    $sqlTonKho = "SELECT s.id, s.tensanpham, s.soluongton, s.hinhanh, s.giaban, d.tendanhmuc 
                  FROM sanpham s 
                  LEFT JOIN danhmuc d ON d.id = s.danhmuc_id 
                  WHERE s.trangthai = 1 AND s.soluongton <= 5 
                  ORDER BY s.soluongton ASC, s.id DESC LIMIT 5";
    $spSapHet = $dbcon->query($sqlTonKho)->fetchAll(PDO::FETCH_ASSOC);

    // 5. Đơn hàng mới nhất
    $sqlTopDon = "SELECT id, madonhang, tennguoinhan, sdtnhan, tongtien, trangthai, ngay 
                  FROM donhang 
                  ORDER BY id DESC LIMIT 5";
    $donHangMoiNhat = $dbcon->query($sqlTopDon)->fetchAll(PDO::FETCH_ASSOC);

    // 6. Đánh giá mới nhất
    $sqlTopDg = "SELECT dg.*, nd.hoten, sp.tensanpham, sp.hinhanh as sanpham_anh 
                 FROM danhgia dg 
                 LEFT JOIN nguoidung nd ON nd.id = dg.nguoidung_id 
                 LEFT JOIN sanpham sp ON sp.id = dg.sanpham_id 
                 ORDER BY dg.id DESC LIMIT 4";
    $danhGiaMoiNhat = $dbcon->query($sqlTopDg)->fetchAll(PDO::FETCH_ASSOC);

    // 7. Thống kê 7 ngày gần nhất cho biểu đồ doanh thu & đơn hàng
    $chartDates = [];
    $chartRevenueData = [];
    $chartOrdersData = [];
    $tongDoanhThu7Ngay = 0;

    for ($i = 6; $i >= 0; $i--) {
        $curDate = date('Y-m-d', strtotime("-$i days"));
        $curLabel = date('d/m', strtotime("-$i days"));
        $chartDates[] = $curLabel;

        $stmtRev = $dbcon->prepare("SELECT COALESCE(SUM(tongtien), 0) FROM donhang WHERE trangthai = 5 AND DATE(ngay) = ?");
        $stmtRev->execute([$curDate]);
        $revVal = (float)$stmtRev->fetchColumn();
        $chartRevenueData[] = $revVal;
        $tongDoanhThu7Ngay += $revVal;

        $stmtCnt = $dbcon->prepare("SELECT COUNT(*) FROM donhang WHERE DATE(ngay) = ?");
        $stmtCnt->execute([$curDate]);
        $chartOrdersData[] = (int)$stmtCnt->fetchColumn();
    }

    $totalOrders = (int)$tkDonHang['tong_don'];
    $completedOrders = (int)$tkDonHang['don_hoanthanh'];
    $tyLeHoanThanh = ($totalOrders > 0) ? round(($completedOrders / $totalOrders) * 100, 1) : 0;

} catch (Exception $e) {
    error_log("Lỗi tải dữ liệu Dashboard: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Bảng điều khiển - GymBro Admin</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- AdminKit base -->
    <link href="../inc/css/app.css" rel="stylesheet">
    <script src="../inc/js/app.js"></script>

    <!-- Bootstrap 5 & FontAwesome 6 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">

    <!-- Chart.js for High Performance Data Visuals -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

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

                    <!-- Atmospheric Frosted Welcome Banner -->
                    <div class="gb-welcome-banner mb-4">
                        <div class="row align-items-center">
                            <div class="col-12 col-lg-7">
                                <div class="d-inline-flex align-items-center gap-2 px-3 py-1 mb-2 rounded-pill" style="background: rgba(14, 165, 233, 0.18); border: 1px solid rgba(56, 189, 248, 0.35);">
                                    <span class="gb-pulse-dot"></span>
                                    <span style="font-size: 0.75rem; color: #38bdf8; font-weight: 700; letter-spacing: 0.05em; text-transform: uppercase;">
                                        <?= $roleName ?> &bull; GymBro v2.5
                                    </span>
                                </div>
                                <h1 class="gb-welcome-title">Xin chào, <?= htmlspecialchars($user["hoten"]) ?>!</h1>
                                <p class="gb-welcome-subtitle">
                                    Tổng quan hoạt động kinh doanh, đồ thị doanh thu và giám sát vận hành GymBro hôm nay.
                                </p>
                            </div>
                            <div class="col-12 col-lg-5 text-lg-end mt-3 mt-lg-0">
                                <div class="d-inline-flex flex-wrap gap-2 justify-content-lg-end">
                                    <a href="../qldonhang/index.php" class="gb-pill-btn gb-pill-btn-cyan">
                                        <i class="fa fa-receipt"></i>
                                        <span>Duyệt đơn hàng</span>
                                        <?php if ($tkDonHang['don_moi'] > 0): ?>
                                            <span class="badge bg-danger rounded-pill ms-1 px-2"><?= $tkDonHang['don_moi'] ?></span>
                                        <?php endif; ?>
                                    </a>
                                    <a href="../qlsanpham/index.php?action=them" class="gb-pill-btn gb-pill-btn-glass">
                                        <i class="fa fa-plus"></i>
                                        <span>Thêm sản phẩm</span>
                                    </a>
                                    <a href="../../public/index.php" target="_blank" class="gb-pill-btn gb-pill-btn-glass" title="Mở trang chủ shop khách hàng">
                                        <i class="fa fa-arrow-up-right-from-square"></i>
                                        <span>Shop</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- 4 THẺ METRIC CHÍNH (KPIS) -->
                    <div class="row g-3 mb-4">
                        <!-- 1. Doanh thu hoàn thành -->
                        <div class="col-12 col-sm-6 col-xl-3">
                            <div class="gb-stat-card">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div>
                                        <div class="gb-stat-label">Doanh thu hoàn tất</div>
                                        <div class="gb-stat-number text-success fs-3">
                                            <?= number_format($tkDonHang['doanhthu_hoanthanh'], 0, ',', '.') ?><small class="fs-6 fw-normal">đ</small>
                                        </div>
                                        <div class="text-muted small mt-1">
                                            <span>Tháng này: <strong><?= number_format($doanhThuThangNay, 0, ',', '.') ?>đ</strong></span>
                                        </div>
                                    </div>
                                    <div class="gb-icon-box" style="background-color: #ecfdf5; color: #059669;">
                                        <i class="fa fa-sack-dollar"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- 2. Đơn hàng & Cảnh báo đơn mới -->
                        <div class="col-12 col-sm-6 col-xl-3">
                            <div class="gb-stat-card">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div>
                                        <div class="gb-stat-label">Đơn hàng hệ thống</div>
                                        <div class="gb-stat-number fs-3"><?= number_format($tkDonHang['tong_don']) ?></div>
                                        <div class="small mt-1">
                                            <?php if ($tkDonHang['don_moi'] > 0): ?>
                                                <a href="../qldonhang/index.php?trangthai=1" class="text-danger fw-bold text-decoration-none">
                                                    <i class="fa fa-bell fa-shake me-1"></i><?= $tkDonHang['don_moi'] ?> đơn mới cần duyệt
                                                </a>
                                            <?php else: ?>
                                                <span class="text-muted">Không có đơn chờ duyệt</span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <div class="gb-icon-box" style="background-color: #eff6ff; color: #2563eb;">
                                        <i class="fa fa-receipt"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- 3. Sản phẩm & Cảnh báo tồn kho -->
                        <div class="col-12 col-sm-6 col-xl-3">
                            <div class="gb-stat-card">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div>
                                        <div class="gb-stat-label">Sản phẩm & Tồn kho</div>
                                        <div class="gb-stat-number fs-3"><?= number_format($countSanPham) ?></div>
                                        <div class="small mt-1">
                                            <?php if (count($spSapHet) > 0): ?>
                                                <span class="text-warning fw-semibold">
                                                    <i class="fa fa-triangle-exclamation me-1"></i><?= count($spSapHet) ?> mặt hàng sắp hết
                                                </span>
                                            <?php else: ?>
                                                <span class="text-success small">Tồn kho đảm bảo</span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <div class="gb-icon-box" style="background-color: #fff7ed; color: #ea580c;">
                                        <i class="fa fa-boxes-stacked"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- 4. Khách hàng thành viên -->
                        <div class="col-12 col-sm-6 col-xl-3">
                            <div class="gb-stat-card">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div>
                                        <div class="gb-stat-label">Khách hàng thành viên</div>
                                        <div class="gb-stat-number fs-3"><?= number_format($countKhachHang) ?></div>
                                        <div class="text-muted small mt-1">
                                            <span>Tài khoản người dùng</span>
                                        </div>
                                    </div>
                                    <div class="gb-icon-box" style="background-color: #f3e8ff; color: #7e22ce;">
                                        <i class="fa fa-users"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- HÀNG BIỂU ĐỒ TƯƠNG TÁC (CHART.JS - FROSTED GLASS CARDS) -->
                    <div class="row g-4 mb-4">
                        <!-- Biểu đồ cột: Doanh thu & Giao dịch 7 ngày -->
                        <div class="col-12 col-xl-8">
                            <div class="gb-chart-card h-100">
                                <div class="card-header bg-transparent py-3 px-4 d-flex justify-content-between align-items-center flex-wrap gap-2">
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="gb-icon-box" style="width: 36px; height: 36px; font-size: 0.95rem; background: #e0f2fe; color: #0284c7;">
                                            <i class="fa fa-chart-column"></i>
                                        </div>
                                        <div>
                                            <h5 class="card-title mb-0 fw-bold text-dark">Xu hướng Doanh thu & Đơn hàng</h5>
                                            <small class="text-muted">7 ngày gần nhất (Thời gian thực)</small>
                                        </div>
                                    </div>
                                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-3 py-1.5 fw-bold rounded-pill">
                                        <i class="fa fa-wallet me-1"></i> 7 ngày: <?= number_format($tongDoanhThu7Ngay, 0, ',', '.') ?>đ
                                    </span>
                                </div>
                                <div class="card-body p-3 p-md-4">
                                    <div style="position: relative; height: 285px; width: 100%;">
                                        <canvas id="revenueBarChart"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Biểu đồ tròn: Trạng thái Đơn hàng -->
                        <div class="col-12 col-xl-4">
                            <div class="gb-chart-card h-100">
                                <div class="card-header bg-transparent py-3 px-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="gb-icon-box" style="width: 36px; height: 36px; font-size: 0.95rem; background: #fef3c7; color: #d97706;">
                                            <i class="fa fa-chart-pie"></i>
                                        </div>
                                        <div>
                                            <h5 class="card-title mb-0 fw-bold text-dark">Phân bố Đơn hàng</h5>
                                            <small class="text-muted">Tổng <?= number_format($totalOrders) ?> đơn</small>
                                        </div>
                                    </div>
                                    <span class="badge bg-success-subtle text-success border border-success-subtle px-2.5 py-1 fw-bold rounded-pill">
                                        <?= $tyLeHoanThanh ?>% Hoàn thành
                                    </span>
                                </div>
                                <div class="card-body p-3">
                                    <div style="position: relative; height: 180px; width: 100%;">
                                        <canvas id="orderStatusDonutChart"></canvas>
                                    </div>
                                    <!-- Legend Pills Grid -->
                                    <div class="d-flex flex-column gap-1.5 mt-3">
                                        <div class="gb-legend-item">
                                            <span class="d-flex align-items-center gap-2">
                                                <span class="gb-legend-color" style="background-color: #f59e0b;"></span>
                                                <span class="text-dark fw-medium">Mới đặt</span>
                                            </span>
                                            <span class="badge bg-warning-subtle text-warning border border-warning-subtle rounded-pill"><?= (int)$tkDonHang['don_moi'] ?></span>
                                        </div>
                                        <div class="gb-legend-item">
                                            <span class="d-flex align-items-center gap-2">
                                                <span class="gb-legend-color" style="background-color: #0ea5e9;"></span>
                                                <span class="text-dark fw-medium">Đã xác nhận</span>
                                            </span>
                                            <span class="badge bg-info-subtle text-info border border-info-subtle rounded-pill"><?= (int)$tkDonHang['don_xacnhan'] ?></span>
                                        </div>
                                        <div class="gb-legend-item">
                                            <span class="d-flex align-items-center gap-2">
                                                <span class="gb-legend-color" style="background-color: #6366f1;"></span>
                                                <span class="text-dark fw-medium">Đang giao</span>
                                            </span>
                                            <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill"><?= (int)$tkDonHang['don_vanchuyen'] ?></span>
                                        </div>
                                        <div class="gb-legend-item">
                                            <span class="d-flex align-items-center gap-2">
                                                <span class="gb-legend-color" style="background-color: #10b981;"></span>
                                                <span class="text-dark fw-bold">Hoàn tất</span>
                                            </span>
                                            <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill"><?= (int)$tkDonHang['don_hoanthanh'] ?></span>
                                        </div>
                                        <div class="gb-legend-item">
                                            <span class="d-flex align-items-center gap-2">
                                                <span class="gb-legend-color" style="background-color: #ef4444;"></span>
                                                <span class="text-dark fw-medium">Đã hủy</span>
                                            </span>
                                            <span class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill"><?= (int)$tkDonHang['don_huy'] ?></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- NỘI DUNG CHÍNH: 2 CỘT -->
                    <div class="row g-4 mb-4">
                        <!-- Cột Trái (8 phần): Đơn hàng mới nhất & Cảnh báo tồn kho -->
                        <div class="col-12 col-xl-8">
                            <!-- Bảng đơn hàng mới nhất -->
                            <div class="card border-0 shadow-sm mb-4">
                                <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
                                    <div class="d-flex align-items-center gap-2">
                                        <i class="fa fa-clock-rotate-left text-primary"></i>
                                        <h5 class="card-title mb-0 fw-bold">Đơn hàng mới nhất</h5>
                                    </div>
                                    <a href="../qldonhang/index.php" class="btn btn-sm btn-outline-primary">
                                        Xem tất cả đơn <i class="fa fa-arrow-right ms-1"></i>
                                    </a>
                                </div>
                                <div class="card-body p-0">
                                    <div class="table-responsive">
                                        <table class="table table-hover align-middle mb-0">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>Mã đơn</th>
                                                    <th>Người nhận</th>
                                                    <th>Thời gian</th>
                                                    <th>Tổng tiền</th>
                                                    <th class="text-center">Trạng thái</th>
                                                    <th class="text-center">Thao tác</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php if (empty($donHangMoiNhat)): ?>
                                                    <tr>
                                                        <td colspan="6" class="text-center py-4 text-muted">Chưa có đơn hàng nào.</td>
                                                    </tr>
                                                <?php else: ?>
                                                    <?php foreach ($donHangMoiNhat as $dh): ?>
                                                        <tr>
                                                            <td>
                                                                <span class="badge bg-dark-subtle text-dark border font-monospace">
                                                                    <?= htmlspecialchars($dh["madonhang"] ?? ('#' . $dh["id"])) ?>
                                                                </span>
                                                            </td>
                                                            <td>
                                                                <div class="fw-semibold text-dark"><?= htmlspecialchars($dh["tennguoinhan"]) ?></div>
                                                                <div class="text-muted small"><?= htmlspecialchars($dh["sdtnhan"]) ?></div>
                                                            </td>
                                                            <td class="text-muted small">
                                                                <?= date('d/m/Y H:i', strtotime($dh["ngay"])) ?>
                                                            </td>
                                                            <td class="fw-bold text-dark">
                                                                <?= number_format((float)$dh["tongtien"], 0, ',', '.') ?>đ
                                                            </td>
                                                            <td class="text-center">
                                                                <?php
                                                                $tt = (int)$dh["trangthai"];
                                                                if ($tt === 1) {
                                                                    echo '<span class="badge bg-warning-subtle text-warning border border-warning-subtle">Mới đặt</span>';
                                                                } elseif ($tt === 2) {
                                                                    echo '<span class="badge bg-info-subtle text-info border border-info-subtle">Đã xác nhận</span>';
                                                                } elseif ($tt === 3 || $tt === 4) {
                                                                    echo '<span class="badge bg-primary-subtle text-primary border border-primary-subtle">Đang giao</span>';
                                                                } elseif ($tt === 5) {
                                                                    echo '<span class="badge bg-success-subtle text-success border border-success-subtle">Hoàn thành</span>';
                                                                } else {
                                                                    echo '<span class="badge bg-danger-subtle text-danger border border-danger-subtle">Đã hủy</span>';
                                                                }
                                                                ?>
                                                            </td>
                                                            <td class="text-center">
                                                                <a href="../qldonhang/index.php?action=chitiet&id=<?= $dh['id'] ?>" class="btn btn-sm btn-outline-primary" title="Xem chi tiết đơn hàng">
                                                                    <i class="fa fa-eye"></i>
                                                                </a>
                                                            </td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                <?php endif; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <!-- Bảng cảnh báo Tồn kho báo động (Cần nhập hàng ngay) -->
                            <div class="card border-0 shadow-sm mb-4">
                                <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
                                    <div class="d-flex align-items-center gap-2">
                                        <i class="fa fa-triangle-exclamation text-warning"></i>
                                        <h5 class="card-title mb-0 fw-bold">Cảnh báo tồn kho (Tồn kho &le; 5)</h5>
                                    </div>
                                    <a href="../qlsanpham/index.php" class="btn btn-sm btn-outline-warning">
                                        Quản lý kho hàng <i class="fa fa-arrow-right ms-1"></i>
                                    </a>
                                </div>
                                <div class="card-body p-0">
                                    <div class="table-responsive">
                                        <table class="table table-hover align-middle mb-0">
                                            <thead class="table-light">
                                                <tr>
                                                    <th style="width: 80px;">Ảnh</th>
                                                    <th>Tên sản phẩm</th>
                                                    <th>Danh mục</th>
                                                    <th>Giá bán</th>
                                                    <th class="text-center" style="width: 140px;">Tồn kho</th>
                                                    <th class="text-center" style="width: 110px;">Thao tác</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php if (empty($spSapHet)): ?>
                                                    <tr>
                                                        <td colspan="6" class="text-center py-4 text-success">
                                                            <i class="fa fa-circle-check me-1"></i> Tất cả các mặt hàng hiện đều đủ tồn kho an toàn!
                                                        </td>
                                                    </tr>
                                                <?php else: ?>
                                                    <?php foreach ($spSapHet as $sp): ?>
                                                        <tr>
                                                            <td>
                                                                <?php 
                                                                $img = !empty($sp["hinhanh"]) ? '../../assets/images/products/' . $sp["hinhanh"] : '../../assets/images/products/product.png';
                                                                ?>
                                                                <img src="<?= $img ?>" alt="" style="width: 44px; height: 44px; object-fit: cover; border-radius: 6px; border: 1px solid #e2e8f0;">
                                                            </td>
                                                            <td>
                                                                <div class="fw-bold text-dark fs-6"><?= htmlspecialchars($sp["tensanpham"]) ?></div>
                                                            </td>
                                                            <td class="text-muted small">
                                                                <?= htmlspecialchars($sp["tendanhmuc"] ?? 'Chưa phân loại') ?>
                                                            </td>
                                                            <td class="text-dark fw-semibold">
                                                                <?= number_format((float)$sp["giaban"], 0, ',', '.') ?>đ
                                                            </td>
                                                            <td class="text-center">
                                                                <?php if ((int)$sp["soluongton"] <= 0): ?>
                                                                    <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2.5 py-1">
                                                                        <i class="fa fa-circle-xmark me-1"></i> Hết hàng (0)
                                                                    </span>
                                                                <?php else: ?>
                                                                    <span class="badge bg-warning-subtle text-warning border border-warning-subtle px-2.5 py-1">
                                                                        <i class="fa fa-triangle-exclamation me-1"></i> Còn <?= (int)$sp["soluongton"] ?> món
                                                                    </span>
                                                                <?php endif; ?>
                                                            </td>
                                                            <td class="text-center">
                                                                <a href="../qlsanpham/index.php?action=sua&id=<?= $sp['id'] ?>" class="btn btn-sm btn-outline-primary" title="Cập nhật số lượng nhập kho">
                                                                    <i class="fa fa-pen-to-square"></i> Nhập thêm
                                                                </a>
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

                        <!-- Cột Phải (4 phần): Đánh giá gần đây & Truy cập nhanh -->
                        <div class="col-12 col-xl-4">
                            <!-- Đánh giá mới nhất -->
                            <div class="card border-0 shadow-sm mb-4">
                                <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
                                    <div class="d-flex align-items-center gap-2">
                                        <i class="fa fa-comments text-info"></i>
                                        <h5 class="card-title mb-0 fw-bold">Đánh giá gần đây</h5>
                                    </div>
                                    <a href="../qldanhgia/index.php" class="btn btn-sm btn-outline-info">
                                        Xem tất cả <i class="fa fa-arrow-right ms-1"></i>
                                    </a>
                                </div>
                                <div class="card-body p-3">
                                    <?php if (empty($danhGiaMoiNhat)): ?>
                                        <div class="text-center py-4 text-muted small">Chưa có đánh giá nào từ khách hàng.</div>
                                    <?php else: ?>
                                        <div class="d-flex flex-column gap-3">
                                            <?php foreach ($danhGiaMoiNhat as $dg): ?>
                                                <div class="p-2.5 bg-light rounded border">
                                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                                        <span class="fw-bold text-dark small"><?= htmlspecialchars($dg["hoten"] ?? 'Khách hàng') ?></span>
                                                        <div class="text-warning small">
                                                            <?php for ($i = 1; $i <= (int)$dg["sao"]; $i++): ?>
                                                                <i class="fa fa-star" style="font-size: 0.75rem;"></i>
                                                            <?php endfor; ?>
                                                        </div>
                                                    </div>
                                                    <div class="text-muted small text-truncate mb-1" title="<?= htmlspecialchars($dg["tensanpham"] ?? '') ?>">
                                                        <i class="fa fa-box me-1"></i> <?= htmlspecialchars($dg["tensanpham"] ?? '') ?>
                                                    </div>
                                                    <div class="text-dark small fst-italic">
                                                        "<?= htmlspecialchars(mb_substr($dg["noidung"] ?? '', 0, 70, 'UTF-8')) ?><?= mb_strlen($dg["noidung"] ?? '') > 70 ? '...' : '' ?>"
                                                    </div>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <!-- Lối tắt quản trị nhanh (Quick Shortcuts) -->
                            <div class="card border-0 shadow-sm mb-4">
                                <div class="card-header bg-white py-3 border-bottom">
                                    <h5 class="card-title mb-0 fw-bold text-dark">
                                        <i class="fa fa-bolt text-warning me-2"></i>Lối tắt thao tác nhanh
                                    </h5>
                                </div>
                                <div class="card-body p-3">
                                    <div class="d-flex flex-column gap-2">
                                        <a href="../qlsanpham/index.php" class="btn btn-light border text-start d-flex align-items-center justify-content-between p-2.5">
                                            <div class="d-flex align-items-center gap-2">
                                                <div class="gb-icon-box" style="width: 32px; height: 32px; font-size: 0.85rem; background-color: #ecfdf5; color: #059669;">
                                                    <i class="fa fa-plus"></i>
                                                </div>
                                                <span class="fw-semibold text-dark">Thêm sản phẩm mới</span>
                                            </div>
                                            <i class="fa fa-chevron-right text-muted small"></i>
                                        </a>

                                        <a href="../qlvoucher/index.php" class="btn btn-light border text-start d-flex align-items-center justify-content-between p-2.5">
                                            <div class="d-flex align-items-center gap-2">
                                                <div class="gb-icon-box" style="width: 32px; height: 32px; font-size: 0.85rem; background-color: #fdf2f8; color: #db2777;">
                                                    <i class="fa fa-tag"></i>
                                                </div>
                                                <span class="fw-semibold text-dark">Tạo mã Voucher giảm giá</span>
                                            </div>
                                            <i class="fa fa-chevron-right text-muted small"></i>
                                        </a>

                                        <a href="../qltintuc/index.php" class="btn btn-light border text-start d-flex align-items-center justify-content-between p-2.5">
                                            <div class="d-flex align-items-center gap-2">
                                                <div class="gb-icon-box" style="width: 32px; height: 32px; font-size: 0.85rem; background-color: #eff6ff; color: #2563eb;">
                                                    <i class="fa fa-pen-nib"></i>
                                                </div>
                                                <span class="fw-semibold text-dark">Đăng bài viết cẩm nang</span>
                                            </div>
                                            <i class="fa fa-chevron-right text-muted small"></i>
                                        </a>

                                        <a href="../qlthuonghieu/index.php" class="btn btn-light border text-start d-flex align-items-center justify-content-between p-2.5">
                                            <div class="d-flex align-items-center gap-2">
                                                <div class="gb-icon-box" style="width: 32px; height: 32px; font-size: 0.85rem; background-color: #f0fdf4; color: #16a34a;">
                                                    <i class="fa fa-award"></i>
                                                </div>
                                                <span class="fw-semibold text-dark">Quản lý đối tác thương hiệu</span>
                                            </div>
                                            <i class="fa fa-chevron-right text-muted small"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <!-- Thông tin phiên làm việc -->
                            <div class="card border-0 shadow-sm rounded-3">
                                <div class="card-body p-3">
                                    <div class="d-flex align-items-center justify-content-between mb-2">
                                        <span class="text-muted small">Phiên làm việc</span>
                                        <span class="badge bg-success-subtle text-success border border-success-subtle">Online</span>
                                    </div>
                                    <div class="small text-dark mb-1">
                                        <strong><?= htmlspecialchars($user["hoten"]) ?></strong> (<?= $roleName ?>)
                                    </div>
                                    <div class="text-muted small">
                                        <?= htmlspecialchars($user["email"] ?? $user["gmail"] ?? '') ?>
                                    </div>
                                    <hr class="my-2">
                                    <div class="text-muted small d-flex justify-content-between">
                                        <span>Máy chủ PHP:</span>
                                        <span class="fw-semibold text-dark"><?= PHP_VERSION ?></span>
                                    </div>
                                    <div class="text-muted small d-flex justify-content-between mt-1">
                                        <span>Giờ hệ thống:</span>
                                        <span class="fw-semibold text-dark"><?= date('H:i d/m/Y') ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </main>

            <?php include('../inc/footer.php'); ?>
        </div>
    </div>

    <!-- Chart.js Modern Initializer -->
    <script>
    document.addEventListener("DOMContentLoaded", function () {
        // 1. Biểu đồ cột: Doanh thu & Đơn hàng 7 ngày qua
        const ctxBar = document.getElementById('revenueBarChart');
        if (ctxBar) {
            const labels = <?= json_encode($chartDates) ?>;
            const revData = <?= json_encode($chartRevenueData) ?>;
            const ordData = <?= json_encode($chartOrdersData) ?>;

            const barGradient = ctxBar.getContext('2d').createLinearGradient(0, 0, 0, 260);
            barGradient.addColorStop(0, 'rgba(14, 165, 233, 0.95)');
            barGradient.addColorStop(1, 'rgba(2, 132, 199, 0.35)');

            new Chart(ctxBar, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [
                        {
                            type: 'bar',
                            label: 'Doanh thu',
                            data: revData,
                            backgroundColor: barGradient,
                            borderColor: '#0284c7',
                            borderWidth: 1.5,
                            borderRadius: 8,
                            borderSkipped: false,
                            yAxisID: 'y'
                        },
                        {
                            type: 'line',
                            label: 'Số đơn hoàn tất',
                            data: ordData,
                            borderColor: '#10b981',
                            backgroundColor: 'rgba(16, 185, 129, 0.2)',
                            borderWidth: 2.5,
                            pointBackgroundColor: '#10b981',
                            pointBorderColor: '#ffffff',
                            pointBorderWidth: 2,
                            pointRadius: 4,
                            pointHoverRadius: 6,
                            tension: 0.35,
                            yAxisID: 'y1'
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: {
                        mode: 'index',
                        intersect: false
                    },
                    plugins: {
                        legend: {
                            position: 'top',
                            labels: {
                                font: { family: "'Plus Jakarta Sans', sans-serif", weight: '600', size: 12 },
                                color: '#e2e8f0',
                                usePointStyle: true,
                                boxWidth: 8
                            }
                        },
                        tooltip: {
                            backgroundColor: 'rgba(15, 23, 42, 0.95)',
                            titleFont: { family: "'Plus Jakarta Sans', sans-serif", weight: '700' },
                            bodyFont: { family: "'Plus Jakarta Sans', sans-serif" },
                            padding: 12,
                            cornerRadius: 12,
                            callbacks: {
                                label: function(context) {
                                    if (context.dataset.type === 'bar') {
                                        return 'Doanh thu: ' + new Intl.NumberFormat('vi-VN').format(context.raw) + ' đ';
                                    }
                                    return 'Số đơn hoàn tất: ' + context.raw + ' đơn';
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            grid: { display: false },
                            ticks: {
                                font: { family: "'Plus Jakarta Sans', sans-serif", weight: '600' },
                                color: '#cbd5e1'
                            }
                        },
                        y: {
                            type: 'linear',
                            display: true,
                            position: 'left',
                            grid: { color: 'rgba(255, 255, 255, 0.08)' },
                            ticks: {
                                font: { family: "'Plus Jakarta Sans', sans-serif" },
                                color: '#cbd5e1',
                                callback: function(val) {
                                    if (val >= 1000000) return (val / 1000000).toFixed(1) + 'Tr';
                                    if (val >= 1000) return (val / 1000).toFixed(0) + 'k';
                                    return val;
                                }
                            }
                        },
                        y1: {
                            type: 'linear',
                            display: true,
                            position: 'right',
                            grid: { drawOnChartArea: false },
                            ticks: {
                                font: { family: "'Plus Jakarta Sans', sans-serif" },
                                color: '#34d399',
                                stepSize: 1
                            }
                        }
                    }
                }
            });
        }

        // 2. Biểu đồ tròn: Trạng thái Đơn hàng
        const ctxDonut = document.getElementById('orderStatusDonutChart');
        if (ctxDonut) {
            new Chart(ctxDonut, {
                type: 'doughnut',
                data: {
                    labels: ['Mới đặt', 'Đã xác nhận', 'Đang giao', 'Hoàn tất', 'Đã hủy'],
                    datasets: [{
                        data: [
                            <?= (int)$tkDonHang['don_moi'] ?>,
                            <?= (int)$tkDonHang['don_xacnhan'] ?>,
                            <?= (int)$tkDonHang['don_vanchuyen'] ?>,
                            <?= (int)$tkDonHang['don_hoanthanh'] ?>,
                            <?= (int)$tkDonHang['don_huy'] ?>
                        ],
                        backgroundColor: [
                            '#f59e0b',
                            '#0ea5e9',
                            '#6366f1',
                            '#10b981',
                            '#ef4444'
                        ],
                        borderWidth: 2,
                        borderColor: 'rgba(15, 23, 42, 0.6)',
                        hoverOffset: 6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '72%',
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: 'rgba(15, 23, 42, 0.95)',
                            titleFont: { family: "'Plus Jakarta Sans', sans-serif", weight: '700' },
                            bodyFont: { family: "'Plus Jakarta Sans', sans-serif" },
                            padding: 10,
                            cornerRadius: 10,
                            callbacks: {
                                label: function(context) {
                                    const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                    const val = context.raw;
                                    const pct = total > 0 ? ((val / total) * 100).toFixed(1) : 0;
                                    return `${context.label}: ${val} đơn (${pct}%)`;
                                }
                            }
                        }
                    }
                }
            });
        }
    });
    </script>
</body>

</html>
