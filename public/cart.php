<?php
if (!isset($gsModule36)) {
    $_REQUEST['action'] = 'giohang';
    require __DIR__ . '/index.php';
    return;
}
$gsTieude = 'Giỏ hàng của bạn';
include(__DIR__ . '/inc/head-client.php');

$gsFreeshipMoc = 500000;
$gsTamtinhHientai = (float) $gsTongtien['tamtinh'];
$gsConThieuFreeship = max(0, $gsFreeshipMoc - $gsTamtinhHientai);
$gsPhantramFreeship = ($gsTamtinhHientai >= $gsFreeshipMoc) ? 100 : min(100, round(($gsTamtinhHientai / $gsFreeshipMoc) * 100));
?>

<section class="gs-section gs-section--grey gs-page-head">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb gs-breadcrumb">
                <li class="breadcrumb-item"><a href="index.php">Trang chủ</a></li>
                <li class="breadcrumb-item active" aria-current="page">Giỏ hàng</li>
            </ol>
        </nav>
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
            <div>
                <h1 class="gs-page-head__title mb-1">
                    Giỏ hàng của bạn
                    <?php if (count($giohang) > 0) : ?>
                        <span class="gs-cart-count-badge"><?php echo (int) $gh->dem(); ?> món</span>
                    <?php endif; ?>
                </h1>
                <p class="gs-page-head__sub mb-0">
                    <?php if ($gh->dadangnhap()) : ?>
                        Giỏ hàng được đồng bộ tự động với tài khoản <strong><?php echo htmlspecialchars($gsND['hoten'] ?? ''); ?></strong>.
                    <?php else : ?>
                        Bạn đang mua sắm với tư cách khách. Giỏ hàng sẽ được lưu khi bạn đăng nhập.
                    <?php endif; ?>
                </p>
            </div>
            <?php if (count($giohang) > 0) : ?>
                <a class="btn-gs btn-gs--outline btn-gs--sm" href="index.php?action=sanpham">
                    Mua thêm sản phẩm
                </a>
            <?php endif; ?>
        </div>
    </div>
</section>

