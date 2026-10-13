<?php
/* ============================================================
   GYMSHOP - Trang chủ (Client Homepage) - DỮ LIỆU CỨNG
   Không kết nối CSDL: toàn bộ dữ liệu mẫu khai báo trong file này.
   ============================================================ */


if (!function_exists('gs_tien')) {
    function gs_tien($n)
    {
        return number_format((float) $n, 0, ',', '.');
    }
}


if (!function_exists('gs_phan_tram')) {
    function gs_phan_tram($giagoc, $giaban)
    {
        $giagoc = (float) $giagoc;
        $giaban = (float) $giaban;
        if ($giagoc > 0 && $giaban < $giagoc) {
            return (int) round(($giagoc - $giaban) / $giagoc * 100);
        }
        return 0;
    }
}


if (!function_exists('gs_sizes')) {
    function gs_sizes($sizes)
    {
        if (empty($sizes)) {
            return array();
        }
        $mang = array_map('trim', explode(',', $sizes));
        return array_values(array_filter($mang, 'strlen'));
    }
}


if (!function_exists('gs_la_moi')) {
    function gs_la_moi($ngaytao)
    {
        if (empty($ngaytao)) {
            return false;
        }
        return strtotime($ngaytao) >= strtotime('-30 days');
    }
}

/* ------------------------------------------------------------
   DỮ LIỆU CỨNG: 4 danh mục nổi bật (Shop by Category)
   ------------------------------------------------------------ */
$danhMucNoiBat = array(
    array(
        'tieude' => 'Đồ tập nam',
        'soluong' => 5,
        'link' => 'sanpham.php?loai=quanao&doituong=nam',
        'hinhanh' => 'https://images.unsplash.com/photo-1541534741688-6078c6bfb5c5?auto=format&fit=crop&w=800&q=80',
    ),
    array(
        'tieude' => 'Đồ tập nữ',
        'soluong' => 4,
        'link' => 'sanpham.php?loai=quanao&doituong=nu',
        'hinhanh' => 'https://images.unsplash.com/photo-1546483875-ad9014c88eba?auto=format&fit=crop&w=800&q=80',
    ),
    array(
        'tieude' => 'Whey & TPBS',
        'soluong' => 8,
        'link' => 'sanpham.php?loai=tpbs',
        'hinhanh' => 'https://images.unsplash.com/photo-1594882645126-14020914d58d?auto=format&fit=crop&w=800&q=80',
    ),
    array(
        'tieude' => 'Phụ kiện',
        'soluong' => 3,
        'link' => 'sanpham.php?loai=phukien',
        'hinhanh' => 'https://images.unsplash.com/photo-1526506118085-60ce8714f8c5?auto=format&fit=crop&w=800&q=80',
    ),
);

/* ------------------------------------------------------------
   DỮ LIỆU CỨNG: 8 sản phẩm bán chạy (sắp theo luotmua giảm dần)
   Cấu trúc khớp bảng sanpham: id, tensanpham, giaban, giagoc,
   hinhanh, sizes, doituong, noibat, luotmua, tendanhmuc, ngaytao
   ------------------------------------------------------------ */
