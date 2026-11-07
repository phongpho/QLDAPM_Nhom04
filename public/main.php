<?php
if (!isset($gsModule36)) {
    $_REQUEST['action'] = 'macdinh';
    require __DIR__ . '/index.php';
    return;
}
$gsTieude = 'Trang chủ - Đồ tập Gym & Dinh dưỡng thể thao';
include(__DIR__ . '/inc/head-client.php');
include(__DIR__ . '/inc/carousel.php');
?>

    <section class="gs-section">
        <div class="container">
            <div class="gs-section__head">
                <div>
                    <h2 class="gs-section__title">Mua sắm theo danh mục</h2>
                </div>
                <span class="gs-section__sub">Khám phá bộ sưu tập dành riêng cho mục tiêu tập luyện của bạn</span>
            </div>
            <div class="gs-cat-grid">

                <a class="gs-cat-card" href="index.php?action=sanpham&loai=quanao&doituong=nam">
                    <img class="gs-cat-card__img" src="https://images.unsplash.com/photo-1541534741688-6078c6bfb5c5?auto=format&fit=crop&w=800&q=80" alt="Đồ tập nam" loading="lazy" onerror="gsOnErrorImg(this)">
                    <div class="gs-cat-card__overlay">
                        <h3 class="gs-cat-card__title">Đồ tập nam</h3>
                        <span class="gs-cat-card__count"><?php echo (int) $dm36->laysoluongsanphamtheoloai('quanao', 'nam'); ?> sản phẩm</span>
                    </div>
                </a>

                <a class="gs-cat-card" href="index.php?action=sanpham&loai=quanao&doituong=nu">
                    <img class="gs-cat-card__img" src="https://images.unsplash.com/photo-1546483875-ad9014c88eba?auto=format&fit=crop&w=800&q=80" alt="Đồ tập nữ" loading="lazy" onerror="gsOnErrorImg(this)">
                    <div class="gs-cat-card__overlay">
                        <h3 class="gs-cat-card__title">Đồ tập nữ</h3>
                        <span class="gs-cat-card__count"><?php echo (int) $dm36->laysoluongsanphamtheoloai('quanao', 'nu'); ?> sản phẩm</span>
                    </div>
                </a>

                <a class="gs-cat-card" href="index.php?action=sanpham&loai=tpbs">
                    <img class="gs-cat-card__img" src="https://images.unsplash.com/photo-1594882645126-14020914d58d?auto=format&fit=crop&w=800&q=80" alt="Whey & TPBS" loading="lazy" onerror="gsOnErrorImg(this)">
                    <div class="gs-cat-card__overlay">
                        <h3 class="gs-cat-card__title">Whey &amp; TPBS</h3>
                        <span class="gs-cat-card__count"><?php echo (int) $dm36->laysoluongsanphamtheoloai('tpbs'); ?> sản phẩm</span>
                    </div>
                </a>

                <a class="gs-cat-card" href="index.php?action=sanpham&loai=phukien">
                    <img class="gs-cat-card__img" src="https://images.unsplash.com/photo-1526506118085-60ce8714f8c5?auto=format&fit=crop&w=800&q=80" alt="Phụ kiện" loading="lazy" onerror="gsOnErrorImg(this)">
                    <div class="gs-cat-card__overlay">
                        <h3 class="gs-cat-card__title">Phụ kiện</h3>
                        <span class="gs-cat-card__count"><?php echo (int) $dm36->laysoluongsanphamtheoloai('phukien'); ?> sản phẩm</span>
                    </div>
                </a>

            </div>
        </div>
    </section>

    <!-- ================================================================= -->
    <!-- CTA BANNER: GỢI Ý SIZE & COMBO ĐỒ TẬP THEO CHỈ SỐ BMI (CHUYỂN TRANG RIÊNG) -->
    <!-- ================================================================= -->
    <section class="gs-section py-4">
        <div class="container">
            <div class="p-4 p-lg-5 rounded-4 position-relative overflow-hidden" 
                 style="background: linear-gradient(135deg, rgba(20, 28, 43, 0.95) 0%, rgba(11, 15, 23, 0.98) 100%); border: 1px solid rgba(255, 255, 255, 0.08); box-shadow: 0 10px 30px rgba(0,0,0,0.45);">
                <div class="row align-items-center g-3">
                    <div class="col-12 col-lg-8">
                        <div class="d-flex align-items-center gap-2 mb-2 flex-wrap">
                            <span class="badge" style="background-color: rgba(249, 115, 22, 0.15); color: var(--gs-primary); border: 1px solid rgba(249, 115, 22, 0.35); font-size: 0.8rem; font-weight: 700; padding: 5px 12px; border-radius: var(--gs-radius-pill);">
                                <i class="fa-solid fa-wand-magic-sparkles me-1"></i> Tính năng cá nhân hóa
                            </span>
                            <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle small px-3 py-1 rounded-pill">
                                Tiêu chuẩn WHO &amp; GymBro
                            </span>
                        </div>
                        <h3 class="text-white fw-bold mb-2" style="font-family: var(--gs-font-display); letter-spacing: -0.01em; font-size: clamp(1.4rem, 2.5vw, 1.9rem);">
                            BẠN CHƯA BIẾT CHỌN SIZE ĐỒ TẬP HAY LOẠI DINH DƯỠNG NÀO?
                        </h3>
                        <p class="text-secondary small mb-0" style="max-width: 640px; line-height: 1.6;">
                            Chỉ cần 30 giây đo chỉ số thể trạng BMI để hệ thống tự động tính điểm số thể hình, phân loại vóc dáng, tra cứu size chuẩn form và tuyển chọn combo sản phẩm phù hợp nhất cho bạn.
                        </p>
                    </div>
                    <div class="col-12 col-lg-4 text-lg-end">
                        <a href="index.php?action=goy" class="btn-gs btn-gs--primary btn-gs--md px-4 py-3 d-inline-flex align-items-center gap-2" style="font-size: 0.95rem; border-radius: var(--gs-radius-pill);">
                            <i class="fa-solid fa-calculator"></i>
                            <span>ĐO BMI &amp; XEM GỢI Ý NGAY</span>
                            <i class="fa-solid fa-arrow-right-long ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <?php
    $gsSizeGoiy = ($gsGoiy && $gsGoiy['sizegoiy']) ? $gsGoiy['sizegoiy']['size'] : '';
    $gsKhoi = array(
        array('tieude' => 'Đồ tập Nam', 'mota' => 'Trang phục tập luyện nam co giãn, thoát mồ hôi nhanh', 'link' => 'index.php?action=sanpham&loai=quanao&doituong=nam', 'ds' => $sp->laysanphamtheonhom('quanao', 'nam', 4), 'nen' => ''),
        array('tieude' => 'Đồ tập Nữ', 'mota' => 'Bộ sưu tập nữ ôm form, nâng mông, thấm hút tốt', 'link' => 'index.php?action=sanpham&loai=quanao&doituong=nu', 'ds' => $sp->laysanphamtheonhom('quanao', 'nu', 4), 'nen' => 'grey'),
        array('tieude' => 'Phụ kiện tập luyện', 'mota' => 'Găng tay, đai lưng, bình lắc và phụ kiện hỗ trợ khác', 'link' => 'index.php?action=sanpham&loai=phukien', 'ds' => $sp->laysanphamtheonhom('phukien', '', 4), 'nen' => ''),
        array('tieude' => 'Thực phẩm bổ sung', 'mota' => 'Whey, Creatine, hỗ trợ đốt mỡ chính hãng', 'link' => 'index.php?action=sanpham&loai=tpbs', 'ds' => $sp->laysanphamtheonhom('tpbs', '', 4), 'nen' => 'grey'),
        array('tieude' => 'Sản phẩm mới', 'mota' => 'Những sản phẩm vừa lên kệ tại GYMBRO', 'link' => 'index.php?action=sanpham&sapxep=moi', 'ds' => $sp->laysanphammoi(4), 'nen' => ''),
        array('tieude' => 'Sản phẩm bán chạy', 'mota' => 'Được khách hàng GYMBRO chọn mua nhiều nhất', 'link' => 'index.php?action=sanpham&sapxep=banchay', 'ds' => $sp->laysanphambanchay(4), 'nen' => 'grey'),
        array('tieude' => 'Sản phẩm nổi bật', 'mota' => 'Danh mục sản phẩm do GYMBRO tuyển chọn', 'link' => 'index.php?action=sanpham', 'ds' => $sp->laysanphamnoibatmoi(4), 'nen' => ''),
        array('tieude' => 'Sản phẩm khuyến mãi', 'mota' => 'Ưu đãi giảm giá sâu, số lượng có hạn', 'link' => 'index.php?action=sanpham&sapxep=giamgia', 'ds' => $sp->laysanphamkhuyenmai(4), 'nen' => 'grey')
    );
    ?>

    <?php foreach ($gsKhoi as $khoi) : ?>
        <section class="gs-section<?php echo ($khoi['nen'] === 'grey') ? ' gs-section--grey' : ''; ?>">
            <div class="container">
                <div class="gs-section__head">
                    <div>
                        <h2 class="gs-section__title"><?php echo htmlspecialchars($khoi['tieude']); ?></h2>
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
                            <?php include('inc/product-card.php'); ?>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </section>
    <?php endforeach; ?>
    <?php include(__DIR__ . '/inc/footer.php'); ?>