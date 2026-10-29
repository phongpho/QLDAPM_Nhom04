<?php
$currentUser = $_SESSION["nguoidung"] ?? null;
$userName = $currentUser["hoten"] ?? "Quản trị viên";
$userLoai = (int)($currentUser["loai"] ?? 2);
$roleText = ($userLoai === 1) ? "Quản trị viên" : (($userLoai === 2) ? "Nhân viên" : "Khách hàng");
$roleBadgeClass = ($userLoai === 1) ? "bg-primary-subtle text-primary border border-primary-subtle" : "bg-light text-secondary border";

$userImg = "../../assets/images/users/user.png";
if (!empty($currentUser["hinhanh"])) {
    $customImgPath = "../../assets/images/users/" . $currentUser["hinhanh"];
    if (file_exists($customImgPath)) {
        $userImg = $customImgPath;
    }
}
?>
<nav class="navbar navbar-expand navbar-light navbar-bg">
    <a class="sidebar-toggle js-sidebar-toggle" title="Đóng/mở thanh điều hướng">
        <i class="hamburger align-self-center"></i>
    </a>

    <div class="navbar-collapse collapse">
        <ul class="navbar-nav navbar-align align-items-center">
            <li class="nav-item dropdown">
                <a class="nav-icon dropdown-toggle" href="#" id="alertsDropdown" data-bs-toggle="dropdown" aria-expanded="false" title="Thông báo hệ thống">
                    <div class="position-relative">
                        <i class="align-middle" data-feather="bell"></i>
                        <span class="indicator"></span>
                    </div>
                </a>
                <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end py-0 shadow border-0" aria-labelledby="alertsDropdown">
                    <div class="dropdown-menu-header py-2 px-3 fw-bold text-dark border-bottom small">
                        Thông báo hệ thống
                    </div>
                    <div class="list-group list-group-flush">
                        <a href="../qldanhmuc/index.php" class="list-group-item list-group-item-action py-2">
                            <div class="row g-0 align-items-center">
                                <div class="col-2 text-center">
                                    <i class="text-primary" data-feather="grid"></i>
                                </div>
                                <div class="col-10">
                                    <div class="text-dark small fw-semibold">Quản lý danh mục</div>
                                    <div class="text-muted small">Kiểm tra các danh mục hàng hóa mới nhất.</div>
                                </div>
                            </div>
                        </a>
                        <a href="../qlsanpham/index.php" class="list-group-item list-group-item-action py-2">
                            <div class="row g-0 align-items-center">
                                <div class="col-2 text-center">
                                    <i class="text-success" data-feather="package"></i>
                                </div>
                                <div class="col-10">
                                    <div class="text-dark small fw-semibold">Hàng hóa tồn kho</div>
                                    <div class="text-muted small">Cập nhật danh sách phụ kiện và thực phẩm bổ sung.</div>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="dropdown-menu-footer text-center py-2 border-top">
                        <a href="#" class="text-muted small text-decoration-none">Đánh dấu tất cả đã đọc</a>
                    </div>
                </div>
            </li>

            <!-- User Menu -->
            <li class="nav-item dropdown ms-2">
                <a class="nav-link dropdown-toggle navbar-user-pill text-decoration-none" href="#" data-bs-toggle="dropdown" aria-expanded="false">
                    <img src="<?= htmlspecialchars($userImg) ?>" class="navbar-user-avatar" alt="<?= htmlspecialchars($userName) ?>" />
                    <div class="d-none d-sm-block text-start lh-sm">
                        <div class="text-dark fw-bold small"><?= htmlspecialchars($userName) ?></div>
                        <div class="text-muted" style="font-size: 0.725rem;"><?= $roleText ?></div>
                    </div>
                </a>
                <div class="dropdown-menu dropdown-menu-end shadow border-0 py-2 mt-2" style="min-width: 200px;">
                    <div class="px-3 py-2 border-bottom d-sm-none">
                        <div class="fw-bold text-dark"><?= htmlspecialchars($userName) ?></div>
                        <span class="badge <?= $roleBadgeClass ?> small"><?= $roleText ?></span>
                    </div>
                    <a class="dropdown-item py-2 d-flex align-items-center" href="../ktnguoidung/index.php?action=hoso">
                        <i class="align-middle me-2 text-primary" data-feather="user"></i>
                        <span>Hồ sơ cá nhân</span>
                    </a>
                    <a class="dropdown-item py-2 d-flex align-items-center" href="../ktnguoidung/index.php?action=matkhau">
                        <i class="align-middle me-2 text-warning" data-feather="key"></i>
                        <span>Đổi mật khẩu</span>
                    </a>
                    <div class="dropdown-divider my-1"></div>
                    <a class="dropdown-item py-2 d-flex align-items-center text-danger" href="../ktnguoidung/index.php?action=dangxuat">
                        <i class="align-middle me-2 text-danger" data-feather="log-out"></i>
                        <span>Đăng xuất</span>
                    </a>
                </div>
            </li>
        </ul>
    </div>
</nav>