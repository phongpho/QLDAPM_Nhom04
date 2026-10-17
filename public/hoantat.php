<?php
if (!isset($gsModule36)) {
    $_REQUEST['action'] = 'hoantat';
    require __DIR__ . '/index.php';
    return;
}
$gsTieude = 'Đặt hàng thành công';
include(__DIR__ . '/inc/head-client.php');
?>

<section class="gs-section">
    <div class="container">
        <?php if (!$donhang) : ?>
            <div class="gs-empty">
                <i class="fa-solid fa-circle-question"></i>
                <h3>Không tìm thấy đơn hàng</h3>
                <p>Đường dẫn không hợp lệ hoặc đơn hàng đã bị xóa.</p>
                <a class="btn-gs btn-gs--dark" href="index.php">Về trang chủ</a>
            </div>
        <?php else : ?>
            <div class="gs-result">
                <div class="gs-result__icon"><i class="fa-solid fa-circle-check"></i></div>
                <h1 class="gs-result__title">Đặt hàng thành công!</h1>
                <p class="gs-result__sub">
                    Cảm ơn bạn đã mua sắm tại GYMBRO. Mã đơn hàng của bạn là
                    <strong class="text-danger"><?php echo htmlspecialchars($donhang['madonhang']); ?></strong>.
                </p>

                <div class="row g-4 mt-2 text-start">
                    <div class="col-12 col-lg-7">
                        <div class="gs-checkout__box">
                            <h3 class="gs-checkout__title"><i class="fa-solid fa-box"></i> Sản phẩm trong đơn</h3>
                            <?php foreach ($chitiet as $ct) : ?>
                                <div class="gs-checkout__item">
                                    <img src="uploads/<?php echo htmlspecialchars($ct['hinhanh']); ?>"
                                        alt="<?php echo htmlspecialchars($ct['tensanpham']); ?>" onerror="gsOnErrorImg(this)">
                                    <div>
                                        <span class="gs-checkout__item-name"><?php echo htmlspecialchars($ct['tensanpham']); ?></span>
                                        <span class="gs-checkout__item-meta">
                                            <?php echo ((string) $ct['size'] !== '') ? ('Size ' . htmlspecialchars($ct['size'])) : 'Freesize'; ?>
                                            × <?php echo (int) $ct['soluong']; ?>
                                            · <?php echo gsTien($ct['dongia']); ?>
                                        </span>
                                    </div>
                                    <span class="gs-checkout__item-price"><?php echo gsTien($ct['thanhtien']); ?></span>
                                </div>
                            <?php endforeach; ?>
                        </div>

                        <?php if ($donhang['phuongthuc_tt'] === 'chuyenkhoan') : ?>
                            <?php $urlqr = $vnpay->layUrlQrVietQR($donhang['madonhang'], $donhang['tongtien']); ?>
                            <div class="gs-checkout__box mt-4">
                                <h3 class="gs-checkout__title"><i class="fa-solid fa-qrcode"></i> Quét mã VietQR để chuyển khoản</h3>
                                <div class="gs-qr">
                                    <img src="<?php echo htmlspecialchars($urlqr); ?>" alt="Mã QR chuyển khoản đơn <?php echo htmlspecialchars($donhang['madonhang']); ?>"
                                        onerror="gsOnErrorImg(this)">
                                    <div class="gs-qr__info">
                                        <span>Số tiền: <strong><?php echo gsTien($donhang['tongtien']); ?></strong></span>
                                        <span>Nội dung chuyển khoản: <strong><?php echo htmlspecialchars($donhang['madonhang']); ?></strong></span>
                                        <span>Ngân hàng: <strong>Vietcombank</strong> · Số TK: <strong>1234567890</strong></span>
                                        <span class="text-secondary small">Đơn hàng sẽ được đánh dấu <em>đã thanh toán</em> sau khi nhân viên đối soát.</span>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>

                        <?php if ($donhang['phuongthuc_tt'] === 'vnpay' && (int) $donhang['datt'] === 0) : ?>
                            <div class="gs-checkout__box mt-4">
                                <h3 class="gs-checkout__title"><i class="fa-solid fa-credit-card"></i> Chưa hoàn tất thanh toán VNPay</h3>
                                <p class="mb-2">Đơn hàng đã được tạo nhưng chưa thanh toán. Bạn có thể thanh toán lại ngay bây giờ.</p>
                                <a class="btn-gs btn-gs--dark" href="vnpay_demo.php?madonhang=<?php echo urlencode($donhang['madonhang']); ?>">
                                    <i class="fa-solid fa-rotate-right"></i> Thanh toán lại qua VNPay
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="col-12 col-lg-5">
                        <div class="gs-cart-summary">
                            <h3 class="gs-cart-summary__title">Thông tin đơn hàng</h3>
                            <div class="gs-cart-summary__line">
                                <span>Mã đơn hàng</span>
                                <strong><?php echo htmlspecialchars($donhang['madonhang']); ?></strong>
                            </div>
                            <div class="gs-cart-summary__line">
                                <span>Ngày đặt</span>
                                <strong><?php echo htmlspecialchars(date('d/m/Y H:i', strtotime($donhang['ngay']))); ?></strong>
                            </div>
                            <div class="gs-cart-summary__line">
                                <span>Trạng thái</span>
                                <strong>
                                    <span class="badge <?php echo $dh->laymautrangthai($donhang['trangthai']); ?>">
                                        <?php echo htmlspecialchars($dh->laytentrangthai($donhang['trangthai'])); ?>
                                    </span>
                                </strong>
                            </div>
                            <div class="gs-cart-summary__line">
                                <span>Thanh toán</span>
                                <strong>
                                    <?php echo ((int) $donhang['datt'] === 1) ? 'Đã thanh toán' : 'Chưa thanh toán'; ?>
                                </strong>
                            </div>
                            <div class="gs-cart-summary__line">
                                <span>Phương thức</span>
                                <strong><?php echo htmlspecialchars($dh->laytenphuongthuc($donhang['phuongthuc_tt'])); ?></strong>
                            </div>
                            <div class="gs-cart-summary__line">
                                <span>Người nhận</span>
                                <strong><?php echo htmlspecialchars($donhang['tennguoinhan']); ?></strong>
                            </div>
                            <div class="gs-cart-summary__line">
                                <span>Số điện thoại</span>
                                <strong><?php echo htmlspecialchars($donhang['sdtnhan']); ?></strong>
                            </div>
                            <div class="gs-cart-summary__line gs-cart-summary__line--address">
                                <span>Địa chỉ</span>
                                <strong><?php echo htmlspecialchars($donhang['diachinhan']); ?></strong>
                            </div>
                            <?php if (!empty($donhang['ghichu'])) : ?>
                                <div class="gs-cart-summary__line gs-cart-summary__line--address">
                                    <span>Ghi chú</span>
                                    <strong><?php echo htmlspecialchars($donhang['ghichu']); ?></strong>
                                </div>
                            <?php endif; ?>

                            <hr>
                            <div class="gs-cart-summary__line">
                                <span>Tạm tính</span>
                                <strong><?php echo gsTien($donhang['tamtinh']); ?></strong>
                            </div>
                            <div class="gs-cart-summary__line">
                                <span>Giảm giá</span>
                                <strong class="<?php echo ((float) $donhang['giamgia'] > 0) ? 'text-danger' : ''; ?>">
                                    <?php echo ((float) $donhang['giamgia'] > 0 ? '-' : '') . gsTien($donhang['giamgia']); ?>
                                </strong>
                            </div>
                            <div class="gs-cart-summary__line">
                                <span>Phí vận chuyển</span>
                                <strong><?php echo ((float) $donhang['phivanchuyen'] > 0) ? gsTien($donhang['phivanchuyen']) : 'Miễn phí'; ?></strong>
                            </div>
                            <div class="gs-cart-summary__line gs-cart-summary__line--total">
                                <span>Tổng cộng</span>
                                <strong><?php echo gsTien($donhang['tongtien']); ?></strong>
                            </div>

                            <a class="btn-gs btn-gs--light w-100" href="index.php?action=donhang">
                                <i class="fa-solid fa-receipt"></i> Xem đơn hàng của tôi
                            </a>
                            <a class="gs-cart__link mt-3" href="index.php?action=sanpham"><i class="fa-solid fa-arrow-left-long"></i> Tiếp tục mua sắm</a>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php include(__DIR__ . '/inc/footer.php'); ?>
