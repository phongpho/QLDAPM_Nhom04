<nav class="navbar navbar-expand-lg navbar-dark bg-black sticky-top shadow-sm py-3">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center p-0" href="index.php" title="GYMBRO">
            <img src="../assets/images/systems/logo.png?v=<?php echo file_exists(__DIR__ . '/../../assets/images/systems/logo.png') ? filemtime(__DIR__ . '/../../assets/images/systems/logo.png') : time(); ?>" alt="GYMBRO" height="44" class="d-inline-block align-text-top" style="height: 44px; max-height: 44px; width: auto; object-fit: contain; display: block;">
        </a>
        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#gsNavbarNav"
            aria-controls="gsNavbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="gsNavbarNav">
            <ul class="navbar-nav mx-auto mb-2 mb-lg-0 fw-semibold text-uppercase gap-lg-1 align-items-lg-center">
                <li class="nav-item dropdown" id="gsFashionDropdownWrap">
                    <a class="nav-link dropdown-toggle text-white-50 px-3 hover-white text-nowrap" href="#"
                        id="fashionDropdown" role="button" aria-expanded="false">
                        Thời trang
                    </a>
                    <ul class="dropdown-menu dropdown-menu-dark bg-dark border-secondary p-2 gs-dropdown-menu"
                        aria-labelledby="fashionDropdown">
                        <li>
                            <a class="dropdown-item gs-dropdown-item d-flex align-items-center gap-2"
                                href="index.php?action=sanpham&doituong=nam">
                                <i class="fa-solid fa-mars text-danger"></i> Nam
                            </a>
                        </li>
                        <li>
                            <hr class="dropdown-divider border-secondary my-1">
                        </li>
                        <li>
                            <a class="dropdown-item gs-dropdown-item d-flex align-items-center gap-2"
                                href="index.php?action=sanpham&doituong=nu">
                                <i class="fa-solid fa-venus text-danger"></i> Nữ
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white-50 px-3 hover-white text-nowrap" href="index.php?action=sanpham&loai=phukien">Phụ
                        kiện</a>
                <li class="nav-item">
                    <a class="nav-link text-white-50 px-3 hover-white text-nowrap" href="index.php?action=sanpham&loai=tpbs">Thực
                        phẩm bổ sung</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white-50 px-3 hover-white text-nowrap" href="index.php?action=goy">
                        Gợi ý theo chỉ số
                    </a>
                </li>
            </ul>
            <div class="d-flex align-items-center gap-3 pt-3 pt-lg-0">
                <!-- thanh tim kiem -->
                <form action="index.php" method="get" class="m-0 d-flex align-items-center gap-1 gs-nav-search-form position-relative">
                    <input type="hidden" name="action" value="sanpham">
                    <div class="input-group input-group-sm">
                        <input type="text" name="q" id="gsNavSearchInput"
                            class="form-control text-white border-secondary"
                            style="background-color: #1a1a1a; min-width: 200px; color: #fff !important;"
                            placeholder="Tìm sản phẩm..."
                            value="<?= htmlspecialchars($_GET['q'] ?? '') ?>"
                            autocomplete="off"
                            required>
                        <button class="btn btn-danger px-3 d-flex align-items-center justify-content-center" type="submit">
                            <i class="fa-solid fa-magnifying-glass"></i>
                        </button>
                    </div>
                    <a href="index.php?action=sanpham" class="btn btn-danger btn-sm d-flex align-items-center justify-content-center px-2 py-1" style="height: 31px;" title="Bộ lọc nâng cao"><i class="fa-solid fa-filter"></i></a>
                    <!-- hop goi y live search -->
                    <div class="gs-live-search-dropdown shadow-lg" id="gsLiveSearchDropdown" style="display: none;"></div>
                </form>
                <!-- Menu dropdown 3 dấu gạch ngang (Bootstrap) -->
                <div class="dropdown gs-user-dropdown">
                    <button class="btn btn-outline-secondary border-0 text-white p-2" type="button" id="menuNguoiDung" data-bs-toggle="dropdown" data-bs-auto-close="true" aria-expanded="false" title="Menu tài khoản">
                        <i class="fa-solid fa-bars fs-4"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-dark dropdown-menu-end shadow border-secondary p-2 gs-user-menu-dropdown" aria-labelledby="menuNguoiDung">
                        <?php if (isset($_SESSION['nguoidung'])): ?>
                            <li class="px-3 py-2 text-white-50 small border-bottom border-secondary mb-1">
                                Xin chào, <strong class="text-white d-block text-truncate"><?= htmlspecialchars($_SESSION['nguoidung']['hoten']) ?></strong>
                            </li>
                            <li>
                                <a class="dropdown-item py-2 d-flex align-items-center justify-content-between" href="taikhoan.php">
                                    <span class="d-flex align-items-center gap-2">
                                        <span class="gs-menu-icon-box"><i class="fa-solid fa-id-card"></i></span>
                                        <span>Hồ sơ cá nhân</span>
                                    </span>
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item py-2 d-flex align-items-center justify-content-between" href="index.php?action=donhang">
                                    <span class="d-flex align-items-center gap-2">
                                        <span class="gs-menu-icon-box"><i class="fa-solid fa-box"></i></span>
                                        <span>Đơn hàng của tôi</span>
                                    </span>
                                </a>
                            </li>
                        <?php else: ?>
                            <li>
                                <a class="dropdown-item py-2 d-flex align-items-center justify-content-between" href="index.php?action=dangnhap" data-bs-toggle="modal" data-bs-target="#gsAuthModal">
                                    <span class="d-flex align-items-center gap-2">
                                        <span class="gs-menu-icon-box"><i class="fa-solid fa-right-to-bracket"></i></span>
                                        <span>Đăng nhập / Đăng ký</span>
                                    </span>
                                </a>
                            </li>
                        <?php endif; ?>
                        <li><hr class="dropdown-divider border-secondary my-1"></li>
                        <li>
                            <a class="dropdown-item py-2 d-flex align-items-center justify-content-between" href="index.php?action=yeuthich">
                                <span class="d-flex align-items-center gap-2">
                                    <span class="gs-menu-icon-box"><i class="fa-regular fa-heart"></i></span>
                                    <span>Yêu thích</span>
                                </span>
                                <span class="badge rounded-pill bg-danger" id="badgeYeuthichNavbar"><?php echo isset($gsSoluongYeuthich) ? (int) $gsSoluongYeuthich : 0; ?></span>
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item py-2 d-flex align-items-center justify-content-between" href="index.php?action=giohang">
                                <span class="d-flex align-items-center gap-2">
                                    <span class="gs-menu-icon-box"><i class="fa-solid fa-bag-shopping"></i></span>
                                    <span>Giỏ hàng</span>
                                </span>
                                <span class="badge rounded-pill bg-warning text-dark fw-bold" id="badgeGiohangNavbar"><?php echo isset($gh) ? (int) $gh->dem() : 0; ?></span>
                            </a>
                        </li>
                        <?php if (isset($_SESSION['nguoidung'])): ?>
                            <li><hr class="dropdown-divider border-secondary my-1"></li>
                            <li>
                                <a class="dropdown-item py-2 d-flex align-items-center justify-content-between" href="index.php?action=dangxuat">
                                    <span class="d-flex align-items-center gap-2">
                                        <span class="gs-menu-icon-box gs-menu-icon-box--danger"><i class="fa-solid fa-arrow-right-from-bracket"></i></span>
                                        <span class="text-danger">Đăng xuất</span>
                                    </span>
                                </a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</nav>

