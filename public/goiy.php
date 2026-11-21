<?php
if (!isset($gsModule36)) {
    $_REQUEST['action'] = 'goy';
    require __DIR__ . '/index.php';
    return;
}
$gsTieude = 'Gợi ý theo chỉ số cơ thể';
// Nhúng header giao diện
include(__DIR__ . '/inc/head-client.php');
?>

<!-- Tiêu đề trang và breadcrumb gợi ý BMI -->
<section class="gs-section gs-section--grey gs-page-head">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb gs-breadcrumb">
                <li class="breadcrumb-item"><a href="index.php">Trang chủ</a></li>
                <li class="breadcrumb-item active" aria-current="page">Gợi ý theo chỉ số</li>
            </ol>
        </nav>
        <h1 class="gs-page-head__title">Gợi ý size &amp; combo theo chỉ số cơ thể</h1>
        <p class="gs-page-head__sub">
            Nhập chiều cao, cân nặng, giới tính và mục tiêu tập luyện để hệ thống tính BMI, phân loại thể trạng,
            chọn size quần áo chuẩn và gợi ý combo sản phẩm phù hợp nhất.
        </p>
    </div>
</section>

<!-- Biểu mẫu nhập và tính toán chỉ số cơ thể BMI -->
<section class="gs-section">
    <div class="container">
        <?php include(__DIR__ . '/inc/bmi-form.php'); ?>

        <?php if (!$gsGoiy) : ?>
            <div class="gs-empty mt-4">
                <i class="fa-solid fa-weight-scale"></i>
                <h3>Chưa có dữ liệu để gợi ý</h3>
                <p>Vui lòng nhập đầy đủ chiều cao (100-250cm) và cân nặng (25-300kg) ở biểu mẫu trên.</p>
            </div>
        <?php endif; ?>
    </div>
</section>

<!-- Hiển thị sản phẩm và combo gợi ý sau khi tính BMI -->
<?php
if ($gsGoiy) {
    $gsGoiyTieude = 'Kết quả gợi ý dành riêng cho bạn';
    include(__DIR__ . '/inc/recommendation.php');
}
?>

<!-- Nhúng footer chân trang -->
<?php include(__DIR__ . '/inc/footer.php'); ?>
