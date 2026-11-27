<?php
// Chuyển hướng về bộ điều phối index.php nếu truy cập trực tiếp file
if (!isset($gsModule36)) {
    $_REQUEST['action'] = 'chitiet';
    require __DIR__ . '/index.php';
    return;
}
$gsTieude = $sanpham['tensanpham'];

// Tính phần trăm giảm giá, trạng thái tồn kho và danh sách size của sản phẩm
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

// Quy đổi tên màu sắc sang mã màu HEX để hiển thị chấm màu
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

// Tách mô tả thành phần tổng quan (đoạn đầu) và hướng dẫn chi tiết (các đoạn sau)
$motaRaw = (string)$sanpham['mota'];
$motaParts = explode("\n\n", $motaRaw);
$motaTongQuan = array_shift($motaParts);
$motaChiTiet = implode("\n\n", $motaParts);

// Nhúng header giao diện
include(__DIR__ . '/inc/head-client.php');
?>

<!-- Breadcrumb điều hướng -->
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

            <!-- Cột trái: Hình ảnh sản phẩm -->
            <div class="col-12 col-lg-5">
                <div class="gs-detail__media" style="background: #F6F6F6; position: relative;">
                    <img class="gs-detail__img" id="gsDetailMainImg" src="../assets/images/products/<?php echo htmlspecialchars($sanpham['hinhanh']); ?>"
                        alt="<?php echo htmlspecialchars($sanpham['tensanpham']); ?>" onerror="gsOnErrorImg(this)">
                    <?php if ($phantramgiam > 0) : ?>
                        <span class="badge-gs badge-gs--sale gs-detail__sale" style="position: absolute; top: 1rem; left: 1rem;">-<?php echo $phantramgiam; ?>%</span>
                    <?php endif; ?>
                </div>
                <?php if (!empty($sanpham['hinhanh_phu'])) : ?>
                    <div class="d-flex gap-2 mt-2">
                        <img src="../assets/images/products/<?php echo htmlspecialchars($sanpham['hinhanh']); ?>"
                             alt="Ảnh chính"
                             onclick="document.getElementById('gsDetailMainImg').src=this.src;"
                             style="width: 68px; height: 84px; object-fit: cover; cursor: pointer; border: 2px solid #141414;"
                             onerror="this.style.display='none'">
                        <img src="../assets/images/products/<?php echo htmlspecialchars($sanpham['hinhanh_phu']); ?>"
                             alt="Ảnh người mẫu"
                             onclick="document.getElementById('gsDetailMainImg').src=this.src;"
                             style="width: 68px; height: 84px; object-fit: cover; cursor: pointer; border: 1px solid #D1D5DB;"
                             onerror="this.style.display='none'">
                    </div>
                <?php endif; ?>
                <div class="d-flex align-items-center justify-content-between mt-3 px-1 text-muted small">
                    <span>100% Chính hãng</span>
                    <span>•</span>
                    <span>Giao nhanh 24h</span>
                    <span>•</span>
                    <span>Đổi trả 7 ngày</span>
                </div>
            </div>

            <!-- Cột phải: Thông tin giá, size, màu sắc và đặt hàng -->
            <div class="col-12 col-lg-7">
                <div class="d-flex align-items-center gap-2 mb-2">
                    <span class="badge px-2 py-1 text-uppercase fw-bold" style="background: #FFECF3; color: #D02545; font-size: 0.76rem;"><?php echo htmlspecialchars($danhmucCha); ?></span>
                    <span class="badge px-2 py-1 fw-semibold" style="background: #F7F4EC; color: #1A1A1A; font-size: 0.76rem;"><?php echo htmlspecialchars($tenthuonghieu); ?></span>
                </div>

                <h1 class="gs-detail__title mb-2" style="font-size: 2rem; line-height: 1.25;"><?php echo htmlspecialchars($sanpham['tensanpham']); ?></h1>

                <div class="d-flex flex-wrap align-items-center gap-3 mb-3 text-muted small">
                    <div class="d-flex align-items-center gap-1">
                        <span style="color: #FFB74A;"><i class="fa-solid fa-star"></i> <strong class="text-dark"><?php echo number_format($diemDanhgia['diem'], 1, ',', '.'); ?>/5</strong></span>
                        <span>(<?php echo (int) $diemDanhgia['soluot']; ?> đánh giá)</span>
                    </div>
                    <span>•</span>
                    <div>Đã bán <strong class="text-dark"><?php echo (int) $sanpham['luotmua']; ?></strong></div>
                    <span>•</span>
                    <div><strong class="text-dark"><?php echo (int) $sanpham['luotxem'] + 1; ?></strong> lượt xem</div>
                </div>

                <!-- Hiển thị giá bán, giá gốc và số tiền tiết kiệm -->
                <div class="gs-detail__price">
                    <span class="gs-price"><?php echo gsTien($giaban); ?></span>
                    <?php if ($phantramgiam > 0) : ?>
                        <span class="gs-price--old"><?php echo gsTien($giagoc); ?></span>
                        <span class="badge-gs badge-gs--sale">Tiết kiệm <?php echo gsTien($giagoc - $giaban); ?></span>
                    <?php endif; ?>
                </div>

                <!-- Tóm tắt đặc tính, size gợi ý và độ nén / hiệu suất -->
                <div class="gs-pdp-spec-card">
                    <div>
                        <div class="gs-pdp-spec-label">ĐẶC TÍNH (FIT)</div>
                        <div class="gs-pdp-spec-val">
                            <?php
                            if ($sanpham['loai'] === 'tpbs') {
                                echo 'Hấp thu nhanh, tối ưu phục hồi';
                            } elseif ($sanpham['loai'] === 'phukien') {
                                echo 'Cố định chắc chắn, bảo hộ khớp';
                            } else {
                                echo 'Ôm vừa, nâng đỡ tự nhiên';
                            }
                            ?>
                        </div>
                    </div>
                    <div>
                        <div class="gs-pdp-spec-label">ĐỐI TƯỢNG / SIZE</div>
                        <div class="gs-pdp-spec-val">
                            <?php echo ($sanpham['doituong'] === 'nu') ? 'Nữ' : (($sanpham['doituong'] === 'nam') ? 'Nam' : 'Unisex'); ?>
                            <?php if ($sizegoiy !== '') : ?>
                                · Gợi ý <strong><?php echo htmlspecialchars($sizegoiy); ?></strong>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div>
                        <div class="gs-pdp-spec-label"><?php echo ($sanpham['loai'] === 'quanao') ? 'ĐỘ NÉN (COMPRESSION)' : 'HIỆU SUẤT SỬ DỤNG'; ?></div>
                        <div class="gs-pdp-spec-val">Medium - High</div>
                        <div class="gs-compression-bars">
                            <span class="gs-compression-bar is-filled"></span>
                            <span class="gs-compression-bar is-filled"></span>
                            <span class="gs-compression-bar<?php echo ($sanpham['loai'] === 'tpbs') ? ' is-filled' : ''; ?>"></span>
                        </div>
                    </div>
                </div>

                <div class="gs-pdp-benefits">
                    <?php if ($sanpham['loai'] === 'tpbs') : ?>
                        <span class="gs-pdp-benefit-pill">Chính hãng 100%</span>
                        <span class="gs-pdp-benefit-pill">Kiểm định an toàn</span>
                        <span class="gs-pdp-benefit-pill">Dễ hòa tan</span>
                    <?php elseif ($sanpham['loai'] === 'phukien') : ?>
                        <span class="gs-pdp-benefit-pill">Chất liệu bền bỉ</span>
                        <span class="gs-pdp-benefit-pill">Chống trơn trượt</span>
                        <span class="gs-pdp-benefit-pill">Thoải mái khi tập nặng</span>
                    <?php else : ?>
                        <span class="gs-pdp-benefit-pill">Thoáng khí</span>
                        <span class="gs-pdp-benefit-pill">Co giãn 4 chiều</span>
                        <span class="gs-pdp-benefit-pill">Thấm hút mồ hôi</span>
                    <?php endif; ?>
                </div>

                <div class="mb-3">
                    <?php if ($conhang) : ?>
                        <span class="badge bg-success-subtle text-success border border-success-subtle px-3 py-1 rounded-pill">
                            Còn hàng (<?php echo (int) $sanpham['soluongton']; ?> sản phẩm)
                        </span>
                    <?php else : ?>
                        <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-3 py-1 rounded-pill">
                            Tạm hết hàng
                        </span>
                    <?php endif; ?>
                </div>

                <!-- Form chọn size, màu sắc, số lượng và thêm vào giỏ / yêu thích / so sánh -->
                <form class="gs-form-cart gs-detail__form" id="gsFormThemGioMain" method="post" action="index.php">
                    <input type="hidden" name="action" value="themvaogiohang">
                    <input type="hidden" name="sanpham_id" value="<?php echo (int) $sanpham['id']; ?>">

                    <?php if (count($mangsizes) > 0) : ?>
                        <div class="mb-3">
                            <div class="d-flex align-items-center justify-content-between mb-1 flex-wrap gap-2">
                                <div class="d-flex align-items-center gap-2">
                                    <span class="gs-detail__label mb-0">Chọn size</span>
                                    <button type="button" class="btn btn-link p-0 text-decoration-none small text-danger fw-semibold d-inline-flex align-items-center gap-1" data-bs-toggle="modal" data-bs-target="#modalBangSize" style="font-size: 0.82rem;">
                                        <i class="fa-solid fa-ruler-combined"></i> Bảng quy đổi size
                                    </button>
                                </div>
                                <?php if ($sizegoiy !== '') : ?>
                                    <span class="gs-sizegoiy-note mt-0">
                                        Gợi ý theo chỉ số: <strong>Size <?php echo htmlspecialchars($sizegoiy); ?></strong>
                                    </span>
                                <?php endif; ?>
                            </div>
                            <div class="gs-size-options">
                                <?php foreach ($mangsizes as $idx => $size) : ?>
                                    <label class="gs-size-option<?php echo ($sizegoiy === $size) ? ' is-goiy' : ''; ?><?php echo !$conhang ? ' is-disabled' : ''; ?>">
                                        <input type="radio" name="size" value="<?php echo htmlspecialchars($size); ?>"
                                            <?php echo ($sizegoiy === $size || ($sizegoiy === '' && $idx === 0)) ? 'checked' : ''; ?> required <?php echo $conhang ? '' : 'disabled'; ?>>
                                        <span><?php echo htmlspecialchars($size); ?></span>
                                    </label>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php else : ?>
                        <input type="hidden" name="size" value="">
                        <p class="gs-detail__freesize">Sản phẩm tiêu chuẩn (Freesize).</p>
                    <?php endif; ?>

                    <?php if (!empty($sanpham['mausac'])) : ?>
                        <div class="mb-3">
                            <span class="gs-detail__label mb-1 d-block">Màu sắc / Hương vị</span>
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

                    <div class="d-flex align-items-end gap-2 mt-4" id="gsBuyBoxAnchor">
                        <div>
                            <label class="gs-detail__label mb-1 d-block" for="gsSoLuong">Số lượng</label>
                            <div class="gs-qty-group">
                                <button type="button" class="gs-qty-btn" onclick="gsGiamSoLuong()">-</button>
                                <input class="gs-qty-input" type="number" id="gsSoLuong" name="soluong" min="1"
                                    max="<?php echo max(1, (int) $sanpham['soluongton']); ?>" value="1" <?php echo $conhang ? '' : 'disabled'; ?>>
                                <button type="button" class="gs-qty-btn" onclick="gsTangSoLuong()">+</button>
                            </div>
                        </div>
                        <button type="submit" class="btn-gs btn-gs--dark flex-grow-1" style="height: 46px;" <?php echo $conhang ? '' : 'disabled'; ?>>
                            <?php echo $conhang ? 'Thêm vào giỏ hàng' : 'Tạm hết hàng'; ?>
                        </button>
                        <?php $daThichDetail = in_array((int)$sanpham['id'], $dsIdYeuthich ?? array(), true); ?>
                        <button type="button" class="btn btn-outline-danger d-flex align-items-center justify-content-center px-3"
                                style="border-radius: 8px; height: 46px; min-width: 46px;"
                                onclick="gsToggleYeuThich(this, <?php echo (int) $sanpham['id']; ?>)"
                                title="<?php echo $daThichDetail ? 'Bỏ yêu thích' : 'Thêm vào yêu thích'; ?>">
                            <i class="<?php echo $daThichDetail ? 'fa-solid fa-heart text-danger fs-5' : 'fa-regular fa-heart text-danger fs-5'; ?>"></i>
                        </button>
                        <?php $daSosanhDetail = in_array((int)$sanpham['id'], $_SESSION['gs_sosanh'] ?? array(), true); ?>
                        <button type="button" class="btn btn-outline-dark d-flex align-items-center justify-content-center px-3 gap-2 gs-btn-detail-compare"
                                style="border-radius: 8px; min-height: 46px;"
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
                            <strong class="d-block text-dark">Freeship từ 500k</strong>
                            <small>Giao hàng toàn quốc</small>
                        </div>
                    </div>
                    <div class="gs-detail__policy-item">
                        <div>
                            <strong class="d-block text-dark">Đổi trả 7 ngày</strong>
                            <small>Lỗi NSX hoặc đổi size</small>
                        </div>
                    </div>
                    <div class="gs-detail__policy-item">
                        <div>
                            <strong class="d-block text-dark">Chính hãng 100%</strong>
                            <small>Đảm bảo nguồn gốc</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabs thông tin: Mô tả, thông số và đánh giá nhận xét -->
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
                <!-- Tab 1: Mô tả chi tiết và hướng dẫn sử dụng -->
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

                    <div class="gs-mota-section" style="background: #F7F4EC; border-color: rgba(208, 37, 69, 0.15);">
                        <h4 class="gs-mota-title" style="color: #D02545;">
                            Cam kết chất lượng từ GYMBRO
                        </h4>
                        <div class="row g-3 pt-2">
                            <div class="col-12 col-md-4">
                                <strong class="text-dark d-block">100% Chính Hãng</strong>
                                <small class="text-muted">Phân phối trực tiếp, nguồn gốc rõ ràng minh bạch.</small>
                            </div>
                            <div class="col-12 col-md-4">
                                <strong class="text-dark d-block">Đổi Size Linh Hoạt</strong>
                                <small class="text-muted">Hỗ trợ đổi size trong vòng 7 ngày nhanh chóng.</small>
                            </div>
                            <div class="col-12 col-md-4">
                                <strong class="text-dark d-block">Tư Vấn Thể Hình</strong>
                                <small class="text-muted">Gợi ý sản phẩm chuẩn xác theo chỉ số cơ thể của bạn.</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tab 2: Thông số sản phẩm hoặc thành phần dinh dưỡng -->
                <div class="tab-pane fade" id="gsTabThongso" role="tabpanel">
                    <div class="gs-mota-section">
                        <h4 class="gs-mota-title">
                            Thông số cơ bản &amp; Phân loại
                        </h4>
                        <div class="row g-3 pt-1">
                            <div class="col-12 col-md-6">
                                <div class="d-flex align-items-center justify-content-between p-2 rounded bg-white border">
                                    <span class="text-muted small">Danh mục</span>
                                    <strong class="text-dark small"><?php echo htmlspecialchars($sanpham['tendanhmuc']); ?> (<?php echo htmlspecialchars($mucdanhmuc[$sanpham['loai']]); ?>)</strong>
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="d-flex align-items-center justify-content-between p-2 rounded bg-white border">
                                    <span class="text-muted small">Thương hiệu</span>
                                    <strong class="text-dark small"><?php echo htmlspecialchars($tenthuonghieu); ?></strong>
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="d-flex align-items-center justify-content-between p-2 rounded bg-white border">
                                    <span class="text-muted small">Đối tượng</span>
                                    <strong class="text-dark small"><?php echo ($sanpham['doituong'] === 'nu') ? 'Nữ' : (($sanpham['doituong'] === 'nam') ? 'Nam' : 'Unisex (Nam & Nữ)'); ?></strong>
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="d-flex align-items-center justify-content-between p-2 rounded bg-white border">
                                    <span class="text-muted small">Size khả dụng</span>
                                    <strong class="text-dark small"><?php echo (count($mangsizes) > 0) ? htmlspecialchars(implode(', ', $mangsizes)) : 'Freesize'; ?></strong>
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="d-flex align-items-center justify-content-between p-2 rounded bg-white border">
                                    <span class="text-muted small">Màu sắc</span>
                                    <strong class="text-dark small"><?php echo !empty($sanpham['mausac']) ? htmlspecialchars($sanpham['mausac']) : 'Tiêu chuẩn'; ?></strong>
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="d-flex align-items-center justify-content-between p-2 rounded bg-white border">
                                    <span class="text-muted small">Tình trạng kho</span>
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
                                    <strong class="text-dark small text-nowrap" style="min-width: 150px;">Chất liệu cấu tạo:</strong>
                                    <span class="small text-muted"><?php echo htmlspecialchars($sanpham['chatlieu']); ?></span>
                                </div>
                            <?php endif; ?>
                            <?php if (!empty($sanpham['thanhphan'])) : ?>
                                <div class="d-flex align-items-start gap-2 mb-2">
                                    <strong class="text-dark small text-nowrap" style="min-width: 150px;">Thành phần vi chất:</strong>
                                    <span class="small text-muted"><?php echo htmlspecialchars($sanpham['thanhphan']); ?></span>
                                </div>
                            <?php endif; ?>
                            <?php if (!empty($sanpham['hansudung'])) : ?>
                                <div class="d-flex align-items-start gap-2 mb-2">
                                    <strong class="text-dark small text-nowrap" style="min-width: 150px;">Hạn dùng khuyến nghị:</strong>
                                    <span class="small fw-semibold text-danger"><?php echo htmlspecialchars(date('d/m/Y', strtotime($sanpham['hansudung']))); ?></span>
                                </div>
                            <?php endif; ?>
                            <?php if (empty($sanpham['chatlieu']) && empty($sanpham['thanhphan']) && empty($sanpham['hansudung'])) : ?>
                                <p class="small text-muted mb-0">Sản phẩm đạt chứng nhận an toàn thể thao và độ bền tiêu chuẩn quốc tế.</p>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="gs-mota-section" style="background: #F7F4EC; border-color: rgba(208, 37, 69, 0.15);">
                        <h4 class="gs-mota-title" style="color: #D02545;">
                            Mục tiêu luyện tập đề xuất
                        </h4>
                        <div class="d-flex flex-wrap gap-2 pt-1 mb-2">
                            <?php
                            $danhSachMuctieu = explode(',', (string) $sanpham['muctieu']);
                            foreach ($danhSachMuctieu as $mt) :
                                $mtTrim = trim($mt);
                                if ($mtTrim !== '') :
                            ?>
                                <span class="badge bg-white text-dark border" style="padding: 0.5rem 0.9rem; font-size: 0.84rem;">
                                    <?php echo htmlspecialchars(gsTenMuctieu($mtTrim)); ?>
                                </span>
                            <?php
                                endif;
                            endforeach;
                            ?>
                        </div>
                        <small class="text-muted d-block mt-2">
                            Hệ thống GYMBRO tự động phân tích và tối ưu sản phẩm này phù hợp cho gymer theo đuổi các mục tiêu thể trạng trên.
                        </small>
                    </div>

                    <div class="gs-mota-section">
                        <h4 class="gs-mota-title">
                            Tiêu chuẩn chứng nhận &amp; Bảo hành
                        </h4>
                        <div class="row g-3 pt-2">
                            <div class="col-12 col-md-4">
                                <strong class="text-dark d-block small">Nguồn Gốc Xuất Xứ</strong>
                                <small class="text-muted">Nhập khẩu chính hãng từ thương hiệu <?php echo htmlspecialchars($tenthuonghieu); ?>.</small>
                            </div>
                            <div class="col-12 col-md-4">
                                <strong class="text-dark d-block small">Bảo Hành Đổi Trả</strong>
                                <small class="text-muted">Đổi trả 1-1 trong 7 ngày nếu lỗi kỹ thuật từ nhà sản xuất.</small>
                            </div>
                            <div class="col-12 col-md-4">
                                <strong class="text-dark d-block small">Đóng Gói Chuyên Nghiệp</strong>
                                <small class="text-muted">Hộp carton bảo vệ 3 lớp, chống va đập trong quá trình vận chuyển.</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tab 3: Gửi đánh giá (khi đã mua) và danh sách nhận xét -->
                <div class="tab-pane fade" id="gsTabDanhgia" role="tabpanel">
                    <?php if ($gsNguoidungId <= 0) : ?>
                        <div class="p-3 mb-4 rounded border bg-light d-flex align-items-center gap-2">
                            <i class="fa-solid fa-lock text-danger"></i>
                            <span>Vui lòng <a href="index.php?action=dangnhap" class="text-danger fw-bold">đăng nhập</a> để đánh giá sản phẩm sau khi mua hàng.</span>
                        </div>
                    <?php elseif (empty($duocDanhgia)) : ?>
                        <div class="p-3 mb-4 rounded border bg-light d-flex align-items-center gap-2">
                            <i class="fa-solid fa-circle-info text-dark"></i>
                            <span>Chỉ khách hàng <strong>đã mua và nhận hàng thành công</strong> mới có thể gửi đánh giá sản phẩm này.</span>
                        </div>
                    <?php else : ?>
                        <div class="card bg-white border p-4 mb-4 rounded-3 shadow-sm">
                            <div class="d-flex align-items-center justify-content-between mb-3 flex-wrap gap-2">
                                <h5 class="m-0 text-dark fw-bold">
                                    <?php echo !empty($danhgiaCuaToi) ? 'Cập nhật đánh giá của bạn' : 'Viết đánh giá của bạn'; ?>
                                </h5>
                                <?php if (!empty($danhgiaCuaToi)) : ?>
                                    <span class="badge bg-success-subtle text-success border border-success-subtle">
                                        Đã đánh giá (<?php echo date('d/m/Y', strtotime($danhgiaCuaToi['ngay'])); ?>)
                                    </span>
                                <?php endif; ?>
                            </div>

                            <form action="index.php?action=danhgiasanpham" method="POST">
                                <input type="hidden" name="sanpham_id" value="<?php echo (int) $sanpham['id']; ?>">
                                <input type="hidden" name="sao" id="inputRatingStarDetail" value="<?php echo !empty($danhgiaCuaToi) ? (int)$danhgiaCuaToi['sao'] : 5; ?>">

                                <div class="mb-3">
                                    <label class="form-label text-muted small mb-1">Mức độ hài lòng:</label>
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
                                    <label for="noidungDanhGiaDetail" class="form-label text-muted small mb-1">Nội dung nhận xét:</label>
                                    <textarea class="form-control" 
                                              id="noidungDanhGiaDetail" 
                                              name="noidung" 
                                              rows="3" 
                                              placeholder="Chia sẻ trải nghiệm về chất liệu, form dáng, độ bền..."><?php echo htmlspecialchars($danhgiaCuaToi['noidung'] ?? ''); ?></textarea>
                                </div>

                                <button type="submit" class="btn-gs btn-gs--primary px-4">
                                    <?php echo !empty($danhgiaCuaToi) ? 'Lưu thay đổi' : 'Gửi đánh giá'; ?>
                                </button>
                            </form>
                        </div>
                    <?php endif; ?>

                    <h6 class="text-muted text-uppercase fw-bold small mb-3">Tất cả nhận xét &amp; đánh giá</h6>
                    <?php if (count($dsDanhgia) === 0) : ?>
                        <p class="text-muted">Chưa có đánh giá nào cho sản phẩm này.</p>
                    <?php else : ?>
                        <?php foreach ($dsDanhgia as $dg) : ?>
                            <div class="gs-review">
                                <div class="gs-review__head">
                                    <strong class="text-dark"><?php echo htmlspecialchars($dg['hoten']); ?></strong>
                                    <span class="gs-review__sao">
                                        <?php for ($i = 1; $i <= 5; $i++) : ?>
                                            <i class="fa-<?php echo ($i <= (int) $dg['sao']) ? 'solid' : 'regular'; ?> fa-star"></i>
                                        <?php endfor; ?>
                                    </span>
                                    <span class="text-muted small"><?php echo htmlspecialchars(date('d/m/Y', strtotime($dg['ngay']))); ?></span>
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

