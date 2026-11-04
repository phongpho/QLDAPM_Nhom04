<?php
if (!isset($gsModule36)) {
    $_REQUEST['action'] = 'vnpay_return';
    require __DIR__ . '/index.php';
    return;
}
$gsTieude = 'Kết quả thanh toán VNPay';
include(__DIR__ . '/inc/head-client.php');

$thanhcong = $donhang
    && ((int) $donhang['datt'] === 1 || (isset($_GET['vnp_ResponseCode']) && $_GET['vnp_ResponseCode'] === '00'
    && (!isset($_GET['vnp_TransactionStatus']) || $_GET['vnp_TransactionStatus'] === '00')));
$sotienvnpay = isset($_GET['vnp_Amount']) ? ((float) $_GET['vnp_Amount'] / 100) : 0;
?>

<section class="gs-section gs-section--grey gs-page-head">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb gs-breadcrumb">
                <li class="breadcrumb-item"><a href="index.php">Trang chủ</a></li>
                <li class="breadcrumb-item"><a href="index.php?action=donhang">Đơn hàng</a></li>
                <li class="breadcrumb-item active" aria-current="page">Kết quả thanh toán</li>
            </ol>
        </nav>
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
            <div>
                <h1 class="gs-page-head__title mb-1">
                    <?php echo $thanhcong ? 'Thanh toán VNPay thành công' : 'Thanh toán VNPay không thành công'; ?>
                </h1>
                <p class="gs-page-head__sub mb-0">
                    <?php if ($donhang) : ?>
                        Mã đơn hàng <strong><?php echo htmlspecialchars($donhang['madonhang']); ?></strong>
                        <?php echo $thanhcong ? 'đã được ghi nhận thanh toán thành công.' : 'chưa hoàn tất thanh toán.'; ?>
                    <?php else : ?>
                        Không tìm thấy thông tin đơn hàng tương ứng.
                    <?php endif; ?>
                </p>
            </div>
            <a class="btn-gs btn-gs--outline btn-gs--sm" href="index.php?action=sanpham">Tiếp tục mua sắm</a>
        </div>
    </div>
</section>

