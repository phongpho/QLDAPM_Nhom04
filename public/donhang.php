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
        <h1 class="gs-page-head__title">Đơn hàng của tôi</h1>
        <p class="gs-page-head__sub">Theo dõi trạng thái và chi tiết các đơn hàng bạn đã đặt tại GYMBRO.</p>
    </div>
</section>

<section class="gs-section">
    <div class="container">
        <?php if (count($dsDonhang) === 0) : ?>
            <div class="gs-empty">
                <i class="fa-solid fa-receipt"></i>
                <h3>Bạn chưa có đơn hàng nào</h3>
                <p>Hãy chọn sản phẩm phù hợp với mục tiêu tập luyện và đặt đơn đầu tiên nhé!</p>
                <a class="btn-gs btn-gs--dark" href="index.php?action=sanpham">Mua sắm ngay</a>
            </div>
        <?php else : ?>
            <?php foreach ($dsDonhang as $od) : ?>
                <?php $chitietdon = $dh->laychitietdonhang($od['id']); ?>
                <div class="gs-order">
                    <div class="gs-order__head">
                        <div>
                            <span class="gs-order__ma"><?php echo htmlspecialchars($od['madonhang']); ?></span>
                            <span class="gs-order__ngay"><?php echo htmlspecialchars(date('d/m/Y H:i', strtotime($od['ngay']))); ?></span>
                        </div>
                        <div class="gs-order__right">
                            <span class="badge <?php echo $dh->laymautrangthai($od['trangthai']); ?>">
                                <?php echo htmlspecialchars($dh->laytentrangthai($od['trangthai'])); ?>
                            </span>
                            <span class="gs-order__tt">
                                <?php echo ((int) $od['datt'] === 1) ? 'Đã thanh toán' : 'Chưa thanh toán'; ?>
                                · <?php echo htmlspecialchars($dh->laytenphuongthuc($od['phuongthuc_tt'])); ?>
                            </span>
                        </div>
                    </div>

                    <div class="gs-order__body">
                        <div class="gs-order__items">
                            <?php foreach ($chitietdon as $ct) : ?>
                                <div class="gs-order__item">
                                    <img src="../assets/images/products/<?php echo htmlspecialchars($ct['hinhanh']); ?>"
                                        alt="<?php echo htmlspecialchars($ct['tensanpham']); ?>" onerror="gsOnErrorImg(this)">
                                    <div>
                                        <a class="gs-order__name" href="index.php?action=chitiet&id=<?php echo (int) $ct['sanpham_id']; ?>">
                                            <?php echo htmlspecialchars($ct['tensanpham']); ?>
                                        </a>
                                        <span class="gs-order__meta">
                                            <?php echo ((string) $ct['size'] !== '') ? ('Size ' . htmlspecialchars($ct['size'])) : 'Freesize'; ?>
                                            × <?php echo (int) $ct['soluong']; ?>
                                            · <?php echo gsTien($ct['dongia']); ?>
                                        </span>
                                    </div>
                                    <span class="gs-order__price"><?php echo gsTien($ct['thanhtien']); ?></span>
                                </div>
                            <?php endforeach; ?>
                        </div>

                        <div class="gs-order__foot">
                            <div class="gs-order__info">
                                <span><i class="fa-solid fa-user"></i> <?php echo htmlspecialchars($od['tennguoinhan']); ?> · <?php echo htmlspecialchars($od['sdtnhan']); ?></span>
                                <span><i class="fa-solid fa-location-dot"></i> <?php echo htmlspecialchars($od['diachinhan']); ?></span>
                                <?php if (!empty($od['ghichu'])) : ?>
                                    <span><i class="fa-solid fa-note-sticky"></i> <?php echo htmlspecialchars($od['ghichu']); ?></span>
                                <?php endif; ?>
                            </div>
                            <div class="gs-order__sum">
                                <span>Tạm tính: <strong><?php echo gsTien($od['tamtinh']); ?></strong></span>
                                <span>Giảm giá: <strong class="<?php echo ((float) $od['giamgia'] > 0) ? 'text-danger' : ''; ?>"><?php echo ((float) $od['giamgia'] > 0 ? '-' : '') . gsTien($od['giamgia']); ?></strong></span>
                                <span>Phí vận chuyển: <strong><?php echo ((float) $od['phivanchuyen'] > 0) ? gsTien($od['phivanchuyen']) : 'Miễn phí'; ?></strong></span>
                                <span class="gs-order__tong">Tổng cộng: <strong><?php echo gsTien($od['tongtien']); ?></strong></span>
                            </div>
                            <?php if ((int) $od['datt'] === 0 && $od['phuongthuc_tt'] === 'vnpay') : ?>
                                <a class="btn-gs btn-gs--dark" href="vnpay_demo.php?madonhang=<?php echo urlencode($od['madonhang']); ?>">
                                    <i class="fa-solid fa-rotate-right"></i> Thanh toán lại
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</section>

<?php include(__DIR__ . '/inc/footer.php'); ?>
