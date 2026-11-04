<?php
if (!isset($gsModule36)) {
    $_REQUEST['action'] = 'thanhtoan';
    require __DIR__ . '/index.php';
    return;
}
$gsTieude = 'Thanh toán đơn hàng';

$diachimacdinh = isset($diachimacdinh) ? $diachimacdinh : null;
$dsDiachi = isset($dsDiachi) ? $dsDiachi : array();
$hoten = isset($gsND['hoten']) ? $gsND['hoten'] : '';
$sodienthoai = isset($gsND['sodienthoai']) ? $gsND['sodienthoai'] : '';
include(__DIR__ . '/inc/head-client.php');
?>

<section class="gs-section gs-section--grey gs-page-head">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb gs-breadcrumb">
                <li class="breadcrumb-item"><a href="index.php">Trang chủ</a></li>
                <li class="breadcrumb-item"><a href="index.php?action=giohang">Giỏ hàng</a></li>
                <li class="breadcrumb-item active" aria-current="page">Thanh toán</li>
            </ol>
        </nav>
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
            <div>
                <h1 class="gs-page-head__title mb-1">Thanh toán đơn hàng</h1>
                <p class="gs-page-head__sub mb-0">Vui lòng kiểm tra thông tin nhận hàng và chọn phương thức thanh toán phù hợp.</p>
            </div>
            <a class="btn-gs btn-gs--outline btn-gs--sm" href="index.php?action=giohang">Quay lại giỏ hàng</a>
        </div>
    </div>
</section>