<section class="gs-section">
    <div class="container">
        <?php if (count($giohang) === 0) : ?>
            <div class="gs-empty gs-cart-empty-box">
                <h3 class="mt-2 mb-2">Giỏ hàng của bạn đang trống</h3>
                <p class="text-muted mb-4">Hãy lướt qua bộ sưu tập đồ tập gym, phụ kiện và dinh dưỡng thể thao để chọn món đồ ưng ý nhé!</p>
                <div class="d-flex flex-wrap justify-content-center gap-3">
                    <a class="btn-gs btn-gs--primary" href="index.php?action=sanpham">
                        Khám phá sản phẩm
                    </a>
                    <?php if ($gh->dadangnhap()) : ?>
                        <a class="btn-gs btn-gs--outline" href="index.php?action=goy">
                            Gợi ý theo chỉ số
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        <?php else : ?>
            <div class="row g-4">
                <div class="col-12 col-lg-8">
                    <div class="gs-cart">
                        <div class="gs-cart__header d-none d-md-flex">
                            <span class="gs-cart__header-col gs-cart__header-col--product">Sản phẩm</span>
                            <span class="gs-cart__header-col gs-cart__header-col--price">Đơn giá</span>
                            <span class="gs-cart__header-col gs-cart__header-col--qty">Số lượng</span>
                            <span class="gs-cart__header-col gs-cart__header-col--total">Thành tiền</span>
                            <span class="gs-cart__header-col gs-cart__header-col--action"></span>
                        </div>

                        <?php foreach ($giohang as $dong) : ?>
                            <?php
                            $giagoc = (float) $dong['giagoc'];
                            $giaban = (float) $dong['giaban'];
                            $thanhtien = $giaban * (int) $dong['soluong'];
                            $spId = (int) $dong['sanpham_id'];
                            $sizeVal = (string) $dong['size'];
                            $soluongTon = max(1, (int) $dong['soluongton']);
                            ?>
                            <div class="gs-cart__row">
                                <a class="gs-cart__media" href="index.php?action=chitiet&id=<?php echo $spId; ?>">
                                    <img src="../assets/images/products/<?php echo htmlspecialchars($dong['hinhanh']); ?>"
                                        alt="<?php echo htmlspecialchars($dong['tensanpham']); ?>" onerror="gsOnErrorImg(this)">
                                </a>

                                <div class="gs-cart__info">
                                    <div class="gs-cart__meta mb-1">
                                        <span class="gs-badge gs-badge--cat">
                                            <?php echo htmlspecialchars($dong['tendanhmuc']); ?>
                                        </span>
                                        <?php if ($sizeVal !== '') : ?>
                                            <span class="gs-badge gs-badge--size">Size: <strong><?php echo htmlspecialchars($sizeVal); ?></strong></span>
                                        <?php else : ?>
                                            <span class="gs-badge gs-badge--size">Freesize</span>
                                        <?php endif; ?>
                                    </div>
                                    <a class="gs-cart__name" href="index.php?action=chitiet&id=<?php echo $spId; ?>">
                                        <?php echo htmlspecialchars($dong['tensanpham']); ?>
                                    </a>
                                    <?php if ((int) $dong['soluongton'] <= 0) : ?>
                                        <div class="text-danger small mt-1">Tạm hết hàng</div>
                                    <?php endif; ?>

                                    <div class="gs-cart__mobile-meta d-md-none mt-2">
                                        <span class="gs-price"><?php echo gsTien($giaban); ?></span>
                                        <?php if ($giagoc > $giaban) : ?>
                                            <span class="gs-price--old ms-1"><?php echo gsTien($giagoc); ?></span>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <div class="gs-cart__price-cell d-none d-md-flex flex-column justify-content-center">
                                    <span class="gs-price"><?php echo gsTien($giaban); ?></span>
                                    <?php if ($giagoc > $giaban) : ?>
                                        <span class="gs-price--old"><?php echo gsTien($giagoc); ?></span>
                                    <?php endif; ?>
                                </div>

                                <div class="gs-cart__qty">
                                    <form method="post" action="index.php" class="gs-qty-form">
                                        <input type="hidden" name="action" value="capnhatgiohang">
                                        <input type="hidden" name="sanpham_id" value="<?php echo $spId; ?>">
                                        <input type="hidden" name="size" value="<?php echo htmlspecialchars($sizeVal); ?>">
                                        <div class="gs-qty-group">
                                            <button type="submit" name="giam" value="1" class="gs-qty-btn" aria-label="Giảm số lượng">
                                                -
                                            </button>
                                            <input class="gs-qty-input" type="number" name="soluong" min="1"
                                                max="<?php echo $soluongTon; ?>"
                                                value="<?php echo (int) $dong['soluong']; ?>"
                                                onchange="this.form.submit()"
                                                aria-label="Số lượng">
                                            <button type="submit" name="tang" value="1" class="gs-qty-btn" aria-label="Tăng số lượng">
                                                +
                                            </button>
                                        </div>
                                    </form>
                                </div>

                                <div class="gs-cart__total">
                                    <span class="gs-cart__total-label d-md-none">Thành tiền:</span>
                                    <span class="gs-cart__total-value"><?php echo gsTien($thanhtien); ?></span>
                                </div>

                                <form class="gs-cart__remove" method="post" action="index.php" onsubmit="return confirm('Xóa sản phẩm này khỏi giỏ hàng?');">
                                    <input type="hidden" name="action" value="xoagiohang">
                                    <input type="hidden" name="sanpham_id" value="<?php echo $spId; ?>">
                                    <input type="hidden" name="size" value="<?php echo htmlspecialchars($sizeVal); ?>">
                                    <button type="submit" class="gs-cart__remove-btn" title="Xóa sản phẩm" aria-label="Xóa sản phẩm">
                                        &times;
                                    </button>
                                </form>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <div class="gs-cart__actions mt-3">
                        <a class="btn-gs btn-gs--outline btn-gs--sm" href="index.php?action=sanpham">
                            Tiếp tục mua sắm
                        </a>
                        <form method="post" action="index.php" onsubmit="return confirm('Bạn chắc chắn muốn xóa toàn bộ sản phẩm trong giỏ hàng?');">
                            <input type="hidden" name="action" value="xoatoangiohang">
                            <button type="submit" class="btn-gs btn-gs--subtle-danger btn-gs--sm">
                                Xóa toàn bộ giỏ hàng
                            </button>
                        </form>
                    </div>
                </div>

                <div class="col-12 col-lg-4">
                    <div class="gs-cart-summary">
                        <div class="gs-cart-summary__head">
                            <h3 class="gs-cart-summary__title mb-0">Tổng kết đơn hàng</h3>
                        </div>

                        <div class="gs-freeship-banner mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-1 small">
                                <?php if ($gsConThieuFreeship > 0) : ?>
                                    <span class="text-white-50">
                                        Mua thêm <strong class="text-warning"><?php echo gsTien($gsConThieuFreeship); ?></strong> để được <strong>FREESHIP</strong>
                                    </span>
                                <?php else : ?>
                                    <span class="text-success fw-bold">
                                        Đơn hàng đã đủ điều kiện FREESHIP!
                                    </span>
                                <?php endif; ?>
                                <span class="fw-bold <?php echo ($gsPhantramFreeship >= 100) ? 'text-success' : 'text-warning'; ?>">
                                    <?php echo $gsPhantramFreeship; ?>%
                                </span>
                            </div>
                            <div class="progress gs-freeship-progress">
                                <div class="progress-bar <?php echo ($gsPhantramFreeship >= 100) ? 'bg-success' : 'bg-warning'; ?>"
                                    role="progressbar"
                                    style="width: <?php echo $gsPhantramFreeship; ?>%"
                                    aria-valuenow="<?php echo $gsPhantramFreeship; ?>"
                                    aria-valuemin="0"
                                    aria-valuemax="100">
                                </div>
                            </div>
                        </div>

                        <form method="post" action="index.php" class="gs-voucher-form mb-3">
                            <input type="hidden" name="action" value="apdungvoucher">
                            <input type="hidden" name="quaylai" value="giohang">
                            <label class="gs-form-label mb-2" for="gsMaVoucher">Mã giảm giá</label>
                            <div class="input-group gs-voucher-input-group">
                                <input type="text" class="form-control" id="gsMaVoucher" name="ma"
                                    placeholder="Nhập mã giảm giá..."
                                    value="<?php echo $gsTongtien['voucher'] ? htmlspecialchars($gsTongtien['voucher']['ma']) : ''; ?>">
                                <button class="btn-gs btn-gs--primary" type="submit">Áp dụng</button>
                            </div>
                        </form>

                        <?php if ($gsTongtien['voucher']) : ?>
                            <div class="gs-voucher-applied-box mb-3">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="gs-voucher-tag">
                                            <?php echo htmlspecialchars($gsTongtien['voucher']['ma']); ?>
                                        </span>
                                        <span class="small text-success fw-bold">
                                            -<?php echo gsTien($gsTongtien['giamgia']); ?>
                                        </span>
                                    </div>
                                    <form method="post" action="index.php" class="m-0">
                                        <input type="hidden" name="action" value="boapdungvoucher">
                                        <button type="submit" class="gs-voucher-remove-link" title="Bỏ mã giảm giá">
                                            Gỡ
                                        </button>
                                    </form>
                                </div>
                            </div>
                        <?php elseif (count($dsVoucher) > 0) : ?>
                            <div class="gs-voucher-suggest-box mb-3">
                                <div class="gs-voucher-suggest-title mb-2">Mã ưu đãi có thể dùng:</div>
                                <div class="gs-voucher-suggest-list">
                                    <?php foreach ($dsVoucher as $v) : ?>
                                        <form method="post" action="index.php" class="gs-voucher-ticket">
                                            <input type="hidden" name="action" value="apdungvoucher">
                                            <input type="hidden" name="quaylai" value="giohang">
                                            <input type="hidden" name="ma" value="<?php echo htmlspecialchars($v['ma']); ?>">
                                            <div class="gs-voucher-ticket__left">
                                                <span class="gs-voucher-ticket__badge"><?php echo htmlspecialchars($v['ma']); ?></span>
                                                <div class="gs-voucher-ticket__desc"><?php echo htmlspecialchars($v['mota']); ?></div>
                                            </div>
                                            <button type="submit" class="btn-gs btn-gs--outline btn-gs--xs gs-voucher-ticket__btn">
                                                Dùng mã
                                            </button>
                                        </form>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        <?php endif; ?>

                        <div class="gs-cart-summary__breakdown">
                            <div class="gs-cart-summary__line">
                                <span>Tạm tính</span>
                                <strong><?php echo gsTien($gsTongtien['tamtinh']); ?></strong>
                            </div>
                            <div class="gs-cart-summary__line">
                                <span>Giảm giá voucher</span>
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

                        <a class="btn-gs btn-gs--primary btn-gs--lg w-100 gs-btn-checkout mt-3" href="index.php?action=thanhtoan">
                            TIẾN HÀNH THANH TOÁN
                        </a>

                        <?php if (!$gh->dadangnhap()) : ?>
                            <div class="gs-cart-login-hint mt-2 text-center">
                                <span class="small text-white-50">
                                    Bạn cần <a href="index.php?action=dangnhap" class="text-warning text-decoration-underline">đăng nhập</a> để thanh toán.
                                </span>
                            </div>
                        <?php endif; ?>

                        <div class="gs-cart-guarantee-line mt-3 pt-3 border-top border-secondary border-opacity-25 text-center small text-white-50">
                            Chính hãng 100% · Đổi trả 7 ngày · Giao nhanh 2h
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php if ($gh->dadangnhap() && count($goiyMuakem) > 0) : ?>
    <section class="gs-section gs-section--grey">
        <div class="container">
            <div class="gs-section__head">
                <div>
                    <h2 class="gs-section__title">Gợi ý mua kèm dành riêng cho bạn</h2>
                    <span class="gs-section__sub">
                        <?php if ($gsGoiy) : ?>
                            Sản phẩm tối ưu cho mục tiêu <?php echo htmlspecialchars(gsTenMuctieu($gsGoiy['muctieu'])); ?> mà bạn chưa có trong giỏ hàng.
                        <?php else : ?>
                            Nhập <a href="index.php?action=goy" class="text-warning text-decoration-underline">chỉ số cơ thể</a> để nhận gợi ý chuẩn xác theo thể trạng của bạn.
                        <?php endif; ?>
                    </span>
                </div>
            </div>
            <div class="row g-4">
                <?php
                $gsSizeGoiy = ($gsGoiy && $gsGoiy['sizegoiy']) ? $gsGoiy['sizegoiy']['size'] : '';
                foreach ($goiyMuakem as $card) :
                    ?>
                    <?php include(__DIR__ . '/inc/product-card.php'); ?>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
<?php endif; ?>

<?php include(__DIR__ . '/inc/footer.php'); ?>
