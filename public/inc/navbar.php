<nav class="navbar navbar-expand-lg navbar-dark bg-black sticky-top shadow-sm py-3">
    <div class="container">
        <a class="navbar-brand fw-bold fs-3 text-uppercase text-white text-decoration-none d-flex align-items-center gap-2"
            href="index.php">
            <img src="../assets/images/logo.png"
                onerror="this.onerror=null; this.src='data:image/svg+xml;utf8,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22><rect width=%22100%22 height=%22100%22 rx=%2220%22 fill=%22%23141414%22 stroke=%22%23dc3545%22 stroke-width=%224%22/><path d=%22M20 35 h8 v30 h-8 z M32 25 h10 v50 h-10 z M58 25 h10 v50 h-10 z M72 35 h8 v30 h-8 z%22 fill=%22%23dc3545%22/><rect x=%2242%22 y=%2245%22 width=%2216%22 height=%2210%22 fill=%22%23ffffff%22/><rect x=%2212%22 y=%2245%22 width=%228%22 height=%2210%22 rx=%222%22 fill=%22%23ffffff%22/><rect x=%2280%22 y=%2245%22 width=%228%22 height=%2210%22 rx=%222%22 fill=%22%23ffffff%22/></svg>';"
                alt="GymBro" width="42" height="42" class="d-inline-block align-text-top rounded">
            <span>GYM<span class="text-danger">BRO</span></span>
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
                                href="sanpham.php?doituong=nam">
                                <i class="fa-solid fa-mars text-danger"></i> Nam
                            </a>
                        </li>
                        <li>
                            <hr class="dropdown-divider border-secondary my-1">
                        </li>
                        <li>
                            <a class="dropdown-item gs-dropdown-item d-flex align-items-center gap-2"
                                href="sanpham.php?doituong=nu">
                                <i class="fa-solid fa-venus text-danger"></i> Nữ
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white-50 px-3 hover-white text-nowrap" href="sanpham.php?loai=phukien">Phụ
                        kiện</a>
                <li class="nav-item">
                    <a class="nav-link text-white-50 px-3 hover-white text-nowrap" href="sanpham.php?loai=tpbs">Thực
                        phẩm bổ sung</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white px-3 d-flex align-items-center gap-1 text-nowrap" href="goy.php">
                        Gợi ý theo chỉ số
                        <span class="badge bg-danger text-white rounded-pill ms-1 gs-badge-sm">Cá nhân
                            hóa</span>
                    </a>
                </li>
            </ul>

            <div class="d-flex align-items-center gap-3 pt-3 pt-lg-0">
                <button type="button" class="btn btn-outline-secondary border-0 text-white p-2" data-bs-toggle="modal"
                    data-bs-target="#gsSearchModal" aria-label="Tìm kiếm">
                    <i class="fa-solid fa-magnifying-glass fs-5"></i>
                </button>

                <a href="yeuthich.php" class="btn btn-outline-secondary border-0 text-white position-relative p-2"
                    aria-label="Yêu thích">
                    <i class="fa-regular fa-heart fs-5"></i>
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger gs-badge-sm">
                        2
                    </span>
                </a>

                <a href="taikhoan.php" class="btn btn-outline-secondary border-0 text-white p-2" aria-label="Tài khoản">
                    <i class="fa-regular fa-user fs-5"></i>
                </a>

                <a href="giohang.php" class="btn btn-outline-secondary border-0 text-white position-relative p-2"
                    aria-label="Giỏ hàng">
                    <i class="fa-solid fa-bag-shopping fs-5"></i>
                    <span
                        class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-warning text-dark fw-bold gs-badge-sm">
                        3
                    </span>
                </a>
            </div>
        </div>
    </div>
</nav>

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
                <form action="sanpham.php" method="get">
                    <div class="input-group input-group-lg mb-3">
                        <input type="text" name="q" class="form-control bg-black text-white border-secondary"
                            placeholder="Tìm kiếm sản phẩm, thương hiệu, mục tiêu tập luyện..." autocomplete="off">
                        <button class="btn btn-danger px-4" type="submit">Tìm kiếm</button>
                    </div>
                </form>
                <div>
                    <span class="text-secondary small me-2">Gợi ý tìm kiếm:</span>
                    <a href="sanpham.php?q=whey" class="badge bg-secondary text-decoration-none me-1">Whey Protein</a>
                    <a href="sanpham.php?q=legging" class="badge bg-secondary text-decoration-none me-1">Legging</a>
                    <a href="sanpham.php?q=creatine" class="badge bg-secondary text-decoration-none me-1">Creatine</a>
                    <a href="sanpham.php?doituong=nam" class="badge bg-secondary text-decoration-none me-1">Đồ tập
                        nam</a>
                    <a href="sanpham.php?doituong=nu" class="badge bg-secondary text-decoration-none">Đồ tập nữ</a>
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