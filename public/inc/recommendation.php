<?php
if (!isset($gsModule36)) {
    header('Location: ../index.php');
    exit();
}

// Kiểm tra biến gợi ý từ controller; nếu chưa có thì tự động tra cứu
if (!isset($thongTinBmiKhach) || !isset($dsSanPhamBmiGoiy)) {
    if (isset($bmiModel) && isset($sp) && isset($gsNguoidungId) && $gsNguoidungId > 0) {
        $bmiGoiyData = gsLayGoiYTheoBmi($bmiModel, $sp, $gsNguoidungId);
        $thongTinBmiKhach = $bmiGoiyData['thongtin'];
        $dsSanPhamBmiGoiy = $bmiGoiyData['sanpham'];
    }
}

// QUY TẮC BẮT BUỘC THEO HƯỚNG DẪN:
// 1. Khách chưa đăng nhập -> TUYỆT ĐỐI KHÔNG hiển thị
// 2. Khách đã đăng nhập nhưng CHƯA nhập thể trạng -> TUYỆT ĐỐI KHÔNG hiển thị (không chừa khoảng trống)
// 3. Mốc BMI chưa được Admin gán sản phẩm (danh sách rỗng) -> TUYỆT ĐỐI KHÔNG hiển thị
if (empty($thongTinBmiKhach) || empty($dsSanPhamBmiGoiy)) {
    return;
}
?>

<!-- ================================================================= -->
<!-- SECTION: MỘT SỐ SẢN PHẨM PHÙ HỢP VỚI BẠN (GỢI Ý THEO CHỈ SỐ BMI)  -->
<!-- ĐIỀU KIỆN: CHỈ HIỂN THỊ KHI ĐÃ ĐĂNG NHẬP VÀ ĐÃ NHẬP FORM THỂ TRẠNG -->
<!-- ================================================================= -->
<section class="gs-section gs-section--bmi-recommended py-5" style="border-top: 1px solid var(--gs-border); border-bottom: 1px solid var(--gs-border); background: rgba(16, 22, 34, 0.6);">
    <div class="container">
        
        <!-- Header của Section -->
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
            <div>
                <div class="d-flex align-items-center gap-2 mb-2 flex-wrap">
                    <span class="badge" style="background-color: rgba(249, 115, 22, 0.15); color: var(--gs-primary); border: 1px solid rgba(249, 115, 22, 0.35); font-size: 0.82rem; font-weight: 700; padding: 6px 12px; border-radius: var(--gs-radius-pill);">
                        <i class="fa-solid fa-heart-pulse me-1"></i> Gợi ý theo chỉ số BMI
                    </span>
                    <span class="badge" style="background-color: rgba(255, 255, 255, 0.05); color: #ffffff; border: 1px solid var(--gs-border); font-size: 0.82rem; font-weight: 600; padding: 6px 12px; border-radius: var(--gs-radius-pill);">
                        Thể trạng: <strong class="text-warning"><?= htmlspecialchars($thongTinBmiKhach['phanloai']) ?></strong> (BMI: <?= number_format($thongTinBmiKhach['bmi'], 1) ?>)
                    </span>
                </div>
                <h2 class="gs-section__title mb-1" style="font-size: 1.8rem; font-weight: 800; color: #ffffff;">
                    Một số sản phẩm phù hợp với bạn
                </h2>
                <p class="text-secondary small mb-0 mt-1">
                    <?php if (!empty($thongTinBmiKhach['khuyenghe'])): ?>
                        <i class="fa-solid fa-lightbulb text-warning me-1"></i>
                        <?= htmlspecialchars($thongTinBmiKhach['khuyenghe']) ?>
                    <?php else: ?>
                        Các trang phục tập gym, phụ kiện và thực phẩm bổ sung được tuyển chọn phù hợp nhất với thể trạng của bạn.
                    <?php endif; ?>
                </p>
            </div>
            <div>
                <a href="index.php?action=taikhoan&tab=thetrang" class="btn btn-sm btn-outline-warning" style="font-weight: 600; border-radius: 8px; padding: 7px 16px;">
                    <i class="fa-solid fa-sliders me-1"></i> Cập nhật chỉ số cơ thể
                </a>
            </div>
        </div>

        <!-- Danh sách thẻ sản phẩm gợi ý do Admin gán -->
        <div class="row g-4">
            <?php foreach ($dsSanPhamBmiGoiy as $card): ?>
                <?php
                // Tái sử dụng thẻ sản phẩm chuẩn của Storefront
                include(__DIR__ . '/product-card.php'); 
                ?>
            <?php endforeach; ?>
        </div>

    </div>
</section>
