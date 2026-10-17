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
                    <span class="gs-vnpay__env">SANDBOX · CHẾ ĐỘ MÔ PHỎNG</span>
                </div>

                <div class="gs-vnpay__body">
                    <div class="gs-vnpay__info">
                        <h1 class="gs-vnpay__title">Cổng thanh toán VNPay</h1>
                        <p class="text-secondary small">
                            Màn hình mô phỏng dùng cho môi trường localhost. Khi điền
                            <code>vnp_TmnCode</code> và <code>vnp_HashSecret</code> trong <code>model/vnpay.php</code>,
                            hệ thống sẽ chuyển sang cổng VNPay Sandbox thật
                            (<code>sandbox.vnpayment.vn</code>) với chữ ký <code>vnp_SecureHash</code> SHA512.
                        </p>

                        <table class="table gs-table">
                            <tbody>
                                <tr>
                                    <th>Mã đơn hàng</th>
                                    <td><?php echo htmlspecialchars($donhang['madonhang']); ?></td>
                                </tr>
                                <tr>
                                    <th>Số tiền</th>
                                    <td><strong><?php echo gsTien($donhang['tongtien']); ?></strong></td>
                                </tr>
                                <tr>
                                    <th>Nội dung</th>
                                    <td>Thanh toan don hang <?php echo htmlspecialchars($donhang['madonhang']); ?></td>
                                </tr>
                                <tr>
                                    <th>Người nhận</th>
                                    <td><?php echo htmlspecialchars($donhang['tennguoinhan']); ?> · <?php echo htmlspecialchars($donhang['sdtnhan']); ?></td>
                                </tr>
                            </tbody>
                        </table>

                        <div class="gs-vnpay__card">
                            <span class="gs-vnpay__card-title"><i class="fa-solid fa-credit-card"></i> Thẻ test NCB (Sandbox)</span>
                            <div class="gs-vnpay__card-row"><span>Số thẻ</span><strong><?php echo VNPAY::THE_TEST; ?></strong></div>
                            <div class="gs-vnpay__card-row"><span>Chủ thẻ</span><strong><?php echo VNPAY::CHU_THE_TEST; ?></strong></div>
                            <div class="gs-vnpay__card-row"><span>Ngày phát hành</span><strong><?php echo VNPAY::NGAY_PHAT_HANH_TEST; ?></strong></div>
                            <div class="gs-vnpay__card-row"><span>OTP</span><strong><?php echo VNPAY::OTP_TEST; ?></strong></div>
                        </div>
                    </div>

                    <div class="gs-vnpay__actions">
                        <form method="post" action="index.php">
                            <input type="hidden" name="action" value="vnpay_demo_xuly">
                            <input type="hidden" name="madonhang" value="<?php echo htmlspecialchars($donhang['madonhang']); ?>">
                            <input type="hidden" name="ketqua" value="thanhcong">
                            <button type="submit" class="btn-gs btn-gs--dark w-100">
                                <i class="fa-solid fa-circle-check"></i> Xác nhận thanh toán thành công
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
                        <p class="gs-vnpay__note">
                            Sau khi xử lý, hệ thống trả về trang <strong>vnpay_return.php</strong> kèm các tham số
                            <code>vnp_ResponseCode</code>, <code>vnp_TransactionStatus</code> để cập nhật cột
                            <code>donhang.datt = 1</code>.
                        </p>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php include(__DIR__ . '/inc/footer.php'); ?>
