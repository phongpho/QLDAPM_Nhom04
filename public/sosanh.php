<?php
// kiem tra bao mat module
if (!isset($gsModule36)) {
    $_REQUEST['action'] = 'sosanh';
    require __DIR__ . '/index.php';
    return;
}

$gsTieude = 'So sánh sản phẩm';
include(__DIR__ . '/inc/head-client.php');
?>

<main class="gs-main py-5" style="background-color: #0b0f17; min-height: 85vh;">
    <div class="container">
        <!-- breadcrumb -->
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb gs-breadcrumb m-0">
                <li class="breadcrumb-item"><a href="index.php" class="text-white-50 text-decoration-none"><i class="fa-solid fa-house me-1"></i> Trang chủ</a></li>
                <li class="breadcrumb-item active text-warning" aria-current="page">So sánh sản phẩm</li>
            </ol>
        </nav>

        <!-- tieu de trang -->
        <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 mb-4 pb-3 border-bottom border-secondary">
            <div>
                <span class="badge bg-warning text-dark fw-bold mb-2 px-3 py-1 text-uppercase letter-spacing-1">Module 5 • Đối chiếu thông số</span>
                <h1 class="h2 text-white fw-bold mb-1">
                    <i class="fa-solid fa-code-compare text-warning me-2"></i>Bảng so sánh sản phẩm
                </h1>
                <p class="text-white-50 mb-0">Đối chiếu chi tiết giá bán, thương hiệu, chất liệu, thành phần dinh dưỡng và mục tiêu tập luyện để chọn sản phẩm phù hợp nhất.</p>
            </div>
            <div class="d-flex align-items-center gap-2">
                <?php if (!empty($dsSosanh)) : ?>
                    <button type="button" class="btn btn-outline-danger btn-sm px-3 py-2" onclick="gsXoaTatCaSoSanh()">
                        <i class="fa-solid fa-trash-can me-1"></i> Xóa tất cả
                    </button>
                <?php endif; ?>
                <a href="index.php?action=sanpham" class="btn btn-outline-warning btn-sm px-3 py-2">
                    <i class="fa-solid fa-plus me-1"></i> Chọn thêm sản phẩm
                </a>
            </div>
        </div>

        <?php if (empty($dsSosanh)) : ?>
            <!-- trang thai trong khi chua chon san pham -->
            <div class="gs-empty-state text-center py-5 px-3 rounded-4 mb-5" style="background: #111827; border: 1px dashed rgba(249, 115, 22, 0.3);">
                <div class="rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px; background: rgba(249, 115, 22, 0.1);">
                    <i class="fa-solid fa-code-compare fs-1 text-warning"></i>
                </div>
                <h3 class="text-white fw-bold mb-2">Chưa có sản phẩm nào trong danh sách so sánh</h3>
                <p class="text-white-50 mx-auto mb-4" style="max-width: 500px;">
                    Hãy bấm vào biểu tượng so sánh <i class="fa-solid fa-code-compare text-warning"></i> trên mỗi sản phẩm ở trang cửa hàng để bắt đầu đối chiếu thông số kỹ thuật.
                </p>
                <a href="index.php?action=sanpham" class="btn btn-danger px-4 py-2 fw-semibold">
                    <i class="fa-solid fa-bag-shopping me-2"></i>Khám phá sản phẩm ngay
                </a>
            </div>
        <?php else : ?>
            <!-- bang so sanh chi tiet -->
            <div class="gs-compare-container rounded-4 overflow-hidden mb-5 shadow-lg" style="background: #131b26; border: 1px solid rgba(255, 255, 255, 0.08);">
                <div class="table-responsive">
                    <table class="table table-dark table-bordered gs-compare-table m-0 align-middle">
                        <colgroup>
                            <col style="width: 20%; min-width: 180px;">
                            <?php foreach ($dsSosanh as $p) : ?>
                                <col style="width: <?php echo (80 / max(count($dsSosanh), 1)); ?>%; min-width: 240px;">
                            <?php endforeach; ?>
                            <?php if (count($dsSosanh) < 3) : ?>
                                <col style="min-width: 220px;">
                            <?php endif; ?>
                        </colgroup>
                        <tbody>
                            <!-- hang 1: anh va ten san pham -->
                            <tr>
                                <th class="gs-compare-label text-warning text-uppercase fw-bold p-3" style="background: #0d131d;">
                                    Sản phẩm
                                </th>
                                <?php foreach ($dsSosanh as $p) : 
                                    $img = !empty($p['hinhanh']) ? '../assets/images/products/' . $p['hinhanh'] : '../assets/images/systems/no-image.png';
                                    $conhang = ((int) $p['soluongton'] > 0);
                                    $mangsizes = $sp->tachsizethanhmmang($p['sizes'] ?? '');
                                ?>
                                    <td class="p-3 text-center position-relative">
                                        <button type="button" class="btn btn-sm btn-outline-danger position-absolute top-0 end-0 m-2 rounded-circle d-flex align-items-center justify-content-center p-0" 
                                                style="width: 28px; height: 28px;"
                                                onclick="gsBoSoSanh(<?php echo (int) $p['id']; ?>)" 
                                                title="Bỏ khỏi so sánh">
                                            <i class="fa-solid fa-xmark"></i>
                                        </button>
                                        <a href="index.php?action=chitiet&id=<?php echo (int) $p['id']; ?>" class="d-block mb-3 text-decoration-none">
                                            <img src="<?php echo htmlspecialchars($img); ?>" 
                                                 alt="<?php echo htmlspecialchars($p['tensanpham']); ?>" 
                                                 class="img-fluid rounded-3 gs-compare-img"
                                                 style="width: 150px; height: 150px; object-fit: contain; background: #070a0f; padding: 6px;"
                                                 onerror="gsOnErrorImg(this)">
                                        </a>
                                        <div class="small text-warning text-uppercase fw-semibold mb-1">
                                            <?php echo htmlspecialchars($p['tendanhmuc'] ?? ''); ?>
                                        </div>
                                        <h5 class="fw-bold mb-3 fs-6">
                                            <a href="index.php?action=chitiet&id=<?php echo (int) $p['id']; ?>" class="text-white text-decoration-none hover-warning">
                                                <?php echo htmlspecialchars($p['tensanpham']); ?>
                                            </a>
                                        </h5>
                                        <!-- nut them vao gio -->
                                        <?php if ($conhang) : ?>
                                            <?php if (count($mangsizes) > 0) : ?>
                                                <a href="index.php?action=chitiet&id=<?php echo (int) $p['id']; ?>" class="btn btn-outline-warning btn-sm w-100 fw-semibold">
                                                    <i class="fa-solid fa-list-check me-1"></i> Chọn size mua ngay
                                                </a>
                                            <?php else : ?>
                                                <form method="post" action="index.php" class="m-0">
                                                    <input type="hidden" name="action" value="themvaogiohang">
                                                    <input type="hidden" name="sanpham_id" value="<?php echo (int) $p['id']; ?>">
                                                    <input type="hidden" name="size" value="">
                                                    <input type="hidden" name="soluong" value="1">
                                                    <button type="submit" class="btn btn-danger btn-sm w-100 fw-semibold">
                                                        <i class="fa-solid fa-bag-shopping me-1"></i> Thêm vào giỏ
                                                    </button>
                                                </form>
                                            <?php endif; ?>
                                        <?php else : ?>
                                            <button class="btn btn-secondary btn-sm w-100 disabled" disabled>Hết hàng</button>
                                        <?php endif; ?>
                                    </td>
                                <?php endforeach; ?>
                                <?php if (count($dsSosanh) < 3) : 
                                    $linkThem = !empty($dsSosanh) ? 'index.php?action=sanpham&danhmuc_id=' . (int)$dsSosanh[0]['danhmuc_id'] : 'index.php?action=sanpham';
                                    $tenDmHienTai = !empty($dsSosanh) ? ($dsSosanh[0]['tendanhmuc'] ?? '') : '';
                                ?>
                                    <td class="p-3 text-center align-middle" style="background: rgba(0,0,0,0.2);">
                                        <div class="p-4 border border-secondary border-dashed rounded-3">
                                            <i class="fa-solid fa-circle-plus fs-1 text-warning mb-2 d-block"></i>
                                            <span class="text-white-50 d-block small mb-2">Thêm sản phẩm khác để đối chiếu (Tối đa 3 món)</span>
                                            <?php if ($tenDmHienTai !== '') : ?>
                                                <span class="badge bg-warning-subtle text-warning border border-warning-subtle mb-3 d-inline-block">Cùng loại: <?php echo htmlspecialchars($tenDmHienTai); ?></span>
                                            <?php endif; ?>
                                            <div>
                                                <a href="<?php echo htmlspecialchars($linkThem); ?>" class="btn btn-outline-warning btn-sm">
                                                    <i class="fa-solid fa-magnifying-glass me-1"></i> Chọn thêm sản phẩm
                                                </a>
                                            </div>
                                        </div>
                                    </td>
                                <?php endif; ?>
                            </tr>

                            <!-- hang 2: gia ban va khuyen mai -->
                            <tr>
                                <th class="gs-compare-label text-white-50 p-3" style="background: #0d131d;">
                                    <i class="fa-solid fa-tag text-warning me-2"></i>Giá bán
                                </th>
                                <?php foreach ($dsSosanh as $p) : 
                                    $giaban = (float) $p['giaban'];
                                    $giagoc = (float) $p['giagoc'];
                                    $pt = ($giagoc > $giaban && $giagoc > 0) ? (int) round(100 - ($giaban * 100 / $giagoc)) : 0;
                                ?>
                                    <td class="p-3">
                                        <div class="fs-5 fw-bold text-warning mb-1">
                                            <?php echo number_format($giaban, 0, ',', '.'); ?>đ
                                        </div>
                                        <?php if ($pt > 0) : ?>
                                            <div class="d-flex align-items-center gap-2">
                                                <span class="text-white-50 text-decoration-line-through small">
                                                    <?php echo number_format($giagoc, 0, ',', '.'); ?>đ
                                                </span>
                                                <span class="badge bg-danger rounded-pill">-<?php echo $pt; ?>%</span>
                                            </div>
                                        <?php else : ?>
                                            <span class="text-white-50 small">Giá niêm yết chính hãng</span>
                                        <?php endif; ?>
                                    </td>
                                <?php endforeach; ?>
                                <?php if (count($dsSosanh) < 3) : ?><td></td><?php endif; ?>
                            </tr>

                            <!-- hang 3: danh gia va luot ban -->
                            <tr>
                                <th class="gs-compare-label text-white-50 p-3" style="background: #0d131d;">
                                    <i class="fa-solid fa-star text-warning me-2"></i>Đánh giá & Độ hot
                                </th>
                                <?php foreach ($dsSosanh as $p) : 
                                    $dg = $p['danhgia_thongke'] ?? array('diem' => 5, 'soluot' => 0);
                                    $diem = $dg['diem'] > 0 ? $dg['diem'] : 5;
                                ?>
                                    <td class="p-3">
                                        <div class="d-flex align-items-center gap-1 text-warning mb-1">
                                            <?php for ($i = 1; $i <= 5; $i++) : ?>
                                                <i class="fa-solid fa-star small <?php echo $i <= round($diem) ? 'text-warning' : 'text-white-50'; ?>"></i>
                                            <?php endfor; ?>
                                            <span class="text-white fw-bold ms-1 small"><?php echo $diem; ?>/5</span>
                                            <span class="text-white-50 small">(<?php echo (int) $dg['soluot']; ?>)</span>
                                        </div>
                                        <div class="small text-white-50">
                                            <i class="fa-solid fa-fire text-danger me-1"></i>Đã bán <?php echo (int) $p['luotmua']; ?> lượt
                                        </div>
                                    </td>
                                <?php endforeach; ?>
                                <?php if (count($dsSosanh) < 3) : ?><td></td><?php endif; ?>
                            </tr>

                            <!-- hang 4: thuong hieu -->
                            <tr>
                                <th class="gs-compare-label text-white-50 p-3" style="background: #0d131d;">
                                    <i class="fa-solid fa-copyright text-warning me-2"></i>Thương hiệu
                                </th>
                                <?php foreach ($dsSosanh as $p) : ?>
                                    <td class="p-3">
                                        <?php if (!empty($p['tenthuonghieu'])) : ?>
                                            <span class="badge bg-dark border border-warning text-warning px-3 py-2 fs-6 fw-semibold">
                                                <?php echo htmlspecialchars($p['tenthuonghieu']); ?>
                                            </span>
                                        <?php else : ?>
                                            <span class="text-white-50">GYMBRO Chính Hãng</span>
                                        <?php endif; ?>
                                    </td>
                                <?php endforeach; ?>
                                <?php if (count($dsSosanh) < 3) : ?><td></td><?php endif; ?>
                            </tr>

                            <!-- hang 5: doi tuong su dung -->
                            <tr>
                                <th class="gs-compare-label text-white-50 p-3" style="background: #0d131d;">
                                    <i class="fa-solid fa-users text-warning me-2"></i>Đối tượng
                                </th>
                                <?php foreach ($dsSosanh as $p) : 
                                    $dt = strtolower($p['doituong'] ?? 'unisex');
                                ?>
                                    <td class="p-3">
                                        <?php if ($dt === 'nam') : ?>
                                            <span class="badge bg-primary px-2 py-1"><i class="fa-solid fa-mars me-1"></i> Dành cho Nam</span>
                                        <?php elseif ($dt === 'nu') : ?>
                                            <span class="badge bg-danger px-2 py-1"><i class="fa-solid fa-venus me-1"></i> Dành cho Nữ</span>
                                        <?php else : ?>
                                            <span class="badge bg-secondary px-2 py-1"><i class="fa-solid fa-venus-mars me-1"></i> Unisex (Nam & Nữ)</span>
                                        <?php endif; ?>
                                    </td>
                                <?php endforeach; ?>
                                <?php if (count($dsSosanh) < 3) : ?><td></td><?php endif; ?>
                            </tr>

                            <!-- hang 6: muc tieu tap luyen -->
                            <tr>
                                <th class="gs-compare-label text-white-50 p-3" style="background: #0d131d;">
                                    <i class="fa-solid fa-bullseye text-warning me-2"></i>Mục tiêu tập luyện
                                </th>
                                <?php foreach ($dsSosanh as $p) : 
                                    $muctieuArr = !empty($p['muctieu']) ? explode(',', $p['muctieu']) : array();
                                ?>
                                    <td class="p-3">
                                        <div class="d-flex flex-wrap gap-1">
                                            <?php 
                                            if (empty($muctieuArr)) {
                                                echo '<span class="text-white-50 small">Mọi mục tiêu tập luyện</span>';
                                            } else {
                                                foreach ($muctieuArr as $mt) {
                                                    $mt = trim($mt);
                                                    if ($mt === 'tangcan') {
                                                        echo '<span class="badge bg-success-subtle text-success border border-success-subtle">Tăng cơ / Tăng cân</span>';
                                                    } elseif ($mt === 'giamcan') {
                                                        echo '<span class="badge bg-danger-subtle text-danger border border-danger-subtle">Giảm mỡ / Siết cân</span>';
                                                    } elseif ($mt === 'duytri') {
                                                        echo '<span class="badge bg-info-subtle text-info border border-info-subtle">Duy trì vóc dáng</span>';
                                                    }
                                                }
                                            }
                                            ?>
                                        </div>
                                    </td>
                                <?php endforeach; ?>
                                <?php if (count($dsSosanh) < 3) : ?><td></td><?php endif; ?>
                            </tr>

                            <!-- hang 7: chat lieu vai / phu kien -->
                            <tr>
                                <th class="gs-compare-label text-white-50 p-3" style="background: #0d131d;">
                                    <i class="fa-solid fa-shirt text-warning me-2"></i>Chất liệu
                                </th>
                                <?php foreach ($dsSosanh as $p) : ?>
                                    <td class="p-3">
                                        <?php if (!empty($p['chatlieu'])) : ?>
                                            <span class="text-white fw-semibold"><?php echo htmlspecialchars($p['chatlieu']); ?></span>
                                        <?php else : ?>
                                            <span class="text-white-50 italic">Không áp dụng (TPBS)</span>
                                        <?php endif; ?>
                                    </td>
                                <?php endforeach; ?>
                                <?php if (count($dsSosanh) < 3) : ?><td></td><?php endif; ?>
                            </tr>

                            <!-- hang 8: thanh phan dinh duong (tpbs) -->
                            <tr>
                                <th class="gs-compare-label text-white-50 p-3" style="background: #0d131d;">
                                    <i class="fa-solid fa-dna text-warning me-2"></i>Thành phần dinh dưỡng
                                </th>
                                <?php foreach ($dsSosanh as $p) : ?>
                                    <td class="p-3">
                                        <?php if (!empty($p['thanhphan'])) : ?>
                                            <div class="small p-2 rounded-2" style="background: rgba(249, 115, 22, 0.08); border-left: 3px solid #f97316; color: #fed7aa;">
                                                <?php echo nl2br(htmlspecialchars($p['thanhphan'])); ?>
                                            </div>
                                        <?php else : ?>
                                            <span class="text-white-50 italic">Không áp dụng (Thời trang & Phụ kiện)</span>
                                        <?php endif; ?>
                                    </td>
                                <?php endforeach; ?>
                                <?php if (count($dsSosanh) < 3) : ?><td></td><?php endif; ?>
                            </tr>

                            <!-- hang 9: kich thuoc (size) -->
                            <tr>
                                <th class="gs-compare-label text-white-50 p-3" style="background: #0d131d;">
                                    <i class="fa-solid fa-ruler-combined text-warning me-2"></i>Size có sẵn
                                </th>
                                <?php foreach ($dsSosanh as $p) : 
                                    $sizes = $sp->tachsizethanhmmang($p['sizes'] ?? '');
                                ?>
                                    <td class="p-3">
                                        <?php if (!empty($sizes)) : ?>
                                            <div class="d-flex flex-wrap gap-1">
                                                <?php foreach ($sizes as $s) : ?>
                                                    <span class="badge bg-dark border border-secondary text-white px-2 py-1"><?php echo htmlspecialchars($s); ?></span>
                                                <?php endforeach; ?>
                                            </div>
                                        <?php else : ?>
                                            <span class="text-white-50">Free size / Tiêu chuẩn</span>
                                        <?php endif; ?>
                                    </td>
                                <?php endforeach; ?>
                                <?php if (count($dsSosanh) < 3) : ?><td></td><?php endif; ?>
                            </tr>

                            <!-- hang 10: mau sac -->
                            <tr>
                                <th class="gs-compare-label text-white-50 p-3" style="background: #0d131d;">
                                    <i class="fa-solid fa-palette text-warning me-2"></i>Màu sắc / Hương vị
                                </th>
                                <?php foreach ($dsSosanh as $p) : ?>
                                    <td class="p-3">
                                        <?php if (!empty($p['mausac'])) : ?>
                                            <span class="text-white-50"><?php echo htmlspecialchars($p['mausac']); ?></span>
                                        <?php else : ?>
                                            <span class="text-white-50">Mặc định theo nhà sản xuất</span>
                                        <?php endif; ?>
                                    </td>
                                <?php endforeach; ?>
                                <?php if (count($dsSosanh) < 3) : ?><td></td><?php endif; ?>
                            </tr>

                            <!-- hang 11: han su dung -->
                            <tr>
                                <th class="gs-compare-label text-white-50 p-3" style="background: #0d131d;">
                                    <i class="fa-solid fa-calendar-days text-warning me-2"></i>Hạn sử dụng
                                </th>
                                <?php foreach ($dsSosanh as $p) : ?>
                                    <td class="p-3">
                                        <?php if (!empty($p['hansudung'])) : ?>
                                            <span class="badge bg-success-subtle text-success">
                                                <i class="fa-solid fa-shield-halved me-1"></i><?php echo date('d/m/Y', strtotime($p['hansudung'])); ?>
                                            </span>
                                        <?php else : ?>
                                            <span class="text-white-50">Không thời hạn</span>
                                        <?php endif; ?>
                                    </td>
                                <?php endforeach; ?>
                                <?php if (count($dsSosanh) < 3) : ?><td></td><?php endif; ?>
                            </tr>

                            <!-- hang 12: tinh trang ton kho -->
                            <tr>
                                <th class="gs-compare-label text-white-50 p-3" style="background: #0d131d;">
                                    <i class="fa-solid fa-boxes-stacked text-warning me-2"></i>Tình trạng kho
                                </th>
                                <?php foreach ($dsSosanh as $p) : 
                                    $conhang = ((int) $p['soluongton'] > 0);
                                ?>
                                    <td class="p-3">
                                        <?php if ($conhang) : ?>
                                            <span class="text-success fw-bold"><i class="fa-solid fa-check-circle me-1"></i> Còn hàng (<?php echo (int) $p['soluongton']; ?>)</span>
                                        <?php else : ?>
                                            <span class="text-danger fw-bold"><i class="fa-solid fa-circle-xmark me-1"></i> Hết hàng</span>
                                        <?php endif; ?>
                                    </td>
                                <?php endforeach; ?>
                                <?php if (count($dsSosanh) < 3) : ?><td></td><?php endif; ?>
                            </tr>

                            <!-- hang 13: xem chi tiet -->
                            <tr>
                                <th class="gs-compare-label text-white-50 p-3" style="background: #0d131d;">
                                    Xem chi tiết
                                </th>
                                <?php foreach ($dsSosanh as $p) : ?>
                                    <td class="p-3 text-center">
                                        <a href="index.php?action=chitiet&id=<?php echo (int) $p['id']; ?>" class="btn btn-outline-light btn-sm w-100">
                                            Xem đầy đủ trang chi tiết <i class="fa-solid fa-arrow-right ms-1"></i>
                                        </a>
                                    </td>
                                <?php endforeach; ?>
                                <?php if (count($dsSosanh) < 3) : ?><td></td><?php endif; ?>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php endif; ?>

        <!-- goi y san pham noi bat / cung loai -->
        <?php if (!empty($spGoiySosanh)) : 
            $tenGoiY = !empty($dsSosanh) ? ('cùng loại ' . htmlspecialchars($dsSosanh[0]['tendanhmuc'] ?? '')) : 'nổi bật';
        ?>
            <div class="mt-5">
                <div class="d-flex align-items-center justify-content-between mb-4">
                    <div>
                        <h3 class="h4 text-white fw-bold mb-1">
                            <i class="fa-solid fa-fire text-warning me-2"></i>Sản phẩm gợi ý <?php echo $tenGoiY; ?>
                        </h3>
                        <span class="text-white-50 small">Bấm vào biểu tượng so sánh để đối chiếu trực tiếp</span>
                    </div>
                    <?php if (!empty($dsSosanh)) : ?>
                        <a href="index.php?action=sanpham&danhmuc_id=<?php echo (int)$dsSosanh[0]['danhmuc_id']; ?>" class="text-warning text-decoration-none small fw-semibold hover-underline">
                            Xem thêm sản phẩm cùng loại <i class="fa-solid fa-arrow-right ms-1"></i>
                        </a>
                    <?php else : ?>
                        <a href="index.php?action=sanpham" class="text-warning text-decoration-none small fw-semibold hover-underline">
                            Xem tất cả <i class="fa-solid fa-arrow-right ms-1"></i>
                        </a>
                    <?php endif; ?>
                </div>
                <div class="row g-4">
                    <?php 
                    foreach ($spGoiySosanh as $card) : 
                        include(__DIR__ . '/inc/product-card.php');
                    endforeach; 
                    ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
</main>

<script>
// ham bo san pham khoi so sanh tai trang sosanh.php
function gsBoSoSanh(sanphamId) {
    fetch('index.php?action=ajax_sosanh_them&id=' + sanphamId, {
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(r => r.json())
    .then(data => {
        if (data.ok) {
            window.location.reload();
        }
    })
    .catch(() => {
        window.location.href = 'index.php?action=ajax_sosanh_xoa&id=' + sanphamId;
    });
}
</script>

<?php include(__DIR__ . '/inc/footer.php'); ?>
