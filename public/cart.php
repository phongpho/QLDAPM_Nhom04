<?php
/* =====================================================================
   TRANG GIỎ HÀNG (Module 6)
   Nạp từ router: index.php?action=giohang (hoặc file shim giohang.php)
   ===================================================================== */
if (!isset($gsModule36)) {
    $_REQUEST['action'] = 'giohang';
    require __DIR__ . '/index.php';
    return;
}
$gsTieude = 'Giỏ hàng của bạn';
include(__DIR__ . '/inc/head-client.php');
?>

<section class="gs-section gs-section--grey gs-page-head">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb gs-breadcrumb">
                <li class="breadcrumb-item"><a href="index.php">Trang chủ</a></li>
                <li class="breadcrumb-item active" aria-current="page">Giỏ hàng</li>
            </ol>
        </nav>
        <h1 class="gs-page-head__title">Giỏ hàng của bạn</h1>
        <p class="gs-page-head__sub">
            <?php if ($gh->dadangnhap()) : ?>
                Giỏ hàng được lưu vào tài khoản của bạn, xem lại trên mọi thiết bị.
            <?php else : ?>
                Bạn đang mua với tư cách khách vãng lai - giỏ hàng sẽ tự chuyển vào tài khoản khi bạn đăng nhập.
            <?php endif; ?>
        </p>
    </div>
</section>

