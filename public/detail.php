<?php
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

function gsLayMauHex($ten) {
    $t = mb_strtolower(trim($ten), 'UTF-8');
    if (strpos($t, 'đen') !== false) return '#18181b';
    if (strpos($t, 'trắng') !== false) return '#ffffff';
    if (strpos($t, 'xám') !== false) return '#71717a';
    if (strpos($t, 'xanh navy') !== false || strpos($t, 'xanh than') !== false) return '#1e3a8a';
    if (strpos($t, 'xanh dương') !== false) return '#2563eb';
    if (strpos($t, 'xanh mint') !== false) return '#34d399';
    if (strpos($t, 'hồng') !== false) return '#f472b6';
    if (strpos($t, 'đỏ') !== false) return '#dc2626';
    if (strpos($t, 'tím') !== false) return '#a855f7';
    if (strpos($t, 'cam') !== false) return '#f97316';
    return '#475569';
}

$motaRaw = (string)$sanpham['mota'];
$motaParts = explode("\n\n", $motaRaw);
$motaTongQuan = array_shift($motaParts);
$motaChiTiet = implode("\n\n", $motaParts);

include(__DIR__ . '/inc/head-client.php');
?>

<section class="gs-section gs-section--grey gs-page-head py-3">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb gs-breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="index.php">Trang chủ</a></li>
                <li class="breadcrumb-item"><a href="index.php?action=sanpham">Sản phẩm</a></li>
                <li class="breadcrumb-item">
                    <a href="index.php?action=sanpham&loai=<?php echo urlencode($sanpham['loai']); ?>"><?php echo htmlspecialchars($danhmucCha); ?></a>
                </li>
                <li class="breadcrumb-item active" aria-current="page"><?php echo htmlspecialchars($sanpham['tensanpham']); ?></li>
            </ol>
        </nav>
    </div>
</section>

