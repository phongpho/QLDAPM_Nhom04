<?php
if (!isset($gsModule36)) {
    $_REQUEST['action'] = 'hoantat';
    require __DIR__ . '/index.php';
    return;
}
$gsTieude = 'Đặt hàng thành công';
include(__DIR__ . '/inc/head-client.php');
?>

<section class="gs-section gs-section--grey gs-page-head">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb gs-breadcrumb">
                <li class="breadcrumb-item"><a href="index.php">Trang chủ</a></li>
                <li class="breadcrumb-item"><a href="index.php?action=donhang">Đơn hàng</a></li>
                <li class="breadcrumb-item active" aria-current="page">Hoàn tất</li>
            </ol>
        </nav>
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
            <div>
                <h1 class="gs-page-head__title mb-1">Đặt hàng thành công</h1>
                <p class="gs-page-head__sub mb-0">
                    <?php if ($donhang) : ?>
                        Cảm ơn bạn đã mua sắm tại GYMBRO. Mã đơn hàng của bạn là <strong><?php echo htmlspecialchars($donhang['madonhang']); ?></strong>.
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
                <p class="text-muted mb-4">Đường dẫn không hợp lệ hoặc đơn hàng không tồn tại.</p>
                <a class="btn-gs btn-gs--primary" href="index.php">Về trang chủ</a>
            </div>
        <?php else : ?>
            <div class="row g-4">
                <div class="col-12 col-lg-7">
                    <div class="gs-checkout__box">
                        <div class="gs-checkout__box-head mb-3">
                            <h3 class="gs-checkout__title mb-1">
                                Sản phẩm trong đơn (<?php echo count($chitiet); ?> món)
                            </h3>
                            <span class="small text-white-50">Danh sách các món đồ bạn đã đặt mua.</span>
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

                    <?php if ($donhang['phuongthuc_tt'] === 'chuyenkhoan') : ?>
                        <?php $urlqr = $vnpay->layUrlQrVietQR($donhang['madonhang'], $donhang['tongtien']); ?>
                        <div class="gs-checkout__box mt-4">
                            <div class="gs-checkout__box-head mb-3">
                                <h3 class="gs-checkout__title mb-1">Quét mã VietQR chuyển khoản</h3>
                                <span class="small text-white-50">Sử dụng app ngân hàng quét mã QR để chuyển khoản nhanh.</span>
                            </div>

                            <div class="d-flex flex-wrap align-items-center gap-4">
                                <div class="p-2 bg-white rounded-3 shadow-sm text-center" style="max-width: 200px;">
                                    <img src="<?php echo htmlspecialchars($urlqr); ?>"
                                        alt="VietQR <?php echo htmlspecialchars($donhang['madonhang']); ?>"
                                        class="img-fluid rounded-2"
                                        onerror="gsOnErrorImg(this)">
                                </div>
                                <div class="flex-grow-1">
                                    <div class="gs-info-list">
                                        <div class="gs-info-row">
                                            <span class="gs-info-label">Số tiền:</span>
                                            <span class="gs-info-value text-warning fs-5 fw-bold"><?php echo gsTien($donhang['tongtien']); ?></span>
                                        </div>
                                        <div class="gs-info-row">
                                            <span class="gs-info-label">Nội dung chuyển khoản:</span>
                                            <span class="gs-info-value font-monospace text-warning"><?php echo htmlspecialchars($donhang['madonhang']); ?></span>
                                        </div>
                                        <div class="gs-info-row">
                                            <span class="gs-info-label">Ngân hàng:</span>
                                            <span class="gs-info-value">Vietcombank</span>
                                        </div>
                                        <div class="gs-info-row">
                                            <span class="gs-info-label">Số tài khoản:</span>
                                            <span class="gs-info-value font-monospace">1234567890</span>
                                        </div>
                                        <div class="gs-info-row">
                                            <span class="gs-info-label">Chủ tài khoản:</span>
                                            <span class="gs-info-value">GYMBRO STORE</span>
                                        </div>
                                    </div>
                                    <div class="small text-white-50 mt-2">
                                        Đơn hàng sẽ được chuyển sang trạng thái <em>Đã thanh toán</em> sau khi hệ thống ghi nhận giao dịch.
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

                    <?php if ($donhang['phuongthuc_tt'] === 'vnpay' && (int) $donhang['datt'] === 0) : ?>
                        <div class="gs-checkout__box mt-4">
                            <div class="gs-checkout__box-head mb-3">
                                <h3 class="gs-checkout__title mb-1">Chưa hoàn tất thanh toán VNPay</h3>
                                <span class="small text-white-50">Đơn hàng đã được tạo nhưng chưa hoàn tất thanh toán.</span>
                            </div>
                            <a class="btn-gs btn-gs--primary btn-gs--lg" href="index.php?action=thanhtoanlai_vnpay&madonhang=<?php echo urlencode($donhang['madonhang']); ?>">
                                THANH TOÁN LẠI QUA VNPAY
                            </a>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="col-12 col-lg-5">
                    <div class="gs-cart-summary gs-checkout-summary">
                        <div class="gs-cart-summary__head mb-3">
                            <h3 class="gs-cart-summary__title mb-0">Trạng thái đơn hàng</h3>
                        </div>

                        <div class="gs-info-list mb-3">
                            <div class="gs-info-row">
                                <span class="gs-info-label">Mã đơn hàng</span>
                                <span class="gs-info-value font-monospace text-warning"><?php echo htmlspecialchars($donhang['madonhang']); ?></span>
                            </div>
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
                                <span class="gs-info-label">Phương thức</span>
                                <span class="gs-info-value"><?php echo htmlspecialchars($dh->laytenphuongthuc($donhang['phuongthuc_tt'])); ?></span>
                            </div>
                            <div class="gs-info-row">
                                <span class="gs-info-label">Ngày đặt</span>
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
                            <?php if (!empty($donhang['ghichu'])) : ?>
                                <div class="gs-info-row">
                                    <span class="gs-info-label">Ghi chú</span>
                                    <span class="gs-info-value text-break" style="max-width: 60%;"><?php echo htmlspecialchars($donhang['ghichu']); ?></span>
                                </div>
                            <?php endif; ?>
                        </div>

                        <a class="btn-gs btn-gs--primary btn-gs--lg w-100 text-center" href="index.php?action=donhang">
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

