<?php
if (!isset($gsModule36)) {
    $_REQUEST['action'] = 'yeuthich';
    require __DIR__ . '/index.php';
    return;
}
$gsTieude = 'Sản phẩm yêu thích';
include(__DIR__ . '/inc/head-client.php');
?>

<section class="gs-section gs-section--grey gs-page-head">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb gs-breadcrumb">
                <li class="breadcrumb-item"><a href="index.php">Trang chủ</a></li>
                <li class="breadcrumb-item active" aria-current="page">Sản phẩm yêu thích</li>
            </ol>
        </nav>
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
            <div>
                <h1 class="gs-page-head__title mb-1">
                    Sản phẩm yêu thích
                    <?php if (!empty($dsYeuthich)) : ?>
                        <span class="gs-cart-count-badge"><?php echo count($dsYeuthich); ?> món</span>
                    <?php endif; ?>
                </h1>
                <p class="gs-page-head__sub mb-0">Các sản phẩm bạn đã lưu để theo dõi và mua sắm sau này.</p>
            </div>
            <?php if (!empty($dsYeuthich)) : ?>
                <a class="btn-gs btn-gs--outline btn-gs--sm" href="index.php?action=sanpham">Khám phá thêm</a>
            <?php endif; ?>
        </div>
    </div>
</section>

<section class="gs-section py-5">
    <div class="container">
        <?php if (empty($dsYeuthich)) : ?>
            <div class="gs-empty gs-cart-empty-box">
                <h3 class="mt-2 mb-2">Danh sách yêu thích của bạn đang trống</h3>
                <p class="text-muted mb-4" style="max-width: 520px; margin: 0 auto;">
                    Hãy bấm vào biểu tượng trái tim ở các sản phẩm bạn quan tâm để lưu lại và theo dõi bất cứ khi nào!
                </p>
                <a href="index.php?action=sanpham" class="btn-gs btn-gs--primary">
                    Khám phá sản phẩm ngay
                </a>
            </div>
        <?php else : ?>
            <div class="row g-4" id="gsWishlistGrid">
                <?php foreach ($dsYeuthich as $card) : ?>
                    <?php include(__DIR__ . '/inc/product-card.php'); ?>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php include(__DIR__ . '/inc/footer.php'); ?>
