<?php
/* ============================================================
   GYMSHOP - Trang chủ (Client Homepage)
   ============================================================ */
require_once dirname(__DIR__) . '/model/database.php';

if (!function_exists('gs_format_tien')) {
    function gs_format_tien($n)
    {
        return number_format((float) $n, 0, ',', '.');
    }
}

/* Sản phẩm bán chạy (Trending) */
$spBanChay = array();
try {
    $st = $conn->prepare("SELECT sp.id, sp.tensanpham, sp.giagoc, sp.giaban, sp.hinhanh,
                                 sp.sizes, sp.doituong, sp.noibat, sp.ngaytao,
                                 (SELECT d.tendanhmuc FROM danhmuc d WHERE d.id = sp.danhmuc_id) AS tendanhmuc
                          FROM sanpham sp
                          WHERE sp.trangthai = 1
                          ORDER BY sp.luotmua DESC, sp.id ASC
                          LIMIT 8");
    $st->execute();
    $spBanChay = $st->fetchAll();
} catch (Exception $e) {
    $spBanChay = array();
}

/* Số lượng sản phẩm theo từng danh mục */
$catCount = array('dongtap_nam' => 0, 'dongtap_nu' => 0, 'tpbs' => 0, 'phukien' => 0);
try {
    $q = $conn->query("SELECT d.loai, sp.doituong, COUNT(*) AS c
                       FROM sanpham sp
                       JOIN danhmuc d ON d.id = sp.danhmuc_id
                       WHERE sp.trangthai = 1
                       GROUP BY d.loai, sp.doituong");
    foreach ($q as $r) {
        if ($r['loai'] === 'quanao') {
            if ($r['doituong'] === 'nam') {
                $catCount['dongtap_nam'] += (int) $r['c'];
            } elseif ($r['doituong'] === 'nu') {
                $catCount['dongtap_nu'] += (int) $r['c'];
            } else {
                $catCount['dongtap_nam'] += (int) $r['c'];
                $catCount['dongtap_nu'] += (int) $r['c'];
            }
        } elseif ($r['loai'] === 'phukien') {
            $catCount['phukien'] += (int) $r['c'];
        } elseif ($r['loai'] === 'tpbs') {
            $catCount['tpbs'] += (int) $r['c'];
        }
    }
} catch (Exception $e) {
    /* giữ nguyên giá trị 0 */
}

require_once __DIR__ . '/inc/navbar.php';
?>

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
            <a class="gs-cat-card" href="sanpham.php?loai=quanao&doituong=nam">
                <img class="gs-cat-card__img" src="https://images.unsplash.com/photo-1541534741688-6078c6bfb5c5?auto=format&fit=crop&w=800&q=80" alt="Đồ tập nam" loading="lazy" onerror="onErrorImg(this)">
                <div class="gs-cat-card__overlay">
                    <h3 class="gs-cat-card__title">Đồ tập nam</h3>
                    <span class="gs-cat-card__count"><?php echo $catCount['dongtap_nam']; ?> sản phẩm</span>
                </div>
            </a>
            <a class="gs-cat-card" href="sanpham.php?loai=quanao&doituong=nu">
                <img class="gs-cat-card__img" src="https://images.unsplash.com/photo-1546483875-ad9014c88eba?auto=format&fit=crop&w=800&q=80" alt="Đồ tập nữ" loading="lazy" onerror="onErrorImg(this)">
                <div class="gs-cat-card__overlay">
                    <h3 class="gs-cat-card__title">Đồ tập nữ</h3>
                    <span class="gs-cat-card__count"><?php echo $catCount['dongtap_nu']; ?> sản phẩm</span>
                </div>
            </a>
            <a class="gs-cat-card" href="sanpham.php?loai=tpbs">
                <img class="gs-cat-card__img" src="https://images.unsplash.com/photo-1594882645126-14020914d58d?auto=format&fit=crop&w=800&q=80" alt="Thực phẩm bổ sung" loading="lazy" onerror="onErrorImg(this)">
                <div class="gs-cat-card__overlay">
                    <h3 class="gs-cat-card__title">Whey &amp; TPBS</h3>
                    <span class="gs-cat-card__count"><?php echo $catCount['tpbs']; ?> sản phẩm</span>
                </div>
            </a>
            <a class="gs-cat-card" href="sanpham.php?loai=phukien">
                <img class="gs-cat-card__img" src="https://images.unsplash.com/photo-1526506118085-60ce8714f8c5?auto=format&fit=crop&w=800&q=80" alt="Phụ kiện" loading="lazy" onerror="onErrorImg(this)">
                <div class="gs-cat-card__overlay">
                    <h3 class="gs-cat-card__title">Phụ kiện</h3>
                    <span class="gs-cat-card__count"><?php echo $catCount['phukien']; ?> sản phẩm</span>
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
            <?php if (empty($spBanChay)): ?>
                <div class="col-12 text-center py-5">
                    <p class="text-muted mb-0">Chưa có sản phẩm nào. Vui lòng import gymshop.sql.</p>
                </div>
            <?php endif; ?>

            <?php foreach ($spBanChay as $sp): ?>
                <?php
                $phanTram = 0;
                if ((float) $sp['giagoc'] > 0 && (float) $sp['giaban'] < (float) $sp['giagoc']) {
                    $phanTram = (int) round(((float) $sp['giagoc'] - (float) $sp['giaban']) / (float) $sp['giagoc'] * 100);
                }
                $sizes = array();
                if (!empty($sp['sizes'])) {
                    $sizes = array_map('trim', explode(',', $sp['sizes']));
                }
                $isNew = !empty($sp['ngaytao']) && (strtotime($sp['ngaytao']) >= strtotime('-30 days'));
                $tenSP = htmlspecialchars($sp['tensanpham']);
                ?>
                <div class="col-6 col-md-4 col-lg-3">
                    <div class="gs-product-card" data-name="<?php echo $tenSP; ?>">
                        <div class="gs-product-card__media">
                            <a class="gs-product-card__link" href="sanpham.php?id=<?php echo (int) $sp['id']; ?>">
                                <img class="gs-product-card__img"
                                     src="uploads/<?php echo htmlspecialchars($sp['hinhanh'] ? $sp['hinhanh'] : 'no-image.jpg'); ?>"
                                     alt="<?php echo $tenSP; ?>"
                                     loading="lazy"
                                     onerror="onErrorImg(this)">
                                <span class="gs-product-card__badges">
                                    <?php if ($phanTram > 0): ?>
                                        <span class="badge-gs badge-gs--sale">-<?php echo $phanTram; ?>%</span>
                                    <?php endif; ?>
                                    <?php if ((int) $sp['noibat'] === 1): ?>
                                        <span class="badge-gs badge-gs--hot">Hot</span>
                                    <?php endif; ?>
                                    <?php if ($isNew && (int) $sp['noibat'] !== 1): ?>
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
                            <div class="gs-product-card__cat"><?php echo htmlspecialchars(isset($sp['tendanhmuc']) ? $sp['tendanhmuc'] : ''); ?></div>
                            <a class="gs-product-card__name" href="sanpham.php?id=<?php echo (int) $sp['id']; ?>"><?php echo $tenSP; ?></a>
                            <div class="gs-product-card__price">
                                <span class="gs-price"><?php echo gs_format_tien($sp['giaban']); ?>đ</span>
                                <?php if ($phanTram > 0): ?>
                                    <span class="gs-price--old"><?php echo gs_format_tien($sp['giagoc']); ?>đ</span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/inc/bottom.php'; ?>
