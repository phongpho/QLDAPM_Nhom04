<?php
if (!isset($gsModule36)) {
    $_REQUEST['action'] = 'vnpay_demo';
    require __DIR__ . '/index.php';
    return;
}
$gsTieude = 'Cổng thanh toán VNPay Sandbox';
include(__DIR__ . '/inc/head-client.php');
?>

<section class="gs-section">
    <div class="container">
        <?php if (!$donhang) : ?>
            <div class="gs-empty">
                <i class="fa-solid fa-circle-question"></i>
                <h3>Không tìm thấy đơn hàng cần thanh toán</h3>
                <a class="btn-gs btn-gs--dark" href="index.php?action=giohang">Về giỏ hàng</a>
            </div>
        <?php else : ?>
            <div class="gs-vnpay">
                <div class="gs-vnpay__head">
                    <span class="gs-vnpay__logo">VN<b>PAY</b></span>
                    <span class="gs-vnpay__env"><i class="fa-solid fa-shield-halved"></i> Thanh toán an toàn</span>
                </div>

                <div class="gs-vnpay__body">
                    <div class="gs-vnpay__info">
                        <h1 class="gs-vnpay__title">Thông tin thanh toán</h1>

                        <div class="gs-vnpay__amount">
                            <span>Số tiền thanh toán</span>
                            <strong><?php echo gsTien($donhang['tongtien']); ?></strong>
                        </div>

                        <ul class="gs-vnpay__rows">
                            <li>
                                <span>Mã đơn hàng</span>
                                <strong><?php echo htmlspecialchars($donhang['madonhang']); ?></strong>
                            </li>
                            <li>
                                <span>Nội dung</span>
                                <strong>Thanh toan don hang <?php echo htmlspecialchars($donhang['madonhang']); ?></strong>
                            </li>
                            <li>
                                <span>Người nhận</span>
                                <strong><?php echo htmlspecialchars($donhang['tennguoinhan']); ?></strong>
                            </li>
                            <li>
                                <span>Số điện thoại</span>
                                <strong><?php echo htmlspecialchars($donhang['sdtnhan']); ?></strong>
                            </li>
                        </ul>

                        <div class="gs-vnpay__bankcard">
                            <span class="gs-vnpay__bankcard-brand">NCB · Thẻ nội địa</span>
                            <span class="gs-vnpay__bankcard-number"><?php echo VNPAY::THE_TEST; ?></span>
                            <div class="gs-vnpay__bankcard-foot">
                                <span>
                                    <small>Chủ thẻ</small>
                                    <?php echo VNPAY::CHU_THE_TEST; ?>
                                </span>
                                <span>
                                    <small>Ngày phát hành</small>
                                    <?php echo VNPAY::NGAY_PHAT_HANH_TEST; ?>
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="gs-vnpay__actions">
                        <h2 class="gs-vnpay__subtitle">Xác thực giao dịch</h2>
                        <p class="gs-vnpay__hint">
                            Nhập mã OTP của thẻ <strong><?php echo VNPAY::OTP_TEST; ?></strong> để hoàn tất thanh toán.
                        </p>

                        <form method="post" action="index.php">
                            <input type="hidden" name="action" value="vnpay_demo_xuly">
                            <input type="hidden" name="madonhang" value="<?php echo htmlspecialchars($donhang['madonhang']); ?>">
                            <input type="hidden" name="ketqua" value="thanhcong">
                            <label class="gs-form-label" for="gsOtp">Mã OTP</label>
                            <input class="form-control" type="text" id="gsOtp" name="otp" inputmode="numeric"
                                pattern="[0-9]{6}" maxlength="6" autocomplete="one-time-code"
                                placeholder="Nhập 6 chữ số" required>
                            <button type="submit" class="btn-gs btn-gs--dark w-100 mt-3">
                                <i class="fa-solid fa-lock"></i> Thanh toán <?php echo gsTien($donhang['tongtien']); ?>
                            </button>
                        </form>
                        <form method="post" action="index.php" class="mt-2">
                            <input type="hidden" name="action" value="vnpay_demo_xuly">
                            <input type="hidden" name="madonhang" value="<?php echo htmlspecialchars($donhang['madonhang']); ?>">
                            <input type="hidden" name="ketqua" value="huy">
                            <button type="submit" class="btn-gs btn-gs--light w-100">
                                <i class="fa-solid fa-xmark"></i> Hủy giao dịch
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php include(__DIR__ . '/inc/footer.php'); ?>
