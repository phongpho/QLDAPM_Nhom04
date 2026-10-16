<?php
/* Truy cập trực tiếp public/main.php (không qua router): nạp router Module 3 & 6
   để có model, session và dữ liệu sản phẩm; router sẽ nạp lại chính file này. */
if (!isset($gsModule36)) {
    $_REQUEST['action'] = 'macdinh';
    require __DIR__ . '/index.php';
    return;
}
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GymBro - Trang chủ</title>

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

                <!-- Danh mục 1: Đồ tập nam -->
                <a class="gs-cat-card" href="sanpham.php?loai=quanao&doituong=nam">
                    <img class="gs-cat-card__img" src="https://images.unsplash.com/photo-1541534741688-6078c6bfb5c5?auto=format&fit=crop&w=800&q=80" alt="Đồ tập nam" loading="lazy" onerror="gsOnErrorImg(this)">
                    <div class="gs-cat-card__overlay">
                        <h3 class="gs-cat-card__title">Đồ tập nam</h3>
                        <span class="gs-cat-card__count"><?php echo (int) $dm36->laysoluongsanphamtheoloai('quanao', 'nam'); ?> sản phẩm</span>
                    </div>
                </a>

                <!-- Danh mục 2: Đồ tập nữ -->
                <a class="gs-cat-card" href="sanpham.php?loai=quanao&doituong=nu">
                    <img class="gs-cat-card__img" src="https://images.unsplash.com/photo-1546483875-ad9014c88eba?auto=format&fit=crop&w=800&q=80" alt="Đồ tập nữ" loading="lazy" onerror="gsOnErrorImg(this)">
                    <div class="gs-cat-card__overlay">
                        <h3 class="gs-cat-card__title">Đồ tập nữ</h3>
                        <span class="gs-cat-card__count"><?php echo (int) $dm36->laysoluongsanphamtheoloai('quanao', 'nu'); ?> sản phẩm</span>
                    </div>
                </a>

                <!-- Danh mục 3: Thực phẩm bổ sung -->
                <a class="gs-cat-card" href="sanpham.php?loai=tpbs">
                    <img class="gs-cat-card__img" src="https://images.unsplash.com/photo-1594882645126-14020914d58d?auto=format&fit=crop&w=800&q=80" alt="Whey & TPBS" loading="lazy" onerror="gsOnErrorImg(this)">
                    <div class="gs-cat-card__overlay">
                        <h3 class="gs-cat-card__title">Whey &amp; TPBS</h3>
                        <span class="gs-cat-card__count"><?php echo (int) $dm36->laysoluongsanphamtheoloai('tpbs'); ?> sản phẩm</span>
                    </div>
                </a>

                <!-- Danh mục 4: Phụ kiện -->
                <a class="gs-cat-card" href="sanpham.php?loai=phukien">
                    <img class="gs-cat-card__img" src="https://images.unsplash.com/photo-1526506118085-60ce8714f8c5?auto=format&fit=crop&w=800&q=80" alt="Phụ kiện" loading="lazy" onerror="gsOnErrorImg(this)">
                    <div class="gs-cat-card__overlay">
                        <h3 class="gs-cat-card__title">Phụ kiện</h3>
                        <span class="gs-cat-card__count"><?php echo (int) $dm36->laysoluongsanphamtheoloai('phukien'); ?> sản phẩm</span>
                    </div>
                </a>

            </div>
        </div>
    </section>

    <?php
    /* Gợi ý cá nhân hóa theo chỉ số cơ thể (khối này tự ẩn với khách chưa đăng nhập) */
    include('inc/khoi-goiy.php');
    ?>

    <?php
    /* Các section sản phẩm bên dưới lấy dữ liệu thật từ CSDL (Module 3)
       Gồm: Đồ tập Nam, Đồ tập Nữ, Phụ kiện, Thực phẩm bổ sung,
             Sản phẩm mới, Bán chạy, Nổi bật, Khuyến mãi */
    $gsSizeGoiy = ($gsGoiy && $gsGoiy['sizegoiy']) ? $gsGoiy['sizegoiy']['size'] : '';
    $gsKhoi = array(
        array('tieude' => 'Đồ tập Nam', 'mota' => 'Trang phục tập luyện nam co giãn, thoát mồ hôi nhanh', 'link' => 'sanpham.php?loai=quanao&doituong=nam', 'ds' => $sp->laysanphamtheonhom('quanao', 'nam', 4), 'nen' => ''),
        array('tieude' => 'Đồ tập Nữ', 'mota' => 'Bộ sưu tập nữ ôm form, nâng mông, thấm hút tốt', 'link' => 'sanpham.php?loai=quanao&doituong=nu', 'ds' => $sp->laysanphamtheonhom('quanao', 'nu', 4), 'nen' => 'grey'),
        array('tieude' => 'Phụ kiện tập luyện', 'mota' => 'Găng tay, đai lưng, bình lắc và phụ kiện hỗ trợ khác', 'link' => 'sanpham.php?loai=phukien', 'ds' => $sp->laysanphamtheonhom('phukien', '', 4), 'nen' => ''),
        array('tieude' => 'Thực phẩm bổ sung', 'mota' => 'Whey, Creatine, hỗ trợ đốt mỡ chính hãng', 'link' => 'sanpham.php?loai=tpbs', 'ds' => $sp->laysanphamtheonhom('tpbs', '', 4), 'nen' => 'grey'),
        array('tieude' => 'Sản phẩm mới', 'mota' => 'Những sản phẩm vừa lên kệ tại GYMBRO', 'link' => 'sanpham.php?sapxep=moi', 'ds' => $sp->laysanphammoi(4), 'nen' => ''),
        array('tieude' => 'Sản phẩm bán chạy', 'mota' => 'Được khách hàng GYMBRO chọn mua nhiều nhất', 'link' => 'sanpham.php?sapxep=banchay', 'ds' => $sp->laysanphambanchay(4), 'nen' => 'grey'),
        array('tieude' => 'Sản phẩm nổi bật', 'mota' => 'Danh mục sản phẩm do GYMBRO tuyển chọn', 'link' => 'sanpham.php', 'ds' => $sp->laysanphamnoibatmoi(4), 'nen' => ''),
        array('tieude' => 'Sản phẩm khuyến mãi', 'mota' => 'Ưu đãi giảm giá sâu, số lượng có hạn', 'link' => 'sanpham.php?sapxep=giamgia', 'ds' => $sp->laysanphamkhuyenmai(4), 'nen' => 'grey')
    );
    ?>

    <?php foreach ($gsKhoi as $khoi) : ?>
        <section class="gs-section<?php echo ($khoi['nen'] === 'grey') ? ' gs-section--grey' : ''; ?>">
            <div class="container">
                <div class="gs-section__head">
                    <div>
                        <h2 class="gs-section__title"><?php echo htmlspecialchars($khoi['tieude']); ?></h2>
                        <span class="gs-section__sub"><?php echo htmlspecialchars($khoi['mota']); ?></span>
                    </div>
                    <a class="gs-section__link" href="<?php echo htmlspecialchars($khoi['link']); ?>">
                        Xem tất cả <i class="fa-solid fa-arrow-right-long"></i>
                    </a>
                </div>
                <?php if (count($khoi['ds']) === 0) : ?>
                    <p class="text-secondary">Danh mục này đang được cập nhật sản phẩm.</p>
                <?php else : ?>
                    <div class="row g-4">
                        <?php foreach ($khoi['ds'] as $card) : ?>
                            <?php include('inc/card-sanpham.php'); ?>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </section>
    <?php endforeach; ?>
    <?php include('inc/footer.php'); ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>

</html>