<!-- Thanh mua nhanh cố định khi cuộn trang -->
<div class="gs-sticky-quickbuy" id="gsStickyQuickBuy">
    <img src="../assets/images/products/<?php echo htmlspecialchars($sanpham['hinhanh']); ?>" alt="" class="gs-sticky-quickbuy__img" onerror="gsOnErrorImg(this)">
    <div class="gs-sticky-quickbuy__info">
        <div class="gs-sticky-quickbuy__title"><?php echo htmlspecialchars($sanpham['tensanpham']); ?></div>
        <div class="gs-sticky-quickbuy__price"><?php echo gsTien($giaban); ?></div>
    </div>
    <?php if ($conhang) : ?>
        <button type="button" class="btn-gs btn-gs--dark btn-gs--sm" onclick="document.getElementById('gsFormThemGioMain').requestSubmit ? document.getElementById('gsFormThemGioMain').requestSubmit() : document.getElementById('gsFormThemGioMain').submit();">
            Thêm vào giỏ
        </button>
    <?php endif; ?>
</div>

<!-- Gợi ý sản phẩm & combo theo chỉ số cơ thể (BMI) -->
<?php
$gsGoiyTieude = 'Sản phẩm & combo gợi ý theo chỉ số cơ thể của bạn';
include(__DIR__ . '/inc/recommendation.php');
?>

