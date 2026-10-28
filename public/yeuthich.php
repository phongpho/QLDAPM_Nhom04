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
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
            <div>
                <h1 class="gs-page-head__title mb-1">
                    <i class="fa-solid fa-heart text-danger me-2"></i>Sản phẩm yêu thích
                </h1>
                <p class="gs-page-head__sub mb-0">Các sản phẩm bạn đã lưu để theo dõi và mua sắm sau này.</p>
            </div>
            <?php if (!empty($dsYeuthich)) : ?>
                <span class="badge bg-danger rounded-pill fs-6 px-3 py-2">
                    <?php echo count($dsYeuthich); ?> sản phẩm đã lưu
                </span>
            <?php endif; ?>
        </div>
    </div>
</section>

<section class="gs-section py-5">
    <div class="container">
        <?php if (empty($dsYeuthich)) : ?>
            <div class="card border-secondary text-center py-5 shadow-sm" style="background-color: #141414; border-radius: 12px;">
                <div class="card-body py-5">
                    <div class="mb-3">
                        <i class="fa-regular fa-heart text-danger" style="font-size: 4rem;"></i>
                    </div>
                    <h3 class="fw-bold text-white mb-2">Danh sách yêu thích của bạn đang trống</h3>
                    <p class="text-white-50 mb-4" style="max-width: 480px; margin: 0 auto;">
                        Hãy bấm vào biểu tượng trái tim ở các sản phẩm bạn quan tâm để lưu lại và xem lại bất cứ khi nào!
                    </p>
                    <a href="index.php?action=sanpham" class="btn btn-danger px-4 py-2 fw-semibold" style="border-radius: 8px;">
                        <i class="fa-solid fa-bag-shopping me-2"></i> Khám phá sản phẩm ngay
                    </a>
                </div>
            </div>
        <?php else : ?>
            <div class="row g-4" id="gsWishlistGrid">
                <?php foreach ($dsYeuthich as $spYt) : 
                    $giagoc = (float) $spYt['giagoc'];
                    $giaban = (float) $spYt['giaban'];
                    $phantramgiam = 0;
                    if ($giagoc > $giaban && $giagoc > 0) {
                        $phantramgiam = (int) round(100 - ($giaban * 100 / $giagoc));
                    }
                    $conhang = ((int) $spYt['soluongton'] > 0);
                    $hinhanh = trim((string) $spYt['hinhanh']);
                    $duongdananh = ($hinhanh !== '') ? '../assets/images/products/' . $hinhanh : '';
                    $mangsizes = $sp->tachsizethanhmmang($spYt['sizes']);
                ?>
                    <div class="col-6 col-md-4 col-lg-3" id="wishlist-item-<?php echo (int) $spYt['id']; ?>">
                        <div class="card h-100 border-secondary position-relative text-white" 
                             style="background-color: #161616; border-radius: 12px; overflow: hidden; transition: transform 0.2s, box-shadow 0.2s;">
                            
                            <!-- nut bo thich -->
                            <a href="index.php?action=xoayeuthich&sanpham_id=<?php echo (int) $spYt['id']; ?>"
                               class="btn btn-sm position-absolute top-0 end-0 m-2 rounded-circle p-0 d-flex align-items-center justify-content-center"
                               style="width: 32px; height: 32px; background: rgba(0,0,0,0.65); color: #ff4d4f; z-index: 5; border: 1px solid rgba(255,255,255,0.1);"
                               title="Bỏ khỏi yêu thích">
                                <i class="fa-solid fa-xmark"></i>
                            </a>

                            <!-- anh san pham -->
                            <a href="index.php?action=chitiet&id=<?php echo (int) $spYt['id']; ?>" class="d-block position-relative text-decoration-none" style="aspect-ratio: 1/1; background: #202020;">
                                <img src="<?php echo htmlspecialchars($duongdananh); ?>"
                                     alt="<?php echo htmlspecialchars($spYt['tensanpham']); ?>"
                                     class="w-100 h-100"
                                     style="object-fit: cover;"
                                     loading="lazy"
                                     onerror="this.src='../assets/images/systems/logo.png'">
                                
                                <div class="position-absolute top-0 start-0 m-2 d-flex flex-column gap-1">
                                    <?php if ($phantramgiam > 0) : ?>
                                        <span class="badge bg-danger rounded-pill fw-bold">-<?php echo $phantramgiam; ?>%</span>
                                    <?php endif; ?>
                                    <?php if (!$conhang) : ?>
                                        <span class="badge bg-dark border border-secondary text-white-50 rounded-pill">Hết hàng</span>
                                    <?php endif; ?>
                                </div>
                            </a>

                            <!-- thong tin san pham -->
                            <div class="card-body p-3 d-flex flex-column justify-content-between">
                                <div>
                                    <div class="d-flex align-items-center justify-content-between mb-1">
                                        <span class="text-secondary small text-uppercase fw-semibold" style="font-size: 0.75rem;">
                                            <?php echo htmlspecialchars($spYt['tendanhmuc']); ?>
                                        </span>
                                        <?php if (!empty($spYt['tenthuonghieu'])) : ?>
                                            <span class="badge bg-dark border border-secondary text-secondary small" style="font-size: 0.7rem;">
                                                <?php echo htmlspecialchars($spYt['tenthuonghieu']); ?>
                                            </span>
                                        <?php endif; ?>
                                    </div>

                                    <h6 class="card-title mb-2 text-truncate-2" style="font-size: 0.95rem; font-weight: 600; line-height: 1.4;">
                                        <a href="index.php?action=chitiet&id=<?php echo (int) $spYt['id']; ?>" class="text-white text-decoration-none hover-danger">
                                            <?php echo htmlspecialchars($spYt['tensanpham']); ?>
                                        </a>
                                    </h6>

                                    <div class="d-flex align-items-baseline gap-2 mb-3">
                                        <span class="text-danger fw-bold fs-6">
                                            <?php echo gsTien($giaban); ?>
                                        </span>
                                        <?php if ($phantramgiam > 0) : ?>
                                            <span class="text-secondary text-decoration-line-through small" style="font-size: 0.8rem;">
                                                <?php echo gsTien($giagoc); ?>
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <div class="pt-2 border-top border-secondary-subtle">
                                    <?php if (!$conhang) : ?>
                                        <button class="btn btn-secondary btn-sm w-100 disabled" disabled>Tạm hết hàng</button>
                                    <?php elseif (count($mangsizes) > 0) : ?>
                                        <a href="index.php?action=chitiet&id=<?php echo (int) $spYt['id']; ?>" class="btn btn-outline-danger btn-sm w-100 fw-semibold">
                                            <i class="fa-solid fa-cart-plus me-1"></i> Chọn size & Mua
                                        </a>
                                    <?php else : ?>
                                        <form method="post" action="index.php" class="m-0">
                                            <input type="hidden" name="action" value="themvaogiohang">
                                            <input type="hidden" name="sanpham_id" value="<?php echo (int) $spYt['id']; ?>">
                                            <input type="hidden" name="size" value="">
                                            <input type="hidden" name="soluong" value="1">
                                            <button type="submit" class="btn btn-danger btn-sm w-100 fw-semibold">
                                                <i class="fa-solid fa-cart-plus me-1"></i> Thêm vào giỏ
                                            </button>
                                        </form>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<style>
.text-truncate-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
.hover-danger:hover {
    color: #dc3545 !important;
}
</style>

<?php include(__DIR__ . '/inc/footer.php'); ?>
