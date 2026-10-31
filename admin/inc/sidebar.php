<?php
$isAdmin = isset($_SESSION["nguoidung"]) && (int)$_SESSION["nguoidung"]["loai"] === 1;
?>
<nav id="sidebar" class="sidebar js-sidebar">
    <div class="sidebar-content js-simplebar">
        <a class="sidebar-brand d-flex align-items-center justify-content-between text-decoration-none" href="../ktnguoidung/index.php" style="text-decoration: none !important;">
            <span class="d-flex align-items-center gap-2 text-decoration-none" style="text-decoration: none !important;">
                <i class="align-middle text-primary" data-feather="activity"></i>
                <span class="align-middle fw-bold text-decoration-none" style="text-decoration: none !important;">GymBro</span>
            </span>
            <span class="sidebar-brand-badge text-decoration-none" style="text-decoration: none !important;"><?= $isAdmin ? 'ADMIN' : 'STAFF' ?></span>
        </a>

        <ul class="sidebar-nav">
            <li class="sidebar-header">
                TỔNG QUAN
            </li>

            <li class="sidebar-item <?= (strpos($_SERVER['SCRIPT_NAME'], 'ktnguoidung') !== false && (empty($_GET['action']) || $_GET['action'] === 'macdinh')) ? 'active' : '' ?>">
                <a class="sidebar-link" href="../ktnguoidung/index.php">
                    <i class="align-middle" data-feather="sliders"></i>
                    <span class="align-middle">Bảng điều khiển</span>
                </a>
            </li>

            <?php if ($isAdmin): ?>
                <li class="sidebar-header">
                    HỆ THỐNG & NHÂN SỰ
                </li>

                <li class="sidebar-item <?= (strpos($_SERVER['SCRIPT_NAME'], 'qlnhansu') !== false) ? 'active' : '' ?>">
                    <a class="sidebar-link" href="../qlnhansu/index.php">
                        <i class="align-middle" data-feather="users"></i>
                        <span class="align-middle">Quản lý nhân sự</span>
                    </a>
                </li>

                <li class="sidebar-item <?= (strpos($_SERVER['SCRIPT_NAME'], 'qlchisoBMI') !== false) ? 'active' : '' ?>">
                    <a class="sidebar-link" href="../qlchisoBMI/index.php">
                        <i class="align-middle" data-feather="heart"></i>
                        <span class="align-middle">Cấu hình chỉ số BMI</span>
                    </a>
                </li>
            <?php endif; ?>

            <li class="sidebar-header">
                QUẢN LÝ KINH DOANH
            </li>

            <li class="sidebar-item <?= (strpos($_SERVER['SCRIPT_NAME'], 'qldanhmuc') !== false) ? 'active' : '' ?>">
                <a class="sidebar-link" href="../qldanhmuc/index.php">
                    <i class="align-middle" data-feather="grid"></i>
                    <span class="align-middle">Quản lý danh mục</span>
                </a>
            </li>

            <li class="sidebar-item <?= (strpos($_SERVER['SCRIPT_NAME'], 'qlsanpham') !== false) ? 'active' : '' ?>">
                <a class="sidebar-link" href="../qlsanpham/index.php">
                    <i class="align-middle" data-feather="package"></i>
                    <span class="align-middle">Quản lý hàng hóa</span>
                </a>
            </li>

            <li class="sidebar-item <?= (strpos($_SERVER['SCRIPT_NAME'], 'qlkhachhang') !== false) ? 'active' : '' ?>">
                <a class="sidebar-link" href="../qlkhachhang/index.php">
                    <i class="align-middle" data-feather="user-check"></i>
                    <span class="align-middle">Quản lý khách hàng</span>
                </a>
            </li>

            <li class="sidebar-item <?= (strpos($_SERVER['SCRIPT_NAME'], 'qldonhang') !== false) ? 'active' : '' ?>">
                <a class="sidebar-link" href="../qldonhang/index.php">
                    <i class="align-middle" data-feather="truck"></i>
                    <span class="align-middle">Quản lý đơn hàng</span>
                </a>
            </li>
        </ul>
    </div>
</nav>
