<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Kiểm tra quyền Admin (1) hoặc Nhân viên (2)
if (!isset($_SESSION["nguoidung"]) || !in_array((int)$_SESSION["nguoidung"]["loai"], [1, 2])) {
    header("Location: ../ktnguoidung/index.php?action=dangnhap");
    exit();
}

$khachhang = $k;
$thetrang = $khachhang["thetrang"] ?? null;
$diachi_list = $khachhang["diachi_list"] ?? [];
$donhang_list = $khachhang["donhang_list"] ?? [];

$hinhHienTai = (!empty($khachhang["hinhanh"]) && file_exists("../../assets/images/users/" . $khachhang["hinhanh"])) 
    ? "../../assets/images/users/" . $khachhang["hinhanh"] 
    : "../../assets/images/users/user.png";

// Tính toán BMI đánh giá nếu có dữ liệu thể trạng
$bmiValue = null;
$bmiCategory = "";
$bmiBadgeClass = "";
if ($thetrang && !empty($thetrang["bmi"])) {
    $bmiValue = (float)$thetrang["bmi"];
} elseif ($thetrang && !empty($thetrang["chieucao"]) && !empty($thetrang["cannang"])) {
    $hMet = (float)$thetrang["chieucao"] / 100;
    if ($hMet > 0) {
        $bmiValue = round((float)$thetrang["cannang"] / ($hMet * $hMet), 1);
    }
}

if ($bmiValue !== null) {
    if ($bmiValue < 18.5) {
        $bmiCategory = "Hơi gầy / Cần tăng cân";
        $bmiBadgeClass = "background-color: #eff6ff; color: #1d4ed8; border: 1px solid #bfdbfe;";
    } elseif ($bmiValue <= 24.9) {
        $bmiCategory = "Cân đối / Thể trạng chuẩn";
        $bmiBadgeClass = "background-color: #ecfdf5; color: #047857; border: 1px solid #a7f3d0;";
    } elseif ($bmiValue <= 29.9) {
        $bmiCategory = "Thừa cân / Cần siết cơ";
        $bmiBadgeClass = "background-color: #fffbeb; color: #b45309; border: 1px solid #fde68a;";
    } else {
        $bmiCategory = "Béo phì / Cần giảm mỡ";
        $bmiBadgeClass = "background-color: #fef2f2; color: #b91c1c; border: 1px solid #fecaca;";
    }
}
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Hồ sơ khách hàng: <?= htmlspecialchars($khachhang["hoten"]) ?> - GymBro Admin</title>

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
</head>