<script>
document.addEventListener('DOMContentLoaded', function () {
    const starTexts = {
        1: '1 sao - Có gì nhắn shop nhen',
        2: '2 sao - Bình tĩnh nhắn cho sop đi',
        3: '3 sao - Nhắn shop đi mò',
        4: '4 sao - Sản phẩm rất tốt',
        5: '5 sao - Sản phẩm tốt mà shop còn đẹp trai nữa'
    };

    document.querySelectorAll('.form-danhgia-hoantat').forEach(form => {
        const picker = form.querySelector('.ht-star-picker');
        const input = form.querySelector('.input-sao-val');
        const label = form.querySelector('.ht-star-label');
        if (!picker || !input) return;
        const stars = picker.querySelectorAll('.ht-star-item');

        function updateStars(val) {
            stars.forEach(s => {
                const v = parseInt(s.dataset.val, 10);
                if (v <= val) {
                    s.classList.remove('fa-regular');
                    s.classList.add('fa-solid');
                } else {
                    s.classList.remove('fa-solid');
                    s.classList.add('fa-regular');
                }
            });
            if (label && starTexts[val]) {
                label.textContent = starTexts[val];
            }
        }

        stars.forEach(s => {
            s.addEventListener('mouseenter', function () {
                updateStars(parseInt(this.dataset.val, 10));
            });
            s.addEventListener('click', function () {
                const v = parseInt(this.dataset.val, 10);
                input.value = v;
                updateStars(v);
            });
        });

        picker.addEventListener('mouseleave', function () {
            updateStars(parseInt(input.value, 10));
        });
    });
});
</script>

<?php include(__DIR__ . '/inc/footer.php'); ?>
