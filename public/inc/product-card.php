<?php
if (!isset($gsSizeGoiy)) {
    $gsSizeGoiy = '';
}

if (!isset($gsModule36)) {
    header('Location: ../index.php');
    exit();
}

$giagoc = (float) $card['giagoc'];
$giaban = (float) $card['giaban'];
$phantramgiam = 0;
if ($giagoc > $giaban && $giagoc > 0) {
    $phantramgiam = (int) round(100 - ($giaban * 100 / $giagoc));
}
$mangsizes = $sp->tachsizethanhmmang($card['sizes']);
$conhang = ((int) $card['soluongton'] > 0);
$tendanhmuc = isset($card['tendanhmuc']) ? $card['tendanhmuc'] : (isset($card['loai']) ? gsTenLoai($card['loai']) : '');
$hinhanh = isset($card['hinhanh']) ? trim((string) $card['hinhanh']) : '';
$duongdananh = ($hinhanh !== '') ? '../assets/images/products/' . $hinhanh : '';
$hinhanhPhu = isset($card['hinhanh_phu']) ? trim((string) $card['hinhanh_phu']) : '';
$duongdananhPhu = ($hinhanhPhu !== '') ? '../assets/images/products/' . $hinhanhPhu : '';
$danhSachMauCard = !empty($card['mausac']) ? array_slice(explode(',', (string)$card['mausac']), 0, 4) : array();
?>
<div class="col-6 col-md-4 col-lg-3">
    <div class="gs-product-card" data-id="<?php echo (int) $card['id']; ?>"
        data-name="<?php echo htmlspecialchars($card['tensanpham']); ?>">
        <div class="gs-product-card__media">
            <a class="gs-product-card__link" href="index.php?action=chitiet&id=<?php echo (int) $card['id']; ?>">
                <img class="gs-product-card__img" src="<?php echo htmlspecialchars($duongdananh); ?>"
                    alt="<?php echo htmlspecialchars($card['tensanpham']); ?>" loading="lazy" onerror="gsOnErrorImg(this)">
                <?php if ($duongdananhPhu !== '') : ?>
                    <img class="gs-product-card__img--secondary" src="<?php echo htmlspecialchars($duongdananhPhu); ?>"
                        alt="<?php echo htmlspecialchars($card['tensanpham']); ?>" loading="lazy" onerror="this.style.display='none'">
                <?php endif; ?>
                <span class="gs-product-card__badges">
                    <?php if ($phantramgiam > 0) : ?>
                        <span class="badge-gs badge-gs--sale">-<?php echo $phantramgiam; ?>%</span>
                    <?php endif; ?>
                    <?php if ((int) $card['noibat'] === 1) : ?>
                        <span class="badge-gs badge-gs--new">Nổi bật</span>
                    <?php endif; ?>
                    <?php if (!$conhang) : ?>
                        <span class="badge-gs badge-gs--hot">Hết hàng</span>
                    <?php endif; ?>
                </span>
            </a>

            <?php 
            $daThichCard = in_array((int)$card['id'], $dsIdYeuthich ?? array(), true);
            $daSosanhCard = in_array((int)$card['id'], $_SESSION['gs_sosanh'] ?? array(), true);
            ?>
            <div class="position-absolute top-0 end-0 m-2 d-flex flex-column gap-2" style="z-index: 5;">
                <button type="button" class="btn btn-sm gs-btn-wishlist rounded-circle d-flex align-items-center justify-content-center p-0" 
                        style="width: 32px; height: 32px;"
                        data-id="<?php echo (int) $card['id']; ?>" 
                        onclick="event.preventDefault(); event.stopPropagation(); gsToggleYeuThich(this, <?php echo (int) $card['id']; ?>);"
                        title="<?php echo $daThichCard ? 'Bỏ yêu thích' : 'Thêm vào yêu thích'; ?>">
                    <i class="<?php echo $daThichCard ? 'fa-solid fa-heart text-danger' : 'fa-regular fa-heart text-dark'; ?>"></i>
                </button>
                <button type="button" class="btn btn-sm gs-btn-compare rounded-circle d-flex align-items-center justify-content-center p-0" 
                        style="width: 32px; height: 32px;"
                        data-id="<?php echo (int) $card['id']; ?>" 
                        onclick="event.preventDefault(); event.stopPropagation(); gsToggleSoSanh(this, <?php echo (int) $card['id']; ?>);"
                        title="<?php echo $daSosanhCard ? 'Bỏ so sánh' : 'Thêm vào so sánh'; ?>">
                    <i class="fa-solid fa-code-compare <?php echo $daSosanhCard ? 'text-danger' : 'text-dark'; ?>"></i>
                </button>
            </div>

            <div class="gs-product-card__actions">
                <?php if (!$conhang) : ?>
                    <button type="button" class="gs-add-btn" disabled>Hết hàng</button>
                <?php elseif (count($mangsizes) > 0) : ?>
                    <form class="gs-form-cart" method="post" action="index.php">
                        <input type="hidden" name="action" value="themvaogiohang">
                        <input type="hidden" name="sanpham_id" value="<?php echo (int) $card['id']; ?>">
                        <input type="hidden" name="soluong" value="1">
                        <div class="gs-size-pills">
                            <?php foreach ($mangsizes as $size) : ?>
                                <button type="submit" name="size" value="<?php echo htmlspecialchars($size); ?>"
                                    class="gs-size-btn<?php echo ($gsSizeGoiy === $size) ? ' is-goiy' : ''; ?>"
                                    title="<?php echo ($gsSizeGoiy === $size) ? 'Size gợi ý theo chiều cao & cân nặng của bạn' : ('Thêm size ' . htmlspecialchars($size) . ' vào giỏ'); ?>">
                                    <?php echo htmlspecialchars($size); ?>
                                </button>
                            <?php endforeach; ?>
                        </div>
                    </form>
                <?php else : ?>
                    <form class="gs-form-cart" method="post" action="index.php">
                        <input type="hidden" name="action" value="themvaogiohang">
                        <input type="hidden" name="sanpham_id" value="<?php echo (int) $card['id']; ?>">
                        <input type="hidden" name="size" value="">
                        <input type="hidden" name="soluong" value="1">
                        <button type="submit" class="gs-add-btn">Thêm vào giỏ</button>
                    </form>
                <?php endif; ?>
            </div>
        </div>

        <div class="gs-product-card__body">
            <div class="gs-product-card__cat"><?php echo htmlspecialchars($tendanhmuc); ?></div>
            <a class="gs-product-card__name" href="index.php?action=chitiet&id=<?php echo (int) $card['id']; ?>">
                <?php echo htmlspecialchars($card['tensanpham']); ?>
            </a>

            <?php if (!empty($danhSachMauCard)) : ?>
                <div class="gs-card-swatches">
                    <?php foreach ($danhSachMauCard as $mRaw) :
                        $mt = mb_strtolower(trim($mRaw), 'UTF-8');
                        $hex = '#475569';
                        if (strpos($mt, 'vàng pastel') !== false || strpos($mt, 'vàng nhạt') !== false || strpos($mt, 'bơ') !== false) $hex = '#FDE68A';
                        elseif (strpos($mt, 'kem') !== false || strpos($mt, 'be') !== false) $hex = '#F3E5AB';
                        elseif (strpos($mt, 'vàng') !== false) $hex = '#FACC15';
                        elseif (strpos($mt, 'xanh mint') !== false || strpos($mt, 'bạc hà') !== false || strpos($mt, 'lá pastel') !== false) $hex = '#A7F3D0';
                        elseif (strpos($mt, 'xanh pastel') !== false || strpos($mt, 'xanh nhạt') !== false || strpos($mt, 'xanh baby') !== false) $hex = '#93C5FD';
                        elseif (strpos($mt, 'hồng pastel') !== false || strpos($mt, 'hồng nhạt') !== false || strpos($mt, 'hồng phấn') !== false) $hex = '#F9A8D4';
                        elseif (strpos($mt, 'tím pastel') !== false || strpos($mt, 'tím nhạt') !== false || strpos($mt, 'lavender') !== false) $hex = '#D8B4FE';
                        elseif (strpos($mt, 'đen') !== false) $hex = '#18181b';
                        elseif (strpos($mt, 'trắng') !== false) $hex = '#ffffff';
                        elseif (strpos($mt, 'xám') !== false || strpos($mt, 'ghi') !== false) $hex = '#71717a';
                        elseif (strpos($mt, 'navy') !== false || strpos($mt, 'than') !== false) $hex = '#1e3a8a';
                        elseif (strpos($mt, 'xanh lá') !== false || strpos($mt, 'rêu') !== false) $hex = '#22c55e';
                        elseif (strpos($mt, 'xanh') !== false) $hex = '#2563eb';
                        elseif (strpos($mt, 'hồng') !== false) $hex = '#f472b6';
                        elseif (strpos($mt, 'đỏ') !== false) $hex = '#D02545';
                        elseif (strpos($mt, 'tím') !== false) $hex = '#a855f7';
                        elseif (strpos($mt, 'cam') !== false) $hex = '#f97316';
                        elseif (strpos($mt, 'nâu') !== false) $hex = '#92400e';
                    ?>
                        <span class="gs-card-swatch" style="background-color: <?php echo $hex; ?>;" title="<?php echo htmlspecialchars(trim($mRaw)); ?>"></span>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <div class="gs-product-card__meta">
                <span>Đã bán <?php echo (int) $card['luotmua']; ?></span>
                <?php if (!empty($card['tenthuonghieu'])) : ?>
                    <span>· <?php echo htmlspecialchars($card['tenthuonghieu']); ?></span>
                <?php endif; ?>
            </div>

            <div class="gs-product-card__price">
                <span class="gs-price"><?php echo gsTien($giaban); ?></span>
                <?php if ($phantramgiam > 0) : ?>
                    <span class="gs-price--old"><?php echo gsTien($giagoc); ?></span>
                <?php endif; ?>
            </div>

            <a class="gs-card-link" href="index.php?action=chitiet&id=<?php echo (int) $card['id']; ?>">
                Xem chi tiết &rarr;
            </a>
        </div>
    </div>
</div>
