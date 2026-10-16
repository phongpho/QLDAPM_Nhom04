<?php
/* =====================================================================
   TRANG DANH MỤC SẢN PHẨM (Module 3)
   Nạp từ router: index.php?action=sanpham&loai=&doituong=&muctieu=&q=&sapxep=&trang=
   ===================================================================== */
if (!isset($gsModule36)) {
    $_REQUEST['action'] = 'sanpham';
    require __DIR__ . '/index.php';
    return;
}
$gsTieude = 'Sản phẩm';

// Hàm dựng URL giữ nguyên các tham số lọc hiện tại (đổi tham số truyền vào)
$taourl = function ($thaydoi = array()) use ($loai, $doituong, $muctieu, $tukhoa, $sapxep) {
    $thamso = array(
        'action'   => 'sanpham',
        'loai'     => $loai,
        'doituong' => $doituong,
        'muctieu'  => $muctieu,
        'q'        => $tukhoa,
        'sapxep'   => $sapxep,
        'trang'    => ''
    );
    foreach ($thaydoi as $khoa => $giatri) {
        $thamso[$khoa] = $giatri;
    }
    $thamso = array_filter($thamso, function ($v) {
        return $v !== '' && $v !== null;
    });
    return 'index.php?' . http_build_query($thamso);
};

$gsSizeGoiy = ($gsGoiy && $gsGoiy['sizegoiy']) ? $gsGoiy['sizegoiy']['size'] : '';

$tenloc = 'Tất cả sản phẩm';
if ($loai !== '') {
    $tenloc = gsTenLoai($loai);
}
if ($doituong !== '') {
    $tenloc .= ' - ' . (($doituong === 'nam') ? 'Nam' : 'Nữ');
}
if ($tukhoa !== '') {
    $tenloc .= ' - Từ khóa "' . $tukhoa . '"';
}
include(__DIR__ . '/inc/head-client.php');
?>

<section class="gs-section gs-section--grey gs-page-head">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb gs-breadcrumb">
                <li class="breadcrumb-item"><a href="index.php">Trang chủ</a></li>
                <li class="breadcrumb-item"><a href="sanpham.php">Sản phẩm</a></li>
                <li class="breadcrumb-item active" aria-current="page"><?php echo htmlspecialchars($tenloc); ?></li>
            </ol>
        </nav>
        <h1 class="gs-page-head__title"><?php echo htmlspecialchars($tenloc); ?></h1>
        <p class="gs-page-head__sub">Tìm thấy <strong><?php echo (int) $tongsp; ?></strong> sản phẩm đang bán</p>
    </div>
</section>

