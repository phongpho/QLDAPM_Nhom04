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
                <form action="index.php" method="get" class="m-0 d-flex align-items-center gap-1">
                    <input type="hidden" name="action" value="sanpham">
                    <div class="input-group input-group-sm">
                        <input type="text" name="q"
                            class="form-control text-white border-secondary"
                            style="background-color: #1a1a1a; min-width: 200px; color: #fff !important;"
                            placeholder="Tìm sản phẩm..."
                            value="<?= htmlspecialchars($_GET['q'] ?? '') ?>"
                            required>
                        <button class="btn btn-danger px-3 d-flex align-items-center justify-content-center" type="submit">
                            <i class="fa-solid fa-magnifying-glass"></i>
                        </button>
                    </div>
                    <a href="index.php?action=sanpham" class="btn btn-danger btn-sm d-flex align-items-center justify-content-center px-2 py-1" style="height: 31px;" title="Bộ lọc nâng cao"><i class="fa-solid fa-filter"></i></a>
                </form>
                <!-- Menu dropdown 3 dấu gạch ngang (Bootstrap) -->
                <div class="dropdown gs-user-dropdown">
                    <button class="btn btn-outline-secondary border-0 text-white p-2" type="button" id="menuNguoiDung" data-bs-toggle="dropdown" data-bs-auto-close="true" aria-expanded="false" title="Menu tài khoản">
                        <i class="fa-solid fa-bars fs-4"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-dark dropdown-menu-end shadow border-secondary p-2" aria-labelledby="menuNguoiDung" style="min-width: 230px;">
                        <?php if (isset($_SESSION['nguoidung'])): ?>
                            <li class="px-3 py-2 text-white-50 small border-bottom border-secondary mb-1">
                                Xin chào, <strong class="text-white d-block text-truncate"><?= htmlspecialchars($_SESSION['nguoidung']['hoten']) ?></strong>
                            </li>
                            <li>
                                <a class="dropdown-item py-2 d-flex align-items-center gap-2" href="taikhoan.php">
                                    <i class="fa-solid fa-id-card text-info"></i> Hồ sơ cá nhân
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item py-2 d-flex align-items-center gap-2" href="index.php?action=donhang">
                                    <i class="fa-solid fa-box text-success"></i> Đơn hàng của tôi
                                </a>
                            </li>
                        <?php else: ?>
                            <li>
                                <a class="dropdown-item py-2 d-flex align-items-center gap-2" href="index.php?action=dangnhap">
                                    <i class="fa-solid fa-right-to-bracket text-danger"></i> Đăng nhập / Đăng ký
                                </a>
                            </li>
                        <?php endif; ?>
                        <li><hr class="dropdown-divider border-secondary my-1"></li>
                        <li>
                            <a class="dropdown-item py-2 d-flex align-items-center justify-content-between" href="yeuthich.php">
                                <span><i class="fa-regular fa-heart text-danger me-2"></i> Yêu thích</span>
                                <span class="badge rounded-pill bg-danger">2</span>
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item py-2 d-flex align-items-center justify-content-between" href="index.php?action=giohang">
                                <span><i class="fa-solid fa-bag-shopping text-warning me-2"></i> Giỏ hàng</span>
                                <span class="badge rounded-pill bg-warning text-dark fw-bold">3</span>
                            </a>
                        </li>
                        <?php if (isset($_SESSION['nguoidung'])): ?>
                            <li><hr class="dropdown-divider border-secondary my-1"></li>
                            <li>
                                <a class="dropdown-item py-2 text-danger d-flex align-items-center gap-2" href="index.php?action=dangxuat">
                                    <i class="fa-solid fa-arrow-right-from-bracket"></i> Đăng xuất
                                </a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</nav>
<style>
    /* Hỗ trợ rê chuột (hover) trên màn hình máy tính cho menu 3 gạch */
    @media (min-width: 992px) {
        .gs-user-dropdown:hover .dropdown-menu {
            display: block;
            margin-top: 0;
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