$spBanChay = array(
    array(
        'id' => 10, 'tensanpham' => 'Bình lắc Shaker 700ml', 'giaban' => 99000, 'giagoc' => 129000,
        'hinhanh' => 'shaker-700.jpg', 'sizes' => null, 'doituong' => 'unisex',
        'noibat' => 1, 'luotmua' => 120, 'tendanhmuc' => 'Bình nước & túi', 'ngaytao' => '2026-10-10 08:00:00',
    ),
    array(
        'id' => 12, 'tensanpham' => 'Whey Gold Standard 5lb', 'giaban' => 1790000, 'giagoc' => 1990000,
        'hinhanh' => 'whey-gold.jpg', 'sizes' => null, 'doituong' => 'unisex',
        'noibat' => 1, 'luotmua' => 88, 'tendanhmuc' => 'Whey Protein', 'ngaytao' => '2026-10-10 08:00:00',
    ),
    array(
        'id' => 11, 'tensanpham' => 'Whey Isolate 2lb', 'giaban' => 1250000, 'giagoc' => 1450000,
        'hinhanh' => 'whey-isolate.jpg', 'sizes' => null, 'doituong' => 'unisex',
        'noibat' => 1, 'luotmua' => 66, 'tendanhmuc' => 'Whey Protein', 'ngaytao' => '2026-10-10 08:00:00',
    ),
    array(
        'id' => 14, 'tensanpham' => 'Creatine Monohydrate 300g', 'giaban' => 390000, 'giagoc' => 450000,
        'hinhanh' => 'creatine.jpg', 'sizes' => null, 'doituong' => 'unisex',
        'noibat' => 1, 'luotmua' => 61, 'tendanhmuc' => 'Creatine', 'ngaytao' => '2026-10-10 08:00:00',
    ),
    array(
        'id' => 16, 'tensanpham' => 'L-Carnitine 3000 dạng nước', 'giaban' => 420000, 'giagoc' => 490000,
        'hinhanh' => 'l-carnitine.jpg', 'sizes' => null, 'doituong' => 'unisex',
        'noibat' => 1, 'luotmua' => 57, 'tendanhmuc' => 'Hỗ trợ đốt mỡ', 'ngaytao' => '2026-10-10 08:00:00',
    ),
    array(
        'id' => 5, 'tensanpham' => 'Quần legging nữ cạp cao', 'giaban' => 399000, 'giagoc' => 479000,
        'hinhanh' => 'legging-caocap.jpg', 'sizes' => 'S,M,L', 'doituong' => 'nu',
        'noibat' => 1, 'luotmua' => 52, 'tendanhmuc' => 'Quần tập', 'ngaytao' => '2026-10-10 08:00:00',
    ),
    array(
        'id' => 18, 'tensanpham' => 'Vitamin tổng hợp Daily 90 viên', 'giaban' => 320000, 'giagoc' => 380000,
        'hinhanh' => 'vitamin.jpg', 'sizes' => null, 'doituong' => 'unisex',
        'noibat' => 0, 'luotmua' => 48, 'tendanhmuc' => 'Vitamin & Khoáng chất', 'ngaytao' => '2026-10-10 08:00:00',
    ),
    array(
        'id' => 4, 'tensanpham' => 'Áo bra thể thao nữ Support', 'giaban' => 259000, 'giagoc' => 319000,
        'hinhanh' => 'bra-support.jpg', 'sizes' => 'S,M,L', 'doituong' => 'nu',
        'noibat' => 1, 'luotmua' => 45, 'tendanhmuc' => 'Áo tập', 'ngaytao' => '2026-10-10 08:00:00',
    ),
);
?>
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
    

    <!-- HERO BANNER -->
    <section class="gs-hero">
        <div class="gs-hero__bg" style="background-image:url('https://images.unsplash.com/photo-1517836357463-d25dfeac3438?auto=format&fit=crop&w=1800&q=80');"></div>
        <div class="gs-hero__overlay"></div>
        <div class="container position-relative">
            <div class="gs-hero__content">
                <span class="gs-hero__kicker">Gymshop - Đồ tập &amp; Dinh dưỡng thể thao</span>
                <h1 class="gs-hero__title">Bứt phá giới hạn<br>chinh phục vóc dáng</h1>
                <p class="gs-hero__sub">
                    Trang phục tập luyện, phụ kiện và thực phẩm bổ sung được cá nhân hóa theo chỉ số cơ thể của riêng bạn.
                </p>
                <div class="gs-hero__cta">
                    <a href="sanpham.php?loai=quanao" class="btn-gs btn-gs--light">Đồ tập gym</a>
                    <a href="sanpham.php?loai=tpbs" class="btn-gs btn-gs--outline-light">Thực phẩm bổ trợ</a>
                </div>
            </div>
        </div>
    </section>

    <!-- USP CÁ NHÂN HÓA -->
    <section class="gs-usps">
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
    </section>

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
                <?php foreach ($danhMucNoiBat as $dm): ?>
                    <a class="gs-cat-card" href="<?php echo $dm['link']; ?>">
                        <img class="gs-cat-card__img" src="<?php echo $dm['hinhanh']; ?>" alt="<?php echo $dm['tieude']; ?>" loading="lazy" onerror="gsOnErrorImg(this)">
                        <div class="gs-cat-card__overlay">
                            <h3 class="gs-cat-card__title"><?php echo $dm['tieude']; ?></h3>
                            <span class="gs-cat-card__count"><?php echo $dm['soluong']; ?> sản phẩm</span>
                        </div>
                    </a>
                <?php endforeach; ?>
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
                <?php foreach ($spBanChay as $sp): ?>
                    <?php
                    $phanTram = gs_phan_tram($sp['giagoc'], $sp['giaban']);
                    $sizes = gs_sizes($sp['sizes']);
                    $laMoi = gs_la_moi($sp['ngaytao']);
                    $tenSP = htmlspecialchars($sp['tensanpham'], ENT_QUOTES, 'UTF-8');
                    ?>
                    <div class="col-6 col-md-4 col-lg-3">
                        <div class="gs-product-card" data-id="<?php echo (int) $sp['id']; ?>" data-name="<?php echo $tenSP; ?>">
                            <div class="gs-product-card__media">
                                <a class="gs-product-card__link" href="chitiet.php?id=<?php echo (int) $sp['id']; ?>">
                                    <img class="gs-product-card__img"
                                         src="uploads/<?php echo htmlspecialchars($sp['hinhanh'] ? $sp['hinhanh'] : 'no-image.jpg'); ?>"
                                         alt="<?php echo $tenSP; ?>"
                                         loading="lazy"
                                         onerror="gsOnErrorImg(this)">
                                    <span class="gs-product-card__badges">
                                        <?php if ($phanTram > 0): ?>
                                            <span class="badge-gs badge-gs--sale">-<?php echo $phanTram; ?>%</span>
                                        <?php endif; ?>
                                        <?php if ((int) $sp['noibat'] === 1): ?>
                                            <span class="badge-gs badge-gs--hot">Hot</span>
                                        <?php endif; ?>
                                        <?php if ($laMoi && (int) $sp['noibat'] !== 1): ?>
                                            <span class="badge-gs badge-gs--new">Mới</span>
                                        <?php endif; ?>
                                    </span>
                                </a>

                                <div class="gs-product-card__actions">
                                    <?php if (!empty($sizes)): ?>
                                        <div class="gs-size-pills">
                                            <?php foreach ($sizes as $sz): ?>
                                                <button type="button" class="gs-size-pill" data-size="<?php echo htmlspecialchars($sz); ?>"><?php echo htmlspecialchars($sz); ?></button>
                                            <?php endforeach; ?>
                                        </div>
                                    <?php else: ?>
                                        <button type="button" class="gs-quick-add"><i class="fa-solid fa-bag-shopping"></i> Thêm nhanh</button>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <div class="gs-product-card__body">
                                <div class="gs-product-card__cat"><?php echo htmlspecialchars($sp['tendanhmuc']); ?></div>
                                <a class="gs-product-card__name" href="chitiet.php?id=<?php echo (int) $sp['id']; ?>"><?php echo $tenSP; ?></a>
                                <div class="gs-product-card__price">
                                    <span class="gs-price"><?php echo gs_tien($sp['giaban']); ?>đ</span>
                                    <?php if ($phanTram > 0): ?>
                                        <span class="gs-price--old"><?php echo gs_tien($sp['giagoc']); ?>đ</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>


    <?php include('inc/footer.php'); ?>