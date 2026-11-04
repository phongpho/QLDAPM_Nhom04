<?php
if (!isset($gsModule36)) {
    $_REQUEST['action'] = 'donhang';
    require __DIR__ . '/index.php';
    return;
}
$gsTieude = 'Đơn hàng của tôi';

if (!isset($dsDonhang)) {
    $dsDonhang = array();
}
include(__DIR__ . '/inc/head-client.php');
?>

<section class="gs-section gs-section--grey gs-page-head">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb gs-breadcrumb">
                <li class="breadcrumb-item"><a href="index.php">Trang chủ</a></li>
                <li class="breadcrumb-item active" aria-current="page">Đơn hàng của tôi</li>
            </ol>
        </nav>
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
            <div>
                <h1 class="gs-page-head__title mb-1">
                    Đơn hàng của tôi
                    <?php if (count($dsDonhang) > 0) : ?>
                        <span class="gs-cart-count-badge"><?php echo count($dsDonhang); ?> đơn</span>
                    <?php endif; ?>
                </h1>
                <p class="gs-page-head__sub mb-0">Theo dõi trạng thái và chi tiết các đơn hàng bạn đã đặt tại GYMBRO.</p>
            </div>
            <a class="btn-gs btn-gs--outline btn-gs--sm" href="index.php?action=sanpham">Tiếp tục mua sắm</a>
        </div>
    </div>
</section>

<section class="gs-section">
    <div class="container">
        <?php if (count($dsDonhang) === 0) : ?>
            <div class="gs-empty gs-cart-empty-box">
                <h3 class="mt-2 mb-2">Bạn chưa có đơn hàng nào</h3>
                <p class="text-muted mb-4">Hãy chọn sản phẩm phù hợp với mục tiêu tập luyện và đặt đơn đầu tiên nhé!</p>
                <a class="btn-gs btn-gs--primary" href="index.php?action=sanpham">Mua sắm ngay</a>
            </div>
        <?php else : ?>
            <div class="gs-orders-list">
                <?php foreach ($dsDonhang as $od) : ?>
                    <?php $chitietdon = $dh->laychitietdonhang($od['id']); ?>
                    <div class="gs-order-card">
                        <div class="gs-order-card__head">
                            <div class="d-flex flex-wrap align-items-center gap-2">
                                <span class="gs-order-card__code"><?php echo htmlspecialchars($od['madonhang']); ?></span>
                                <span class="text-white-50 small">•</span>
                                <span class="text-white-50 small"><?php echo htmlspecialchars(date('d/m/Y H:i', strtotime($od['ngay']))); ?></span>
                            </div>
                            <div class="d-flex flex-wrap align-items-center gap-2 mt-2 mt-md-0">
                                <span class="badge <?php echo $dh->laymautrangthai($od['trangthai']); ?>">
                                    <?php echo htmlspecialchars($dh->laytentrangthai($od['trangthai'])); ?>
                                </span>
                                <?php if ((int) $od['datt'] === 1) : ?>
                                    <span class="badge bg-success-subtle text-success border border-success-subtle">Đã thanh toán</span>
                                <?php else : ?>
                                    <span class="badge bg-danger-subtle text-danger border border-danger-subtle">Chưa thanh toán</span>
                                <?php endif; ?>
                                <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle">
                                    <?php echo htmlspecialchars($dh->laytenphuongthuc($od['phuongthuc_tt'])); ?>
                                </span>
                            </div>
                        </div>

                        <div class="gs-order-card__body">
                            <div class="gs-order-card__items">
                                <?php foreach ($chitietdon as $ct) : ?>
                                    <?php
                                    $spId = (int) ($ct['sanpham_id'] ?? 0);
                                    $sizeText = ((string) $ct['size'] !== '') ? htmlspecialchars($ct['size']) : 'Freesize';
                                    $dongSoluong = (int) $ct['soluong'];
                                    $dongDongia = (float) $ct['dongia'];
                                    $dongThanhtien = (float) $ct['thanhtien'];
                                    ?>
                                    <div class="gs-order-item">
                                        <a class="gs-order-item__thumb" href="index.php?action=chitiet&id=<?php echo $spId; ?>" target="_blank">
                                            <img src="../assets/images/products/<?php echo htmlspecialchars($ct['hinhanh']); ?>"
                                                alt="<?php echo htmlspecialchars($ct['tensanpham']); ?>" onerror="gsOnErrorImg(this)">
                                            <span class="gs-order-item__qty-pill"><?php echo $dongSoluong; ?></span>
                                        </a>
                                        <div class="gs-order-item__info">
                                            <a class="gs-order-item__name" href="index.php?action=chitiet&id=<?php echo $spId; ?>" target="_blank">
                                                <?php echo htmlspecialchars($ct['tensanpham']); ?>
                                            </a>
                                            <div class="gs-order-item__meta mt-1">
                                                <span class="gs-badge gs-badge--size"><?php echo $sizeText; ?></span>
                                                <span class="small text-white-50 ms-1"><?php echo gsTien($dongDongia); ?> × <?php echo $dongSoluong; ?></span>
                                            </div>
                                        </div>
                                        <div class="gs-order-item__total">
                                            <?php echo gsTien($dongThanhtien); ?>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>

                            <div class="gs-order-card__foot">
                                <div class="gs-order-recipient">
                                    <div class="small text-white fw-bold mb-1">Thông tin giao hàng:</div>
                                    <div class="small text-white-50">
                                        <strong><?php echo htmlspecialchars($od['tennguoinhan']); ?></strong> · <?php echo htmlspecialchars($od['sdtnhan']); ?>
                                    </div>
                                    <div class="small text-white-50">
                                        <?php echo htmlspecialchars($od['diachinhan']); ?>
                                    </div>
                                    <?php if (!empty($od['ghichu'])) : ?>
                                        <div class="small text-warning mt-1">
                                            Ghi chú: <?php echo htmlspecialchars($od['ghichu']); ?>
                                        </div>
                                    <?php endif; ?>
                                </div>

                                <div class="gs-order-summary">
                                    <div class="gs-order-summary__line">
                                        <span>Tạm tính:</span>
                                        <strong><?php echo gsTien($od['tamtinh']); ?></strong>
                                    </div>
                                    <?php if ((float) $od['giamgia'] > 0) : ?>
                                        <div class="gs-order-summary__line">
                                            <span>Giảm giá:</span>
                                            <strong class="text-success">-<?php echo gsTien($od['giamgia']); ?></strong>
                                        </div>
                                    <?php endif; ?>
                                    <div class="gs-order-summary__line">
                                        <span>Phí vận chuyển:</span>
                                        <strong>
                                            <?php echo ((float) $od['phivanchuyen'] > 0) ? gsTien($od['phivanchuyen']) : 'Miễn phí'; ?>
                                        </strong>
                                    </div>
                                    <div class="gs-order-summary__line gs-order-summary__line--total">
                                        <span>Tổng cộng:</span>
                                        <strong class="gs-order-total-highlight"><?php echo gsTien($od['tongtien']); ?></strong>
                                    </div>

                                    <?php if ((int) $od['datt'] === 0 && $od['phuongthuc_tt'] === 'vnpay') : ?>
                                        <a class="btn-gs btn-gs--primary btn-gs--sm w-100 mt-2 text-center"
                                            href="index.php?action=thanhtoanlai_vnpay&madonhang=<?php echo urlencode($od['madonhang']); ?>">
                                            THANH TOÁN LẠI QUA VNPAY
                                        </a>
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

<?php include(__DIR__ . '/inc/footer.php'); ?>
