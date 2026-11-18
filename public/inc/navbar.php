<div class="gs-announcement-bar">
    <div class="gs-announcement-track">
        <div class="gs-announcement-item">
            <span><span class="gs-announcement-dot">&#9679;</span> Freeship Đơn Hàng Từ 500k</span>
            <span><span class="gs-announcement-dot">&#9679;</span> Đổi trả miễn phí trong 7 ngày</span>
            <span><span class="gs-announcement-dot">&#9679;</span> Hàng chính hãng 100%</span>
            <span><span class="gs-announcement-dot">&#9679;</span> Gợi ý size &amp; combo chuẩn chỉ số BMI</span>
        </div>
        <div class="gs-announcement-item" aria-hidden="true">
            <span><span class="gs-announcement-dot">&#9679;</span> Freeship Đơn Hàng Từ 500k</span>
            <span><span class="gs-announcement-dot">&#9679;</span> Đổi trả miễn phí trong 7 ngày</span>
            <span><span class="gs-announcement-dot">&#9679;</span> Hàng chính hãng 100%</span>
            <span><span class="gs-announcement-dot">&#9679;</span> Gợi ý size &amp; combo chuẩn chỉ số BMI</span>
        </div>
    </div>
</div>

<nav class="navbar navbar-expand-lg navbar-light sticky-top py-3">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center p-0" href="index.php" title="GYMBRO">
            <img src="../assets/images/systems/logo.png?v=<?php echo file_exists(__DIR__ . '/../../assets/images/systems/logo.png') ? filemtime(__DIR__ . '/../../assets/images/systems/logo.png') : time(); ?>" alt="GYMBRO" height="44" class="d-inline-block align-text-top" style="height: 44px; max-height: 44px; width: auto; object-fit: contain; display: block;">
        </a>
        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#gsNavbarNav"
            aria-controls="gsNavbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="gsNavbarNav">
            <ul class="navbar-nav mx-auto mb-2 mb-lg-0 gap-lg-1 align-items-lg-center">
                <li class="nav-item dropdown" id="gsFashionDropdownWrap">
                    <a class="nav-link dropdown-toggle px-3 text-nowrap" href="#"
                        id="fashionDropdown" role="button" aria-expanded="false">
                        Thời trang
                    </a>
                    <ul class="dropdown-menu p-2 gs-dropdown-menu"
                        aria-labelledby="fashionDropdown">
                        <li>
                            <a class="dropdown-item gs-dropdown-item d-flex align-items-center gap-2"
                                href="index.php?action=sanpham&loai=quanao&doituong=nam">
                                Nam
                            </a>
                        </li>
                        <li>
                            <hr class="dropdown-divider my-1">
                        </li>
                        <li>
                            <a class="dropdown-item gs-dropdown-item d-flex align-items-center gap-2"
                                href="index.php?action=sanpham&loai=quanao&doituong=nu">
                                Nữ
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a class="nav-link px-3 text-nowrap" href="index.php?action=sanpham&loai=phukien">Phụ kiện</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link px-3 text-nowrap" href="index.php?action=sanpham&loai=tpbs">Thực phẩm bổ sung</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link px-3 text-nowrap" href="index.php?action=goy">
                        Gợi ý theo chỉ số
                    </a>
                </li>
            </ul>
            <div class="d-flex align-items-center gap-2 pt-3 pt-lg-0">
                <form action="index.php" method="get" class="m-0 d-flex align-items-center gap-2 gs-nav-search-form position-relative">
                    <input type="hidden" name="action" value="sanpham">
                    <div class="input-group input-group-sm gs-nav-search-group">
                        <input type="text" name="q" id="gsNavSearchInput"
                            class="form-control gs-nav-search-input"
                            placeholder="Tìm sản phẩm..."
                            aria-label="Tìm sản phẩm"
                            value="<?= htmlspecialchars($_GET['q'] ?? '') ?>"
                            autocomplete="off"
                            required>
                        <button class="btn gs-nav-search-btn" type="submit" title="Tìm kiếm">
                            <i class="fa-solid fa-magnifying-glass"></i>
                        </button>
                    </div>
                    <a href="index.php?action=sanpham" class="gs-nav-filter-btn" title="Bộ lọc nâng cao"><i class="fa-solid fa-filter"></i></a>
                    <div class="gs-live-search-dropdown shadow-lg" id="gsLiveSearchDropdown" style="display: none;"></div>
                </form>
                <div class="dropdown gs-user-dropdown">
                    <button class="btn btn-outline-secondary p-2" type="button" id="menuNguoiDung" data-bs-toggle="dropdown" data-bs-auto-close="true" aria-expanded="false" title="Menu tài khoản">
                        <i class="fa-solid fa-bars fs-5"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow p-2 gs-user-menu-dropdown" aria-labelledby="menuNguoiDung">
                        <?php if (isset($_SESSION['nguoidung'])): ?>
                            <li class="px-3 py-2 text-muted small border-bottom mb-1">
                                Xin chào, <strong class="text-dark d-block text-truncate"><?= htmlspecialchars($_SESSION['nguoidung']['hoten']) ?></strong>
                            </li>
                            <li>
                                <a class="dropdown-item py-2 d-flex align-items-center justify-content-between" href="index.php?action=taikhoan">
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
                        <li><hr class="dropdown-divider my-1"></li>
                        <li>
                            <a class="dropdown-item py-2 d-flex align-items-center justify-content-between" href="index.php?action=yeuthich">
                                <span class="d-flex align-items-center gap-2">
                                    <span class="gs-menu-icon-box"><i class="fa-regular fa-heart"></i></span>
                                    <span>Yêu thích</span>
                                </span>
                                <span class="badge rounded-pill" style="background: #D02545; color: #fff;" id="badgeYeuthichNavbar"><?php echo isset($gsSoluongYeuthich) ? (int) $gsSoluongYeuthich : 0; ?></span>
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item py-2 d-flex align-items-center justify-content-between" href="index.php?action=sosanh">
                                <span class="d-flex align-items-center gap-2">
                                    <span class="gs-menu-icon-box"><i class="fa-solid fa-code-compare"></i></span>
                                    <span>So sánh sản phẩm</span>
                                </span>
                                <span class="badge rounded-pill" style="background: #141414; color: #fff;" id="badgeSosanhNavbar"><?php echo isset($_SESSION['gs_sosanh']) ? count($_SESSION['gs_sosanh']) : 0; ?></span>
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item py-2 d-flex align-items-center justify-content-between" href="index.php?action=giohang">
                                <span class="d-flex align-items-center gap-2">
                                    <span class="gs-menu-icon-box"><i class="fa-solid fa-bag-shopping"></i></span>
                                    <span>Giỏ hàng</span>
                                </span>
                                <span class="badge rounded-pill" style="background: #D02545; color: #fff;" id="badgeGiohangNavbar"><?php echo isset($gh) ? (int) $gh->dem() : 0; ?></span>
                            </a>
                        </li>
                        <?php if (isset($_SESSION['nguoidung'])): ?>
                            <li><hr class="dropdown-divider my-1"></li>
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
    <div class="modal-dialog modal-dialog-centered" style="max-width: 860px;">
        <div class="modal-content overflow-hidden border-0 shadow-lg" style="border-radius: 20px;">
            <div class="row g-0">
                <div class="col-lg-5 d-none d-lg-block position-relative" style="background: linear-gradient(135deg, #141414 0%, #201015 100%); min-height: 480px;">
                    <img src="../assets/images/banners/tieuvy_bmi_popup.jpg?v=<?= file_exists(__DIR__ . '/../../assets/images/banners/tieuvy_bmi_popup.jpg') ? filemtime(__DIR__ . '/../../assets/images/banners/tieuvy_bmi_popup.jpg') : time(); ?>" 
                         alt="Hoa hậu Trần Tiểu Vy - GymBro Ambassador" 
                         style="width: 100%; height: 100%; object-fit: cover; object-position: top center; display: block;">
                    <div class="position-absolute top-0 start-0 w-100 h-100" style="background: linear-gradient(180deg, rgba(20,20,20,0.1) 0%, rgba(20,20,20,0.3) 50%, rgba(20,20,20,0.92) 100%);"></div>
                    <div class="position-absolute bottom-0 start-0 w-100 p-4 text-white" style="z-index: 2;">
                        <span class="badge text-uppercase px-2 py-1 mb-2" style="background: #D02545; font-size: 0.7rem; letter-spacing: 0.08em; font-weight: 700;">
                            GYMBRO AMBASSADOR
                        </span>
                        <h5 class="fw-bold mb-1 text-white" style="font-size: 1.15rem;">HOA HẬU TRẦN TIỂU VY</h5>
                        <p class="small text-white-50 mb-0" style="line-height: 1.45; font-size: 0.8rem;">
                            Đồng hành kiến tạo vóc dáng &amp; tư vấn chỉ số thể hình BMI chuẩn form cùng GymBro.
                        </p>
                    </div>
                </div>

                <div class="col-12 col-lg-7 d-flex flex-column justify-content-center p-4 p-sm-5 position-relative bg-white">
                    <button type="button" class="btn-close position-absolute top-0 end-0 m-3" data-bs-dismiss="modal" aria-label="Đóng" style="z-index: 5;"></button>

                    <div class="text-center mb-3">
                        <img src="../assets/images/systems/logo.png?v=<?= file_exists(__DIR__ . '/../../assets/images/systems/logo.png') ? filemtime(__DIR__ . '/../../assets/images/systems/logo.png') : time(); ?>" alt="GYMBRO" height="36" style="height: 36px; width: auto; object-fit: contain; filter: brightness(0);">
                        <p class="small text-muted mt-2 mb-0">Đồng hành cùng mục tiêu tập luyện của bạn</p>
                    </div>

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
                                    <label for="modal-login-email" class="form-label small fw-medium text-dark">Địa chỉ Email</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fa-regular fa-envelope"></i></span>
                                        <input type="email" id="modal-login-email" class="form-control" name="txtemail" placeholder="name@example.com" autocomplete="email" required>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="modal-login-pass" class="form-label small fw-medium text-dark">Mật khẩu</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fa-solid fa-lock"></i></span>
                                        <input type="password" id="modal-login-pass" class="form-control" name="txtmatkhau" placeholder="Nhập mật khẩu" autocomplete="current-password" required>
                                    </div>
                                </div>
                                <button class="btn btn-auth-submit w-100 fw-semibold py-2" type="submit">
                                    Đăng nhập
                                </button>
                            </form>
                        </div>

                        <div class="tab-pane fade" id="modal-tab-register" role="tabpanel">
                            <form action="index.php" method="post">
                                <input type="hidden" name="action" value="xldangky">
                                <div class="mb-2">
                                    <label for="modal-reg-name" class="form-label small fw-medium text-dark">Họ và tên</label>
                                    <input type="text" id="modal-reg-name" class="form-control" name="txthoten" placeholder="Nguyễn Văn A" autocomplete="name" required>
                                </div>
                                <div class="mb-2">
                                    <label for="modal-reg-email" class="form-label small fw-medium text-dark">Địa chỉ Email</label>
                                    <input type="email" id="modal-reg-email" class="form-control" name="txtemail" placeholder="name@example.com" autocomplete="email" required>
                                </div>
                                <div class="mb-2">
                                    <label for="modal-reg-phone" class="form-label small fw-medium text-dark">Số điện thoại</label>
                                    <input type="tel" id="modal-reg-phone" class="form-control" name="txtsodt" placeholder="0901234567" pattern="[0-9]{10}" autocomplete="tel" required>
                                </div>
                                <div class="mb-2">
                                    <label for="modal-reg-pass" class="form-label small fw-medium text-dark">Mật khẩu</label>
                                    <input type="password" id="modal-reg-pass" class="form-control" name="txtmatkhau" placeholder="Tối thiểu 6 ký tự" minlength="6" autocomplete="new-password" required>
                                </div>
                                <div class="mb-3">
                                    <label for="modal-reg-repass" class="form-label small fw-medium text-dark">Xác nhận mật khẩu</label>
                                    <input type="password" id="modal-reg-repass" class="form-control" name="txtxacnhanmatkhau" placeholder="Nhập lại mật khẩu" autocomplete="new-password" required>
                                </div>
                                <button class="btn btn-auth-submit w-100 fw-semibold py-2" type="submit">
                                    Đăng ký tài khoản
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<style>
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
        <div class="modal-content bg-white text-dark">
            <div class="modal-header">
                <h5 class="modal-title fw-bold" id="gsSearchModalLabel">Tìm kiếm sản phẩm</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
            </div>
            <div class="modal-body p-4">
                <form action="index.php" method="get">
                    <input type="hidden" name="action" value="sanpham">
                    <div class="input-group input-group-lg mb-3">
                        <input type="text" name="q" id="gsModalSearchInput" class="form-control" aria-label="Tìm kiếm sản phẩm"
                            placeholder="Tìm kiếm sản phẩm, thương hiệu, mục tiêu tập luyện..." autocomplete="off">
                        <button class="btn-gs btn-gs--primary px-4" type="submit">Tìm kiếm</button>
                    </div>
                </form>
                <div>
                    <span class="text-muted small me-2">Gợi ý tìm kiếm:</span>
                    <a href="index.php?action=sanpham&q=whey" class="badge bg-light text-dark border text-decoration-none me-1">Whey Protein</a>
                    <a href="index.php?action=sanpham&q=legging" class="badge bg-light text-dark border text-decoration-none me-1">Legging</a>
                    <a href="index.php?action=sanpham&q=creatine" class="badge bg-light text-dark border text-decoration-none me-1">Creatine</a>
                    <a href="index.php?action=sanpham&loai=quanao&doituong=nam" class="badge bg-light text-dark border text-decoration-none me-1">Đồ tập nam</a>
                    <a href="index.php?action=sanpham&loai=quanao&doituong=nu" class="badge bg-light text-dark border text-decoration-none">Đồ tập nữ</a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const dropdownWrap = document.getElementById('gsFashionDropdownWrap');
        const dropdownBtn = document.getElementById('fashionDropdown');
        const userMenuBtn = document.getElementById('menuNguoiDung');
        if (dropdownWrap && dropdownBtn) {
            dropdownBtn.addEventListener('click', function (e) {
                e.preventDefault();
                e.stopPropagation();
                if (userMenuBtn && typeof bootstrap !== 'undefined') {
                    const userDd = bootstrap.Dropdown.getInstance(userMenuBtn);
                    if (userDd) userDd.hide();
                }
                dropdownWrap.classList.toggle('show');
                dropdownBtn.setAttribute('aria-expanded', dropdownWrap.classList.contains('show') ? 'true' : 'false');
            });
            document.addEventListener('click', function (e) {
                if (!dropdownWrap.contains(e.target)) {
                    dropdownWrap.classList.remove('show');
                    dropdownBtn.setAttribute('aria-expanded', 'false');
                }
            });
            if (userMenuBtn) {
                userMenuBtn.addEventListener('show.bs.dropdown', function () {
                    dropdownWrap.classList.remove('show');
                    dropdownBtn.setAttribute('aria-expanded', 'false');
                });
            }
        }

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
                            dropdown.innerHTML = '<div class="p-3 text-center text-muted small">Không tìm thấy sản phẩm phù hợp</div>';
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
                                            ${item.tenthuonghieu ? '<span class="badge bg-light border text-dark">' + item.tenthuonghieu + '</span>' : ''}
                                        </div>
                                    </div>
                                    <i class="fa-solid fa-chevron-right text-muted small"></i>
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

        document.addEventListener('click', function (e) {
            if (!input.contains(e.target) && !dropdown.contains(e.target)) {
                dropdown.style.display = 'none';
            }
        });

        input.addEventListener('focus', function () {
            if (input.value.trim().length >= 2 && dropdown.innerHTML.trim() !== '') {
                dropdown.style.display = 'block';
            }
        });
    });
</script>