<section class="gs-section">
    <div class="container">
        <form method="post" action="index.php" class="gs-checkout-form">
            <input type="hidden" name="action" value="datchotdon">
            <div class="row g-4">
                <div class="col-12 col-lg-7">
                    <div class="gs-checkout__box">
                        <div class="gs-checkout__box-head mb-3">
                            <h3 class="gs-checkout__title mb-1">1. Thông tin nhận hàng</h3>
                            <span class="small text-white-50">Địa chỉ giao hàng tận nơi cho đơn hàng của bạn.</span>
                        </div>

                        <?php if (count($dsDiachi) > 0) : ?>
                            <div class="gs-checkout__addresses mb-3">
                                <label class="gs-form-label mb-2">Sổ địa chỉ đã lưu</label>
                                <div class="gs-address-list">
                                    <?php foreach ($dsDiachi as $dc) : ?>
                                        <?php $isMacdinh = ((int) $dc['macdinh'] === 1); ?>
                                        <label class="gs-address-card <?php echo $isMacdinh ? 'active' : ''; ?>">
                                            <input type="radio" name="id_diachi" value="<?php echo (int) $dc['id']; ?>"
                                                <?php echo $isMacdinh ? 'checked' : ''; ?>
                                                data-ten="<?php echo htmlspecialchars($dc['tennguoinhan']); ?>"
                                                data-sdt="<?php echo htmlspecialchars($dc['sodienthoai']); ?>"
                                                data-diachi="<?php echo htmlspecialchars($dc['diachi']); ?>"
                                                onchange="gsChonDiachi(this)">
                                            <div class="gs-address-card__content">
                                                <div class="d-flex align-items-center justify-content-between mb-1">
                                                    <strong class="text-white"><?php echo htmlspecialchars($dc['tennguoinhan']); ?></strong>
                                                    <?php if ($isMacdinh) : ?>
                                                        <span class="gs-badge gs-badge--soft">Mặc định</span>
                                                    <?php endif; ?>
                                                </div>
                                                <div class="small text-warning mb-1">Số điện thoại: <?php echo htmlspecialchars($dc['sodienthoai']); ?></div>
                                                <div class="small text-white-50"><?php echo htmlspecialchars($dc['diachi']); ?></div>
                                            </div>
                                        </label>
                                    <?php endforeach; ?>
                                    <label class="gs-address-card">
                                        <input type="radio" name="id_diachi" value="0" onchange="gsChonDiachi(this)">
                                        <div class="gs-address-card__content">
                                            <div class="d-flex align-items-center gap-2">
                                                <strong class="text-white">+ Giao đến địa chỉ khác</strong>
                                            </div>
                                            <div class="small text-white-50 mt-1">Nhập thông tin người nhận mới ở biểu mẫu phía dưới</div>
                                        </div>
                                    </label>
                                </div>
                            </div>
                        <?php else : ?>
                            <input type="hidden" name="id_diachi" value="0">
                            <div class="alert gs-checkout-alert mb-3">
                                Bạn chưa có địa chỉ nào trong sổ, vui lòng điền biểu mẫu giao hàng bên dưới.
                            </div>
                        <?php endif; ?>

                        <div class="row g-3">
                            <div class="col-12 col-md-6">
                                <label class="gs-form-label" for="gsTenNguoiNhan">
                                    Tên người nhận <span class="text-danger">*</span>
                                </label>
                                <input class="form-control gs-input" type="text" id="gsTenNguoiNhan" name="tennguoinhan"
                                    value="<?php echo htmlspecialchars($diachimacdinh ? $diachimacdinh['tennguoinhan'] : $hoten); ?>"
                                    placeholder="Họ và tên người nhận" required>
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="gs-form-label" for="gsSdtNhan">
                                    Số điện thoại <span class="text-danger">*</span>
                                </label>
                                <input class="form-control gs-input" type="tel" id="gsSdtNhan" name="sdtnhan"
                                    value="<?php echo htmlspecialchars($diachimacdinh ? $diachimacdinh['sodienthoai'] : $sodienthoai); ?>"
                                    placeholder="Số điện thoại nhận hàng (10 số)" pattern="[0-9]{10}" required>
                            </div>
                            <div class="col-12">
                                <label class="gs-form-label" for="gsDiaChiNhan">
                                    Địa chỉ chi tiết <span class="text-danger">*</span>
                                </label>
                                <input class="form-control gs-input" type="text" id="gsDiaChiNhan" name="diachinhan"
                                    value="<?php echo htmlspecialchars($diachimacdinh ? $diachimacdinh['diachi'] : ''); ?>"
                                    placeholder="Số nhà, tên đường, phường/xã, quận/huyện, tỉnh/thành" required>
                            </div>
                            <div class="col-12">
                                <label class="gs-form-label" for="gsGhiChu">
                                    Ghi chú đơn hàng (không bắt buộc)
                                </label>
                                <textarea class="form-control gs-input" id="gsGhiChu" name="ghichu" rows="2"
                                    placeholder="Ví dụ: Giao giờ hành chính, gọi trước khi giao, gửi bảo vệ..."></textarea>
                            </div>
                            <div class="col-12">
                                <label class="gs-checkbox-custom">
                                    <input type="checkbox" name="luudiachi" value="1" checked>
                                    <span class="gs-checkbox-custom__box"></span>
                                    <span class="gs-checkbox-custom__text">Lưu thông tin này vào sổ địa chỉ để sử dụng cho lần sau</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="gs-checkout__box mt-4">
                        <div class="gs-checkout__box-head mb-3">
                            <h3 class="gs-checkout__title mb-1">2. Phương thức thanh toán</h3>
                            <span class="small text-white-50">Chọn hình thức thanh toán thuận tiện nhất với bạn.</span>
                        </div>

                        <div class="gs-paymethod-list mb-3">
                            <label class="gs-paymethod-card active">
                                <input type="radio" name="phuongthuc_tt" value="cod" checked onchange="gsChonPhuongthuc(this)">
                                <div class="gs-paymethod-card__info">
                                    <div class="d-flex align-items-center justify-content-between mb-1">
                                        <strong class="text-white">Thanh toán khi nhận hàng (COD)</strong>
                                        <span class="gs-badge gs-badge--soft">Tiền mặt</span>
                                    </div>
                                    <div class="small text-white-50">Thanh toán tiền mặt cho bưu tá khi nhận và đồng kiểm sản phẩm.</div>
                                </div>
                            </label>

                            <label class="gs-paymethod-card">
                                <input type="radio" name="phuongthuc_tt" value="chuyenkhoan" onchange="gsChonPhuongthuc(this)">
                                <div class="gs-paymethod-card__info">
                                    <div class="d-flex align-items-center justify-content-between mb-1">
                                        <strong class="text-white">Chuyển khoản ngân hàng (VietQR)</strong>
                                        <span class="gs-badge gs-badge--size">Quét QR</span>
                                    </div>
                                    <div class="small text-white-50">Hệ thống tạo mã VietQR tự động, thanh toán qua mọi app ngân hàng.</div>
                                </div>
                            </label>

                            <label class="gs-paymethod-card">
                                <input type="radio" name="phuongthuc_tt" value="vnpay" onchange="gsChonPhuongthuc(this)">
                                <div class="gs-paymethod-card__info">
                                    <div class="d-flex align-items-center justify-content-between mb-1">
                                        <strong class="text-white">Cổng thanh toán VNPay Sandbox</strong>
                                        <span class="gs-badge gs-badge--cat">ATM / Thẻ QT</span>
                                    </div>
                                    <div class="small text-white-50">Thẻ test NCB: <strong>9704198526191432198</strong> · Tên: <strong>NGUYEN VAN A</strong> · <strong>07/15</strong> · OTP: <strong>123456</strong></div>
                                </div>
                            </label>
                        </div>

                        <div class="gs-bankinfo-box" id="gsBankInfoBox">
                            <div class="fw-bold text-white small text-uppercase mb-2">Tài khoản thanh toán GYMBRO</div>
                            <div class="row g-2 small">
                                <div class="col-12 col-md-4">
                                    <div class="text-white-50">Ngân hàng:</div>
                                    <div class="text-white fw-bold">Vietcombank</div>
                                </div>
                                <div class="col-12 col-md-4">
                                    <div class="text-white-50">Số tài khoản:</div>
                                    <div class="text-warning fw-bold font-monospace">1234567890</div>
                                </div>
                                <div class="col-12 col-md-4">
                                    <div class="text-white-50">Chủ tài khoản:</div>
                                    <div class="text-white fw-bold">GYMBRO STORE</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-lg-5">
                    <div class="gs-cart-summary gs-checkout-summary">
                        <div class="gs-cart-summary__head mb-3">
                            <h3 class="gs-cart-summary__title mb-0">
                                Đơn hàng của bạn
                                <span class="gs-cart-count-badge"><?php echo count($giohang); ?> món</span>
                            </h3>
                        </div>

                        <div class="gs-checkout-items-list mb-3">
                            <?php foreach ($giohang as $dong) : ?>
                                <?php
                                $spId = (int) $dong['sanpham_id'];
                                $sizeText = ((string) $dong['size'] !== '') ? htmlspecialchars($dong['size']) : 'Freesize';
                                $dongSoluong = (int) $dong['soluong'];
                                $dongGia = (float) $dong['giaban'];
                                $dongThanhtien = $dongGia * $dongSoluong;
                                ?>
                                <div class="gs-checkout-item">
                                    <a class="gs-checkout-item__thumb" href="index.php?action=chitiet&id=<?php echo $spId; ?>" target="_blank">
                                        <img src="../assets/images/products/<?php echo htmlspecialchars($dong['hinhanh']); ?>"
                                            alt="<?php echo htmlspecialchars($dong['tensanpham']); ?>" onerror="gsOnErrorImg(this)">
                                        <span class="gs-checkout-item__qty-pill"><?php echo $dongSoluong; ?></span>
                                    </a>
                                    <div class="gs-checkout-item__details">
                                        <a class="gs-checkout-item__name" href="index.php?action=chitiet&id=<?php echo $spId; ?>" target="_blank">
                                            <?php echo htmlspecialchars($dong['tensanpham']); ?>
                                        </a>
                                        <div class="gs-checkout-item__meta mt-1">
                                            <span class="gs-badge gs-badge--size"><?php echo $sizeText; ?></span>
                                            <span class="small text-white-50 ms-1"><?php echo gsTien($dongGia); ?></span>
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
                                <strong><?php echo gsTien($gsTongtien['tamtinh']); ?></strong>
                            </div>
                            <div class="gs-cart-summary__line">
                                <span>
                                    Giảm giá voucher
                                    <?php if ($gsTongtien['voucher']) : ?>
                                        <span class="gs-voucher-tag ms-1">(<?php echo htmlspecialchars($gsTongtien['voucher']['ma']); ?>)</span>
                                    <?php endif; ?>
                                </span>
                                <strong class="<?php echo ($gsTongtien['giamgia'] > 0) ? 'text-success' : ''; ?>">
                                    <?php echo ($gsTongtien['giamgia'] > 0 ? '-' : '') . gsTien($gsTongtien['giamgia']); ?>
                                </strong>
                            </div>
                            <div class="gs-cart-summary__line">
                                <span>Phí vận chuyển</span>
                                <strong>
                                    <?php if ($gsTongtien['phivanchuyen'] > 0) : ?>
                                        <?php echo gsTien($gsTongtien['phivanchuyen']); ?>
                                    <?php else : ?>
                                        <span class="text-success">Miễn phí</span>
                                    <?php endif; ?>
                                </strong>
                            </div>
                            <div class="gs-cart-summary__line gs-cart-summary__line--total">
                                <span>Tổng thanh toán</span>
                                <strong class="gs-cart-total-highlight"><?php echo gsTien($gsTongtien['tongtien']); ?></strong>
                            </div>
                        </div>

                        <button type="submit" class="btn-gs btn-gs--primary btn-gs--lg w-100 gs-btn-checkout mt-3">
                            HOÀN TẤT ĐẶT HÀNG
                        </button>

                        <a class="btn-gs btn-gs--outline btn-gs--sm w-100 text-center mt-2" href="index.php?action=giohang">
                            Quay lại giỏ hàng
                        </a>

                        <div class="gs-cart-guarantee-line mt-3 pt-3 border-top border-secondary border-opacity-25 text-center small text-white-50">
                            Chính hãng 100% · Bảo mật SSL · Đổi trả 7 ngày
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</section>

<script>
function gsChonDiachi(radio) {
    document.querySelectorAll('.gs-address-card').forEach(function(el) {
        el.classList.remove('active');
    });
    if (radio.checked) {
        radio.closest('.gs-address-card').classList.add('active');
        if (radio.value !== '0') {
            var ten = radio.getAttribute('data-ten');
            var sdt = radio.getAttribute('data-sdt');
            var diachi = radio.getAttribute('data-diachi');
            if (ten) document.getElementById('gsTenNguoiNhan').value = ten;
            if (sdt) document.getElementById('gsSdtNhan').value = sdt;
            if (diachi) document.getElementById('gsDiaChiNhan').value = diachi;
        }
    }
}

function gsChonPhuongthuc(radio) {
    document.querySelectorAll('.gs-paymethod-card').forEach(function(el) {
        el.classList.remove('active');
    });
    if (radio.checked) {
        radio.closest('.gs-paymethod-card').classList.add('active');
        var bankBox = document.getElementById('gsBankInfoBox');
        if (bankBox) {
            if (radio.value === 'chuyenkhoan') {
                bankBox.style.display = 'block';
            }
        }
    }
}
</script>

<?php include(__DIR__ . '/inc/footer.php'); ?>
