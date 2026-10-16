<?php
/* =====================================================================
   TRANG CHI TIẾT SẢN PHẨM (Module 3)
   Nạp từ router: index.php?action=chitiet&id=...
   ===================================================================== */
if (!isset($gsModule36)) {
    $_REQUEST['action'] = 'chitiet';
    require __DIR__ . '/index.php';
    return;
}
$gsTieude = $sanpham['tensanpham'];

$giagoc = (float) $sanpham['giagoc'];
$giaban = (float) $sanpham['giaban'];
$phantramgiam = 0;
if ($giagoc > $giaban && $giagoc > 0) {
    $phantramgiam = (int) round(100 - ($giaban * 100 / $giagoc));
}
$conhang = ((int) $sanpham['soluongton'] > 0);
$tenthuonghieu = !empty($sanpham['tenthuonghieu']) ? $sanpham['tenthuonghieu'] : 'Đang cập nhật';
$danhmucCha = gsTenLoai($sanpham['loai']);
$mucdanhmuc = array('quanao' => 'Đồ tập', 'phukien' => 'Phụ kiện', 'tpbs' => 'Thực phẩm bổ sung');
$mangsizes = isset($mangsizes) ? $mangsizes : $sp->tachsizethanhmmang($sanpham['sizes']);
include(__DIR__ . '/inc/head-client.php');
?>

<section class="gs-section gs-section--grey gs-page-head">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb gs-breadcrumb">
                <li class="breadcrumb-item"><a href="index.php">Trang chủ</a></li>
                <li class="breadcrumb-item"><a href="sanpham.php">Sản phẩm</a></li>
                <li class="breadcrumb-item">
                    <a href="sanpham.php?loai=<?php echo urlencode($sanpham['loai']); ?>"><?php echo htmlspecialchars($danhmucCha); ?></a>
                </li>
                <li class="breadcrumb-item active" aria-current="page"><?php echo htmlspecialchars($sanpham['tensanpham']); ?></li>
            </ol>
        </nav>
    </div>
</section>