<section class="gs-section">
    <div class="container">
        <?php if (count($giohang) === 0) : ?>
            <div class="gs-empty">
                <i class="fa-solid fa-bag-shopping"></i>
                <h3>Giỏ hàng đang trống</h3>
                <p>Hãy chọn cho mình sản phẩm phù hợp với mục tiêu tập luyện nhé!</p>
                <a class="btn-gs btn-gs--dark" href="sanpham.php">Tiếp tục mua sắm</a>
            </div>
        <?php else : ?>
            <div class="row g-4">
                <!-- ===== Danh sách sản phẩm trong giỏ ===== -->
                <div class="col-12 col-lg-8">
                    <div class="gs-cart">
                        <?php foreach ($giohang as $dong) : ?>
                            <?php
                            $giagoc = (float) $dong['giagoc'];
                            $giaban = (float) $dong['giaban'];
                            $thanhtien = $giaban * (int) $dong['soluong'];
                            ?>
                            <div class="gs-cart__row">
                                <a class="gs-cart__media" href="chitiet.php?id=<?php echo (int) $dong['sanpham_id']; ?>">
                                    <img src="uploads/<?php echo htmlspecialchars($dong['hinhanh']); ?>"
                                        alt="<?php echo htmlspecialchars($dong['tensanpham']); ?>" onerror="gsOnErrorImg(this)">
                                </a>

                                <div class="gs-cart__info">
                                    <a class="gs-cart__name" href="chitiet.php?id=<?php echo (int) $dong['sanpham_id']; ?>">
                                        <?php echo htmlspecialchars($dong['tensanpham']); ?>
                                    </a>
                                    <div class="gs-cart__meta">
                                        <span><i class="fa-solid fa-tag"></i> <?php echo htmlspecialchars($dong['tendanhmuc']); ?></span>
                                        <?php if ((string) $dong['size'] !== '') : ?>
                                            <span class="gs-cart__size">Size: <strong><?php echo htmlspecialchars($dong['size']); ?></strong></span>
                                        <?php else : ?>
                                            <span class="gs-cart__size">Freesize</span>
                                        <?php endif; ?>
                                        <?php if ((int) $dong['soluongton'] <= 0) : ?>
                                            <span class="text-danger">Tạm hết hàng</span>
                                        <?php endif; ?>
                                    </div>
                                    <div class="gs-cart__price">
                                        <span class="gs-price"><?php echo gsTien($giaban); ?></span>
                                        <?php if ($giagoc > $giaban) : ?>
                                            <span class="gs-price--old"><?php echo gsTien($giagoc); ?></span>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <div class="gs-cart__qty">
                                    <form method="post" action="index.php">
                                        <input type="hidden" name="action" value="capnhatgiohang">
                                        <input type="hidden" name="sanpham_id" value="<?php echo (int) $dong['sanpham_id']; ?>">
                                        <input type="hidden" name="size" value="<?php echo htmlspecialchars($dong['size']); ?>">
                                        <div class="gs-qty-group">
                                            <button type="submit" name="giam" value="1" class="gs-qty-btn" aria-label="Giảm số lượng">
                                                <i class="fa-solid fa-minus"></i>
                                            </button>
                                            <input class="gs-qty-input" type="number" name="soluong" min="1"
                                                max="<?php echo max(1, (int) $dong['soluongton']); ?>"
                                                value="<?php echo (int) $dong['soluong']; ?>" aria-label="Số lượng">
                                            <button type="submit" name="tang" value="1" class="gs-qty-btn" aria-label="Tăng số lượng">
                                                <i class="fa-solid fa-plus"></i>
                                            </button>
                                        </div>
                                        <button type="submit" class="gs-cart__update">Cập nhật</button>
                                    </form>
                                </div>

                                <div class="gs-cart__total">
                                    <span class="gs-cart__total-label">Thành tiền</span>
                                    <span class="gs-cart__total-value"><?php echo gsTien($thanhtien); ?></span>
                                </div>

                                <form class="gs-cart__remove" method="post" action="index.php">
                                    <input type="hidden" name="action" value="xoagiohang">
                                    <input type="hidden" name="sanpham_id" value="<?php echo (int) $dong['sanpham_id']; ?>">
                                    <input type="hidden" name="size" value="<?php echo htmlspecialchars($dong['size']); ?>">
                                    <button type="submit" aria-label="Xóa sản phẩm khỏi giỏ">
                                        <i class="fa-regular fa-trash-can"></i>
                                    </button>
                                </form>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <div class="gs-cart__actions">
                        <a class="gs-cart__link" href="sanpham.php"><i class="fa-solid fa-arrow-left-long"></i> Tiếp tục mua sắm</a>
                        <form method="post" action="index.php" onsubmit="return confirm('Bạn chắc chắn muốn xóa toàn bộ giỏ hàng?');">
                            <input type="hidden" name="action" value="xoatoangiohang">
                            <button type="submit" class="gs-cart__link gs-cart__link--danger">
                                <i class="fa-regular fa-trash-can"></i> Xóa toàn bộ giỏ hàng
                            </button>
                        </form>
                    </div>
                </div>

                <!-- ===== Khung tổng kết đơn hàng ===== -->
                <div class="col-12 col-lg-4">
                    <div class="gs-cart-summary">
                        <h3 class="gs-cart-summary__title">Tổng kết đơn hàng</h3>

                        <form method="post" action="index.php" class="gs-voucher-form">
                            <input type="hidden" name="action" value="apdungvoucher">
                            <input type="hidden" name="quaylai" value="giohang">
                            <label class="gs-form-label" for="gsMaVoucher">Mã giảm giá</label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="gsMaVoucher" name="ma" placeholder="VD: GYM10"
                                    value="<?php echo $gsTongtien['voucher'] ? htmlspecialchars($gsTongtien['voucher']['ma']) : ''; ?>">
                                <button class="btn btn-dark" type="submit">Áp dụng</button>
                            </div>
                        </form>

                        <?php if ($gsTongtien['voucher']) : ?>
                            <div class="gs-voucher-applied">
                                <span><i class="fa-solid fa-ticket"></i> Đang dùng mã <strong><?php echo htmlspecialchars($gsTongtien['voucher']['ma']); ?></strong></span>
                                <form method="post" action="index.php">
                                    <input type="hidden" name="action" value="boapdungvoucher">
                                    <button type="submit" class="gs-voucher-applied__xoa">Bỏ mã</button>
                                </form>
                            </div>
                        <?php elseif (count($dsVoucher) > 0) : ?>
                            <div class="gs-voucher-suggest">
                                <span class="gs-voucher-suggest__title">Mã đang có hiệu lực</span>
                                <?php foreach ($dsVoucher as $v) : ?>
                                    <form method="post" action="index.php" class="gs-voucher-suggest__item">
                                        <input type="hidden" name="action" value="apdungvoucher">
                                        <input type="hidden" name="quaylai" value="giohang">
                                        <input type="hidden" name="ma" value="<?php echo htmlspecialchars($v['ma']); ?>">
                                        <button type="submit">
                                            <strong><?php echo htmlspecialchars($v['ma']); ?></strong>
                                            <small><?php echo htmlspecialchars($v['mota']); ?></small>
                                        </button>
                                    </form>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>

                        <div class="gs-cart-summary__line">
                            <span>Tạm tính</span>
                            <strong><?php echo gsTien($gsTongtien['tamtinh']); ?></strong>
                        </div>
                        <div class="gs-cart-summary__line">
                            <span>Giảm giá</span>
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

                        <a class="btn-gs btn-gs--dark w-100" href="index.php?action=thanhtoan">
                            <i class="fa-solid fa-credit-card"></i> Tiến hành thanh toán
                        </a>
                        <?php if (!$gh->dadangnhap()) : ?>
                            <p class="gs-cart-summary__note">
                                Bạn cần <a href="../admin/ktnguoidung/index.php?action=dangnhap">đăng nhập</a> để đặt hàng.
                            </p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php if ($gh->dadangnhap() && count($goiyMuakem) > 0) : ?>
    <!-- Gợi ý mua kèm dành riêng cho mục tiêu của khách -->
    <section class="gs-section gs-section--grey">
        <div class="container">
            <div class="gs-section__head">
                <div>
                    <h2 class="gs-section__title">Gợi ý mua kèm dành riêng cho mục tiêu của bạn</h2>
                    <span class="gs-section__sub">
                        <?php if ($gsGoiy) : ?>
                            Các sản phẩm phù hợp mục tiêu <?php echo htmlspecialchars(gsTenMuctieu($gsGoiy['muctieu'])); ?> mà bạn chưa có trong giỏ.
                        <?php else : ?>
                            Nhập <a href="goy.php">chỉ số cơ thể</a> để nhận gợi ý sát với thể trạng của bạn hơn.
                        <?php endif; ?>
                    </span>
                </div>
            </div>
            <div class="row g-4">
                <?php
                $gsSizeGoiy = ($gsGoiy && $gsGoiy['sizegoiy']) ? $gsGoiy['sizegoiy']['size'] : '';
                foreach ($goiyMuakem as $card) :
                    ?>
                    <?php include(__DIR__ . '/inc/card-sanpham.php'); ?>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
<?php endif; ?>

<?php include(__DIR__ . '/inc/footer.php'); ?>