<?php if (!isset($_SESSION['nguoidung'])): ?>
<div class="modal fade" id="gsAuthModal" tabindex="-1" aria-labelledby="gsAuthModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 440px;">
        <div class="modal-content border-secondary shadow-lg" style="background: #141c2b; border: 1px solid rgba(255, 255, 255, 0.08); border-radius: 20px;">
            <div class="modal-header border-secondary pb-2">
                <div class="d-flex align-items-center gap-2">
                    <img src="../assets/images/systems/logo.png?v=<?= file_exists(__DIR__ . '/../../assets/images/systems/logo.png') ? filemtime(__DIR__ . '/../../assets/images/systems/logo.png') : time(); ?>" alt="GYMBRO" height="36" style="height: 36px; width: auto; object-fit: contain;">
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Đóng"></button>
            </div>
            <div class="modal-body p-4">
                <ul class="nav nav-pills nav-fill mb-3 gb-auth-nav" id="modalAuthTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active py-2" id="modal-login-tab" data-bs-toggle="pill" data-bs-target="#modal-tab-login" type="button" role="tab">
                            Đăng nhập
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link py-2" id="modal-register-tab" data-bs-toggle="pill" data-bs-target="#modal-tab-register" type="button" role="tab">
                            Đăng ký
                        </button>
                    </li>
                </ul>

                <div class="tab-content" id="modalAuthTabContent">
                    <div class="tab-pane fade show active" id="modal-tab-login" role="tabpanel">
                        <form action="index.php" method="post">
                            <input type="hidden" name="action" value="xldangnhap">
                            <div class="mb-3">
                                <label class="form-label small fw-medium text-light">Địa chỉ Email</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fa-regular fa-envelope"></i></span>
                                    <input type="email" class="form-control" name="txtemail" placeholder="name@example.com" required>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label small fw-medium text-light">Mật khẩu</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fa-solid fa-lock"></i></span>
                                    <input type="password" class="form-control" name="txtmatkhau" placeholder="Nhập mật khẩu" required>
                                </div>
                            </div>
                            <button class="btn btn-auth-submit w-100 fw-semibold py-2 mt-2" type="submit">
                                <i class="fa-solid fa-right-to-bracket me-1"></i> Đăng nhập
                            </button>
                        </form>
                    </div>

                    <div class="tab-pane fade" id="modal-tab-register" role="tabpanel">
                        <form action="index.php" method="post">
                            <input type="hidden" name="action" value="xldangky">
                            <div class="mb-2">
                                <label class="form-label small fw-medium text-light">Họ và tên</label>
                                <input type="text" class="form-control" name="txthoten" placeholder="Nguyễn Văn A" required>
                            </div>
                            <div class="mb-2">
                                <label class="form-label small fw-medium text-light">Địa chỉ Email</label>
                                <input type="email" class="form-control" name="txtemail" placeholder="name@example.com" required>
                            </div>
                            <div class="mb-2">
                                <label class="form-label small fw-medium text-light">Số điện thoại</label>
                                <input type="tel" class="form-control" name="txtsodt" placeholder="0901234567" pattern="[0-9]{10}" required>
                            </div>
                            <div class="mb-2">
                                <label class="form-label small fw-medium text-light">Mật khẩu</label>
                                <input type="password" class="form-control" name="txtmatkhau" placeholder="Tối thiểu 6 ký tự" minlength="6" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label small fw-medium text-light">Xác nhận mật khẩu</label>
                                <input type="password" class="form-control" name="txtxacnhanmatkhau" placeholder="Nhập lại mật khẩu" required>
                            </div>
                            <button class="btn btn-auth-submit w-100 fw-semibold py-2" type="submit">
                                <i class="fa-solid fa-user-plus me-1"></i> Đăng ký tài khoản
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<style>
    /* tuy chinh dropdown menu 3 gach dong bo theme gymbro */
    .gs-user-dropdown .gs-user-menu-dropdown {
        min-width: 260px !important;
        background: #0b0f17 !important;
        border: 1px solid rgba(255, 255, 255, 0.1) !important;
        border-radius: 14px !important;
        box-shadow: 0 16px 36px rgba(0, 0, 0, 0.75), 0 0 0 1px rgba(255, 255, 255, 0.05) !important;
        padding: 8px !important;
    }

    .gs-user-dropdown .dropdown-item {
        color: #94a3b8 !important;
        font-size: 0.9rem !important;
        font-weight: 600 !important;
        padding: 9px 12px !important;
        border-radius: 8px !important;
        transition: all 0.2s ease !important;
    }

    .gs-user-dropdown .dropdown-item:hover {
        background: rgba(249, 115, 22, 0.12) !important;
        color: #ffffff !important;
        transform: translateX(3px);
    }

    /* o chua icon kieu khung vuong mau vang cam giong nut tim kiem va loc */
    .gs-menu-icon-box {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 32px;
        height: 32px;
        border-radius: 8px;
        background: rgba(249, 115, 22, 0.15);
        border: 1.5px solid #f97316;
        color: #f97316;
        font-size: 0.95rem;
        flex-shrink: 0;
        transition: all 0.2s ease;
    }

    .gs-user-dropdown .dropdown-item:hover .gs-menu-icon-box {
        background: #f97316;
        color: #ffffff;
        box-shadow: 0 0 10px rgba(249, 115, 22, 0.45);
    }

    .gs-menu-icon-box--danger {
        background: rgba(239, 68, 68, 0.15);
        border-color: #ef4444;
        color: #ef4444;
    }

    .gs-user-dropdown .dropdown-item:hover .gs-menu-icon-box--danger {
        background: #ef4444;
        color: #ffffff;
        box-shadow: 0 0 10px rgba(239, 68, 68, 0.45);
    }

    .gs-user-dropdown .dropdown-divider {
        border-color: rgba(255, 255, 255, 0.08) !important;
        margin: 6px 0 !important;
    }

    /* ho tro re chuot tren desktop */
    @media (min-width: 992px) {
        .gs-user-dropdown:hover .dropdown-menu {
            display: block;
            margin-top: 0;
            animation: gsFadeDown 0.22s cubic-bezier(0.16, 1, 0.3, 1);
        }
    }

    @keyframes gsFadeDown {
        from {
            opacity: 0;
            transform: translateY(8px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>
<div class="modal fade" id="gsSearchModal" tabindex="-1" aria-labelledby="gsSearchModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content bg-dark text-white border-secondary">
            <div class="modal-header border-secondary">
                <h5 class="modal-title fw-bold" id="gsSearchModalLabel">
                    <i class="fa-solid fa-magnifying-glass me-2 text-danger"></i>Tìm kiếm sản phẩm
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Đóng"></button>
            </div>
            <div class="modal-body p-4">
                <form action="index.php" method="get">
                    <input type="hidden" name="action" value="sanpham">
                    <div class="input-group input-group-lg mb-3">
                        <input type="text" name="q" class="form-control bg-black text-white border-secondary"
                            placeholder="Tìm kiếm sản phẩm, thương hiệu, mục tiêu tập luyện..." autocomplete="off">
                        <button class="btn btn-danger px-4" type="submit">Tìm kiếm</button>
                    </div>
                </form>
                <div>
                    <span class="text-secondary small me-2">Gợi ý tìm kiếm:</span>
                    <a href="index.php?action=sanpham&q=whey" class="badge bg-secondary text-decoration-none me-1">Whey Protein</a>
                    <a href="index.php?action=sanpham&q=legging" class="badge bg-secondary text-decoration-none me-1">Legging</a>
                    <a href="index.php?action=sanpham&q=creatine" class="badge bg-secondary text-decoration-none me-1">Creatine</a>
                    <a href="index.php?action=sanpham&doituong=nam" class="badge bg-secondary text-decoration-none me-1">Đồ tập
                        nam</a>
                    <a href="index.php?action=sanpham&doituong=nu" class="badge bg-secondary text-decoration-none">Đồ tập nữ</a>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const dropdownWrap = document.getElementById('gsFashionDropdownWrap');
        const dropdownBtn = document.getElementById('fashionDropdown');
        if (dropdownWrap && dropdownBtn) {
            dropdownBtn.addEventListener('click', function (e) {
                e.preventDefault();
                e.stopPropagation();
                dropdownWrap.classList.toggle('show');
            });
            document.addEventListener('click', function (e) {
                if (!dropdownWrap.contains(e.target)) {
                    dropdownWrap.classList.remove('show');
                }
            });
        }
    });
</script>
<style>
    /* live search dropdown tren navbar */
    .gs-live-search-dropdown {
        position: absolute;
        top: calc(100% + 8px);
        left: 0;
        width: 100%;
        min-width: 330px;
        background: #0b0f17;
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 12px;
        box-shadow: 0 16px 36px rgba(0, 0, 0, 0.75), 0 0 0 1px rgba(255, 255, 255, 0.05);
        z-index: 1080;
        overflow: hidden;
        animation: gsFadeDown 0.2s cubic-bezier(0.16, 1, 0.3, 1);
    }

    .gs-live-search-item {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 9px 12px;
        color: #f8fafc;
        text-decoration: none;
        border-bottom: 1px solid rgba(255, 255, 255, 0.04);
        transition: all 0.18s ease;
    }

    .gs-live-search-item:last-child {
        border-bottom: none;
    }

    .gs-live-search-item:hover {
        background: rgba(249, 115, 22, 0.12);
        color: #ffffff;
    }

    .gs-live-search-img {
        width: 44px;
        height: 44px;
        border-radius: 6px;
        object-fit: cover;
        background: #161616;
        flex-shrink: 0;
        border: 1px solid rgba(255, 255, 255, 0.08);
    }

    .gs-live-search-info {
        flex-grow: 1;
        min-width: 0;
    }

    .gs-live-search-title {
        font-size: 0.86rem;
        font-weight: 600;
        margin-bottom: 2px;
        white-space: nowrap;
        overflow: hidden;
        text-truncate: ellipsis;
        display: block;
        color: #f1f5f9;
    }

    .gs-live-search-meta {
        font-size: 0.75rem;
        color: #94a3b8;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .gs-live-search-price {
        color: #f97316;
        font-weight: 700;
    }

    .gs-live-search-footer {
        padding: 8px 12px;
        background: #070a0f;
        text-align: center;
        font-size: 0.8rem;
        font-weight: 600;
        border-top: 1px solid rgba(255, 255, 255, 0.08);
    }

    .gs-live-search-footer a {
        color: #f97316;
        text-decoration: none;
    }

    .gs-live-search-footer a:hover {
        text-decoration: underline;
    }
</style>
<script>
    // xu ly live search ajax tren navbar
    document.addEventListener('DOMContentLoaded', function () {
        const input = document.getElementById('gsNavSearchInput');
        const dropdown = document.getElementById('gsLiveSearchDropdown');
        let timer = null;

        if (!input || !dropdown) return;

        input.addEventListener('input', function () {
            const query = input.value.trim();
            clearTimeout(timer);

            if (query.length < 2) {
                dropdown.innerHTML = '';
                dropdown.style.display = 'none';
                return;
            }

            timer = setTimeout(function () {
                fetch('index.php?action=ajax_timkiem&q=' + encodeURIComponent(query))
                    .then(res => res.json())
                    .then(res => {
                        if (!res.ok || !res.data || res.data.length === 0) {
                            dropdown.innerHTML = '<div class="p-3 text-center text-secondary small"><i class="fa-solid fa-magnifying-glass me-1 text-warning"></i> Không tìm thấy sản phẩm phù hợp</div>';
                            dropdown.style.display = 'block';
                            return;
                        }

                        let html = '';
                        res.data.forEach(item => {
                            html += `
                                <a href="index.php?action=chitiet&id=${item.id}" class="gs-live-search-item">
                                    <img src="${item.hinhanh}" alt="" class="gs-live-search-img" onerror="this.src='../assets/images/systems/logo.png'">
                                    <div class="gs-live-search-info">
                                        <span class="gs-live-search-title">${item.tensanpham}</span>
                                        <div class="gs-live-search-meta">
                                            <span class="gs-live-search-price">${item.giaban_format}</span>
                                            ${item.tenthuonghieu ? '<span class="badge bg-dark border border-secondary text-secondary">' + item.tenthuonghieu + '</span>' : ''}
                                        </div>
                                    </div>
                                    <i class="fa-solid fa-chevron-right text-secondary small"></i>
                                </a>
                            `;
                        });

                        html += `
                            <div class="gs-live-search-footer">
                                <a href="index.php?action=sanpham&q=${encodeURIComponent(query)}">
                                    Xem tất cả kết quả cho "${query}" <i class="fa-solid fa-arrow-right-long ms-1"></i>
                                </a>
                            </div>
                        `;

                        dropdown.innerHTML = html;
                        dropdown.style.display = 'block';
                    })
                    .catch(() => {
                        dropdown.style.display = 'none';
                    });
            }, 250);
        });

        // an dropdown khi click ra ngoai
        document.addEventListener('click', function (e) {
            if (!input.contains(e.target) && !dropdown.contains(e.target)) {
                dropdown.style.display = 'none';
            }
        });

        // hien lai dropdown neu input van co chu khi click vao
        input.addEventListener('focus', function () {
            if (input.value.trim().length >= 2 && dropdown.innerHTML.trim() !== '') {
                dropdown.style.display = 'block';
            }
        });
    });
</script>
