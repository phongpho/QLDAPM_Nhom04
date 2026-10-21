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
$duongdananh = ($hinhanh !== '') ? '../assets/images/systems/' . $hinhanh : '';
?>
<div class="col-6 col-md-4 col-lg-3">
    <div class="gs-product-card" data-id="<?php echo (int) $card['id']; ?>"
        data-name="<?php echo htmlspecialchars($card['tensanpham']); ?>">
        <div class="gs-product-card__media">
            <a class="gs-product-card__link" href="index.php?action=chitiet&id=<?php echo (int) $card['id']; ?>">
                <img class="gs-product-card__img" src="<?php echo htmlspecialchars($duongdananh); ?>"
                    alt="<?php echo htmlspecialchars($card['tensanpham']); ?>" loading="lazy" onerror="gsOnErrorImg(this)">
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
                        <button type="submit" class="gs-add-btn"><i class="fa-solid fa-bag-shopping"></i> Thêm vào giỏ</button>
                    </form>
                <?php endif; ?>
            </div>
        </div>

        <div class="gs-product-card__body">
            <div class="gs-product-card__cat"><?php echo htmlspecialchars($tendanhmuc); ?></div>
            <a class="gs-product-card__name" href="index.php?action=chitiet&id=<?php echo (int) $card['id']; ?>">
                <?php echo htmlspecialchars($card['tensanpham']); ?>
            </a>

            <div class="gs-product-card__meta">
                <span><i class="fa-solid fa-fire-flame-curved"></i> Đã bán <?php echo (int) $card['luotmua']; ?></span>
                <?php if (!empty($card['tenthuonghieu'])) : ?>
                    <span><i class="fa-solid fa-tag"></i> <?php echo htmlspecialchars($card['tenthuonghieu']); ?></span>
                <?php endif; ?>
            </div>

            <div class="gs-product-card__price">
                <span class="gs-price"><?php echo gsTien($giaban); ?></span>
                <?php if ($phantramgiam > 0) : ?>
                    <span class="gs-price--old"><?php echo gsTien($giagoc); ?></span>
                <?php endif; ?>
            </div>

            <a class="gs-card-link" href="index.php?action=chitiet&id=<?php echo (int) $card['id']; ?>">
                Xem chi tiết <i class="fa-solid fa-arrow-right-long"></i>
            </a>
        </div>
    </div>
</div>
