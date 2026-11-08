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

            <li class="sidebar-item <?= (strpos($_SERVER['SCRIPT_NAME'], 'qlthuonghieu') !== false) ? 'active' : '' ?>">
                <a class="sidebar-link" href="../qlthuonghieu/index.php">
                    <i class="align-middle" data-feather="award"></i>
                    <span class="align-middle">Quản lý thương hiệu</span>
                </a>
            </li>

            <li class="sidebar-item <?= (strpos($_SERVER['SCRIPT_NAME'], 'qlvoucher') !== false) ? 'active' : '' ?>">
                <a class="sidebar-link" href="../qlvoucher/index.php">
                    <i class="align-middle" data-feather="tag"></i>
                    <span class="align-middle">Mã giảm giá & Voucher</span>
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

            <li class="sidebar-header">
                NỘI DUNG & TƯƠNG TÁC
            </li>

            <li class="sidebar-item <?= (strpos($_SERVER['SCRIPT_NAME'], 'qldanhgia') !== false) ? 'active' : '' ?>">
                <a class="sidebar-link" href="../qldanhgia/index.php">
                    <i class="align-middle" data-feather="message-square"></i>
                    <span class="align-middle">Đánh giá & Phản hồi</span>
                </a>
            </li>

            <li class="sidebar-item <?= (strpos($_SERVER['SCRIPT_NAME'], 'qltintuc') !== false) ? 'active' : '' ?>">
                <a class="sidebar-link" href="../qltintuc/index.php">
                    <i class="align-middle" data-feather="file-text"></i>
                    <span class="align-middle">Bài viết & Tin tức</span>
                </a>
            </li>
        </ul>

        <!-- Enterprise System Status Widget (Frosted Glass) -->
        <div class="gb-sidebar-widget">
            <div class="d-flex align-items-center justify-content-between mb-1">
                <span class="d-inline-flex align-items-center gap-2" style="font-size: 0.72rem; color: #94a3b8; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em;">
                    <span class="gb-pulse-dot"></span> GymBro Core
                </span>
                <span class="badge" style="background: rgba(14, 165, 233, 0.2); color: #38bdf8; font-size: 0.65rem; border: 1px solid rgba(56, 189, 248, 0.35); padding: 0.2rem 0.45rem;">v2.5 Pro</span>
            </div>
            <div style="font-size: 0.775rem; color: #cbd5e1; font-weight: 600;">MySQL Database: <span class="text-success">Connected</span></div>
            <div class="text-muted" style="font-size: 0.7rem; margin-top: 2px;">Protected & Isolated Env</div>
        </div>
    </div>
</nav>