<section class="gs-section">
    <div class="container">
        <?php if (!$donhang) : ?>
            <div class="gs-empty gs-cart-empty-box">
                <h3 class="mt-2 mb-2">Không tìm thấy đơn hàng</h3>
                <p class="text-muted mb-4">Không có đơn hàng nào khớp với mã giao dịch VNPay trả về.</p>
                <a class="btn-gs btn-gs--primary" href="index.php">Về trang chủ</a>
            </div>
        <?php else : ?>
            <div class="row g-4">
                <div class="col-12 col-lg-7">
                    <div class="gs-checkout__box">
                        <div class="gs-checkout__box-head mb-3">
                            <h3 class="gs-checkout__title mb-1">Thông tin giao dịch VNPay</h3>
                            <span class="small text-white-50">Chi tiết đối soát giao dịch cổng thanh toán điện tử.</span>
                        </div>

                        <div class="gs-info-list">
                            <div class="gs-info-row">
                                <span class="gs-info-label">Mã đơn hàng</span>
                                <span class="gs-info-value font-monospace text-warning"><?php echo htmlspecialchars($donhang['madonhang']); ?></span>
                            </div>
                            <div class="gs-info-row">
                                <span class="gs-info-label">Mã giao dịch VNPay</span>
                                <span class="gs-info-value font-monospace"><?php echo isset($_GET['vnp_TransactionNo']) ? htmlspecialchars($_GET['vnp_TransactionNo']) : '—'; ?></span>
                            </div>
                            <div class="gs-info-row">
                                <span class="gs-info-label">Số tiền thanh toán</span>
                                <span class="gs-info-value text-white"><?php echo gsTien($sotienvnpay > 0 ? $sotienvnpay : $donhang['tongtien']); ?></span>
                            </div>
                            <div class="gs-info-row">
                                <span class="gs-info-label">Mã phản hồi</span>
                                <span class="gs-info-value"><?php echo isset($_GET['vnp_ResponseCode']) ? htmlspecialchars($_GET['vnp_ResponseCode']) : '—'; ?></span>
                            </div>
                            <div class="gs-info-row">
                                <span class="gs-info-label">Trạng thái giao dịch</span>
                                <span class="gs-info-value">
                                    <?php if ($thanhcong) : ?>
                                        <span class="badge bg-success-subtle text-success border border-success-subtle">Thành công (00)</span>
                                    <?php else : ?>
                                        <span class="badge bg-danger-subtle text-danger border border-danger-subtle">Không thành công</span>
                                    <?php endif; ?>
                                </span>
                            </div>
                            <div class="gs-info-row">
                                <span class="gs-info-label">Thời gian thanh toán</span>
                                <span class="gs-info-value">
                                    <?php
                                    if (isset($_GET['vnp_PayDate']) && strlen($_GET['vnp_PayDate']) >= 14) {
                                        $pd = $_GET['vnp_PayDate'];
                                        echo htmlspecialchars(substr($pd, 6, 2) . '/' . substr($pd, 4, 2) . '/' . substr($pd, 0, 4)
                                            . ' ' . substr($pd, 8, 2) . ':' . substr($pd, 10, 2) . ':' . substr($pd, 12, 2));
                                    } else {
                                        echo '—';
                                    }
                                    ?>
                                </span>
                            </div>
                            <div class="gs-info-row">
                                <span class="gs-info-label">Phương thức</span>
                                <span class="gs-info-value"><?php echo htmlspecialchars($dh->laytenphuongthuc($donhang['phuongthuc_tt'])); ?></span>
                            </div>
                        </div>
                    </div>

                    <div class="gs-checkout__box mt-4">
                        <div class="gs-checkout__box-head mb-3">
                            <h3 class="gs-checkout__title mb-1">
                                Sản phẩm trong đơn (<?php echo count($chitiet); ?> món)
                            </h3>
                            <span class="small text-white-50">Danh sách sản phẩm bạn đã đặt mua.</span>
                        </div>

                        <div class="gs-checkout-items-list mb-3">
                            <?php foreach ($chitiet as $ct) : ?>
                                <?php
                                $spId = (int) ($ct['sanpham_id'] ?? 0);
                                $sizeText = ((string) $ct['size'] !== '') ? htmlspecialchars($ct['size']) : 'Freesize';
                                $dongSoluong = (int) $ct['soluong'];
                                $dongThanhtien = (float) $ct['thanhtien'];
                                ?>
                                <div class="gs-checkout-item">
                                    <div class="gs-checkout-item__thumb">
                                        <img src="../assets/images/products/<?php echo htmlspecialchars($ct['hinhanh']); ?>"
                                            alt="<?php echo htmlspecialchars($ct['tensanpham']); ?>" onerror="gsOnErrorImg(this)">
                                        <span class="gs-checkout-item__qty-pill"><?php echo $dongSoluong; ?></span>
                                    </div>
                                    <div class="gs-checkout-item__details">
                                        <span class="gs-checkout-item__name">
                                            <?php echo htmlspecialchars($ct['tensanpham']); ?>
                                        </span>
                                        <div class="gs-checkout-item__meta mt-1">
                                            <span class="gs-badge gs-badge--size"><?php echo $sizeText; ?></span>
                                            <span class="small text-white-50 ms-1">Số lượng: <?php echo $dongSoluong; ?></span>
                                        </div>
                                    </div>
                                    <div class="gs-checkout-item__total">
                                        <?php echo gsTien($dongThanhtien); ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>

                        <div class="gs-cart-summary__breakdown">
                            <div class="gs-cart-summary__line">
                                <span>Tạm tính</span>
                                <strong><?php echo gsTien($donhang['tamtinh']); ?></strong>
                            </div>
                            <?php if ((float) $donhang['giamgia'] > 0) : ?>
                                <div class="gs-cart-summary__line">
                                    <span>Giảm giá</span>
                                    <strong class="text-success">-<?php echo gsTien($donhang['giamgia']); ?></strong>
                                </div>
                            <?php endif; ?>
                            <div class="gs-cart-summary__line">
                                <span>Phí vận chuyển</span>
                                <strong>
                                    <?php if ((float) $donhang['phivanchuyen'] > 0) : ?>
                                        <?php echo gsTien($donhang['phivanchuyen']); ?>
                                    <?php else : ?>
                                        <span class="text-success">Miễn phí</span>
                                    <?php endif; ?>
                                </strong>
                            </div>
                            <div class="gs-cart-summary__line gs-cart-summary__line--total">
                                <span>Tổng cộng</span>
                                <strong class="gs-cart-total-highlight"><?php echo gsTien($donhang['tongtien']); ?></strong>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-lg-5">
                    <div class="gs-cart-summary gs-checkout-summary">
                        <div class="gs-cart-summary__head mb-3">
                            <h3 class="gs-cart-summary__title mb-0">Trạng thái đơn hàng</h3>
                        </div>

                        <div class="gs-info-list mb-3">
                            <div class="gs-info-row">
                                <span class="gs-info-label">Trạng thái xử lý</span>
                                <span class="gs-info-value">
                                    <span class="badge <?php echo $dh->laymautrangthai($donhang['trangthai']); ?>">
                                        <?php echo htmlspecialchars($dh->laytentrangthai($donhang['trangthai'])); ?>
                                    </span>
                                </span>
                            </div>
                            <div class="gs-info-row">
                                <span class="gs-info-label">Tình trạng thanh toán</span>
                                <span class="gs-info-value">
                                    <?php if ((int) $donhang['datt'] === 1) : ?>
                                        <span class="badge bg-success-subtle text-success border border-success-subtle">Đã thanh toán</span>
                                    <?php else : ?>
                                        <span class="badge bg-danger-subtle text-danger border border-danger-subtle">Chưa thanh toán</span>
                                    <?php endif; ?>
                                </span>
                            </div>
                            <div class="gs-info-row">
                                <span class="gs-info-label">Ngày đặt hàng</span>
                                <span class="gs-info-value"><?php echo htmlspecialchars(date('d/m/Y H:i', strtotime($donhang['ngay']))); ?></span>
                            </div>
                            <div class="gs-info-row">
                                <span class="gs-info-label">Người nhận</span>
                                <span class="gs-info-value"><?php echo htmlspecialchars($donhang['tennguoinhan']); ?></span>
                            </div>
                            <div class="gs-info-row">
                                <span class="gs-info-label">Số điện thoại</span>
                                <span class="gs-info-value"><?php echo htmlspecialchars($donhang['sdtnhan']); ?></span>
                            </div>
                            <div class="gs-info-row">
                                <span class="gs-info-label">Địa chỉ giao</span>
                                <span class="gs-info-value text-break" style="max-width: 60%;"><?php echo htmlspecialchars($donhang['diachinhan']); ?></span>
                            </div>
                        </div>

                        <?php if (!$thanhcong) : ?>
                            <a class="btn-gs btn-gs--primary btn-gs--lg w-100 gs-btn-checkout" href="index.php?action=thanhtoanlai_vnpay&madonhang=<?php echo urlencode($donhang['madonhang']); ?>">
                                THANH TOÁN LẠI
                            </a>
                        <?php endif; ?>

                        <a class="btn-gs <?php echo $thanhcong ? 'btn-gs--primary btn-gs--lg' : 'btn-gs--outline btn-gs--sm mt-2'; ?> w-100 text-center" href="index.php?action=donhang">
                            XEM ĐƠN HÀNG CỦA TÔI
                        </a>

                        <a class="btn-gs btn-gs--outline btn-gs--sm w-100 text-center mt-2" href="index.php?action=sanpham">
                            Tiếp tục mua sắm
                        </a>

                        <div class="gs-cart-guarantee-line mt-3 pt-3 border-top border-secondary border-opacity-25 text-center small text-white-50">
                            Chính hãng 100% · Bảo mật SSL · Đổi trả 7 ngày
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php include(__DIR__ . '/inc/footer.php'); ?>
