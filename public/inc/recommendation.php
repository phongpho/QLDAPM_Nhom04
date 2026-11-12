<?php
if (!isset($gsModule36)) {
    header('Location: ../index.php');
    exit();
}

if (!isset($thongTinBmiKhach) || !isset($dsSanPhamBmiGoiy)) {
    if (isset($bmiModel) && isset($sp) && isset($gsNguoidungId) && $gsNguoidungId > 0) {
        $bmiGoiyData = gsLayGoiYTheoBmi($bmiModel, $sp, $gsNguoidungId);
        $thongTinBmiKhach = $bmiGoiyData['thongtin'];
        $dsSanPhamBmiGoiy = $bmiGoiyData['sanpham'];
    }
}

if (empty($thongTinBmiKhach) || empty($dsSanPhamBmiGoiy)) {
    return;
}
?>

<section class="gs-section gs-section--bmi-recommended py-5" style="border-top: 1px solid var(--gs-border); border-bottom: 1px solid var(--gs-border); background: #F7F4EC;">
    <div class="container">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
            <div>
                <div class="d-flex align-items-center gap-2 mb-2 flex-wrap">
                    <span class="badge" style="background-color: #FFECF3; color: #D02545; border: 1px solid rgba(208, 37, 69, 0.25); font-size: 0.8rem; font-weight: 700; padding: 6px 14px; border-radius: var(--gs-radius-pill);">
                        Gợi ý theo chỉ số BMI
                    </span>
                    <span class="badge bg-white text-dark border" style="font-size: 0.82rem; font-weight: 600; padding: 6px 14px; border-radius: var(--gs-radius-pill);">
                        Chỉ số BMI: <strong><?= number_format($thongTinBmiKhach['bmi'], 1) ?></strong> · Thể trạng: <strong style="color: #D02545;"><?= htmlspecialchars($thongTinBmiKhach['phanloai']) ?></strong>
                    </span>
                </div>
                <h2 class="gs-section__title mb-1">
                    Một số sản phẩm phù hợp với bạn
                </h2>
                <p class="text-muted small mb-0 mt-1">
                    <?php if (!empty($thongTinBmiKhach['khuyenghe'])): ?>
                        <?= htmlspecialchars($thongTinBmiKhach['khuyenghe']) ?>
                    <?php else: ?>
                        Các trang phục tập gym, phụ kiện và thực phẩm bổ sung được tuyển chọn phù hợp nhất với thể trạng của bạn.
                    <?php endif; ?>
                </p>
            </div>
            <div>
                <a href="index.php?action=taikhoan&tab=thetrang" class="btn-gs btn-gs--outline btn-gs--sm">
                    Cập nhật chỉ số cơ thể
                </a>
            </div>
        </div>

        <div class="row g-4">
            <?php foreach ($dsSanPhamBmiGoiy as $card): ?>
                <?php include(__DIR__ . '/product-card.php'); ?>
            <?php endforeach; ?>
        </div>
    </div>
</section>
