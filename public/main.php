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
                    <span class="gs-section__sub">Khám phá bộ sưu tập dành riêng cho mục tiêu tập luyện của bạn</span>
                </div>
            </div>
            <div class="gs-cat-grid">

                <a class="gs-cat-card" href="index.php?action=sanpham&loai=quanao&doituong=nam">
                    <img class="gs-cat-card__img" src="../assets/images/banners/cat_nam.jpg?v=<?php echo filemtime(__DIR__ . '/../assets/images/banners/cat_nam.jpg'); ?>" alt="Đồ tập nam" loading="lazy" onerror="gsOnErrorImg(this)">
                    <div class="gs-cat-card__overlay">
                        <h3 class="gs-cat-card__title">Đồ tập nam</h3>
                        <span class="gs-cat-card__count"><?php echo (int) $dm36->laysoluongsanphamtheoloai('quanao', 'nam'); ?> sản phẩm</span>
                    </div>
                </a>

                <a class="gs-cat-card" href="index.php?action=sanpham&loai=quanao&doituong=nu">
                    <img class="gs-cat-card__img" src="../assets/images/banners/cat_nu.webp?v=<?php echo filemtime(__DIR__ . '/../assets/images/banners/cat_nu.webp'); ?>" alt="Đồ tập nữ" loading="lazy" onerror="gsOnErrorImg(this)" style="object-position: center 15%;">
                    <div class="gs-cat-card__overlay">
                        <h3 class="gs-cat-card__title">Đồ tập nữ</h3>
                        <span class="gs-cat-card__count"><?php echo (int) $dm36->laysoluongsanphamtheoloai('quanao', 'nu'); ?> sản phẩm</span>
                    </div>
                </a>

                <a class="gs-cat-card" href="index.php?action=sanpham&loai=tpbs">
                    <img class="gs-cat-card__img" src="../assets/images/banners/cat_tpbs.jpg?v=<?php echo filemtime(__DIR__ . '/../assets/images/banners/cat_tpbs.jpg'); ?>" alt="Whey & TPBS" loading="lazy" onerror="gsOnErrorImg(this)">
                    <div class="gs-cat-card__overlay">
                        <h3 class="gs-cat-card__title">Whey &amp; TPBS</h3>
                        <span class="gs-cat-card__count"><?php echo (int) $dm36->laysoluongsanphamtheoloai('tpbs'); ?> sản phẩm</span>
                    </div>
                </a>

                <a class="gs-cat-card" href="index.php?action=sanpham&loai=phukien">
                    <img class="gs-cat-card__img" src="../assets/images/banners/cat_phukien.jpg?v=<?php echo filemtime(__DIR__ . '/../assets/images/banners/cat_phukien.jpg'); ?>" alt="Phụ kiện" loading="lazy" onerror="gsOnErrorImg(this)">
                    <div class="gs-cat-card__overlay">
                        <h3 class="gs-cat-card__title">Phụ kiện</h3>
                        <span class="gs-cat-card__count"><?php echo (int) $dm36->laysoluongsanphamtheoloai('phukien'); ?> sản phẩm</span>
                    </div>
                </a>

            </div>
        </div>
    </section>



    <?php
    $gsSizeGoiy = ($gsGoiy && $gsGoiy['sizegoiy']) ? $gsGoiy['sizegoiy']['size'] : '';
    $gsKhoi = array(
        array('key' => 'nam', 'tieude' => 'Đồ tập Nam', 'mota' => 'Trang phục tập luyện nam co giãn, thoát mồ hôi nhanh', 'link' => 'index.php?action=sanpham&loai=quanao&doituong=nam', 'ds' => $sp->laysanphamtheonhom('quanao', 'nam', 4), 'nen' => ''),
        array('key' => 'nu', 'tieude' => 'Đồ tập Nữ', 'mota' => 'Bộ sưu tập nữ ôm form, nâng dáng, thấm hút tốt', 'link' => 'index.php?action=sanpham&loai=quanao&doituong=nu', 'ds' => $sp->laysanphamtheonhom('quanao', 'nu', 4), 'nen' => 'grey'),
        array('key' => 'phukien', 'tieude' => 'Phụ kiện tập luyện', 'mota' => 'Găng tay, đai lưng, bình lắc và phụ kiện hỗ trợ khác', 'link' => 'index.php?action=sanpham&loai=phukien', 'ds' => $sp->laysanphamtheonhom('phukien', '', 4), 'nen' => ''),
        array('key' => 'tpbs', 'tieude' => 'Thực phẩm bổ sung', 'mota' => 'Whey, Creatine, hỗ trợ đốt mỡ chính hãng', 'link' => 'index.php?action=sanpham&loai=tpbs', 'ds' => $sp->laysanphamtheonhom('tpbs', '', 4), 'nen' => 'grey'),
        array('key' => 'moi', 'tieude' => 'Sản phẩm mới', 'mota' => 'Những sản phẩm vừa lên kệ tại GYMBRO', 'link' => 'index.php?action=sanpham&sapxep=moi', 'ds' => $sp->laysanphammoi(4), 'nen' => ''),
        array('key' => 'banchay', 'tieude' => 'Sản phẩm bán chạy', 'mota' => 'Được khách hàng GYMBRO chọn mua nhiều nhất', 'link' => 'index.php?action=sanpham&sapxep=banchay', 'ds' => $sp->laysanphambanchay(4), 'nen' => 'grey'),
        array('key' => 'noibat', 'tieude' => 'Sản phẩm nổi bật', 'mota' => 'Danh mục sản phẩm do GYMBRO tuyển chọn', 'link' => 'index.php?action=sanpham', 'ds' => $sp->laysanphamnoibatmoi(4), 'nen' => ''),
        array('key' => 'giamgia', 'tieude' => 'Sản phẩm khuyến mãi', 'mota' => 'Ưu đãi giảm giá sâu, số lượng có hạn', 'link' => 'index.php?action=sanpham&sapxep=giamgia', 'ds' => $sp->laysanphamkhuyenmai(4), 'nen' => 'grey')
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
                        Xem thêm
                    </a>
                </div>

                <?php if ($khoi['key'] === 'nu') : ?>
                    <div class="gs-ambassador-banner">
                        <div class="row g-0 align-items-center">
                            <div class="col-12 col-lg-7 gs-ambassador-banner__content">
                                <span class="gs-ambassador-banner__eyebrow">Đại sứ dòng sản phẩm nữ • GymBro</span>
                                <h3 class="gs-ambassador-banner__title">HOA HẬU TRẦN TIỂU VY</h3>
                                <p class="gs-ambassador-banner__sub">
                                    Đồng hành cùng GymBro trong bộ sưu tập thời trang thể thao nữ chuẩn form, co giãn 4 chiều mềm mịn và tôn trọn đường nét tự nhiên trong từng chuyển động.
                                </p>
                                <a href="index.php?action=sanpham&loai=quanao&doituong=nu" class="gs-ambassador-banner__cta">
                                    Mua ngay
                                </a>
                            </div>
                            <div class="col-12 col-lg-5 gs-ambassador-banner__img-wrap">
                                <img src="../assets/images/banners/ambassador_tieuvy_2.jpg?v=<?php echo file_exists(__DIR__ . '/../assets/images/banners/ambassador_tieuvy_2.jpg') ? filemtime(__DIR__ . '/../assets/images/banners/ambassador_tieuvy_2.jpg') : time(); ?>"
                                     alt="Hoa hậu Trần Tiểu Vy - Đại sứ GymBro"
                                     class="gs-ambassador-banner__img"
                                     style="object-position: center 18%;"
                                     onerror="gsOnErrorImg(this)">
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if (count($khoi['ds']) === 0) : ?>
                    <p class="text-muted">Danh mục này đang được cập nhật sản phẩm.</p>
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

    <section class="gs-section">
        <div class="container">
            <div class="gs-story-head">
                <h2 class="gs-story-title">PHÍA SAU MỖI THIẾT KẾ GYMBRO LÀ CẢ MỘT HÀNH TRÌNH</h2>
                <p class="gs-story-sub">
                    Tìm hiểu câu chuyện của chúng tôi – để thấy vì sao bạn có thể tin tưởng và tự hào vào bộ trang phục mà mình đã lựa chọn tại GymBro
                </p>
            </div>
            <div class="row g-4">
                <div class="col-12 col-md-4">
                    <div class="gs-story-card" role="button" data-bs-toggle="modal" data-bs-target="#gsStoryModal1">
                        <img src="../assets/images/banners/story_design.jpg" alt="Triết lý thiết kế GymBro" loading="lazy" onerror="gsOnErrorImg(this)">
                        <div class="gs-story-card__overlay">
                            <span class="gs-story-pill">Triết lý thiết kế</span>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-4">
                    <div class="gs-story-card" role="button" data-bs-toggle="modal" data-bs-target="#gsStoryModal2">
                        <img src="../assets/images/banners/story_factory.jpg" alt="Nhà máy dệt vải GymBro" loading="lazy" onerror="gsOnErrorImg(this)">
                        <div class="gs-story-card__overlay">
                            <span class="gs-story-pill">Tham quan nhà máy</span>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-4">
                    <div class="gs-story-card" role="button" data-bs-toggle="modal" data-bs-target="#gsStoryModal3">
                        <img src="../assets/images/banners/story_tailor.jpg" alt="Đội ngũ thợ may GymBro" loading="lazy" onerror="gsOnErrorImg(this)">
                        <div class="gs-story-card__overlay">
                            <span class="gs-story-pill">Gặp gỡ thợ may</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="modal fade" id="gsStoryModal1" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content bg-white text-dark border-0 shadow-lg">
                <div class="modal-header border-bottom">
                    <h5 class="modal-title fw-bold">Triết lý thiết kế GymBro</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
                </div>
                <div class="modal-body p-4">
                    <p class="text-muted mb-0" style="line-height: 1.7;">Mỗi mẫu trang phục GymBro đều khởi nguồn từ hàng chục bản phác thảo giải phẫu chuyển động thể thao. Chúng tôi ưu tiên phom dáng tôn đường nét tự nhiên, tối thiểu đường may cọ xát và đảm bảo độ nâng đỡ bền bỉ qua từng buổi tập nặng.</p>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="gsStoryModal2" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content bg-white text-dark border-0 shadow-lg">
                <div class="modal-header border-bottom">
                    <h5 class="modal-title fw-bold">Quy trình dệt vải chuẩn quốc tế</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
                </div>
                <div class="modal-body p-4">
                    <p class="text-muted mb-0" style="line-height: 1.7;">Toàn bộ chất liệu Polyester, Nylon &amp; Spandex cao cấp được dệt bằng hệ thống máy dệt kim tròn hiện đại, kiểm định độ co giãn 4 chiều, độ bền màu và khả năng thoát ẩm nhanh gấp 3 lần sợi cotton thông thường.</p>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="gsStoryModal3" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content bg-white text-dark border-0 shadow-lg">
                <div class="modal-header border-bottom">
                    <h5 class="modal-title fw-bold">Bàn tay lành nghề &amp; Tỉ mỉ từng đường kim</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
                </div>
                <div class="modal-body p-4">
                    <p class="text-muted mb-0" style="line-height: 1.7;">Đội ngũ thợ may nhiều năm kinh nghiệm trực tiếp hoàn thiện từng đường may khóa chỉ Flatlock êm ái, trải qua 3 bước QC khắt khe trước khi đóng gói đến tay khách hàng.</p>
                </div>
            </div>
        </div>
    </div>

    <?php include(__DIR__ . '/inc/footer.php'); ?>