<section class="gs-section pt-4">
    <div class="container">
        <div class="row g-4 g-lg-5">

            <div class="col-12 col-lg-5">
                <div class="gs-detail__media" style="border-radius: 20px; background: #141c2b; border: 1px solid rgba(255,255,255,0.08); padding: 2rem; position: relative;">
                    <img class="gs-detail__img" src="../assets/images/products/<?php echo htmlspecialchars($sanpham['hinhanh']); ?>"
                        alt="<?php echo htmlspecialchars($sanpham['tensanpham']); ?>" onerror="gsOnErrorImg(this)">
                    <?php if ($phantramgiam > 0) : ?>
                        <span class="badge-gs badge-gs--sale gs-detail__sale" style="position: absolute; top: 1rem; left: 1rem;">-<?php echo $phantramgiam; ?>%</span>
                    <?php endif; ?>
                </div>
                <div class="d-flex align-items-center justify-content-between mt-3 px-2 text-secondary small">
                    <span>100% Chính hãng</span>
                    <span>•</span>
                    <span>Giao nhanh 2h</span>
                    <span>•</span>
                    <span>Đổi trả 7 ngày</span>
                </div>
            </div>

            <div class="col-12 col-lg-7">
                <div class="d-flex align-items-center gap-2 mb-2">
                    <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2.5 py-1 text-uppercase fw-bold" style="font-size: 0.78rem; letter-spacing: 0.05em;"><?php echo htmlspecialchars($danhmucCha); ?></span>
                    <span class="badge bg-secondary-subtle text-secondary px-2.5 py-1 fw-semibold" style="font-size: 0.78rem;"><?php echo htmlspecialchars($tenthuonghieu); ?></span>
                </div>

                <h1 class="gs-detail__title mb-2" style="font-size: 2.1rem; line-height: 1.25;"><?php echo htmlspecialchars($sanpham['tensanpham']); ?></h1>

                <div class="d-flex flex-wrap align-items-center gap-3 mb-3 text-secondary small">
                    <div class="d-flex align-items-center gap-1">
                        <span class="text-warning"><i class="fa-solid fa-star"></i> <?php echo number_format($diemDanhgia['diem'], 1, ',', '.'); ?>/5</span>
                        <span>(<?php echo (int) $diemDanhgia['soluot']; ?> đánh giá)</span>
                    </div>
                    <span>•</span>
                    <div>Đã bán <strong><?php echo (int) $sanpham['luotmua']; ?></strong></div>
                    <span>•</span>
                    <div><strong><?php echo (int) $sanpham['luotxem'] + 1; ?></strong> lượt xem</div>
                </div>

                <div class="gs-detail__price">
                    <span class="gs-price"><?php echo gsTien($giaban); ?></span>
                    <?php if ($phantramgiam > 0) : ?>
                        <span class="gs-price--old"><?php echo gsTien($giagoc); ?></span>
                        <span class="badge-gs badge-gs--sale">Tiết kiệm <?php echo gsTien($giagoc - $giaban); ?></span>
                    <?php endif; ?>
                </div>

                <div class="mb-3">
                    <?php if ($conhang) : ?>
                        <span class="badge bg-success-subtle text-success border border-success-subtle px-3 py-1.5 rounded-pill">
                            Còn hàng (<?php echo (int) $sanpham['soluongton']; ?> sản phẩm)
                        </span>
                    <?php else : ?>
                        <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-3 py-1.5 rounded-pill">
                            Tạm hết hàng
                        </span>
                    <?php endif; ?>
                </div>

                <form class="gs-form-cart gs-detail__form" method="post" action="index.php">
                    <input type="hidden" name="action" value="themvaogiohang">
                    <input type="hidden" name="sanpham_id" value="<?php echo (int) $sanpham['id']; ?>">

                    <?php if (count($mangsizes) > 0) : ?>
                        <div class="mb-3">
                            <div class="d-flex align-items-center justify-content-between mb-1">
                                <span class="gs-detail__label mb-0">Chọn size</span>
                                <?php if ($sizegoiy !== '') : ?>
                                    <span class="gs-sizegoiy-note mt-0">
                                        Gợi ý theo chỉ số: <strong>Size <?php echo htmlspecialchars($sizegoiy); ?></strong>
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
                        <p class="gs-detail__freesize">Sản phẩm này không phân biệt size (Freesize).</p>
                    <?php endif; ?>

                    <?php if (!empty($sanpham['mausac'])) : ?>
                        <div class="mb-3">
                            <span class="gs-detail__label mb-1 d-block">Màu sắc</span>
                            <div class="gs-detail__mausac">
                                <?php
                                $danhSachMau = explode(',', $sanpham['mausac']);
                                foreach ($danhSachMau as $idx => $mau) :
                                    $tenMauTrim = trim($mau);
                                    $colorHex = gsLayMauHex($tenMauTrim);
                                ?>
                                    <span class="gs-mau<?php echo ($idx === 0) ? ' active' : ''; ?>" onclick="gsChonMau(this, '<?php echo htmlspecialchars($tenMauTrim, ENT_QUOTES); ?>')">
                                        <span class="gs-mau__dot" style="background-color: <?php echo $colorHex; ?>;"></span>
                                        <?php echo htmlspecialchars($tenMauTrim); ?>
                                    </span>
                                <?php endforeach; ?>
                            </div>
                            <input type="hidden" id="gsMauChon" name="mausac" value="<?php echo htmlspecialchars(trim($danhSachMau[0] ?? '')); ?>">
                        </div>
                    <?php endif; ?>

                    <div class="d-flex align-items-end gap-3 mt-4">
                        <div>
                            <label class="gs-detail__label mb-1 d-block" for="gsSoLuong">Số lượng</label>
                            <div class="gs-qty-group">
                                <button type="button" class="gs-qty-btn" onclick="gsGiamSoLuong()">-</button>
                                <input class="gs-qty-input" type="number" id="gsSoLuong" name="soluong" min="1"
                                    max="<?php echo max(1, (int) $sanpham['soluongton']); ?>" value="1" <?php echo $conhang ? '' : 'disabled'; ?>>
                                <button type="button" class="gs-qty-btn" onclick="gsTangSoLuong()">+</button>
                            </div>
                        </div>
                        <button type="submit" class="btn-gs btn-gs--dark flex-grow-1" style="height: 48px;" <?php echo $conhang ? '' : 'disabled'; ?>>
                            <?php echo $conhang ? 'THÊM VÀO GIỎ HÀNG' : 'TẠM HẾT HÀNG'; ?>
                        </button>
                        <?php $daThichDetail = in_array((int)$sanpham['id'], $dsIdYeuthich ?? array(), true); ?>
                        <button type="button" class="btn btn-outline-danger d-flex align-items-center justify-content-center px-3"
                                style="border-radius: 8px; height: 48px; min-width: 48px;"
                                onclick="gsToggleYeuThich(this, <?php echo (int) $sanpham['id']; ?>)"
                                title="<?php echo $daThichDetail ? 'Bỏ yêu thích' : 'Thêm vào yêu thích'; ?>">
                            <i class="<?php echo $daThichDetail ? 'fa-solid fa-heart text-danger fs-5' : 'fa-regular fa-heart text-danger fs-5'; ?>"></i>
                        </button>
                        <?php $daSosanhDetail = in_array((int)$sanpham['id'], $_SESSION['gs_sosanh'] ?? array(), true); ?>
                        <button type="button" class="btn btn-outline-warning d-flex align-items-center justify-content-center px-3 gap-2 gs-btn-detail-compare"
                                style="border-radius: 8px; min-height: 48px; border-color: rgba(249, 115, 22, 0.4); color: #f97316;"
                                data-id="<?php echo (int) $sanpham['id']; ?>"
                                onclick="gsToggleSoSanh(this, <?php echo (int) $sanpham['id']; ?>)"
                                title="<?php echo $daSosanhDetail ? 'Bỏ so sánh' : 'Thêm vào so sánh'; ?>">
                            <i class="fa-solid fa-code-compare fs-5"></i>
                            <span class="d-none d-sm-inline gs-compare-btn-text"><?php echo $daSosanhDetail ? 'Đang so sánh' : 'So sánh'; ?></span>
                        </button>
                    </div>
                </form>

                <div class="gs-detail__policies">
                    <div class="gs-detail__policy-item">
                        <div>
                            <strong class="d-block text-white">Freeship từ 500k</strong>
                            <small>Giao hàng toàn quốc</small>
                        </div>
                    </div>
                    <div class="gs-detail__policy-item">
                        <div>
                            <strong class="d-block text-white">Đổi trả 7 ngày</strong>
                            <small>Lỗi NSX hoặc đổi size</small>
                        </div>
                    </div>
                    <div class="gs-detail__policy-item">
                        <div>
                            <strong class="d-block text-white">Chính hãng 100%</strong>
                            <small>Đảm bảo nguồn gốc</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="gs-detail__info mt-5">
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

            <div class="tab-content gs-tab-content pt-4">
                <div class="tab-pane fade show active" id="gsTabMota" role="tabpanel">
                    <div class="gs-mota-section">
                        <h4 class="gs-mota-title">
                            Đặc điểm nổi bật &amp; Thiết kế
                        </h4>
                        <div class="gs-mota-content">
                            <?php echo nl2br(htmlspecialchars($motaTongQuan ?: 'Thông tin mô tả đang được cập nhật.')); ?>
                        </div>
                    </div>

                    <?php if (!empty($motaChiTiet)) : ?>
                        <div class="gs-mota-section">
                            <h4 class="gs-mota-title">
                                Hướng dẫn sử dụng &amp; Bảo quản chuẩn Gymer
                            </h4>
                            <div class="gs-mota-content" style="white-space: pre-line;">
                                <?php echo htmlspecialchars($motaChiTiet); ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <div class="gs-mota-section" style="background: rgba(249, 115, 22, 0.05); border-color: rgba(249, 115, 22, 0.2);">
                        <h4 class="gs-mota-title" style="color: var(--gs-primary);">
                            Cam kết chất lượng từ GYMBRO
                        </h4>
                        <div class="row g-3 pt-2">
                            <div class="col-12 col-md-4">
                                <div class="d-flex align-items-start gap-2">
                                    
                                    <div>
                                        <strong class="text-white d-block">100% Chính Hãng</strong>
                                        <small class="text-secondary">Phân phối trực tiếp, nguồn gốc rõ ràng minh bạch.</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-md-4">
                                <div class="d-flex align-items-start gap-2">
                                    
                                    <div>
                                        <strong class="text-white d-block">Đổi Size Linh Hoạt</strong>
                                        <small class="text-secondary">Hỗ trợ đổi size trong vòng 7 ngày nhanh chóng.</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-md-4">
                                <div class="d-flex align-items-start gap-2">
                                    
                                    <div>
                                        <strong class="text-white d-block">Tư Vấn Thể Hình</strong>
                                        <small class="text-secondary">Gợi ý sản phẩm chuẩn xác theo chỉ số cơ thể của bạn.</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade" id="gsTabThongso" role="tabpanel">
                    <div class="gs-mota-section">
                        <h4 class="gs-mota-title">
                            Thông số cơ bản &amp; Phân loại
                        </h4>
                        <div class="row g-3 pt-1">
                            <div class="col-12 col-md-6">
                                <div class="d-flex align-items-center justify-content-between p-2 rounded" style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.05);">
                                    <span class="text-secondary small">Danh mục</span>
                                    <strong class="text-white small"><?php echo htmlspecialchars($sanpham['tendanhmuc']); ?> (<?php echo htmlspecialchars($mucdanhmuc[$sanpham['loai']]); ?>)</strong>
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="d-flex align-items-center justify-content-between p-2 rounded" style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.05);">
                                    <span class="text-secondary small">Thương hiệu</span>
                                    <strong class="text-white small"><?php echo htmlspecialchars($tenthuonghieu); ?></strong>
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="d-flex align-items-center justify-content-between p-2 rounded" style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.05);">
                                    <span class="text-secondary small">Đối tượng</span>
                                    <strong class="text-white small"><?php echo ($sanpham['doituong'] === 'nu') ? 'Nữ' : (($sanpham['doituong'] === 'nam') ? 'Nam' : 'Unisex (Nam & Nữ)'); ?></strong>
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="d-flex align-items-center justify-content-between p-2 rounded" style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.05);">
                                    <span class="text-secondary small">Size khả dụng</span>
                                    <strong class="text-white small"><?php echo (count($mangsizes) > 0) ? htmlspecialchars(implode(', ', $mangsizes)) : 'Freesize'; ?></strong>
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="d-flex align-items-center justify-content-between p-2 rounded" style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.05);">
                                    <span class="text-secondary small">Màu sắc</span>
                                    <strong class="text-white small"><?php echo !empty($sanpham['mausac']) ? htmlspecialchars($sanpham['mausac']) : 'Tiêu chuẩn'; ?></strong>
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="d-flex align-items-center justify-content-between p-2 rounded" style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.05);">
                                    <span class="text-secondary small">Tình trạng kho</span>
                                    <strong class="<?php echo $conhang ? 'text-success' : 'text-danger'; ?> small"><?php echo $conhang ? ((int) $sanpham['soluongton'] . ' sản phẩm sẵn sàng') : 'Hết hàng'; ?></strong>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="gs-mota-section">
                        <h4 class="gs-mota-title">
                            <?php if ($sanpham['loai'] === 'tpbs') : ?>
                                Thành phần dinh dưỡng &amp; Hạn sử dụng
                            <?php else : ?>
                                Chất liệu &amp; Công nghệ hoàn thiện
                            <?php endif; ?>
                        </h4>
                        <div class="gs-mota-content">
                            <?php if (!empty($sanpham['chatlieu'])) : ?>
                                <div class="d-flex align-items-start gap-2 mb-2">
                                    <strong class="text-white small text-nowrap" style="min-width: 150px;">Chất liệu cấu tạo:</strong>
                                    <span class="small text-secondary"><?php echo htmlspecialchars($sanpham['chatlieu']); ?></span>
                                </div>
                            <?php endif; ?>
                            <?php if (!empty($sanpham['thanhphan'])) : ?>
                                <div class="d-flex align-items-start gap-2 mb-2">
                                    <strong class="text-white small text-nowrap" style="min-width: 150px;">Thành phần vi chất:</strong>
                                    <span class="small text-secondary"><?php echo htmlspecialchars($sanpham['thanhphan']); ?></span>
                                </div>
                            <?php endif; ?>
                            <?php if (!empty($sanpham['hansudung'])) : ?>
                                <div class="d-flex align-items-start gap-2 mb-2">
                                    <strong class="text-white small text-nowrap" style="min-width: 150px;">Hạn dùng khuyến nghị:</strong>
                                    <span class="small text-warning"><?php echo htmlspecialchars(date('d/m/Y', strtotime($sanpham['hansudung']))); ?></span>
                                </div>
                            <?php endif; ?>
                            <?php if (empty($sanpham['chatlieu']) && empty($sanpham['thanhphan']) && empty($sanpham['hansudung'])) : ?>
                                <p class="small text-secondary mb-0">Sản phẩm đạt chứng nhận an toàn thể thao và độ bền tiêu chuẩn quốc tế.</p>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="gs-mota-section" style="background: rgba(249, 115, 22, 0.05); border-color: rgba(249, 115, 22, 0.2);">
                        <h4 class="gs-mota-title" style="color: var(--gs-primary);">
                            Mục tiêu luyện tập đề xuất
                        </h4>
                        <div class="d-flex flex-wrap gap-2 pt-1 mb-2">
                            <?php
                            $danhSachMuctieu = explode(',', (string) $sanpham['muctieu']);
                            foreach ($danhSachMuctieu as $mt) :
                                $mtTrim = trim($mt);
                                if ($mtTrim !== '') :
                            ?>
                                <span class="badge" style="background: #141c2b; border: 1px solid var(--gs-primary); color: #fff; padding: 0.5rem 0.9rem; font-size: 0.85rem;">
                                    <?php echo htmlspecialchars(gsTenMuctieu($mtTrim)); ?>
                                </span>
                            <?php
                                endif;
                            endforeach;
                            ?>
                        </div>
                        <small class="text-secondary d-block mt-2">
                            Hệ thống GYMBRO tự động phân tích và tối ưu sản phẩm này phù hợp cho gymer theo đuổi các mục tiêu thể trạng trên.
                        </small>
                    </div>

                    <div class="gs-mota-section">
                        <h4 class="gs-mota-title">
                            Tiêu chuẩn chứng nhận &amp; Bảo hành
                        </h4>
                        <div class="row g-3 pt-2">
                            <div class="col-12 col-md-4">
                                <div class="d-flex align-items-start gap-2">
                                    
                                    <div>
                                        <strong class="text-white d-block small">Nguồn Gốc Xuất Xứ</strong>
                                        <small class="text-secondary">Nhập khẩu chính hãng từ thương hiệu <?php echo htmlspecialchars($tenthuonghieu); ?>.</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-md-4">
                                <div class="d-flex align-items-start gap-2">
                                    
                                    <div>
                                        <strong class="text-white d-block small">Bảo Hành Đổi Trả</strong>
                                        <small class="text-secondary">Đổi trả 1-1 trong 7 ngày nếu lỗi kỹ thuật từ nhà sản xuất.</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-md-4">
                                <div class="d-flex align-items-start gap-2">
                                    
                                    <div>
                                        <strong class="text-white d-block small">Đóng Gói Chuyên Nghiệp</strong>
                                        <small class="text-secondary">Hộp carton bảo vệ 3 lớp, chống va đập trong quá trình vận chuyển.</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade" id="gsTabDanhgia" role="tabpanel">
                    <?php if ($gsNguoidungId <= 0) : ?>
                        <div class="p-3 mb-4 rounded border border-secondary border-opacity-25 bg-dark d-flex align-items-center gap-2">
                            <i class="fa-solid fa-lock text-warning"></i>
                            <span>Vui lòng <a href="index.php?action=dangnhap" class="text-danger fw-bold">đăng nhập</a> để đánh giá sản phẩm sau khi mua hàng.</span>
                        </div>
                    <?php elseif (empty($duocDanhgia)) : ?>
                        <div class="p-3 mb-4 rounded border border-secondary border-opacity-25 bg-dark d-flex align-items-center gap-2">
                            <i class="fa-solid fa-circle-info text-info"></i>
                            <span>Chỉ khách hàng <strong>đã mua và nhận hàng thành công</strong> mới có thể gửi đánh giá sản phẩm này.</span>
                        </div>
                    <?php else : ?>
                        <div class="card bg-dark border-secondary border-opacity-50 p-4 mb-4 rounded-3 shadow-sm">
                            <div class="d-flex align-items-center justify-content-between mb-3 flex-wrap gap-2">
                                <h5 class="m-0 text-white fw-bold">
                                    <i class="fa-solid fa-pen-to-square text-danger me-2"></i>
                                    <?php echo !empty($danhgiaCuaToi) ? 'Cập nhật đánh giá của bạn' : 'Viết đánh giá của bạn'; ?>
                                </h5>
                                <?php if (!empty($danhgiaCuaToi)) : ?>
                                    <span class="badge bg-success-subtle text-success border border-success-subtle">
                                        <i class="fa-solid fa-check me-1"></i> Đã đánh giá (<?php echo date('d/m/Y', strtotime($danhgiaCuaToi['ngay'])); ?>)
                                    </span>
                                <?php endif; ?>
                            </div>

                            <form action="index.php?action=danhgiasanpham" method="POST">
                                <input type="hidden" name="sanpham_id" value="<?php echo (int) $sanpham['id']; ?>">
                                <input type="hidden" name="sao" id="inputRatingStarDetail" value="<?php echo !empty($danhgiaCuaToi) ? (int)$danhgiaCuaToi['sao'] : 5; ?>">

                                <div class="mb-3">
                                    <label class="form-label text-white-50 small mb-1">Mức độ hài lòng:</label>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="gs-star-picker" id="starRatingDetail">
                                            <?php 
                                            $curSao = !empty($danhgiaCuaToi) ? (int)$danhgiaCuaToi['sao'] : 5;
                                            for ($i = 1; $i <= 5; $i++) : 
                                            ?>
                                                <i class="fa-<?php echo ($i <= $curSao) ? 'solid' : 'regular'; ?> fa-star star-node" data-val="<?php echo $i; ?>"></i>
                                            <?php endfor; ?>
                                        </div>
                                        <span id="starRatingDetailLabel" class="gs-star-badge ms-2">
                                            <?php 
                                            $nhanSao = array(
                                                1 => '1 sao - Có gì nhắn shop nhen',
                                                2 => '2 sao - Bình tĩnh nhắn cho sop đi',
                                                3 => '3 sao - Nhắn shop đi mò',
                                                4 => '4 sao - Sản phẩm rất tốt',
                                                5 => '5 sao - Sản phẩm tốt mà shop còn đẹp trai nữa'
                                            );
                                            echo isset($nhanSao[$curSao]) ? $nhanSao[$curSao] : '5 sao - Sản phẩm tốt mà shop còn đẹp trai nữa';
                                            ?>
                                        </span>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="noidungDanhGiaDetail" class="form-label text-white-50 small mb-1">Nội dung nhận xét:</label>
                                    <textarea class="form-control bg-black text-white border-secondary" 
                                              id="noidungDanhGiaDetail" 
                                              name="noidung" 
                                              rows="3" 
                                              placeholder="Chia sẻ trải nghiệm về chất liệu, form dáng, độ bền..."><?php echo htmlspecialchars($danhgiaCuaToi['noidung'] ?? ''); ?></textarea>
                                </div>

                                <button type="submit" class="btn btn-danger px-4 fw-semibold">
                                    <i class="fa-solid fa-paper-plane me-1"></i>
                                    <?php echo !empty($danhgiaCuaToi) ? 'Lưu thay đổi' : 'Gửi đánh giá'; ?>
                                </button>
                            </form>
                        </div>
                    <?php endif; ?>

                    <h6 class="text-white-50 text-uppercase fw-bold small mb-3">Tất cả nhận xét &amp; đánh giá</h6>
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
$gsGoiyTieude = 'Sản phẩm & combo gợi ý theo chỉ số cơ thể của bạn';
include(__DIR__ . '/inc/recommendation.php');
?>

