<!-- TOP BAR: Thông báo & Khuyến mãi (Bootstrap 5) -->
<div class="bg-dark text-white py-2 small border-bottom border-secondary">
    <div class="container d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div class="d-flex align-items-center gap-3">
            <span><i class="fa-solid fa-truck-fast text-warning me-1"></i> Freeship đơn từ 500.000đ</span>
            <span class="d-none d-md-inline text-secondary">|</span>
            <span class="d-none d-md-inline"><i class="fa-solid fa-ticket text-info me-1"></i> Mã <strong>WELCOME10</strong> giảm 10% cho khách mới</span>
            <span class="d-none d-lg-inline text-secondary">|</span>
            <span class="d-none d-lg-inline"><i class="fa-solid fa-bolt text-danger me-1"></i> <strong>SALE15</strong> - giảm 15% đơn từ 800.000đ</span>
        </div>
        <div>
            <span><i class="fa-solid fa-rotate-left text-success me-1"></i> Miễn phí đổi size trong 7 ngày</span>
        </div>
    </div>
</div>

<!-- MAIN NAVBAR (Bootstrap 5) -->
<nav class="navbar navbar-expand-lg navbar-dark bg-black sticky-top shadow-sm py-3">
    <div class="container">
        <!-- Brand Logo -->
        <a class="navbar-brand fw-bold fs-3 text-uppercase text-white text-decoration-none" href="index.php">
            GYM<span class="text-danger">SHOP</span>
        </a>

        <!-- Mobile Toggler -->
        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#gsNavbarNav" aria-controls="gsNavbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Navbar Menu & Actions -->
        <div class="collapse navbar-collapse" id="gsNavbarNav">
            <ul class="navbar-nav mx-auto mb-2 mb-lg-0 fw-semibold text-uppercase gap-lg-2">
                <li class="nav-item">
                    <a class="nav-link text-white-50 px-3 hover-white" href="sanpham.php?doituong=nam">Nam</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white-50 px-3 hover-white" href="sanpham.php?doituong=nu">Nữ</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white-50 px-3 hover-white" href="sanpham.php?loai=phukien">Phụ kiện</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white-50 px-3 hover-white" href="sanpham.php?loai=tpbs">Thực phẩm bổ sung</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white px-3 d-flex align-items-center gap-1" href="goy.php">
                        Gợi ý theo chỉ số
                        <span class="badge bg-danger text-white rounded-pill ms-1" style="font-size: 0.65rem;">Cá nhân hóa</span>
                    </a>
                </li>
            </ul>

            <!-- Action Buttons -->
            <div class="d-flex align-items-center gap-3 pt-3 pt-lg-0">
                <!-- Search Button -->
                <button type="button" class="btn btn-outline-secondary border-0 text-white p-2" data-bs-toggle="modal" data-bs-target="#gsSearchModal" aria-label="Tìm kiếm">
                    <i class="fa-solid fa-magnifying-glass fs-5"></i>
                </button>

                <!-- Wishlist -->
                <a href="yeuthich.php" class="btn btn-outline-secondary border-0 text-white position-relative p-2" aria-label="Yêu thích">
                    <i class="fa-regular fa-heart fs-5"></i>
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 0.65rem;">
                        2
                    </span>
                </a>

                <!-- Account -->
                <a href="taikhoan.php" class="btn btn-outline-secondary border-0 text-white p-2" aria-label="Tài khoản">
                    <i class="fa-regular fa-user fs-5"></i>
                </a>

                <!-- Cart -->
                <a href="giohang.php" class="btn btn-outline-secondary border-0 text-white position-relative p-2" aria-label="Giỏ hàng">
                    <i class="fa-solid fa-bag-shopping fs-5"></i>
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-warning text-dark fw-bold" style="font-size: 0.65rem;">
                        3
                    </span>
                </a>
            </div>
        </div>
    </div>
</nav>

<!-- SEARCH MODAL (Bootstrap 5) -->
<div class="modal fade" id="gsSearchModal" tabindex="-1" aria-labelledby="gsSearchModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content bg-dark text-white border-secondary">
            <div class="modal-header border-secondary">
                <h5 class="modal-title fw-bold" id="gsSearchModalLabel">
                    <i class="fa-solid fa-magnifying-glass me-2 text-danger"></i>Tìm kiếm sản phẩm
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Đóng"></button>
            </div>
            <div class="modal-body p-4">
                <form action="sanpham.php" method="get">
                    <div class="input-group input-group-lg mb-3">
                        <input type="text" name="q" class="form-control bg-black text-white border-secondary" placeholder="Tìm kiếm sản phẩm, thương hiệu, mục tiêu tập luyện..." autocomplete="off">
                        <button class="btn btn-danger px-4" type="submit">Tìm kiếm</button>
                    </div>
                </form>
                <div>
                    <span class="text-secondary small me-2">Gợi ý tìm kiếm:</span>
                    <a href="sanpham.php?q=whey" class="badge bg-secondary text-decoration-none me-1">Whey Protein</a>
                    <a href="sanpham.php?q=legging" class="badge bg-secondary text-decoration-none me-1">Legging</a>
                    <a href="sanpham.php?q=creatine" class="badge bg-secondary text-decoration-none me-1">Creatine</a>
                    <a href="sanpham.php?doituong=nam" class="badge bg-secondary text-decoration-none me-1">Đồ tập nam</a>
                    <a href="sanpham.php?doituong=nu" class="badge bg-secondary text-decoration-none">Đồ tập nữ</a>
                </div>
            </div>
        </div>
    </div>
</div>
