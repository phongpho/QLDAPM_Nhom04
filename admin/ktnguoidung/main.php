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

// Đếm dữ liệu tổng quan an toàn
$countDanhMuc = 0;
$countSanPham = 0;
$countNhanSu = 0;

try {
    if (file_exists("../../model/danhmuc.php")) {
        require_once("../../model/danhmuc.php");
        $dmModel = new DANHMUC();
        $dsDm = $dmModel->laydanhmuc();
        $countDanhMuc = is_array($dsDm) ? count($dsDm) : 0;
    }
} catch (Exception $e) {}

try {
    if (file_exists("../../model/sanpham.php")) {
        require_once("../../model/sanpham.php");
        $spModel = new SANPHAM();
        $dsSp = $spModel->laysanpham();
        $countSanPham = is_array($dsSp) ? count($dsSp) : 0;
    }
} catch (Exception $e) {}

try {
    if (isset($nd)) {
        $dsNd = $nd->laydanhsachnguoidung();
        if (is_array($dsNd)) {
            foreach ($dsNd as $u) {
                if ((int)$u["loai"] === 2) {
                    $countNhanSu++;
                }
            }
        }
    }
} catch (Exception $e) {}
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

                    <!-- Welcome Banner -->
                    <div class="gb-welcome-banner">
                        <div class="row align-items-center">
                            <div class="col-12 col-lg-8">
                                <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-3 py-1 mb-2 fw-semibold">
                                    <i class="fa fa-shield-halved me-1"></i> <?= $roleName ?>
                                </span>
                                <h1 class="gb-welcome-title">Xin chào, <?= htmlspecialchars($user["hoten"]) ?>!</h1>
                                <p class="gb-welcome-subtitle">
                                    Chào mừng bạn quay trở lại trung tâm quản trị GymBro. Tất cả các dịch vụ hệ thống và cơ sở dữ liệu đang vận hành trơn tru.
                                </p>
                            </div>
                            <div class="col-12 col-lg-4 text-lg-end mt-3 mt-lg-0">
                                <div class="d-inline-flex gap-2">
                                    <a href="index.php?action=hoso" class="btn btn-light fw-semibold shadow-sm">
                                        <i class="fa fa-user-gear me-1"></i> Hồ sơ cá nhân
                                    </a>
                                    <a href="../qldanhmuc/index.php" class="btn btn-primary fw-semibold shadow-sm">
                                        <i class="fa fa-grid-2 me-1"></i> Quản lý
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Metric Cards -->
                    <div class="row g-3 mb-4">
                        <div class="col-12 col-sm-6 col-xl-3">
                            <div class="gb-stat-card">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div>
                                        <div class="gb-stat-label">Danh mục hàng hóa</div>
                                        <div class="gb-stat-number"><?= $countDanhMuc ?></div>
                                        <div class="text-muted small mt-1">Phân loại sản phẩm</div>
                                    </div>
                                    <div class="gb-icon-box" style="background-color: #eff6ff; color: #2563eb;">
                                        <i class="fa fa-layer-group"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 col-sm-6 col-xl-3">
                            <div class="gb-stat-card">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div>
                                        <div class="gb-stat-label">Sản phẩm quản lý</div>
                                        <div class="gb-stat-number"><?= $countSanPham ?></div>
                                        <div class="text-muted small mt-1">Hàng hóa đang kinh doanh</div>
                                    </div>
                                    <div class="gb-icon-box" style="background-color: #ecfdf5; color: #059669;">
                                        <i class="fa fa-boxes-stacked"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 col-sm-6 col-xl-3">
                            <div class="gb-stat-card">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div>
                                        <div class="gb-stat-label">Nhân sự vận hành</div>
                                        <div class="gb-stat-number"><?= $countNhanSu ?></div>
                                        <div class="text-muted small mt-1">Tài khoản nhân viên</div>
                                    </div>
                                    <div class="gb-icon-box" style="background-color: #f3e8ff; color: #7e22ce;">
                                        <i class="fa fa-users-gear"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 col-sm-6 col-xl-3">
                            <div class="gb-stat-card">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div>
                                        <div class="gb-stat-label">Trạng thái máy chủ</div>
                                        <div class="text-success fw-bold fs-5 mt-1">
                                            <i class="fa fa-circle-check me-1"></i> Ổn định
                                        </div>
                                        <div class="text-muted small mt-1">Cơ sở dữ liệu sẵn sàng</div>
                                    </div>
                                    <div class="gb-icon-box" style="background-color: #f0fdf4; color: #16a34a;">
                                        <i class="fa fa-server"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Navigation Cards -->
                    <div class="row g-3 mb-4">
                        <div class="col-12 col-md-6 col-xl-3">
                            <a href="../qldanhmuc/index.php" class="gb-quick-card">
                                <div class="gb-quick-icon" style="background-color: #eff6ff; color: #2563eb;">
                                    <i class="fa fa-grid-2"></i>
                                </div>
                                <div>
                                    <div class="gb-quick-title">Quản lý danh mục</div>
                                    <p class="gb-quick-desc">Xem, thêm mới và cập nhật phân loại</p>
                                </div>
                            </a>
                        </div>

                        <div class="col-12 col-md-6 col-xl-3">
                            <a href="../qlsanpham/index.php" class="gb-quick-card">
                                <div class="gb-quick-icon" style="background-color: #ecfdf5; color: #059669;">
                                    <i class="fa fa-box-open"></i>
                                </div>
                                <div>
                                    <div class="gb-quick-title">Quản lý hàng hóa</div>
                                    <p class="gb-quick-desc">Kho hàng, giá bán và hình ảnh</p>
                                </div>
                            </a>
                        </div>

                        <?php if ($isAdmin): ?>
                            <div class="col-12 col-md-6 col-xl-3">
                                <a href="../qlnhansu/index.php" class="gb-quick-card">
                                    <div class="gb-quick-icon" style="background-color: #f3e8ff; color: #7e22ce;">
                                        <i class="fa fa-user-shield"></i>
                                    </div>
                                    <div>
                                        <div class="gb-quick-title">Quản trị nhân sự</div>
                                        <p class="gb-quick-desc">Đội ngũ nhân viên và phân quyền</p>
                                    </div>
                                </a>
                            </div>
                        <?php else: ?>
                            <div class="col-12 col-md-6 col-xl-3">
                                <a href="index.php?action=hoso" class="gb-quick-card">
                                    <div class="gb-quick-icon" style="background-color: #f3e8ff; color: #7e22ce;">
                                        <i class="fa fa-id-card"></i>
                                    </div>
                                    <div>
                                        <div class="gb-quick-title">Hồ sơ cá nhân</div>
                                        <p class="gb-quick-desc">Xem và chỉnh sửa thông tin</p>
                                    </div>
                                </a>
                            </div>
                        <?php endif; ?>

                        <div class="col-12 col-md-6 col-xl-3">
                            <a href="index.php?action=matkhau" class="gb-quick-card">
                                <div class="gb-quick-icon" style="background-color: #fffbeb; color: #d97706;">
                                    <i class="fa fa-key"></i>
                                </div>
                                <div>
                                    <div class="gb-quick-title">Đổi mật khẩu</div>
                                    <p class="gb-quick-desc">Bảo mật tài khoản định kỳ</p>
                                </div>
                            </a>
                        </div>
                    </div>

                    <!-- System Information Card -->
                    <div class="card border-0 shadow-sm rounded-3">
                        <div class="card-header bg-white border-bottom py-3">
                            <div class="d-flex align-items-center justify-content-between">
                                <h5 class="card-title mb-0 fw-bold text-dark">
                                    <i class="fa fa-info-circle text-primary me-2"></i>Thông tin phiên làm việc
                                </h5>
                                <span class="badge bg-success-subtle text-success border border-success-subtle">
                                    Đang hoạt động
                                </span>
                            </div>
                        </div>
                        <div class="card-body p-4">
                            <div class="row g-3">
                                <div class="col-12 col-md-4">
                                    <div class="text-muted small">Tài khoản đăng nhập</div>
                                    <div class="fw-bold text-dark mt-1"><?= htmlspecialchars($user["hoten"]) ?> (<?= htmlspecialchars($user["email"]) ?>)</div>
                                </div>
                                <div class="col-12 col-md-4">
                                    <div class="text-muted small">Vai trò hệ thống</div>
                                    <div class="fw-bold text-dark mt-1"><?= $roleName ?> (Mã loại: <?= (int)$user["loai"] ?>)</div>
                                </div>
                                <div class="col-12 col-md-4">
                                    <div class="text-muted small">Thời gian máy chủ</div>
                                    <div class="fw-bold text-dark mt-1"><?= date('H:i:s d/m/Y') ?></div>
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