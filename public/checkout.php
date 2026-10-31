<?php
if (!isset($gsModule36)) {
    $_REQUEST['action'] = 'thanhtoan';
    require __DIR__ . '/index.php';
    return;
}
$gsTieude = 'Thanh toán';

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
        <h1 class="gs-page-head__title">Thanh toán đơn hàng</h1>
        <p class="gs-page-head__sub">Vui lòng kiểm tra thông tin nhận hàng và chọn phương thức thanh toán phù hợp.</p>
    </div>
</section>

<section class="gs-section">
    <div class="container">
        <form method="post" action="index.php" class="gs-checkout">
            <input type="hidden" name="action" value="datchotdon">
            <div class="row g-4">
                <div class="col-12 col-lg-7">
                    <div class="gs-checkout__box">
                        <h3 class="gs-checkout__title"><i class="fa-solid fa-truck"></i> Thông tin nhận hàng</h3>

                        <?php if (count($dsDiachi) > 0) : ?>
                            <div class="gs-checkout__addresses">
                                <?php foreach ($dsDiachi as $dc) : ?>
                                    <label class="gs-address">
                                        <input type="radio" name="id_diachi" value="<?php echo (int) $dc['id']; ?>"
                                            <?php echo ((int) $dc['macdinh'] === 1) ? 'checked' : ''; ?>>
                                        <span class="gs-address__body">
                                            <strong><?php echo htmlspecialchars($dc['tennguoinhan']); ?></strong>
                                            <span><?php echo htmlspecialchars($dc['sodienthoai']); ?></span>
                                            <span><?php echo htmlspecialchars($dc['diachi']); ?></span>
                                            <?php if ((int) $dc['macdinh'] === 1) : ?>
                                                <em class="gs-address__macdinh">Mặc định</em>
                                            <?php endif; ?>
                                        </span>
                                    </label>
                                <?php endforeach; ?>
                                <label class="gs-address">
                                    <input type="radio" name="id_diachi" value="0">
                                    <span class="gs-address__body">
                                        <strong>Giao đến địa chỉ khác</strong>
                                        <span>Nhập thông tin người nhận ở biểu mẫu bên dưới</span>
                                    </span>
                                </label>
                            </div>
                        <?php else : ?>
                            <input type="hidden" name="id_diachi" value="0">
                            <p class="text-secondary">Bạn chưa có địa chỉ nào trong sổ địa chỉ, hãy nhập địa chỉ giao hàng bên dưới.</p>
                        <?php endif; ?>

                        <div class="row g-3 mt-1">
                            <div class="col-12 col-md-6">
                                <label class="gs-form-label" for="gsTenNguoiNhan">Tên người nhận</label>
                                <input class="form-control" type="text" id="gsTenNguoiNhan" name="tennguoinhan"
                                    value="<?php echo htmlspecialchars($diachimacdinh ? $diachimacdinh['tennguoinhan'] : $hoten); ?>"
                                    placeholder="VD: Nguyễn Văn An" required>
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="gs-form-label" for="gsSdtNhan">Số điện thoại</label>
                                <input class="form-control" type="tel" id="gsSdtNhan" name="sdtnhan"
                                    value="<?php echo htmlspecialchars($diachimacdinh ? $diachimacdinh['sodienthoai'] : $sodienthoai); ?>"
                                    placeholder="10 số" pattern="[0-9]{10}" required>
                            </div>
                            <div class="col-12">
                                <label class="gs-form-label" for="gsDiaChiNhan">Địa chỉ giao hàng</label>
                                <input class="form-control" type="text" id="gsDiaChiNhan" name="diachinhan"
                                    value="<?php echo htmlspecialchars($diachimacdinh ? $diachimacdinh['diachi'] : ''); ?>"
                                    placeholder="Số nhà, đường, phường/xã, quận/huyện, tỉnh/thành" required>
                            </div>
                            <div class="col-12">
                                <label class="gs-form-label" for="gsGhiChu">Ghi chú đơn hàng (không bắt buộc)</label>
                                <textarea class="form-control" id="gsGhiChu" name="ghichu" rows="2"
                                    placeholder="VD: Giao ngoài giờ hành chính, gọi trước khi giao..."></textarea>
                            </div>
                            <div class="col-12">
                                <label class="gs-check">
                                    <input type="checkbox" name="luudiachi" value="1" checked>
                                    <span>Lưu địa chỉ này vào sổ địa chỉ và đặt làm mặc định</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="gs-checkout__box mt-4">
                        <h3 class="gs-checkout__title"><i class="fa-solid fa-hand-holding-dollar"></i> Phương thức thanh toán</h3>

                        <label class="gs-paymethod">
                            <input type="radio" name="phuongthuc_tt" value="cod" checked>
                            <span class="gs-paymethod__body">
                                <strong>Thanh toán khi nhận hàng (COD)</strong>
                                <small>Thanh toán tiền mặt cho nhân viên giao hàng. Đơn hàng được ghi nhận là <em>chưa thanh toán</em>.</small>
                            </span>
                        </label>

                        <label class="gs-paymethod">
                            <input type="radio" name="phuongthuc_tt" value="chuyenkhoan">
                            <span class="gs-paymethod__body">
                                <strong>Chuyển khoản ngân hàng (VietQR)</strong>
                                <small>Sau khi đặt hàng, hệ thống hiển thị mã QR VietQR kèm số tiền và nội dung chuyển khoản là mã đơn hàng.</small>
                            </span>
                        </label>

                        <label class="gs-paymethod">
                            <input type="radio" name="phuongthuc_tt" value="vnpay">
                            <span class="gs-paymethod__body">
                                <strong>Thanh toán VNPay Sandbox (Thẻ Test NCB)</strong>
                                <small>Số thẻ: <strong>9704198526191432198</strong> · Tên: <strong>NGUYEN VAN A</strong> · Ngày: <strong>07/15</strong> · OTP: <strong>123456</strong></small>
                            </span>
                        </label>

                        <div class="gs-bankinfo">
                            <span class="gs-bankinfo__title">Thông tin chuyển khoản</span>
                            <span>Ngân hàng: <strong>Vietcombank</strong> - Số TK: <strong>1234567890</strong> - Chủ TK: <strong>GYMBRO</strong></span>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-lg-5">
                    <div class="gs-cart-summary gs-checkout__summary">
                        <h3 class="gs-cart-summary__title">Đơn hàng của bạn (<?php echo count($giohang); ?> sản phẩm)</h3>

                        <?php foreach ($giohang as $dong) : ?>
                            <div class="gs-checkout__item">
                                <img src="../assets/images/products/<?php echo htmlspecialchars($dong['hinhanh']); ?>"
                                    alt="<?php echo htmlspecialchars($dong['tensanpham']); ?>" onerror="gsOnErrorImg(this)">
                                <div>
                                    <span class="gs-checkout__item-name"><?php echo htmlspecialchars($dong['tensanpham']); ?></span>
                                    <span class="gs-checkout__item-meta">
                                        <?php echo ((string) $dong['size'] !== '') ? ('Size ' . htmlspecialchars($dong['size'])) : 'Freesize'; ?>
                                        × <?php echo (int) $dong['soluong']; ?>
                                    </span>
                                </div>
                                <span class="gs-checkout__item-price"><?php echo gsTien((float) $dong['giaban'] * (int) $dong['soluong']); ?></span>
                            </div>
                        <?php endforeach; ?>

                        <div class="gs-cart-summary__line">
                            <span>Tạm tính</span>
                            <strong><?php echo gsTien($gsTongtien['tamtinh']); ?></strong>
                        </div>
                        <div class="gs-cart-summary__line">
                            <span>Giảm giá<?php echo $gsTongtien['voucher'] ? ' (' . htmlspecialchars($gsTongtien['voucher']['ma']) . ')' : ''; ?></span>
                            <strong class="<?php echo ($gsTongtien['giamgia'] > 0) ? 'text-danger' : ''; ?>">
                                <?php echo ($gsTongtien['giamgia'] > 0 ? '-' : '') . gsTien($gsTongtien['giamgia']); ?>
                            </strong>
                        </div>
                        <div class="gs-cart-summary__line">
                            <span>Phí vận chuyển</span>
                            <strong><?php echo ($gsTongtien['phivanchuyen'] > 0) ? gsTien($gsTongtien['phivanchuyen']) : 'Miễn phí'; ?></strong>
                        </div>
                        <div class="gs-cart-summary__line gs-cart-summary__line--total">
                            <span>Tổng cộng</span>
                            <strong><?php echo gsTien($gsTongtien['tongtien']); ?></strong>
                        </div>

                        <button type="submit" class="btn-gs btn-gs--dark w-100">
                            <i class="fa-solid fa-circle-check"></i> Hoàn tất đặt hàng
                        </button>
                        <a class="gs-cart__link mt-3" href="index.php?action=giohang"><i class="fa-solid fa-arrow-left-long"></i> Quay lại giỏ hàng</a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</section>

<?php include(__DIR__ . '/inc/footer.php'); ?>
