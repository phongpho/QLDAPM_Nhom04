<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GymShop - Trang chủ</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome 6 -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">
    <!-- Design System -->
    <link href="../assets/css/main.css" rel="stylesheet">

    <script src="../assets/js/main.js"></script>
</head>

<body>
    <?php include('inc/navbar.php'); ?>
    <?php include('inc/carousel.php') ?>


    <!-- <section class="gs-usps">
        <div class="gs-usps__watermark" aria-hidden="true">BMI</div>
        <div class="container position-relative">
            <div class="row align-items-center g-5">
                <div class="col-lg-6">
                    <span class="gs-usps__kicker">Cá nhân hóa thông minh</span>
                    <h2 class="gs-usps__title">Tính BMI &amp; gợi ý combo chuẩn form</h2>
                    <p class="gs-usps__text">
                        Nhập chiều cao, cân nặng và mục tiêu để hệ thống tự động tính BMI và gợi ý combo tập luyện chuẩn form cho bạn.
                    </p>
                    <ul class="gs-usps__list">
                        <li><i class="fa-solid fa-check"></i> Tự động tính BMI, TDEE từ hồ sơ thể trạng</li>
                        <li><i class="fa-solid fa-check"></i> Gợi ý trang phục, phụ kiện &amp; thực phẩm bổ sung phù hợp</li>
                        <li><i class="fa-solid fa-check"></i> Loại trừ sản phẩm gây dị ứng, đúng mục tiêu tập luyện</li>
                    </ul>
                    <a href="goy.php" class="btn-gs btn-gs--light btn-gs--lg mt-3">Tính chỉ số &amp; gợi ý ngay</a>
                </div>
                <div class="col-lg-6">
                    <div class="gs-consult-card">
                        <div class="gs-consult-card__title"><i class="fa-solid fa-calculator"></i> Tư vấn nhanh</div>
                        <form action="goy.php" method="get">
                            <div class="row g-3">
                                <div class="col-6">
                                    <label for="gsChieuCao">Chiều cao (cm)</label>
                                    <input type="number" id="gsChieuCao" name="chieucao" min="100" max="250" step="0.1" placeholder="VD: 175" required>
                                </div>
                                <div class="col-6">
                                    <label for="gsCanNang">Cân nặng (kg)</label>
                                    <input type="number" id="gsCanNang" name="cannang" min="25" max="300" step="0.1" placeholder="VD: 70" required>
                                </div>
                                <div class="col-6">
                                    <label for="gsGioiTinh">Giới tính</label>
                                    <select id="gsGioiTinh" name="gioitinh">
                                        <option value="nam">Nam</option>
                                        <option value="nu">Nữ</option>
                                    </select>
                                </div>
                                <div class="col-6">
                                    <label for="gsMucTieu">Mục tiêu</label>
                                    <select id="gsMucTieu" name="muctieu">
                                        <option value="tangcan">Tăng cân / Tăng cơ</option>
                                        <option value="giamcan">Giảm cân / Giảm mỡ</option>
                                        <option value="duytri">Duy trì vóc dáng</option>
                                    </select>
                                </div>
                            </div>
                            <button type="submit" class="btn-gs btn-gs--dark w-100 mt-3">Tính chỉ số &amp; gợi ý ngay</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section> -->

    <!-- SHOP BY CATEGORY -->
    <section class="gs-section">
        <div class="container">
            <div class="gs-section__head">
                <div>
                    <h2 class="gs-section__title">Mua sắm theo danh mục</h2>
                </div>
                <span class="gs-section__sub">Khám phá bộ sưu tập dành riêng cho mục tiêu tập luyện của bạn</span>
            </div>
            <div class="gs-cat-grid">

                <!-- Danh mục 1 -->
                <a class="gs-cat-card" href="sanpham.php?loai=quanao&doituong=nam">
                    <img class="gs-cat-card__img" src="https://images.unsplash.com/photo-1541534741688-6078c6bfb5c5?auto=format&fit=crop&w=800&q=80" alt="Đồ tập nam" loading="lazy" onerror="gsOnErrorImg(this)">
                    <div class="gs-cat-card__overlay">
                        <h3 class="gs-cat-card__title">Đồ tập nam</h3>
                        <span class="gs-cat-card__count">5 sản phẩm</span>
                    </div>
                </a>

                <!-- Danh mục 2 -->
                <a class="gs-cat-card" href="sanpham.php?loai=quanao&doituong=nu">
                    <img class="gs-cat-card__img" src="https://images.unsplash.com/photo-1546483875-ad9014c88eba?auto=format&fit=crop&w=800&q=80" alt="Đồ tập nữ" loading="lazy" onerror="gsOnErrorImg(this)">
                    <div class="gs-cat-card__overlay">
                        <h3 class="gs-cat-card__title">Đồ tập nữ</h3>
                        <span class="gs-cat-card__count">4 sản phẩm</span>
                    </div>
                </a>

                <!-- Danh mục 3 -->
                <a class="gs-cat-card" href="sanpham.php?loai=tpbs">
                    <img class="gs-cat-card__img" src="https://images.unsplash.com/photo-1594882645126-14020914d58d?auto=format&fit=crop&w=800&q=80" alt="Whey & TPBS" loading="lazy" onerror="gsOnErrorImg(this)">
                    <div class="gs-cat-card__overlay">
                        <h3 class="gs-cat-card__title">Whey & TPBS</h3>
                        <span class="gs-cat-card__count">8 sản phẩm</span>
                    </div>
                </a>

                <!-- Danh mục 4 -->
                <a class="gs-cat-card" href="sanpham.php?loai=phukien">
                    <img class="gs-cat-card__img" src="https://images.unsplash.com/photo-1526506118085-60ce8714f8c5?auto=format&fit=crop&w=800&q=80" alt="Phụ kiện" loading="lazy" onerror="gsOnErrorImg(this)">
                    <div class="gs-cat-card__overlay">
                        <h3 class="gs-cat-card__title">Phụ kiện</h3>
                        <span class="gs-cat-card__count">3 sản phẩm</span>
                    </div>
                </a>

            </div>
        </div>
    </section>

    <!-- TRENDING PRODUCTS -->
    <section class="gs-section gs-section--grey">
        <div class="container">
            <div class="gs-section__head">
                <div>
                    <h2 class="gs-section__title">Sản phẩm bán chạy</h2>
                </div>
                <a class="gs-section__link" href="sanpham.php">Xem tất cả <i class="fa-solid fa-arrow-right-long"></i></a>
            </div>
            <div class="row g-4">

                <!-- Sản phẩm 1 -->
                <div class="col-6 col-md-4 col-lg-3">
                    <div class="gs-product-card" data-id="10" data-name="Bình lắc Shaker 700ml">
                        <div class="gs-product-card__media">
                            <a class="gs-product-card__link" href="chitiet.php?id=10">
                                <img class="gs-product-card__img" src="uploads/shaker-700.jpg" alt="Bình lắc Shaker 700ml" loading="lazy" onerror="gsOnErrorImg(this)">
                                <span class="gs-product-card__badges">
                                    <span class="badge-gs badge-gs--sale">-23%</span>
                                    <span class="badge-gs badge-gs--hot">Hot</span>
                                </span>
                            </a>
                            <div class="gs-product-card__actions">
                                <button type="button" class="gs-quick-add"><i class="fa-solid fa-bag-shopping"></i> Thêm nhanh</button>
                            </div>
                        </div>
                        <div class="gs-product-card__body">
                            <div class="gs-product-card__cat">Bình nước & túi</div>
                            <a class="gs-product-card__name" href="chitiet.php?id=10">Bình lắc Shaker 700ml</a>
                            <div class="gs-product-card__price">
                                <span class="gs-price">99.000đ</span>
                                <span class="gs-price--old">129.000đ</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sản phẩm 2 -->
                <div class="col-6 col-md-4 col-lg-3">
                    <div class="gs-product-card" data-id="12" data-name="Whey Gold Standard 5lb">
                        <div class="gs-product-card__media">
                            <a class="gs-product-card__link" href="chitiet.php?id=12">
                                <img class="gs-product-card__img" src="uploads/whey-gold.jpg" alt="Whey Gold Standard 5lb" loading="lazy" onerror="gsOnErrorImg(this)">
                                <span class="gs-product-card__badges">
                                    <span class="badge-gs badge-gs--sale">-10%</span>
                                    <span class="badge-gs badge-gs--hot">Hot</span>
                                </span>
                            </a>
                            <div class="gs-product-card__actions">
                                <button type="button" class="gs-quick-add"><i class="fa-solid fa-bag-shopping"></i> Thêm nhanh</button>
                            </div>
                        </div>
                        <div class="gs-product-card__body">
                            <div class="gs-product-card__cat">Whey Protein</div>
                            <a class="gs-product-card__name" href="chitiet.php?id=12">Whey Gold Standard 5lb</a>
                            <div class="gs-product-card__price">
                                <span class="gs-price">1.790.000đ</span>
                                <span class="gs-price--old">1.990.000đ</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sản phẩm 3 -->
                <div class="col-6 col-md-4 col-lg-3">
                    <div class="gs-product-card" data-id="11" data-name="Whey Isolate 2lb">
                        <div class="gs-product-card__media">
                            <a class="gs-product-card__link" href="chitiet.php?id=11">
                                <img class="gs-product-card__img" src="uploads/whey-isolate.jpg" alt="Whey Isolate 2lb" loading="lazy" onerror="gsOnErrorImg(this)">
                                <span class="gs-product-card__badges">
                                    <span class="badge-gs badge-gs--sale">-14%</span>
                                    <span class="badge-gs badge-gs--hot">Hot</span>
                                </span>
                            </a>
                            <div class="gs-product-card__actions">
                                <button type="button" class="gs-quick-add"><i class="fa-solid fa-bag-shopping"></i> Thêm nhanh</button>
                            </div>
                        </div>
                        <div class="gs-product-card__body">
                            <div class="gs-product-card__cat">Whey Protein</div>
                            <a class="gs-product-card__name" href="chitiet.php?id=11">Whey Isolate 2lb</a>
                            <div class="gs-product-card__price">
                                <span class="gs-price">1.250.000đ</span>
                                <span class="gs-price--old">1.450.000đ</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sản phẩm 4 -->
                <div class="col-6 col-md-4 col-lg-3">
                    <div class="gs-product-card" data-id="14" data-name="Creatine Monohydrate 300g">
                        <div class="gs-product-card__media">
                            <a class="gs-product-card__link" href="chitiet.php?id=14">
                                <img class="gs-product-card__img" src="uploads/creatine.jpg" alt="Creatine Monohydrate 300g" loading="lazy" onerror="gsOnErrorImg(this)">
                                <span class="gs-product-card__badges">
                                    <span class="badge-gs badge-gs--sale">-13%</span>
                                    <span class="badge-gs badge-gs--hot">Hot</span>
                                </span>
                            </a>
                            <div class="gs-product-card__actions">
                                <button type="button" class="gs-quick-add"><i class="fa-solid fa-bag-shopping"></i> Thêm nhanh</button>
                            </div>
                        </div>
                        <div class="gs-product-card__body">
                            <div class="gs-product-card__cat">Creatine</div>
                            <a class="gs-product-card__name" href="chitiet.php?id=14">Creatine Monohydrate 300g</a>
                            <div class="gs-product-card__price">
                                <span class="gs-price">390.000đ</span>
                                <span class="gs-price--old">450.000đ</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sản phẩm 5 -->
                <div class="col-6 col-md-4 col-lg-3">
                    <div class="gs-product-card" data-id="16" data-name="L-Carnitine 3000 dạng nước">
                        <div class="gs-product-card__media">
                            <a class="gs-product-card__link" href="chitiet.php?id=16">
                                <img class="gs-product-card__img" src="uploads/l-carnitine.jpg" alt="L-Carnitine 3000 dạng nước" loading="lazy" onerror="gsOnErrorImg(this)">
                                <span class="gs-product-card__badges">
                                    <span class="badge-gs badge-gs--sale">-14%</span>
                                    <span class="badge-gs badge-gs--hot">Hot</span>
                                </span>
                            </a>
                            <div class="gs-product-card__actions">
                                <button type="button" class="gs-quick-add"><i class="fa-solid fa-bag-shopping"></i> Thêm nhanh</button>
                            </div>
                        </div>
                        <div class="gs-product-card__body">
                            <div class="gs-product-card__cat">Hỗ trợ đốt mỡ</div>
                            <a class="gs-product-card__name" href="chitiet.php?id=16">L-Carnitine 3000 dạng nước</a>
                            <div class="gs-product-card__price">
                                <span class="gs-price">420.000đ</span>
                                <span class="gs-price--old">490.000đ</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sản phẩm 6 -->
                <div class="col-6 col-md-4 col-lg-3">
                    <div class="gs-product-card" data-id="5" data-name="Quần legging nữ cạp cao">
                        <div class="gs-product-card__media">
                            <a class="gs-product-card__link" href="chitiet.php?id=5">
                                <img class="gs-product-card__img" src="uploads/legging-caocap.jpg" alt="Quần legging nữ cạp cao" loading="lazy" onerror="gsOnErrorImg(this)">
                                <span class="gs-product-card__badges">
                                    <span class="badge-gs badge-gs--sale">-17%</span>
                                    <span class="badge-gs badge-gs--hot">Hot</span>
                                </span>
                            </a>
                            <div class="gs-product-card__actions">
                                <div class="gs-size-pills">
                                    <button type="button" class="gs-size-pill" data-size="S">S</button>
                                    <button type="button" class="gs-size-pill" data-size="M">M</button>
                                    <button type="button" class="gs-size-pill" data-size="L">L</button>
                                </div>
                            </div>
                        </div>
                        <div class="gs-product-card__body">
                            <div class="gs-product-card__cat">Quần tập</div>
                            <a class="gs-product-card__name" href="chitiet.php?id=5">Quần legging nữ cạp cao</a>
                            <div class="gs-product-card__price">
                                <span class="gs-price">399.000đ</span>
                                <span class="gs-price--old">479.000đ</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sản phẩm 7 -->
                <div class="col-6 col-md-4 col-lg-3">
                    <div class="gs-product-card" data-id="18" data-name="Vitamin tổng hợp Daily 90 viên">
                        <div class="gs-product-card__media">
                            <a class="gs-product-card__link" href="chitiet.php?id=18">
                                <img class="gs-product-card__img" src="uploads/vitamin.jpg" alt="Vitamin tổng hợp Daily 90 viên" loading="lazy" onerror="gsOnErrorImg(this)">
                                <span class="gs-product-card__badges">
                                    <span class="badge-gs badge-gs--sale">-16%</span>
                                    <span class="badge-gs badge-gs--new">Mới</span>
                                </span>
                            </a>
                            <div class="gs-product-card__actions">
                                <button type="button" class="gs-quick-add"><i class="fa-solid fa-bag-shopping"></i> Thêm nhanh</button>
                            </div>
                        </div>
                        <div class="gs-product-card__body">
                            <div class="gs-product-card__cat">Vitamin & Khoáng chất</div>
                            <a class="gs-product-card__name" href="chitiet.php?id=18">Vitamin tổng hợp Daily 90 viên</a>
                            <div class="gs-product-card__price">
                                <span class="gs-price">320.000đ</span>
                                <span class="gs-price--old">380.000đ</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sản phẩm 8 -->
                <div class="col-6 col-md-4 col-lg-3">
                    <div class="gs-product-card" data-id="4" data-name="Áo bra thể thao nữ Support">
                        <div class="gs-product-card__media">
                            <a class="gs-product-card__link" href="chitiet.php?id=4">
                                <img class="gs-product-card__img" src="uploads/bra-support.jpg" alt="Áo bra thể thao nữ Support" loading="lazy" onerror="gsOnErrorImg(this)">
                                <span class="gs-product-card__badges">
                                    <span class="badge-gs badge-gs--sale">-19%</span>
                                    <span class="badge-gs badge-gs--hot">Hot</span>
                                </span>
                            </a>
                            <div class="gs-product-card__actions">
                                <div class="gs-size-pills">
                                    <button type="button" class="gs-size-pill" data-size="S">S</button>
                                    <button type="button" class="gs-size-pill" data-size="M">M</button>
                                    <button type="button" class="gs-size-pill" data-size="L">L</button>
                                </div>
                            </div>
                        </div>
                        <div class="gs-product-card__body">
                            <div class="gs-product-card__cat">Áo tập</div>
                            <a class="gs-product-card__name" href="chitiet.php?id=4">Áo bra thể thao nữ Support</a>
                            <div class="gs-product-card__price">
                                <span class="gs-price">259.000đ</span>
                                <span class="gs-price--old">319.000đ</span>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>


    <?php include('inc/footer.php'); ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>

</html>