<section class="gs-section">
    <div class="container">
        <div class="row g-4">

            <!-- ===== Bộ lọc ===== -->
            <div class="col-12 col-lg-3">
                <div class="gs-filter">
                    <form method="get" action="index.php" class="gs-filter__search">
                        <input type="hidden" name="action" value="sanpham">
                        <input type="hidden" name="loai" value="<?php echo htmlspecialchars($loai); ?>">
                        <input type="hidden" name="doituong" value="<?php echo htmlspecialchars($doituong); ?>">
                        <input type="hidden" name="muctieu" value="<?php echo htmlspecialchars($muctieu); ?>">
                        <div class="input-group">
                            <input type="text" class="form-control" name="q" placeholder="Tìm sản phẩm..."
                                value="<?php echo htmlspecialchars($tukhoa); ?>">
                            <button class="btn btn-danger" type="submit" aria-label="Tìm kiếm">
                                <i class="fa-solid fa-magnifying-glass"></i>
                            </button>
                        </div>
                    </form>

                    <div class="gs-filter__group">
                        <h3 class="gs-filter__title">Nhóm sản phẩm</h3>
                        <a class="gs-filter__link<?php echo ($loai === '') ? ' is-active' : ''; ?>" href="<?php echo htmlspecialchars($taourl(array('loai' => '', 'trang' => ''))); ?>">
                            Tất cả <span><?php echo (int) $tongsp; ?></span>
                        </a>
                        <a class="gs-filter__link<?php echo ($loai === 'quanao') ? ' is-active' : ''; ?>" href="<?php echo htmlspecialchars($taourl(array('loai' => 'quanao', 'trang' => ''))); ?>">
                            Đồ tập <span><?php echo (int) $soluongnhom['quanao']; ?></span>
                        </a>
                        <a class="gs-filter__link<?php echo ($loai === 'phukien') ? ' is-active' : ''; ?>" href="<?php echo htmlspecialchars($taourl(array('loai' => 'phukien', 'trang' => ''))); ?>">
                            Phụ kiện <span><?php echo (int) $soluongnhom['phukien']; ?></span>
                        </a>
                        <a class="gs-filter__link<?php echo ($loai === 'tpbs') ? ' is-active' : ''; ?>" href="<?php echo htmlspecialchars($taourl(array('loai' => 'tpbs', 'trang' => ''))); ?>">
                            Thực phẩm bổ sung <span><?php echo (int) $soluongnhom['tpbs']; ?></span>
                        </a>
                    </div>

                    <div class="gs-filter__group">
                        <h3 class="gs-filter__title">Đối tượng</h3>
                        <a class="gs-filter__link<?php echo ($doituong === '') ? ' is-active' : ''; ?>" href="<?php echo htmlspecialchars($taourl(array('doituong' => '', 'trang' => ''))); ?>">Tất cả</a>
                        <a class="gs-filter__link<?php echo ($doituong === 'nam') ? ' is-active' : ''; ?>" href="<?php echo htmlspecialchars($taourl(array('doituong' => 'nam', 'trang' => ''))); ?>">Nam</a>
                        <a class="gs-filter__link<?php echo ($doituong === 'nu') ? ' is-active' : ''; ?>" href="<?php echo htmlspecialchars($taourl(array('doituong' => 'nu', 'trang' => ''))); ?>">Nữ</a>
                    </div>

                    <div class="gs-filter__group">
                        <h3 class="gs-filter__title">Mục tiêu tập luyện</h3>
                        <a class="gs-filter__link<?php echo ($muctieu === 'giamcan') ? ' is-active' : ''; ?>" href="<?php echo htmlspecialchars($taourl(array('muctieu' => 'giamcan', 'trang' => ''))); ?>">Giảm cân / Giảm mỡ</a>
                        <a class="gs-filter__link<?php echo ($muctieu === 'tangcan') ? ' is-active' : ''; ?>" href="<?php echo htmlspecialchars($taourl(array('muctieu' => 'tangcan', 'trang' => ''))); ?>">Tăng cân / Tăng cơ</a>
                        <a class="gs-filter__link<?php echo ($muctieu === 'duytri') ? ' is-active' : ''; ?>" href="<?php echo htmlspecialchars($taourl(array('muctieu' => 'duytri', 'trang' => ''))); ?>">Duy trì vóc dáng</a>
                    </div>

                    <?php if ($loai !== '' || $doituong !== '' || $muctieu !== '' || $tukhoa !== '') : ?>
                        <a class="gs-filter__clear" href="index.php?action=sanpham">
                            <i class="fa-solid fa-xmark"></i> Bỏ tất cả bộ lọc
                        </a>
                    <?php endif; ?>
                </div>
            </div>

            <!-- ===== Danh sách sản phẩm ===== -->
            <div class="col-12 col-lg-9">
                <div class="gs-toolbar">
                    <span class="gs-toolbar__count">
                        Trang <strong><?php echo (int) $trang; ?></strong><?php echo ($tongsotrang > 1) ? ' / ' . (int) $tongsotrang : ''; ?>
                    </span>
                    <form method="get" action="index.php" class="gs-toolbar__sort">
                        <input type="hidden" name="action" value="sanpham">
                        <input type="hidden" name="loai" value="<?php echo htmlspecialchars($loai); ?>">
                        <input type="hidden" name="doituong" value="<?php echo htmlspecialchars($doituong); ?>">
                        <input type="hidden" name="muctieu" value="<?php echo htmlspecialchars($muctieu); ?>">
                        <input type="hidden" name="q" value="<?php echo htmlspecialchars($tukhoa); ?>">
                        <select class="form-select form-select-sm" name="sapxep" aria-label="Sắp xếp sản phẩm" onchange="this.form.submit()">
                            <option value="moi" <?php echo ($sapxep === 'moi') ? 'selected' : ''; ?>>Mới nhất</option>
                            <option value="banchay" <?php echo ($sapxep === 'banchay') ? 'selected' : ''; ?>>Bán chạy nhất</option>
                            <option value="giathap" <?php echo ($sapxep === 'giathap') ? 'selected' : ''; ?>>Giá thấp đến cao</option>
                            <option value="giacao" <?php echo ($sapxep === 'giacao') ? 'selected' : ''; ?>>Giá cao đến thấp</option>
                            <option value="giamgia" <?php echo ($sapxep === 'giamgia') ? 'selected' : ''; ?>>Giảm giá nhiều nhất</option>
                        </select>
                        <noscript><button class="btn btn-sm btn-dark" type="submit">Sắp xếp</button></noscript>
                    </form>
                </div>

                <?php if (count($dsSanpham) === 0) : ?>
                    <div class="gs-empty">
                        <i class="fa-regular fa-face-frown"></i>
                        <h3>Không tìm thấy sản phẩm phù hợp</h3>
                        <p>Hãy thử bỏ một vài bộ lọc hoặc tìm với từ khóa khác nhé.</p>
                        <a class="btn-gs btn-gs--dark" href="index.php?action=sanpham">Xem tất cả sản phẩm</a>
                    </div>
                <?php else : ?>
                    <div class="row g-4">
                        <?php foreach ($dsSanpham as $card) : ?>
                            <?php include(__DIR__ . '/inc/card-sanpham.php'); ?>
                        <?php endforeach; ?>
                    </div>

                    <?php if ($tongsotrang > 1) : ?>
                        <nav class="gs-pagination" aria-label="Phân trang sản phẩm">
                            <ul class="pagination justify-content-center">
                                <li class="page-item<?php echo ($trang <= 1) ? ' disabled' : ''; ?>">
                                    <a class="page-link" href="<?php echo htmlspecialchars($taourl(array('trang' => max(1, $trang - 1)))); ?>">
                                        <i class="fa-solid fa-chevron-left"></i>
                                    </a>
                                </li>
                                <?php for ($i = 1; $i <= $tongsotrang; $i++) : ?>
                                    <li class="page-item<?php echo ($i === $trang) ? ' active' : ''; ?>">
                                        <a class="page-link" href="<?php echo htmlspecialchars($taourl(array('trang' => $i))); ?>"><?php echo $i; ?></a>
                                    </li>
                                <?php endfor; ?>
                                <li class="page-item<?php echo ($trang >= $tongsotrang) ? ' disabled' : ''; ?>">
                                    <a class="page-link" href="<?php echo htmlspecialchars($taourl(array('trang' => min($tongsotrang, $trang + 1)))); ?>">
                                        <i class="fa-solid fa-chevron-right"></i>
                                    </a>
                                </li>
                            </ul>
                        </nav>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<?php include(__DIR__ . '/inc/footer.php'); ?>