<section class="gs-section">
    <div class="container">
        <div class="row g-5">

            <!-- ===== Hình ảnh ===== -->
            <div class="col-12 col-lg-5">
                <div class="gs-detail__media">
                    <img class="gs-detail__img" src="uploads/<?php echo htmlspecialchars($sanpham['hinhanh']); ?>"
                        alt="<?php echo htmlspecialchars($sanpham['tensanpham']); ?>" onerror="gsOnErrorImg(this)">
                    <?php if ($phantramgiam > 0) : ?>
                        <span class="badge-gs badge-gs--sale gs-detail__sale">-<?php echo $phantramgiam; ?>%</span>
                    <?php endif; ?>
                </div>
            </div>

            <!-- ===== Thông tin & chọn size ===== -->
            <div class="col-12 col-lg-7">
                <span class="gs-detail__cat"><?php echo htmlspecialchars($danhmucCha); ?></span>
                <h1 class="gs-detail__title"><?php echo htmlspecialchars($sanpham['tensanpham']); ?></h1>

                <div class="gs-detail__meta">
                    <span><i class="fa-solid fa-tag"></i> Thương hiệu: <strong><?php echo htmlspecialchars($tenthuonghieu); ?></strong></span>
                    <span><i class="fa-solid fa-fire-flame-curved"></i> Đã bán: <strong><?php echo (int) $sanpham['luotmua']; ?></strong></span>
                    <span><i class="fa-regular fa-eye"></i> Lượt xem: <strong><?php echo (int) $sanpham['luotxem'] + 1; ?></strong></span>
                </div>

                <div class="gs-detail__rating">
                    <?php for ($i = 1; $i <= 5; $i++) : ?>
                        <i class="fa-<?php echo ($i <= round($diemDanhgia['diem'])) ? 'solid' : 'regular'; ?> fa-star"></i>
                    <?php endfor; ?>
                    <span><?php echo number_format($diemDanhgia['diem'], 1, ',', '.'); ?>/5</span>
                    <span class="text-secondary">(<?php echo (int) $diemDanhgia['soluot']; ?> đánh giá)</span>
                </div>

                <div class="gs-detail__price">
                    <span class="gs-price"><?php echo gsTien($giaban); ?></span>
                    <?php if ($phantramgiam > 0) : ?>
                        <span class="gs-price--old"><?php echo gsTien($giagoc); ?></span>
                        <span class="badge-gs badge-gs--sale">Tiết kiệm <?php echo gsTien($giagoc - $giaban); ?></span>
                    <?php endif; ?>
                </div>

                <p class="gs-detail__tinhtrang">
                    <?php if ($conhang) : ?>
                        <span class="gs-stock gs-stock--con"><i class="fa-solid fa-circle-check"></i> Còn hàng (<?php echo (int) $sanpham['soluongton']; ?> sản phẩm)</span>
                    <?php else : ?>
                        <span class="gs-stock gs-stock--het"><i class="fa-solid fa-circle-xmark"></i> Hết hàng</span>
                    <?php endif; ?>
                </p>

                <!-- Form thêm vào giỏ hàng (chọn size + màu sắc) -->
                <form class="gs-form-cart gs-detail__form" method="post" action="index.php">
                    <input type="hidden" name="action" value="themvaogiohang">
                    <input type="hidden" name="sanpham_id" value="<?php echo (int) $sanpham['id']; ?>">

                    <?php if (count($mangsizes) > 0) : ?>
                        <div class="gs-detail__option">
                            <div class="gs-detail__option-head">
                                <span class="gs-detail__label">Chọn size</span>
                                <?php if ($sizegoiy !== '') : ?>
                                    <span class="gs-sizegoiy-note">
                                        <i class="fa-solid fa-wand-magic-sparkles"></i> Gợi ý theo chiều cao &amp; cân nặng của bạn: <strong><?php echo htmlspecialchars($sizegoiy); ?></strong>
                                    </span>
                                <?php endif; ?>
                            </div>
                            <div class="gs-size-options">
                                <?php foreach ($mangsizes as $size) : ?>
                                    <label class="gs-size-option<?php echo ($sizegoiy === $size) ? ' is-goiy' : ''; ?>">
                                        <input type="radio" name="size" value="<?php echo htmlspecialchars($size); ?>"
                                            <?php echo ($sizegoiy === $size) ? 'checked' : ''; ?> required>
                                        <span><?php echo htmlspecialchars($size); ?></span>
                                    </label>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php else : ?>
                        <input type="hidden" name="size" value="">
                        <p class="gs-detail__freesize"><i class="fa-solid fa-circle-info"></i> Sản phẩm này không phân biệt size (freesize).</p>
                    <?php endif; ?>

                    <?php if (!empty($sanpham['mausac'])) : ?>
                        <div class="gs-detail__option">
                            <span class="gs-detail__label">Màu sắc</span>
                            <div class="gs-detail__mausac">
                                <?php foreach (explode(',', $sanpham['mausac']) as $mau) : ?>
                                    <span class="gs-mau"><?php echo htmlspecialchars(trim($mau)); ?></span>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <div class="gs-detail__buy">
                        <div class="gs-qty">
                            <label class="gs-detail__label" for="gsSoLuong">Số lượng</label>
                            <input class="form-control" type="number" id="gsSoLuong" name="soluong" min="1"
                                max="<?php echo max(1, (int) $sanpham['soluongton']); ?>" value="1" <?php echo $conhang ? '' : 'disabled'; ?>>
                        </div>
                        <button type="submit" class="btn-gs btn-gs--dark gs-detail__addbtn" <?php echo $conhang ? '' : 'disabled'; ?>>
                            <i class="fa-solid fa-bag-shopping"></i> <?php echo $conhang ? 'Thêm vào giỏ hàng' : 'Tạm hết hàng'; ?>
                        </button>
                    </div>
                </form>

                <div class="gs-detail__policies">
                    <span><i class="fa-solid fa-truck-fast"></i> Miễn phí vận chuyển cho đơn từ 500.000đ</span>
                    <span><i class="fa-solid fa-rotate-left"></i> Đổi trả trong 7 ngày</span>
                    <span><i class="fa-solid fa-shield-halved"></i> Hàng chính hãng 100%</span>
                </div>
            </div>
        </div>

        <!-- ===== Thông tin chi tiết theo loại danh mục ===== -->
        <div class="gs-detail__info">
            <ul class="nav nav-tabs gs-tabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#gsTabMota" type="button" role="tab">
                        Mô tả &amp; hướng dẫn sử dụng
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" data-bs-toggle="tab" data-bs-target="#gsTabThongso" type="button" role="tab">
                        <?php echo ($sanpham['loai'] === 'tpbs') ? 'Thành phần dinh dưỡng' : 'Thông số sản phẩm'; ?>
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" data-bs-toggle="tab" data-bs-target="#gsTabDanhgia" type="button" role="tab">
                        Đánh giá (<?php echo (int) $diemDanhgia['soluot']; ?>)
                    </button>
                </li>
            </ul>

            <div class="tab-content gs-tab-content">
                <div class="tab-pane fade show active" id="gsTabMota" role="tabpanel">
                    <p class="gs-detail__mota">
                        <?php echo nl2br(htmlspecialchars($sanpham['mota'] ? $sanpham['mota'] : 'Thông tin mô tả đang được cập nhật.')); ?>
                    </p>
                </div>

                <div class="tab-pane fade" id="gsTabThongso" role="tabpanel">
                    <table class="table gs-table">
                        <tbody>
                            <tr>
                                <th>Danh mục</th>
                                <td><?php echo htmlspecialchars($sanpham['tendanhmuc']); ?> (<?php echo htmlspecialchars($mucdanhmuc[$sanpham['loai']]); ?>)</td>
                            </tr>
                            <tr>
                                <th>Thương hiệu</th>
                                <td><?php echo htmlspecialchars($tenthuonghieu); ?></td>
                            </tr>
                            <tr>
                                <th>Đối tượng</th>
                                <td><?php echo ($sanpham['doituong'] === 'nu') ? 'Nữ' : (($sanpham['doituong'] === 'nam') ? 'Nam' : 'Unisex (Nam & Nữ)'); ?></td>
                            </tr>
                            <?php if (!empty($sanpham['chatlieu'])) : ?>
                                <tr>
                                    <th>Chất liệu</th>
                                    <td><?php echo htmlspecialchars($sanpham['chatlieu']); ?></td>
                                </tr>
                            <?php endif; ?>
                            <?php if (!empty($sanpham['thanhphan'])) : ?>
                                <tr>
                                    <th>Thành phần dinh dưỡng</th>
                                    <td><?php echo htmlspecialchars($sanpham['thanhphan']); ?></td>
                                </tr>
                            <?php endif; ?>
                            <?php if (!empty($sanpham['hansudung'])) : ?>
                                <tr>
                                    <th>Hạn sử dụng</th>
                                    <td><?php echo htmlspecialchars(date('d/m/Y', strtotime($sanpham['hansudung']))); ?></td>
                                </tr>
                            <?php endif; ?>
                            <tr>
                                <th>Size đang có</th>
                                <td><?php echo (count($mangsizes) > 0) ? htmlspecialchars(implode(', ', $mangsizes)) : 'Freesize'; ?></td>
                            </tr>
                            <tr>
                                <th>Phù hợp mục tiêu</th>
                                <td>
                                    <?php
                                    $tenmuctieu = array();
                                    foreach (explode(',', (string) $sanpham['muctieu']) as $mt) {
                                        if (trim($mt) !== '') {
                                            $tenmuctieu[] = gsTenMuctieu(trim($mt));
                                        }
                                    }
                                    echo htmlspecialchars(implode(' · ', $tenmuctieu));
                                    ?>
                                </td>
                            </tr>
                            <tr>
                                <th>Tình trạng kho</th>
                                <td><?php echo $conhang ? ((int) $sanpham['soluongton'] . ' sản phẩm') : 'Hết hàng'; ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="tab-pane fade" id="gsTabDanhgia" role="tabpanel">
                    <?php if (count($dsDanhgia) === 0) : ?>
                        <p class="text-secondary">Chưa có đánh giá nào cho sản phẩm này.</p>
                    <?php else : ?>
                        <?php foreach ($dsDanhgia as $dg) : ?>
                            <div class="gs-review">
                                <div class="gs-review__head">
                                    <strong><?php echo htmlspecialchars($dg['hoten']); ?></strong>
                                    <span class="gs-review__sao">
                                        <?php for ($i = 1; $i <= 5; $i++) : ?>
                                            <i class="fa-<?php echo ($i <= (int) $dg['sao']) ? 'solid' : 'regular'; ?> fa-star"></i>
                                        <?php endfor; ?>
                                    </span>
                                    <span class="text-secondary small"><?php echo htmlspecialchars(date('d/m/Y', strtotime($dg['ngay']))); ?></span>
                                </div>
                                <p class="gs-review__noidung"><?php echo htmlspecialchars($dg['noidung']); ?></p>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>

<?php
/* Khối gợi ý sản phẩm/combo theo chỉ số cơ thể (chỉ hiện cho khách đã đăng nhập) */
$gsGoiyTieude = 'Sản phẩm & combo gợi ý theo chỉ số cơ thể của bạn';
include(__DIR__ . '/inc/khoi-goiy.php');
?>

<?php if (count($sanphamtuongtu) > 0) : ?>
    <section class="gs-section gs-section--grey">
        <div class="container">
            <div class="gs-section__head">
                <div>
                    <h2 class="gs-section__title">Sản phẩm cùng danh mục</h2>
                </div>
                <a class="gs-section__link" href="sanpham.php?loai=<?php echo urlencode($sanpham['loai']); ?>">
                    Xem tất cả <i class="fa-solid fa-arrow-right-long"></i>
                </a>
            </div>
            <div class="row g-4">
                <?php
                $gsSizeGoiy = ($gsGoiy && $gsGoiy['sizegoiy']) ? $gsGoiy['sizegoiy']['size'] : '';
                foreach ($sanphamtuongtu as $card) :
                    ?>
                    <?php include(__DIR__ . '/inc/card-sanpham.php'); ?>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
<?php endif; ?>

<?php include(__DIR__ . '/inc/footer.php'); ?>
