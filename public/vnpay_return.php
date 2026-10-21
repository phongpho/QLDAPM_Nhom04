<?php
if (!isset($gsModule36)) {
    $_REQUEST['action'] = 'vnpay_return';
    require __DIR__ . '/index.php';
    return;
}
$gsTieude = 'Kết quả thanh toán VNPay';
include(__DIR__ . '/inc/head-client.php');

$thanhcong = $donhang
    && isset($_GET['vnp_ResponseCode']) && $_GET['vnp_ResponseCode'] === '00'
    && (!isset($_GET['vnp_TransactionStatus']) || $_GET['vnp_TransactionStatus'] === '00');
$sotienvnpay = isset($_GET['vnp_Amount']) ? ((float) $_GET['vnp_Amount'] / 100) : 0;
?>

<section class="gs-section">
    <div class="container">
        <?php if (!$donhang) : ?>
            <div class="gs-empty">
                <i class="fa-solid fa-circle-question"></i>
                <h3>Không tìm thấy đơn hàng</h3>
                <p>Không có đơn hàng nào khớp với mã giao dịch VNPay trả về.</p>
                <a class="btn-gs btn-gs--dark" href="index.php">Về trang chủ</a>
            </div>
        <?php else : ?>
            <div class="gs-result<?php echo $thanhcong ? '' : ' gs-result--fail'; ?>">
                <div class="gs-result__icon">
                    <i class="fa-solid <?php echo $thanhcong ? 'fa-circle-check' : 'fa-circle-xmark'; ?>"></i>
                </div>
                <h1 class="gs-result__title">
                    <?php echo $thanhcong ? 'Thanh toán VNPay thành công!' : 'Giao dịch VNPay không thành công'; ?>
                </h1>
                <p class="gs-result__sub">
                    Mã đơn hàng <strong><?php echo htmlspecialchars($donhang['madonhang']); ?></strong>
                    <?php if ($thanhcong) : ?>
                        đã được ghi nhận là <strong>đã thanh toán</strong>.
                    <?php else : ?>
                        chưa được thanh toán. Bạn có thể thử lại bằng VNPay hoặc chọn thanh toán khi nhận hàng (COD).
                    <?php endif; ?>
                </p>

                <div class="row g-4 mt-2 text-start">
                    <div class="col-12 col-lg-7">
                        <div class="gs-checkout__box">
                            <h3 class="gs-checkout__title"><i class="fa-solid fa-receipt"></i> Thông tin giao dịch VNPay</h3>
                            <table class="table gs-table">
                                <tbody>
                                    <tr>
                                        <th>Mã đơn hàng</th>
                                        <td><?php echo htmlspecialchars($donhang['madonhang']); ?></td>
                                    </tr>
                                    <tr>
                                        <th>Mã giao dịch VNPay</th>
                                        <td><?php echo isset($_GET['vnp_TransactionNo']) ? htmlspecialchars($_GET['vnp_TransactionNo']) : '—'; ?></td>
                                    </tr>
                                    <tr>
                                        <th>Số tiền</th>
                                        <td><?php echo gsTien($sotienvnpay > 0 ? $sotienvnpay : $donhang['tongtien']); ?></td>
                                    </tr>
                                    <tr>
                                        <th>Mã phản hồi</th>
                                        <td><?php echo isset($_GET['vnp_ResponseCode']) ? htmlspecialchars($_GET['vnp_ResponseCode']) : '—'; ?></td>
                                    </tr>
                                    <tr>
                                        <th>Trạng thái giao dịch</th>
                                        <td><?php echo isset($_GET['vnp_TransactionStatus']) ? htmlspecialchars($_GET['vnp_TransactionStatus']) : '—'; ?></td>
                                    </tr>
                                    <tr>
                                        <th>Thời gian thanh toán</th>
                                        <td>
                                            <?php
                                            if (isset($_GET['vnp_PayDate']) && strlen($_GET['vnp_PayDate']) >= 14) {
                                                $pd = $_GET['vnp_PayDate'];
                                                echo htmlspecialchars(substr($pd, 6, 2) . '/' . substr($pd, 4, 2) . '/' . substr($pd, 0, 4)
                                                    . ' ' . substr($pd, 8, 2) . ':' . substr($pd, 10, 2) . ':' . substr($pd, 12, 2));
                                            } else {
                                                echo '—';
                                            }
                                            ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Phương thức thanh toán</th>
                                        <td><?php echo htmlspecialchars($dh->laytenphuongthuc($donhang['phuongthuc_tt'])); ?></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="gs-checkout__box mt-4">
                            <h3 class="gs-checkout__title"><i class="fa-solid fa-box"></i> Sản phẩm trong đơn</h3>
                            <?php foreach ($chitiet as $ct) : ?>
                                <div class="gs-checkout__item">
                                    <img src="../assets/images/systems/<?php echo htmlspecialchars($ct['hinhanh']); ?>"
                                        alt="<?php echo htmlspecialchars($ct['tensanpham']); ?>" onerror="gsOnErrorImg(this)">
                                    <div>
                                        <span class="gs-checkout__item-name"><?php echo htmlspecialchars($ct['tensanpham']); ?></span>
                                        <span class="gs-checkout__item-meta">
                                            <?php echo ((string) $ct['size'] !== '') ? ('Size ' . htmlspecialchars($ct['size'])) : 'Freesize'; ?>
                                            × <?php echo (int) $ct['soluong']; ?>
                                        </span>
                                    </div>
                                    <span class="gs-checkout__item-price"><?php echo gsTien($ct['thanhtien']); ?></span>
                                </div>
                            <?php endforeach; ?>
                            <div class="gs-cart-summary__line gs-cart-summary__line--total">
                                <span>Tổng cộng</span>
                                <strong><?php echo gsTien($donhang['tongtien']); ?></strong>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-lg-5">
                        <div class="gs-cart-summary">
                            <h3 class="gs-cart-summary__title">Trạng thái đơn hàng</h3>
                            <div class="gs-cart-summary__line">
                                <span>Trạng thái</span>
                                <strong>
                                    <span class="badge <?php echo $dh->laymautrangthai($donhang['trangthai']); ?>">
                                        <?php echo htmlspecialchars($dh->laytentrangthai($donhang['trangthai'])); ?>
                                    </span>
                                </strong>
                            </div>
                            <div class="gs-cart-summary__line">
                                <span>Đã thanh toán</span>
                                <strong><?php echo ((int) $donhang['datt'] === 1) ? 'Có' : 'Chưa'; ?></strong>
                            </div>
                            <div class="gs-cart-summary__line">
                                <span>Ngày đặt</span>
                                <strong><?php echo htmlspecialchars(date('d/m/Y H:i', strtotime($donhang['ngay']))); ?></strong>
                            </div>

                            <?php if (!$thanhcong) : ?>
                                <a class="btn-gs btn-gs--dark w-100" href="vnpay_demo.php?madonhang=<?php echo urlencode($donhang['madonhang']); ?>">
                                    <i class="fa-solid fa-rotate-right"></i> Thanh toán lại
                                </a>
                            <?php endif; ?>
                            <a class="btn-gs btn-gs--light w-100<?php echo $thanhcong ? '' : ' mt-2'; ?>" href="index.php?action=donhang">
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
