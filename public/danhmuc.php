<?php
if (!isset($gsModule36)) {
    $_REQUEST['action'] = 'sanpham';
    require __DIR__ . '/index.php';
    return;
}
$gsTieude = 'Sản phẩm';

$taourl = function ($thaydoi = array()) use ($loai, $doituong, $muctieu, $tukhoa, $sapxep, $thuonghieu_id) {
    $thamso = array(
        'action'      => 'sanpham',
        'loai'        => $loai,
        'doituong'    => $doituong,
        'muctieu'     => $muctieu,
        'thuonghieu'  => $thuonghieu_id ? $thuonghieu_id : '',
        'q'           => $tukhoa,
        'sapxep'      => $sapxep,
        'trang'       => ''
    );
    foreach ($thaydoi as $khoa => $giatri) {
        $thamso[$khoa] = $giatri;
    }
    $thamso = array_filter($thamso, function ($v) {
        return $v !== '' && $v !== null && $v !== 0;
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
if ($thuonghieu_id > 0 && !empty($dsThuonghieu)) {
    foreach ($dsThuonghieu as $th) {
        if ((int) $th['id'] === $thuonghieu_id) {
            $tenloc .= ' - ' . $th['tenthuonghieu'];
            break;
        }
    }
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
                <li class="breadcrumb-item"><a href="index.php?action=sanpham">Sản phẩm</a></li>
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

            <div class="col-12 col-lg-3">
                <div class="gs-filter">
                    <form method="get" action="index.php" class="gs-filter__search">
                        <input type="hidden" name="action" value="sanpham">
                        <input type="hidden" name="loai" value="<?php echo htmlspecialchars($loai); ?>">
                        <input type="hidden" name="doituong" value="<?php echo htmlspecialchars($doituong); ?>">
                        <input type="hidden" name="muctieu" value="<?php echo htmlspecialchars($muctieu); ?>">
                        <input type="hidden" name="thuonghieu" value="<?php echo (int) $thuonghieu_id; ?>">
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
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <h3 class="gs-filter__title mb-0">Thương hiệu</h3>
                            <?php if ($thuonghieu_id > 0) : ?>
                                <a class="gs-brand-clear-link" href="<?php echo htmlspecialchars($taourl(array('thuonghieu' => '', 'trang' => ''))); ?>" title="Bỏ lọc thương hiệu">
                                    <i class="fa-solid fa-xmark"></i> Bỏ chọn
                                </a>
                            <?php endif; ?>
                        </div>
                        <style>
                            .gs-brand-grid {
                                display: grid !important;
                                grid-template-columns: repeat(3, 1fr) !important;
                                gap: 10px !important;
                                margin-top: 0.5rem !important;
                            }
                            .gs-brand-tile {
                                display: flex !important;
                                align-items: center !important;
                                justify-content: center !important;
                                width: 100% !important;
                                aspect-ratio: 1 / 1 !important;
                                padding: 8px !important;
                                background: #ffffff !important;
                                border: 1.5px solid #dcdcdc !important;
                                border-radius: 50% !important;
                                text-decoration: none !important;
                                transition: all 0.22s ease-in-out !important;
                                box-sizing: border-box !important;
                                cursor: pointer !important;
                                overflow: hidden !important;
                                box-shadow: 0 1px 3px rgba(0,0,0,0.08) !important;
                            }
                            .gs-brand-tile:hover {
                                border-color: #111111 !important;
                                transform: translateY(-2px) scale(1.05) !important;
                                box-shadow: 0 4px 10px rgba(0,0,0,0.15) !important;
                            }
                            .gs-brand-tile.is-active {
                                border-color: #e53935 !important;
                                border-width: 2px !important;
                                box-shadow: 0 0 0 3px rgba(229,57,53,0.25) !important;
                                transform: scale(1.04) !important;
                            }
                            .gs-brand-tile__logo {
                                max-height: 70% !important;
                                max-width: 70% !important;
                                width: auto !important;
                                height: auto !important;
                                object-fit: contain !important;
                                display: block !important;
                                filter: none !important;
                                opacity: 0.95 !important;
                                transition: transform 0.2s ease !important;
                            }
                            .gs-brand-tile:hover .gs-brand-tile__logo,
                            .gs-brand-tile.is-active .gs-brand-tile__logo {
                                opacity: 1 !important;
                                transform: scale(1.08) !important;
                            }
                            .gs-pagination .page-link {
                                color: #e5e5e5 !important;
                                background: #1c1c1c !important;
                                border-color: #333333 !important;
                                font-weight: 700 !important;
                                font-size: 0.85rem !important;
                                box-shadow: none !important;
                                transition: all 0.2s ease !important;
                            }
                            .gs-pagination .page-link:hover,
                            .gs-pagination .page-link:focus {
                                color: #ffffff !important;
                                background: #2a2a2a !important;
                                border-color: #555555 !important;
                                box-shadow: none !important;
                            }
                            .gs-pagination .page-item.active .page-link {
                                background: #ffffff !important;
                                border-color: #ffffff !important;
                                color: #000000 !important;
                                font-weight: 800 !important;
                                box-shadow: 0 2px 8px rgba(255, 255, 255, 0.2) !important;
                            }
                            .gs-pagination .page-item.disabled .page-link {
                                background: #141414 !important;
                                color: #666666 !important;
                                border-color: #2a2a2a !important;
                            }
                        </style>
                        <div class="gs-brand-grid">
                            <?php foreach ($dsThuonghieu as $th) : 
                                $isChon = ($thuonghieu_id === (int) $th['id']);
                                $urlThuonghieu = $taourl(array('thuonghieu' => $isChon ? '' : $th['id'], 'trang' => ''));
                                $logoFile = !empty($th['logo']) ? $th['logo'] : 'logo.png';
                            ?>
                                <a class="gs-brand-tile<?php echo $isChon ? ' is-active' : ''; ?>" 
                                   href="<?php echo htmlspecialchars($urlThuonghieu); ?>"
                                   title="<?php echo htmlspecialchars($th['tenthuonghieu']); ?><?php echo ((int)$th['soluongsp'] > 0) ? ' (' . (int)$th['soluongsp'] . ' sp)' : ''; ?>"
                                   aria-label="<?php echo htmlspecialchars($th['tenthuonghieu']); ?>">
                                    <img class="gs-brand-tile__logo" 
                                         src="../assets/images/systems/<?php echo htmlspecialchars($logoFile); ?>" 
                                         alt="<?php echo htmlspecialchars($th['tenthuonghieu']); ?>"
                                         loading="lazy"
                                         onerror="this.style.display='none'">
                                </a>
                            <?php endforeach; ?>
                        </div>
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

                    <?php if ($loai !== '' || $doituong !== '' || $muctieu !== '' || $thuonghieu_id > 0 || $tukhoa !== '') : ?>
                        <a class="gs-filter__clear" href="index.php?action=sanpham">
                            <i class="fa-solid fa-xmark"></i> Bỏ tất cả bộ lọc
                        </a>
                    <?php endif; ?>
                </div>
            </div>

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
                        <input type="hidden" name="thuonghieu" value="<?php echo (int) $thuonghieu_id; ?>">
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
                            <?php include(__DIR__ . '/inc/product-card.php'); ?>
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