<!-- Gợi ý sản phẩm tương tự cùng danh mục -->
<?php if (count($sanphamtuongtu) > 0) : ?>
    <section class="gs-section gs-section--grey">
        <div class="container">
            <div class="gs-section__head">
                <div>
                    <h2 class="gs-section__title">Sản phẩm cùng danh mục</h2>
                </div>
                <a class="gs-section__link" href="index.php?action=sanpham&loai=<?php echo urlencode($sanpham['loai']); ?>">
                    Xem tất cả &rarr;
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

<!-- Modal bảng quy đổi size đồ tập GymBro -->
<div class="modal fade" id="modalBangSize" tabindex="-1" aria-labelledby="modalBangSizeLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 16px; overflow: hidden;">
            <div class="modal-header border-0 bg-dark text-white p-3 p-md-4">
                <div class="d-flex align-items-center gap-2">
                    <i class="fa-solid fa-ruler-combined text-danger fs-4"></i>
                    <div>
                        <h5 class="modal-title fw-bold mb-0 text-white" id="modalBangSizeLabel">BẢNG QUY ĐỔI SIZE CHUẨN GYMBRO</h5>
                        <small class="text-white-50">Chuẩn phom dáng thể hình &amp; co giãn 4 chiều</small>
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Đóng"></button>
            </div>
            <div class="modal-body p-3 p-md-4">
                <ul class="nav nav-pills nav-fill mb-3" id="pills-size-tab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active fw-bold py-2" id="tab-size-nam-btn" data-bs-toggle="pill" data-bs-target="#tab-size-nam" type="button" role="tab">
                            <i class="fa-solid fa-mars me-1 text-primary"></i> Đồ tập Nam
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link fw-bold py-2" id="tab-size-nu-btn" data-bs-toggle="pill" data-bs-target="#tab-size-nu" type="button" role="tab">
                            <i class="fa-solid fa-venus me-1 text-danger"></i> Đồ tập Nữ
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link fw-bold py-2" id="tab-size-tip-btn" data-bs-toggle="pill" data-bs-target="#tab-size-tip" type="button" role="tab">
                            <i class="fa-solid fa-lightbulb me-1 text-warning"></i> Mẹo chọn size
                        </button>
                    </li>
                </ul>

                <div class="tab-content" id="pills-size-content">
                    <!-- Tab Nam -->
                    <div class="tab-pane fade show active" id="tab-size-nam" role="tabpanel">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover text-center align-middle mb-2">
                                <thead class="table-light">
                                    <tr>
                                        <th class="text-danger fw-bold">Size</th>
                                        <th>Chiều cao (cm)</th>
                                        <th>Cân nặng (kg)</th>
                                        <th>Vòng ngực (cm)</th>
                                        <th>Vòng eo (cm)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><strong class="badge bg-dark px-2 py-1">S</strong></td>
                                        <td>160 - 167</td>
                                        <td>52 - 62</td>
                                        <td>86 - 92</td>
                                        <td>68 - 74</td>
                                    </tr>
                                    <tr>
                                        <td><strong class="badge bg-dark px-2 py-1">M</strong></td>
                                        <td>168 - 173</td>
                                        <td>63 - 70</td>
                                        <td>93 - 98</td>
                                        <td>75 - 80</td>
                                    </tr>
                                    <tr>
                                        <td><strong class="badge bg-dark px-2 py-1">L</strong></td>
                                        <td>174 - 178</td>
                                        <td>71 - 78</td>
                                        <td>99 - 104</td>
                                        <td>81 - 86</td>
                                    </tr>
                                    <tr>
                                        <td><strong class="badge bg-dark px-2 py-1">XL</strong></td>
                                        <td>179 - 185</td>
                                        <td>79 - 88</td>
                                        <td>105 - 110</td>
                                        <td>87 - 92</td>
                                    </tr>
                                    <tr>
                                        <td><strong class="badge bg-dark px-2 py-1">XXL</strong></td>
                                        <td>182 - 190</td>
                                        <td>89 - 100+</td>
                                        <td>111 - 118</td>
                                        <td>93 - 100</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <small class="text-muted d-block mt-2">
                            * Bảng số đo áp dụng cho các dòng áo tập Body Fit, Áo ba lỗ Tanktop và Quần tập gym nam GymBro.
                        </small>
                    </div>

                    <!-- Tab Nữ -->
                    <div class="tab-pane fade" id="tab-size-nu" role="tabpanel">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover text-center align-middle mb-2">
                                <thead class="table-light">
                                    <tr>
                                        <th class="text-danger fw-bold">Size</th>
                                        <th>Chiều cao (cm)</th>
                                        <th>Cân nặng (kg)</th>
                                        <th>Vòng ngực (cm)</th>
                                        <th>Vòng mông (cm)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><strong class="badge bg-danger px-2 py-1">S</strong></td>
                                        <td>150 - 156</td>
                                        <td>42 - 48</td>
                                        <td>78 - 83</td>
                                        <td>85 - 90</td>
                                    </tr>
                                    <tr>
                                        <td><strong class="badge bg-danger px-2 py-1">M</strong></td>
                                        <td>157 - 163</td>
                                        <td>49 - 54</td>
                                        <td>84 - 89</td>
                                        <td>91 - 96</td>
                                    </tr>
                                    <tr>
                                        <td><strong class="badge bg-danger px-2 py-1">L</strong></td>
                                        <td>164 - 169</td>
                                        <td>55 - 61</td>
                                        <td>90 - 95</td>
                                        <td>97 - 102</td>
                                    </tr>
                                    <tr>
                                        <td><strong class="badge bg-danger px-2 py-1">XL</strong></td>
                                        <td>170 - 176</td>
                                        <td>62 - 70</td>
                                        <td>96 - 102</td>
                                        <td>103 - 110</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <small class="text-muted d-block mt-2">
                            * Bảng số đo áp dụng cho Áo tập Bra, Áo croptop và Quần Legging nữ GymBro Ambassador Collection.
                        </small>
                    </div>

                    <!-- Tab Mẹo chọn size -->
                    <div class="tab-pane fade" id="tab-size-tip" role="tabpanel">
                        <div class="p-3 bg-light rounded-3">
                            <h6 class="fw-bold text-dark mb-2"><i class="fa-solid fa-dumbbell text-danger me-2"></i>Mẹo chọn đồ chuẩn cho Gymer:</h6>
                            <ul class="small text-muted ps-3 mb-3" style="line-height: 1.7;">
                                <li><strong>Form Body Fit (Ôm sát cơ):</strong> Chọn đúng size theo bảng số đo để tôn trọn nét cắt cơ bắp và hỗ trợ cố định cơ khi tập nặng.</li>
                                <li><strong>Form Oversize / Regular (Thoải mái):</strong> Tăng lên 1 size nếu bạn thích sự rộng rãi, thoáng mát trong các buổi tập Cardio hay Leg Day.</li>
                                <li><strong>Ở giữa 2 size:</strong> Nếu cân nặng của bạn nằm ở ranh giới giữa 2 size, hãy ưu tiên chọn size lớn hơn để luôn thoải mái vận động.</li>
                            </ul>
                            <div class="alert alert-danger py-2 px-3 small mb-0 d-flex align-items-center gap-2" role="alert">
                                <i class="fa-solid fa-rotate-left fs-5"></i>
                                <div><strong>Chính sách đổi size:</strong> GymBro hỗ trợ đổi size miễn phí trong vòng 7 ngày nếu không vừa vặn.</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-0 pt-0">
                <button type="button" class="btn btn-dark w-100 fw-semibold py-2" data-bs-dismiss="modal">Đã hiểu</button>
            </div>
        </div>
    </div>