<body>
    <div class="wrapper">
        <?php include('../inc/sidebar.php'); ?>

        <div class="main">
            <?php include('../inc/header.php'); ?>

            <main class="content">
                <div class="container-fluid p-0">

                    <!-- Breadcrumbs & Navigation -->
                    <div class="gb-breadcrumb">
                        <a href="index.php"><i class="fa fa-users me-1"></i>Quản lý khách hàng</a>
                        <i class="fa fa-chevron-right" style="font-size: 0.65rem;"></i>
                        <span class="text-dark fw-semibold">Hồ sơ: <?= htmlspecialchars($khachhang["hoten"]) ?></span>
                    </div>

                    <!-- Header profile banner -->
                    <div class="gb-edit-card mb-4">
                        <div class="p-4 p-md-4 d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                            <div class="d-flex align-items-center gap-3">
                                <img src="<?= $hinhHienTai ?>" alt="<?= htmlspecialchars($khachhang["hoten"]) ?>" style="width: 72px; height: 72px; border-radius: 50%; object-fit: cover; border: 3px solid #e2e8f0; box-shadow: 0 4px 10px rgba(0,0,0,0.06);">
                                <div>
                                    <div class="d-flex align-items-center gap-2 flex-wrap">
                                        <h2 class="gb-edit-title mb-0"><?= htmlspecialchars($khachhang["hoten"]) ?></h2>
                                        <span class="badge" style="background-color: #eff6ff; color: #1e40af; border: 1px solid #bfdbfe;">Khách hàng #<?= $khachhang["id"] ?></span>
                                        <?php if ((int)$khachhang["trangthai"] === 1): ?>
                                            <span class="badge" style="background-color: #ecfdf5; color: #047857; border: 1px solid #a7f3d0;">
                                                <i class="fa fa-check-circle me-1"></i>Hoạt động
                                            </span>
                                        <?php else: ?>
                                            <span class="badge" style="background-color: #fef2f2; color: #b91c1c; border: 1px solid #fecaca;">
                                                <i class="fa fa-ban me-1"></i>Đang bị khóa
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                    <div class="text-muted small mt-1">
                                        <i class="fa fa-envelope me-1"></i><?= htmlspecialchars($khachhang["email"]) ?>
                                        <span class="mx-2">·</span>
                                        <i class="fa fa-phone me-1"></i><?= htmlspecialchars($khachhang["sodienthoai"]) ?>
                                        <span class="mx-2">·</span>
                                        <i class="fa fa-calendar-days me-1"></i>Đăng ký: <?= !empty($khachhang["ngaytao"]) ? date("d/m/Y", strtotime($khachhang["ngaytao"])) : "Chưa xác định" ?>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex align-items-center gap-2 flex-wrap">
                                <a href="index.php?action=sua&id=<?= $khachhang["id"] ?>" class="btn btn-sm btn-outline-primary fw-semibold px-3">
                                    <i class="fa fa-pen me-1"></i> Chỉnh sửa
                                </a>
                                <?php if ((int)$khachhang["trangthai"] === 1): ?>
                                    <a href="index.php?action=doitrangthai&id=<?= $khachhang["id"] ?>&trangthai=0" class="btn btn-sm btn-outline-warning fw-semibold px-3" onclick="return confirm('Bạn muốn tạm khóa tài khoản khách hàng này?');">
                                        <i class="fa fa-lock me-1"></i> Khóa tài khoản
                                    </a>
                                <?php else: ?>
                                    <a href="index.php?action=doitrangthai&id=<?= $khachhang["id"] ?>&trangthai=1" class="btn btn-sm btn-outline-success fw-semibold px-3" onclick="return confirm('Bạn muốn mở khóa tài khoản khách hàng này?');">
                                        <i class="fa fa-unlock me-1"></i> Mở khóa
                                    </a>
                                <?php endif; ?>
                                <a href="index.php" class="btn btn-sm btn-light border text-secondary fw-semibold px-3">
                                    <i class="fa fa-arrow-left me-1"></i> Danh sách
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Row 1: Thể trạng Gym & Địa chỉ giao hàng -->
                    <div class="row g-4 mb-4">
                        <!-- Card Thể trạng tập luyện -->
                        <div class="col-lg-6">
                            <div class="card border-0 shadow-sm rounded-3 h-100">
                                <div class="card-header bg-white border-bottom p-3 d-flex align-items-center justify-content-between">
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="gb-icon-badge" style="width: 34px; height: 34px; font-size: 1rem; background-color: #ecfdf5; color: #059669;">
                                            <i class="fa fa-dumbbell"></i>
                                        </div>
                                        <span class="fw-bold text-dark">Hồ sơ thể trạng & Tập luyện</span>
                                    </div>
                                    <?php if ($thetrang): ?>
                                        <span class="badge" style="<?= $bmiBadgeClass ?> font-size: 0.75rem;">
                                            <?= $bmiCategory ?>
                                        </span>
                                    <?php endif; ?>
                                </div>
                                <div class="card-body p-4">
                                    <?php if ($thetrang): ?>
                                        <div class="row g-3 mb-3">
                                            <div class="col-6 col-sm-3">
                                                <div class="p-3 rounded-3 text-center" style="background-color: #f8fafc; border: 1px solid #e2e8f0;">
                                                    <div class="text-muted small mb-1">Chiều cao</div>
                                                    <div class="fw-bold text-dark fs-5"><?= htmlspecialchars($thetrang["chieucao"] ?? 0) ?> <span class="fs-6 fw-normal text-muted">cm</span></div>
                                                </div>
                                            </div>
                                            <div class="col-6 col-sm-3">
                                                <div class="p-3 rounded-3 text-center" style="background-color: #f8fafc; border: 1px solid #e2e8f0;">
                                                    <div class="text-muted small mb-1">Cân nặng</div>
                                                    <div class="fw-bold text-dark fs-5"><?= htmlspecialchars($thetrang["cannang"] ?? 0) ?> <span class="fs-6 fw-normal text-muted">kg</span></div>
                                                </div>
                                            </div>
                                            <div class="col-6 col-sm-3">
                                                <div class="p-3 rounded-3 text-center" style="background-color: #f8fafc; border: 1px solid #e2e8f0;">
                                                    <div class="text-muted small mb-1">Chỉ số BMI</div>
                                                    <div class="fw-bold text-primary fs-5"><?= $bmiValue !== null ? $bmiValue : "N/A" ?></div>
                                                </div>
                                            </div>
                                            <div class="col-6 col-sm-3">
                                                <div class="p-3 rounded-3 text-center" style="background-color: #f8fafc; border: 1px solid #e2e8f0;">
                                                    <div class="text-muted small mb-1">Độ tuổi</div>
                                                    <div class="fw-bold text-dark fs-5"><?= !empty($thetrang["tuoi"]) ? htmlspecialchars($thetrang["tuoi"]) : "N/A" ?></div>
                                                </div>
                                            </div>
                                        </div>

                                        <ul class="list-group list-group-flush small">
                                            <li class="list-group-item px-0 d-flex justify-content-between align-items-center">
                                                <span class="text-muted"><i class="fa fa-venus-mars me-2"></i>Giới tính sinh học:</span>
                                                <span class="fw-semibold text-dark"><?= !empty($thetrang["gioitinh"]) ? htmlspecialchars($thetrang["gioitinh"]) : "Chưa cập nhật" ?></span>
                                            </li>
                                            <li class="list-group-item px-0 d-flex justify-content-between align-items-center">
                                                <span class="text-muted"><i class="fa fa-bullseye me-2"></i>Mục tiêu hình thể:</span>
                                                <span class="fw-semibold text-primary"><?= !empty($thetrang["muctieu"]) ? htmlspecialchars($thetrang["muctieu"]) : "Rèn luyện thể lực chung" ?></span>
                                            </li>
                                            <?php if (!empty($thetrang["ghichu"])): ?>
                                            <li class="list-group-item px-0">
                                                <span class="text-muted"><i class="fa fa-note-sticky me-2"></i>Ghi chú thể lực:</span>
                                                <div class="mt-1 p-2 rounded bg-light text-secondary"><?= htmlspecialchars($thetrang["ghichu"]) ?></div>
                                            </li>
                                            <?php endif; ?>
                                        </ul>
                                    <?php else: ?>
                                        <div class="text-center py-4">
                                            <div class="mb-3 text-muted" style="font-size: 2.25rem;">
                                                <i class="fa fa-heart-pulse opacity-50"></i>
                                            </div>
                                            <p class="text-secondary fw-semibold mb-1">Khách hàng chưa tạo hồ sơ thể trạng</p>
                                            <p class="text-muted small mb-0">Hồ sơ sẽ tự động ghi nhận khi khách dùng tính năng gợi ý thực đơn / lộ trình trên hệ thống.</p>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <!-- Card Sổ địa chỉ giao nhận -->
                        <div class="col-lg-6">
                            <div class="card border-0 shadow-sm rounded-3 h-100">
                                <div class="card-header bg-white border-bottom p-3 d-flex align-items-center justify-content-between">
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="gb-icon-badge" style="width: 34px; height: 34px; font-size: 1rem; background-color: #eff6ff; color: #2563eb;">
                                            <i class="fa fa-map-location-dot"></i>
                                        </div>
                                        <span class="fw-bold text-dark">Sổ địa chỉ nhận hàng (<?= count($diachi_list) ?>)</span>
                                    </div>
                                </div>
                                <div class="card-body p-4">
                                    <?php if (!empty($diachi_list)): ?>
                                        <div class="d-flex flex-column gap-3">
                                            <?php foreach ($diachi_list as $dc): ?>
                                                <div class="p-3 rounded-3" style="background-color: #f8fafc; border: 1px solid <?= ((int)$dc["macdinh"] === 1) ? '#bfdbfe' : '#e2e8f0' ?>;">
                                                    <div class="d-flex align-items-center justify-content-between mb-1">
                                                        <div class="fw-bold text-dark small">
                                                            <i class="fa fa-user me-1 text-muted"></i><?= htmlspecialchars($dc["tennguoinhan"] ?? $khachhang["hoten"]) ?>
                                                            <span class="mx-2 text-muted">·</span>
                                                            <i class="fa fa-phone me-1 text-muted"></i><?= htmlspecialchars($dc["sodienthoai"] ?? $khachhang["sodienthoai"]) ?>
                                                        </div>
                                                        <?php if ((int)$dc["macdinh"] === 1): ?>
                                                            <span class="badge" style="background-color: #eff6ff; color: #1e40af; border: 1px solid #bfdbfe; font-size: 0.68rem;">Mặc định</span>
                                                        <?php endif; ?>
                                                    </div>
                                                    <div class="text-secondary small mt-1">
                                                        <i class="fa fa-location-dot me-1 text-danger"></i><?= htmlspecialchars($dc["diachi"]) ?>
                                                    </div>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    <?php else: ?>
                                        <div class="text-center py-4">
                                            <div class="mb-3 text-muted" style="font-size: 2.25rem;">
                                                <i class="fa fa-map-pin opacity-50"></i>
                                            </div>
                                            <p class="text-secondary fw-semibold mb-1">Chưa lưu địa chỉ giao hàng</p>
                                            <p class="text-muted small mb-0">Khách hàng chưa thêm sổ địa chỉ trong tài khoản cá nhân.</p>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Row 2: Lịch sử đơn hàng gần đây -->
                    <div class="card border-0 shadow-sm rounded-3 mb-4">
                        <div class="card-header bg-white border-bottom p-3 d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center gap-2">
                                <div class="gb-icon-badge" style="width: 34px; height: 34px; font-size: 1rem; background-color: #fffbeb; color: #d97706;">
                                    <i class="fa fa-box-open"></i>
                                </div>
                                <span class="fw-bold text-dark">Lịch sử giao dịch gần đây</span>
                            </div>
                            <span class="text-muted small">Tối đa 10 đơn gần nhất</span>
                        </div>
                        <div class="card-body p-0">
                            <?php if (!empty($donhang_list)): ?>
                                <div class="table-responsive">
                                    <table class="table gb-table mb-0 align-middle">
                                        <thead>
                                            <tr>
                                                <th class="ps-4">Mã đơn</th>
                                                <th>Ngày đặt</th>
                                                <th>Phương thức</th>
                                                <th>Tổng thanh toán</th>
                                                <th>Trạng thái</th>
                                                <th class="text-end pe-4">Hành động</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($donhang_list as $dh): ?>
                                                <tr>
                                                    <td class="ps-4 fw-bold text-dark">
                                                        #<?= $dh["id"] ?>
                                                    </td>
                                                    <td class="small text-secondary">
                                                        <?= !empty($dh["ngaytao"]) ? date("d/m/Y H:i", strtotime($dh["ngaytao"])) : "Chưa rõ" ?>
                                                    </td>
                                                    <td class="small">
                                                        <span class="badge bg-light text-dark border"><?= htmlspecialchars($dh["phuongthuctt"] ?? "COD") ?></span>
                                                    </td>
                                                    <td class="fw-bold text-primary">
                                                        <?= number_format($dh["tongtien"] ?? 0, 0, ',', '.') ?> đ
                                                    </td>
                                                    <td>
                                                        <?php
                                                        $stt = (int)($dh["trangthai"] ?? 0);
                                                        switch ($stt) {
                                                            case 0:
                                                                echo '<span class="badge" style="background-color: #fffbeb; color: #b45309; border: 1px solid #fde68a;">Chờ xử lý</span>';
                                                                break;
                                                            case 1:
                                                                echo '<span class="badge" style="background-color: #eff6ff; color: #1e40af; border: 1px solid #bfdbfe;">Đang giao</span>';
                                                                break;
                                                            case 2:
                                                                echo '<span class="badge" style="background-color: #ecfdf5; color: #047857; border: 1px solid #a7f3d0;">Hoàn thành</span>';
                                                                break;
                                                            case 3:
                                                                echo '<span class="badge" style="background-color: #fef2f2; color: #b91c1c; border: 1px solid #fecaca;">Đã hủy</span>';
                                                                break;
                                                            default:
                                                                echo '<span class="badge bg-secondary">Khác</span>';
                                                                break;
                                                        }
                                                        ?>
                                                    </td>
                                                    <td class="text-end pe-4">
                                                        <a href="../qldonhang/index.php?action=chitiet&id=<?= $dh["id"] ?>" class="btn btn-sm btn-light border text-secondary fw-semibold">
                                                            <i class="fa fa-eye me-1"></i> Xem đơn
                                                        </a>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php else: ?>
                                <div class="text-center py-5">
                                    <div class="mb-3 text-muted" style="font-size: 2.5rem;">
                                        <i class="fa fa-basket-shopping opacity-50"></i>
                                    </div>
                                    <p class="text-secondary fw-semibold mb-1">Khách hàng chưa có đơn hàng nào</p>
                                    <p class="text-muted small mb-0">Khi khách mua sắm tại cửa hàng trực tuyến, lịch sử sẽ xuất hiện tại đây.</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                </div>
            </main>

            <?php include('../inc/footer.php'); ?>
        </div>
    </div>
</body>

</html>