<?php if (count($sanphamtuongtu) > 0) : ?>
    <section class="gs-section gs-section--grey">
        <div class="container">
            <div class="gs-section__head">
                <div>
                    <h2 class="gs-section__title">Sản phẩm cùng danh mục</h2>
                </div>
                <a class="gs-section__link" href="index.php?action=sanpham&loai=<?php echo urlencode($sanpham['loai']); ?>">
                    Xem tất cả <i class="fa-solid fa-arrow-right-long"></i>
                </a>
            </div>
            <div class="row g-4">
                <?php
                $gsSizeGoiy = ($gsGoiy && $gsGoiy['sizegoiy']) ? $gsGoiy['sizegoiy']['size'] : '';
                foreach ($sanphamtuongtu as $card) :
                    ?>
                    <?php include(__DIR__ . '/inc/product-card.php'); ?>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
<?php endif; ?>

<script>
function gsGiamSoLuong() {
    var inp = document.getElementById('gsSoLuong');
    if (inp && parseInt(inp.value) > 1) {
        inp.value = parseInt(inp.value) - 1;
    }
}
function gsTangSoLuong() {
    var inp = document.getElementById('gsSoLuong');
    var max = parseInt(inp.getAttribute('max')) || 999;
    if (inp && parseInt(inp.value) < max) {
        inp.value = parseInt(inp.value) + 1;
    }
}
function gsChonMau(el, tenMau) {
    document.querySelectorAll('.gs-mau').forEach(function(m) { m.classList.remove('active'); });
    el.classList.add('active');
    var inp = document.getElementById('gsMauChon');
    if (inp) inp.value = tenMau;
}
</script>

<?php include(__DIR__ . '/inc/footer.php'); ?>
