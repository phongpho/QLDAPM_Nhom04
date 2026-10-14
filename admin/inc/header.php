<nav class="navbar navbar-expand navbar-light navbar-bg">
    <a class="sidebar-toggle js-sidebar-toggle">
        <i class="hamburger align-self-center"></i>
    </a>

    <div class="navbar-collapse collapse">
        <ul class="navbar-nav navbar-align">
            <li class="nav-item dropdown">
                <a class="nav-icon dropdown-toggle" href="#" id="alertsDropdown" data-bs-toggle="dropdown">
                    <div class="position-relative">
                        <i class="align-middle" data-feather="bell"></i>
                        <span class="indicator">1</span>
                    </div>
                </a>
                <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end py-0" aria-labelledby="alertsDropdown">
                    <div class="dropdown-menu-header">
                        1 thông báo mới
                    </div>
                    <div class="list-group">
                        <a href="#" class="list-group-item">
                            <div class="row g-0 align-items-center">
                                <div class="col-2">
                                    <i class="text-danger" data-feather="alert-circle"></i>
                                </div>
                                <div class="col-10">
                                    <div class="text-dark">Đơn hàng mới</div>
                                    <div class="text-muted small mt-1">Xem danh sách đơn hàng chờ xác nhận.</div>
                                    <div class="text-muted small mt-1">5 phút trước</div>
                                </div>
                            </div>
                        </a>

                    </div>
                    <div class="dropdown-menu-footer">
                        <a href="#" class="text-muted">Tất cả thông báo</a>
                    </div>
                </div>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-icon dropdown-toggle" href="#" id="messagesDropdown" data-bs-toggle="dropdown">
                    <div class="position-relative">
                        <i class="align-middle" data-feather="message-square"></i>
                        <span class="indicator">1</span>
                    </div>
                </a>
                <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end py-0" aria-labelledby="messagesDropdown">
                    <div class="dropdown-menu-header">
                        <div class="position-relative">
                            1 tin nhắn mới
                        </div>
                    </div>
                    <div class="list-group">
                        <a href="#" class="list-group-item">
                            <div class="row g-0 align-items-center">
                                <div class="col-2">
                                    <?php
                                    $hinhanh = $_SESSION["nguoidung"]["hinhanh"] ?? "";

                                    if (empty($hinhanh) || !file_exists("../../images/users/" . $hinhanh)) {
                                        $imgSrc = "../../images/users/user.png";
                                    } else {
                                        $imgSrc = "../../images/users/" . $hinhanh;
                                    }
                                    ?>
                                    <img src="<?php echo $imgSrc; ?>" class="avatar img-fluid rounded-circle" alt="<?php echo $_SESSION["nguoidung"]["hoten"]; ?>">
                                </div>
                                <div class="col-10 ps-2">
                                    <div class="text-dark">Doraemon</div>
                                    <div class="text-muted small mt-1">Mail của mèo máy đến từ tương lai nè ^.^</div>
                                    <div class="text-muted small mt-1">15 phút trước</div>
                                </div>
                            </div>
                        </a>

                    </div>
                    <div class="dropdown-menu-footer">
                        <a href="#" class="text-muted">Tất cả tin nhắn</a>
                    </div>
                </div>
            </li>


            <li class="nav-item dropdown">
                <a class="nav-icon dropdown-toggle d-inline-block d-sm-none" href="#" data-bs-toggle="dropdown">
                    <i class="align-middle" data-feather="settings"></i>
                </a>

                <a class="nav-link dropdown-toggle d-none d-sm-inline-block" href="#" data-bs-toggle="dropdown">
                    <img src="<?php echo $imgSrc; ?>" class="avatar img-fluid rounded me-1" alt="Quản trị viên" />
                    <span class="text-dark">
                        Chào <?php if (isset($_SESSION["nguoidung"])) echo $_SESSION["nguoidung"]["hoten"];
                                else echo "bạn"; ?>
                    </span>
                </a>
                <div class="dropdown-menu dropdown-menu-end">
                    <a class="dropdown-item" href="../ktnguoidung/index.php?action=hoso"><i class="align-middle me-1" data-feather="user"></i> Hồ sơ cá nhân</a>
                    <a class="dropdown-item" href="../ktnguoidung/index.php?action=matkhau"><i class="align-middle me-1" data-feather="key"></i> Đổi mật khẩu</a>

                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="../ktnguoidung/index.php?action=dangxuat"><i class="align-middle me-1" data-feather="log-out"></i> Đăng xuất</a>
                </div>
            </li>
        </ul>
    </div>
</nav>