</div>

<!-- Script xử lý số lượng, màu sắc, đánh giá sao và thanh mua nhanh -->
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
document.addEventListener('DOMContentLoaded', function () {
    var quickBuy = document.getElementById('gsStickyQuickBuy');
    var anchor = document.getElementById('gsBuyBoxAnchor');
    if (quickBuy && anchor) {
        window.addEventListener('scroll', function () {
            var rect = anchor.getBoundingClientRect();
            quickBuy.classList.toggle('is-visible', rect.bottom < 0);
        }, { passive: true });
    }
    var starPicker = document.getElementById('starRatingDetail');
    var starInput = document.getElementById('inputRatingStarDetail');
    var starLabel = document.getElementById('starRatingDetailLabel');
    if (starPicker && starInput) {
        var nodes = starPicker.querySelectorAll('.star-node');
        var texts = {
            1: '1 sao - Có gì nhắn shop nhen',
            2: '2 sao - Bình tĩnh nhắn cho sop đi',
            3: '3 sao - Nhắn shop đi mò',
            4: '4 sao - Sản phẩm rất tốt',
            5: '5 sao - Sản phẩm tốt mà shop còn đẹp trai nữa'
        };
        function paint(v) {
            nodes.forEach(function(n) {
                var val = parseInt(n.dataset.val, 10);
                n.className = (val <= v ? 'fa-solid' : 'fa-regular') + ' fa-star star-node';
            });
            if (starLabel && texts[v]) starLabel.textContent = texts[v];
        }
        nodes.forEach(function(n) {
            n.addEventListener('mouseenter', function() { paint(parseInt(this.dataset.val, 10)); });
            n.addEventListener('click', function() {
                var v = parseInt(this.dataset.val, 10);
                starInput.value = v;
                paint(v);
            });
        });
        starPicker.addEventListener('mouseleave', function() { paint(parseInt(starInput.value, 10)); });
    }
});
</script>

<!-- Nhúng footer chân trang -->
<?php include(__DIR__ . '/inc/footer.php'); ?>
