<?php
$currentScript = $_SERVER['SCRIPT_NAME'] ?? '';
$currentUri = $_SERVER['REQUEST_URI'] ?? '';
$isKtNguoiDung = (strpos($currentScript, 'ktnguoidung') !== false || strpos($currentUri, 'ktnguoidung') !== false) && (empty($_GET['action']) || $_GET['action'] === 'macdinh');
$isNhanSu = (strpos($currentScript, 'qlnhansu') !== false || strpos($currentUri, 'qlnhansu') !== false);
$isDanhMuc = (strpos($currentScript, 'qldanhmuc') !== false || strpos($currentUri, 'qldanhmuc') !== false);
$isSanPham = (strpos($currentScript, 'qlsanpham') !== false || strpos($currentUri, 'qlsanpham') !== false);
$isAdmin = isset($_SESSION["nguoidung"]) && (int)$_SESSION["nguoidung"]["loai"] === 1;
?>
<nav id="sidebar" class="sidebar js-sidebar">
    <div class="sidebar-content js-simplebar">
        <a class="sidebar-brand d-flex align-items-center justify-content-between text-decoration-none" href="../ktnguoidung/index.php">
            <span class="d-flex align-items-center gap-2 text-decoration-none">
                <i class="align-middle text-primary" data-feather="activity"></i>
                <span class="align-middle fw-bold text-decoration-none">GymBro</span>
            </span>
            <span class="sidebar-brand-badge text-decoration-none"><?= $isAdmin ? 'ADMIN' : 'STAFF' ?></span>
        </a>

        <ul class="sidebar-nav">
            <li class="sidebar-header">
                TỔNG QUAN
            </li>

            <li class="sidebar-item <?= $isKtNguoiDung ? 'active' : '' ?>">
                <a class="sidebar-link" href="../ktnguoidung/index.php">
                    <i class="align-middle" data-feather="sliders"></i>
                    <span class="align-middle">Bảng điều khiển</span>
                </a>
            </li>

            <?php if ($isAdmin): ?>
                <li class="sidebar-header">
                    HỆ THỐNG & NHÂN SỰ
                </li>

                <li class="sidebar-item <?= $isNhanSu ? 'active' : '' ?>">
                    <a class="sidebar-link" href="../qlnhansu/index.php">
                        <i class="align-middle" data-feather="users"></i>
                        <span class="align-middle">Quản lý nhân sự</span>
                    </a>
                </li>
            <?php endif; ?>

            <li class="sidebar-header">
                QUẢN LÝ KINH DOANH
            </li>

            <li class="sidebar-item <?= $isDanhMuc ? 'active' : '' ?>">
                <a class="sidebar-link" href="../qldanhmuc/index.php">
                    <i class="align-middle" data-feather="grid"></i>
                    <span class="align-middle">Quản lý danh mục</span>
                </a>
            </li>

            <li class="sidebar-item <?= $isSanPham ? 'active' : '' ?>">
                <a class="sidebar-link" href="../qlsanpham/index.php">
                    <i class="align-middle" data-feather="package"></i>
                    <span class="align-middle">Quản lý hàng hóa</span>
                </a>
            </li>

            <li class="sidebar-item">
                <a class="sidebar-link" href="#">
                    <i class="align-middle" data-feather="user-check"></i>
                    <span class="align-middle">Quản lý khách hàng</span>
                </a>
            </li>

            <li class="sidebar-item">
                <a class="sidebar-link" href="#">
                    <i class="align-middle" data-feather="truck"></i>
                    <span class="align-middle">Quản lý đơn hàng</span>
                </a>
            </li>

            <li class="sidebar-item">
                <a class="sidebar-link" href="#">
                    <i class="align-middle" data-feather="bar-chart-2"></i>
                    <span class="align-middle">Báo cáo doanh thu</span>
                </a>
            </li>

            <li class="sidebar-item">
                <a class="sidebar-link" href="#">
                    <i class="align-middle" data-feather="tag"></i>
                    <span class="align-middle">Chương trình ưu đãi</span>
                </a>
            </li>
        </ul>
    </div>
</nav>