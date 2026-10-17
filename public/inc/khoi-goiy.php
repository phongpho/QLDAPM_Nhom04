<?php
if (!isset($gsGoiyTieude) || $gsGoiyTieude === '') {
    $gsGoiyTieude = 'Gợi ý cá nhân hóa cho bạn';
}

if (!isset($gsNguoidungId) || $gsNguoidungId <= 0) {
    return;
}

if (!isset($gsModule36)) {
    header('Location: ../index.php');
    exit();
}
if (!isset($gsGoiyChiTiet)) {
    $gsGoiyChiTiet = true;
}
$gsSizeGoiy = ($gsGoiy && $gsGoiy['sizegoiy']) ? $gsGoiy['sizegoiy']['size'] : '';
?>
<section class="gs-section gs-section--goiy">
    <div class="container">
        <div class="gs-section__head">
            <div>
                <h2 class="gs-section__title"><?php echo htmlspecialchars($gsGoiyTieude); ?></h2>
                <span class="gs-section__sub">
                    <?php if ($gsGoiy) : ?>
                        Dựa trên chỉ số cơ thể và mục tiêu tập luyện của bạn để chọn size, sản phẩm và combo phù hợp.
                    <?php else : ?>
                        Nhập chiều cao, cân nặng và mục tiêu để nhận gợi ý size cùng combo tập luyện chuẩn form.
                    <?php endif; ?>
                </span>
            </div>
            <?php if ($gsSizeGoiy !== '') : ?>
                <span class="gs-sizegoiy-badge">
                    <i class="fa-solid fa-ruler"></i> Size gợi ý: <?php echo htmlspecialchars($gsSizeGoiy); ?>
                </span>
            <?php endif; ?>
        </div>

        <?php if (!$gsGoiy) : ?>
            <div class="gs-cta-banner">
                <div class="gs-cta-banner__text">
                    <h3>Bạn chưa nhập chỉ số cơ thể!</h3>
                    <p>
                        Nhập chiều cao, cân nặng, giới tính và mục tiêu để hệ thống tự động tính BMI,
                        gợi ý size quần áo chuẩn và combo sản phẩm phù hợp nhất.
                    </p>
                </div>
                <div class="gs-cta-banner__icon" aria-hidden="true">
                    <i class="fa-solid fa-weight-scale"></i>
                </div>
            </div>

            <?php include(__DIR__ . '/form-chiso.php'); ?>

        <?php else : ?>
            <div class="gs-goiy-summary">
                <div class="gs-goiy-summary__item">
                    <span>Chỉ số BMI</span>
                    <strong><?php echo htmlspecialchars($gsGoiy['bmi']); ?></strong>
                </div>
                <div class="gs-goiy-summary__item">
                    <span>Phân loại</span>
                    <strong><?php echo $gsGoiy['cauhinh'] ? htmlspecialchars($gsGoiy['cauhinh']['phanloai']) : 'Chưa xác định'; ?></strong>
                </div>
                <div class="gs-goiy-summary__item">
                    <span>Mục tiêu tập luyện</span>
                    <strong><?php echo htmlspecialchars(gsTenMuctieu($gsGoiy['muctieu'])); ?></strong>
                </div>
                <div class="gs-goiy-summary__item">
                    <span>Size quần áo chuẩn</span>
                    <strong><?php echo ($gsSizeGoiy !== '') ? htmlspecialchars($gsSizeGoiy) : '—'; ?></strong>
                    <?php if ($gsGoiy['sizegoiy']) : ?>
                        <small>
                            (theo cao: <?php echo htmlspecialchars($gsGoiy['sizegoiy']['size_cao'] ? $gsGoiy['sizegoiy']['size_cao'] : '—'); ?>
                            · theo nặng: <?php echo htmlspecialchars($gsGoiy['sizegoiy']['size_nang'] ? $gsGoiy['sizegoiy']['size_nang'] : '—'); ?>)
                        </small>
                    <?php endif; ?>
                </div>
                <div class="gs-goiy-summary__item">
                    <span>Thể trạng</span>
                    <strong><?php echo htmlspecialchars($gsGoiy['chieucao']); ?> cm · <?php echo htmlspecialchars($gsGoiy['cannang']); ?> kg
                        · <?php echo ($gsGoiy['gioitinh'] === 'nu') ? 'Nữ' : 'Nam'; ?></strong>
                </div>
            </div>

            <?php if ($gsGoiy['cauhinh'] && !empty($gsGoiy['cauhinh']['khuyenghe'])) : ?>
                <p class="gs-goiy-advice">
                    <i class="fa-solid fa-lightbulb"></i>
                    <span><?php echo htmlspecialchars($gsGoiy['cauhinh']['khuyenghe']); ?></span>
                </p>
            <?php endif; ?>

            <?php if ($gsGoiyChiTiet && count($gsGoiy['sanpham']) > 0) : ?>
                <div class="row g-4 mt-2">
                    <?php foreach ($gsGoiy['sanpham'] as $card) : ?>
                        <?php include(__DIR__ . '/card-sanpham.php'); ?>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <?php
            $combo = $gsGoiy['combo'];
            $comboCoSanpham = ($combo['quanao'] || $combo['phukien'] || $combo['tpbs']);
            ?>
            <?php if ($gsGoiyChiTiet && $comboCoSanpham) : ?>
                <?php
                $tongcombo = 0;
                foreach ($combo as $motsp) {
                    if ($motsp) {
                        $tongcombo += (float) $motsp['giaban'];
                    }
                }
                ?>
                <div class="gs-combo">
                    <div class="gs-combo__head">
                        <h3 class="gs-combo__title"><i class="fa-solid fa-layer-group text-danger"></i> Combo gợi ý theo thể trạng của bạn</h3>
                        <span class="gs-combo__sub">1 Đồ tập + 1 Phụ kiện + 1 Thực phẩm bổ sung phù hợp mục tiêu
                            <?php echo htmlspecialchars(gsTenMuctieu($gsGoiy['muctieu'])); ?></span>
                    </div>

                    <div class="row g-3">
                        <?php foreach (array('quanao' => 'Đồ tập', 'phukien' => 'Phụ kiện', 'tpbs' => 'Thực phẩm bổ sung') as $nhom => $tennhom) : ?>
                            <?php $motsp = $combo[$nhom]; ?>
                            <?php if ($motsp) : ?>
                                <div class="col-12 col-md-4">
                                    <a class="gs-combo__item" href="index.php?action=chitiet&id=<?php echo (int) $motsp['id']; ?>">
                                        <span class="gs-combo__nhom"><?php echo htmlspecialchars($tennhom); ?></span>
                                        <img class="gs-combo__img" src="uploads/<?php echo htmlspecialchars($motsp['hinhanh']); ?>"
                                            alt="<?php echo htmlspecialchars($motsp['tensanpham']); ?>" loading="lazy" onerror="gsOnErrorImg(this)">
                                        <span class="gs-combo__name"><?php echo htmlspecialchars($motsp['tensanpham']); ?></span>
                                        <span class="gs-combo__price"><?php echo gsTien($motsp['giaban']); ?></span>
                                        <?php if ($nhom === 'quanao' && $gsSizeGoiy !== '') : ?>
                                            <span class="gs-combo__note"><i class="fa-solid fa-ruler"></i> Tự chọn size gợi ý: <?php echo htmlspecialchars($gsSizeGoiy); ?></span>
                                        <?php elseif ($nhom !== 'quanao') : ?>
                                            <span class="gs-combo__note"><i class="fa-solid fa-check"></i> Không cần chọn size</span>
                                        <?php endif; ?>
                                    </a>
                                </div>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </div>

                    <div class="gs-combo__foot">
                        <div class="gs-combo__total">
                            <span>Tổng combo</span>
                            <strong><?php echo gsTien($tongcombo); ?></strong>
                        </div>
                        <form class="gs-form-cart" method="post" action="index.php">
                            <input type="hidden" name="action" value="themcombo">
                            <input type="hidden" name="quanao_id" value="<?php echo $combo['quanao'] ? (int) $combo['quanao']['id'] : 0; ?>">
                            <input type="hidden" name="phukien_id" value="<?php echo $combo['phukien'] ? (int) $combo['phukien']['id'] : 0; ?>">
                            <input type="hidden" name="tpbs_id" value="<?php echo $combo['tpbs'] ? (int) $combo['tpbs']['id'] : 0; ?>">
                            <input type="hidden" name="size" value="<?php echo htmlspecialchars($gsSizeGoiy); ?>">
                            <button type="submit" class="btn-gs btn-gs--dark">
                                <i class="fa-solid fa-bag-shopping"></i> Thêm cả Combo vào giỏ
                            </button>
                        </form>
                    </div>
                </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</section>
