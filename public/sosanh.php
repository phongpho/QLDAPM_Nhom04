<?php
if (!isset($gsModule36)) {
    $_REQUEST['action'] = 'sosanh';
    require __DIR__ . '/index.php';
    return;
}

$gsTieude = 'So sánh sản phẩm';
include(__DIR__ . '/inc/head-client.php');
?>

<section class="gs-section gs-section--grey gs-page-head">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb gs-breadcrumb">
                <li class="breadcrumb-item"><a href="index.php">Trang chủ</a></li>
                <li class="breadcrumb-item active" aria-current="page">So sánh sản phẩm</li>
            </ol>
        </nav>
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
            <div>
                <h1 class="gs-page-head__title mb-1">
                    So sánh sản phẩm
                    <?php if (!empty($dsSosanh)) : ?>
                        <span class="gs-cart-count-badge"><?php echo count($dsSosanh); ?> sản phẩm</span>
                    <?php endif; ?>
                </h1>
                <p class="gs-page-head__sub mb-0">Đối chiếu chi tiết giá bán, thương hiệu, chất liệu và thông số kỹ thuật để lựa chọn sản phẩm phù hợp nhất.</p>
            </div>
            <div class="d-flex align-items-center gap-2">
                <?php if (!empty($dsSosanh)) : ?>
                    <button type="button" class="btn-gs btn-gs--outline btn-gs--sm" onclick="gsXoaTatCaSoSanh()">
                        Xóa tất cả
                    </button>
                <?php endif; ?>
                <a class="btn-gs btn-gs--outline btn-gs--sm" href="index.php?action=sanpham">Khám phá thêm</a>
            </div>
        </div>
    </div>
</section>

<section class="gs-section py-5">
    <div class="container">
        <?php if (empty($dsSosanh)) : ?>
            <div class="gs-empty gs-cart-empty-box">
                <h3 class="mt-2 mb-2">Chưa có sản phẩm nào trong danh sách so sánh</h3>
                <p class="text-muted mb-4" style="max-width: 520px; margin: 0 auto;">
                    Hãy bấm vào biểu tượng so sánh trên mỗi sản phẩm ở trang cửa hàng để bắt đầu đối chiếu thông số kỹ thuật.
                </p>
                <a href="index.php?action=sanpham" class="btn-gs btn-gs--primary">
                    Khám phá sản phẩm ngay
                </a>
            </div>
        <?php else : ?>
            <div class="rounded-3 overflow-hidden mb-5" style="background: var(--gs-dark-800); border: 1px solid var(--gs-border);">
                <div class="table-responsive">
                    <table class="table table-dark m-0 align-middle" style="background: transparent; border-color: var(--gs-border);">
                        <colgroup>
                            <col style="width: 20%; min-width: 170px;">
                            <?php foreach ($dsSosanh as $p) : ?>
                                <col style="width: <?php echo (80 / max(count($dsSosanh), 1)); ?>%; min-width: 230px;">
                            <?php endforeach; ?>
                            <?php if (count($dsSosanh) < 3) : ?>
                                <col style="min-width: 220px;">
                            <?php endif; ?>
                        </colgroup>
                        <tbody>
                            <tr>
                                <th class="text-white-50 text-uppercase small fw-bold p-3" style="background: var(--gs-dark-900); border-color: var(--gs-border);">
                                    Sản phẩm
                                </th>
                                <?php foreach ($dsSosanh as $p) : 
                                    $img = !empty($p['hinhanh']) ? '../assets/images/products/' . $p['hinhanh'] : '../assets/images/systems/no-image.png';
                                    $conhang = ((int) $p['soluongton'] > 0);
                                    $mangsizes = $sp->tachsizethanhmmang($p['sizes'] ?? '');
                                ?>
                                    <td class="p-3 text-center position-relative" style="border-color: var(--gs-border);">
                                        <button type="button" class="btn btn-sm btn-outline-secondary position-absolute top-0 end-0 m-2 rounded-circle d-flex align-items-center justify-content-center p-0" 
                                                style="width: 26px; height: 26px; border-color: var(--gs-border); color: var(--gs-text-muted);"
                                                onclick="gsBoSoSanh(<?php echo (int) $p['id']; ?>)" 
                                                title="Bỏ khỏi so sánh">
                                            <i class="fa-solid fa-xmark"></i>
                                        </button>
                                        <a href="index.php?action=chitiet&id=<?php echo (int) $p['id']; ?>" class="d-block mb-3 text-decoration-none">
                                            <img src="<?php echo htmlspecialchars($img); ?>" 
                                                 alt="<?php echo htmlspecialchars($p['tensanpham']); ?>" 
                                                 class="img-fluid rounded-3 mx-auto d-block"
                                                 style="width: 140px; height: 140px; object-fit: contain; background: var(--gs-dark-900); padding: 6px; border: 1px solid var(--gs-border);"
                                                 onerror="gsOnErrorImg(this)">
                                        </a>
                                        <div class="small text-white-50 text-uppercase fw-semibold mb-1">
                                            <?php echo htmlspecialchars($p['tendanhmuc'] ?? ''); ?>
                                        </div>
                                        <h5 class="fw-bold mb-3 fs-6">
                                            <a href="index.php?action=chitiet&id=<?php echo (int) $p['id']; ?>" class="text-white text-decoration-none">
                                                <?php echo htmlspecialchars($p['tensanpham']); ?>
                                            </a>
                                        </h5>
                                        <?php if ($conhang) : ?>
                                            <?php if (count($mangsizes) > 0) : ?>
                                                <a href="index.php?action=chitiet&id=<?php echo (int) $p['id']; ?>" class="btn-gs btn-gs--outline btn-gs--sm w-100">
                                                    Chọn size mua ngay
                                                </a>
                                            <?php else : ?>
                                                <form method="post" action="index.php" class="m-0">
                                                    <input type="hidden" name="action" value="themvaogiohang">
                                                    <input type="hidden" name="sanpham_id" value="<?php echo (int) $p['id']; ?>">
                                                    <input type="hidden" name="size" value="">
                                                    <input type="hidden" name="soluong" value="1">
                                                    <button type="submit" class="btn-gs btn-gs--primary btn-gs--sm w-100">
                                                        Thêm vào giỏ
                                                    </button>
                                                </form>
                                            <?php endif; ?>
                                        <?php else : ?>
                                            <button class="btn-gs btn-gs--secondary btn-gs--sm w-100 disabled" disabled>Hết hàng</button>
                                        <?php endif; ?>
                                    </td>
                                <?php endforeach; ?>
                                <?php if (count($dsSosanh) < 3) : 
                                    $linkThem = !empty($dsSosanh) ? 'index.php?action=sanpham&danhmuc_id=' . (int)$dsSosanh[0]['danhmuc_id'] : 'index.php?action=sanpham';
                                    $tenDmHienTai = !empty($dsSosanh) ? ($dsSosanh[0]['tendanhmuc'] ?? '') : '';
                                ?>
                                    <td class="p-3 text-center align-middle" style="background: rgba(0,0,0,0.2); border-color: var(--gs-border);">
                                        <div class="p-4 rounded-3" style="border: 1px dashed var(--gs-border);">
                                            <span class="text-white-50 d-block small mb-2">Thêm sản phẩm khác để đối chiếu (Tối đa 3 món)</span>
                                            <?php if ($tenDmHienTai !== '') : ?>
                                                <span class="badge bg-dark border border-secondary text-white-50 mb-3 d-inline-block">Cùng loại: <?php echo htmlspecialchars($tenDmHienTai); ?></span>
                                            <?php endif; ?>
                                            <div>
                                                <a href="<?php echo htmlspecialchars($linkThem); ?>" class="btn-gs btn-gs--outline btn-gs--sm">
                                                    Chọn thêm sản phẩm
                                                </a>
                                            </div>
                                        </div>
                                    </td>
                                <?php endif; ?>
                            </tr>

                            <tr>
                                <th class="text-white-50 p-3 small fw-semibold" style="background: var(--gs-dark-900); border-color: var(--gs-border);">
                                    Giá bán
                                </th>
                                <?php foreach ($dsSosanh as $p) : 
                                    $giaban = (float) $p['giaban'];
                                    $giagoc = (float) $p['giagoc'];
                                    $pt = ($giagoc > $giaban && $giagoc > 0) ? (int) round(100 - ($giaban * 100 / $giagoc)) : 0;
                                ?>
                                    <td class="p-3 text-center align-middle" style="border-color: var(--gs-border);">
                                        <div class="fs-5 fw-bold text-white mb-1">
                                            <?php echo number_format($giaban, 0, ',', '.'); ?>đ
                                        </div>
                                        <?php if ($pt > 0) : ?>
                                            <div class="d-flex align-items-center justify-content-center gap-2">
                                                <span class="text-white-50 text-decoration-line-through small">
                                                    <?php echo number_format($giagoc, 0, ',', '.'); ?>đ
                                                </span>
                                                <span class="badge rounded-pill fw-bold" style="background: var(--gs-primary, #f97316); color: #000; font-size: 0.72rem;">-<?php echo $pt; ?>%</span>
                                            </div>
                                        <?php else : ?>
                                            <span class="text-white-50 small">Giá niêm yết chính hãng</span>
                                        <?php endif; ?>
                                    </td>
                                <?php endforeach; ?>
                                <?php if (count($dsSosanh) < 3) : ?><td style="border-color: var(--gs-border);"></td><?php endif; ?>
                            </tr>

                            <tr>
                                <th class="text-white-50 p-3 small fw-semibold" style="background: var(--gs-dark-900); border-color: var(--gs-border);">
                                    Đánh giá & Lượt bán
                                </th>
                                <?php foreach ($dsSosanh as $p) : 
                                    $dg = $p['danhgia_thongke'] ?? array('diem' => 5, 'soluot' => 0);
                                    $diem = $dg['diem'] > 0 ? $dg['diem'] : 5;
                                ?>
                                    <td class="p-3 text-center align-middle" style="border-color: var(--gs-border);">
                                        <div class="d-flex align-items-center justify-content-center gap-1 mb-1">
                                            <?php for ($i = 1; $i <= 5; $i++) : ?>
                                                <i class="fa-solid fa-star small <?php echo $i <= round($diem) ? 'text-warning' : 'text-white-50'; ?>"></i>
                                            <?php endfor; ?>
                                            <span class="text-white fw-bold ms-1 small"><?php echo $diem; ?>/5</span>
                                            <span class="text-white-50 small">(<?php echo (int) $dg['soluot']; ?>)</span>
                                        </div>
                                        <div class="small text-white-50">
                                            Đã bán <?php echo (int) $p['luotmua']; ?> sản phẩm
                                        </div>
                                    </td>
                                <?php endforeach; ?>
                                <?php if (count($dsSosanh) < 3) : ?><td style="border-color: var(--gs-border);"></td><?php endif; ?>
                            </tr>

                            <tr>
                                <th class="text-white-50 p-3 small fw-semibold" style="background: var(--gs-dark-900); border-color: var(--gs-border);">
                                    Thương hiệu
                                </th>
                                <?php foreach ($dsSosanh as $p) : ?>
                                    <td class="p-3 text-center align-middle" style="border-color: var(--gs-border);">
                                        <span class="text-white fw-semibold"><?php echo !empty($p['tenthuonghieu']) ? htmlspecialchars($p['tenthuonghieu']) : 'GYMBRO'; ?></span>
                                    </td>
                                <?php endforeach; ?>
                                <?php if (count($dsSosanh) < 3) : ?><td style="border-color: var(--gs-border);"></td><?php endif; ?>
                            </tr>

                            <tr>
                                <th class="text-white-50 p-3 small fw-semibold" style="background: var(--gs-dark-900); border-color: var(--gs-border);">
                                    Đối tượng
                                </th>
                                <?php foreach ($dsSosanh as $p) : 
                                    $dt = strtolower($p['doituong'] ?? 'unisex');
                                ?>
                                    <td class="p-3 text-center align-middle" style="border-color: var(--gs-border);">
                                        <span class="text-white">
                                            <?php 
                                            if ($dt === 'nam') echo 'Nam';
                                            elseif ($dt === 'nu') echo 'Nữ';
                                            else echo 'Unisex (Nam & Nữ)';
                                            ?>
                                        </span>
                                    </td>
                                <?php endforeach; ?>
                                <?php if (count($dsSosanh) < 3) : ?><td style="border-color: var(--gs-border);"></td><?php endif; ?>
                            </tr>

                            <tr>
                                <th class="text-white-50 p-3 small fw-semibold" style="background: var(--gs-dark-900); border-color: var(--gs-border);">
                                    Mục tiêu tập luyện
                                </th>
                                <?php foreach ($dsSosanh as $p) : 
                                    $muctieuArr = !empty($p['muctieu']) ? explode(',', $p['muctieu']) : array();
                                ?>
                                    <td class="p-3 text-center align-middle" style="border-color: var(--gs-border);">
                                        <span class="text-white">
                                            <?php 
                                            if (empty($muctieuArr)) {
                                                echo 'Mọi mục tiêu';
                                            } else {
                                                $tenMt = array();
                                                foreach ($muctieuArr as $mt) {
                                                    $mt = trim($mt);
                                                    if ($mt === 'tangcan') $tenMt[] = 'Tăng cơ / Tăng cân';
                                                    elseif ($mt === 'giamcan') $tenMt[] = 'Giảm mỡ / Siết cân';
                                                    elseif ($mt === 'duytri') $tenMt[] = 'Duy trì vóc dáng';
                                                }
                                                echo htmlspecialchars(implode(', ', $tenMt));
                                            }
                                            ?>
                                        </span>
                                    </td>
                                <?php endforeach; ?>
                                <?php if (count($dsSosanh) < 3) : ?><td style="border-color: var(--gs-border);"></td><?php endif; ?>
                            </tr>

                            <tr>
                                <th class="text-white-50 p-3 small fw-semibold" style="background: var(--gs-dark-900); border-color: var(--gs-border);">
                                    Chất liệu
                                </th>
                                <?php foreach ($dsSosanh as $p) : ?>
                                    <td class="p-3 text-center align-middle" style="border-color: var(--gs-border);">
                                        <span class="text-white"><?php echo !empty($p['chatlieu']) ? htmlspecialchars($p['chatlieu']) : 'Không áp dụng'; ?></span>
                                    </td>
                                <?php endforeach; ?>
                                <?php if (count($dsSosanh) < 3) : ?><td style="border-color: var(--gs-border);"></td><?php endif; ?>
                            </tr>

                            <tr>
                                <th class="text-white-50 p-3 small fw-semibold" style="background: var(--gs-dark-900); border-color: var(--gs-border);">
                                    Thành phần dinh dưỡng
                                </th>
                                <?php foreach ($dsSosanh as $p) : ?>
                                    <td class="p-3 text-center align-middle" style="border-color: var(--gs-border);">
                                        <div class="text-white small mx-auto" style="max-width: 280px; line-height: 1.5;">
                                            <?php echo !empty($p['thanhphan']) ? nl2br(htmlspecialchars($p['thanhphan'])) : 'Không áp dụng'; ?>
                                        </div>
                                    </td>
                                <?php endforeach; ?>
                                <?php if (count($dsSosanh) < 3) : ?><td style="border-color: var(--gs-border);"></td><?php endif; ?>
                            </tr>

                            <tr>
                                <th class="text-white-50 p-3 small fw-semibold" style="background: var(--gs-dark-900); border-color: var(--gs-border);">
                                    Size có sẵn
                                </th>
                                <?php foreach ($dsSosanh as $p) : 
                                    $sizes = $sp->tachsizethanhmmang($p['sizes'] ?? '');
                                ?>
                                    <td class="p-3 text-center align-middle" style="border-color: var(--gs-border);">
                                        <span class="text-white"><?php echo !empty($sizes) ? htmlspecialchars(implode(', ', $sizes)) : 'Free size / Tiêu chuẩn'; ?></span>
                                    </td>
                                <?php endforeach; ?>
                                <?php if (count($dsSosanh) < 3) : ?><td style="border-color: var(--gs-border);"></td><?php endif; ?>
                            </tr>

                            <tr>
                                <th class="text-white-50 p-3 small fw-semibold" style="background: var(--gs-dark-900); border-color: var(--gs-border);">
                                    Màu sắc / Hương vị
                                </th>
                                <?php foreach ($dsSosanh as $p) : ?>
                                    <td class="p-3 text-center align-middle" style="border-color: var(--gs-border);">
                                        <span class="text-white"><?php echo !empty($p['mausac']) ? htmlspecialchars($p['mausac']) : 'Mặc định'; ?></span>
                                    </td>
                                <?php endforeach; ?>
                                <?php if (count($dsSosanh) < 3) : ?><td style="border-color: var(--gs-border);"></td><?php endif; ?>
                            </tr>

                            <tr>
                                <th class="text-white-50 p-3 small fw-semibold" style="background: var(--gs-dark-900); border-color: var(--gs-border);">
                                    Hạn sử dụng
                                </th>
                                <?php foreach ($dsSosanh as $p) : ?>
                                    <td class="p-3 text-center align-middle" style="border-color: var(--gs-border);">
                                        <span class="text-white"><?php echo !empty($p['hansudung']) ? date('d/m/Y', strtotime($p['hansudung'])) : 'Không thời hạn'; ?></span>
                                    </td>
                                <?php endforeach; ?>
                                <?php if (count($dsSosanh) < 3) : ?><td style="border-color: var(--gs-border);"></td><?php endif; ?>
                            </tr>

                            <tr>
                                <th class="text-white-50 p-3 small fw-semibold" style="background: var(--gs-dark-900); border-color: var(--gs-border);">
                                    Tình trạng kho
                                </th>
                                <?php foreach ($dsSosanh as $p) : 
                                    $conhang = ((int) $p['soluongton'] > 0);
                                ?>
                                    <td class="p-3 text-center align-middle" style="border-color: var(--gs-border);">
                                        <span class="text-white"><?php echo $conhang ? ('Còn hàng (' . (int) $p['soluongton'] . ')') : 'Hết hàng'; ?></span>
                                    </td>
                                <?php endforeach; ?>
                                <?php if (count($dsSosanh) < 3) : ?><td style="border-color: var(--gs-border);"></td><?php endif; ?>
                            </tr>

                            <tr>
                                <th class="text-white-50 p-3 small fw-semibold" style="background: var(--gs-dark-900); border-color: var(--gs-border);">
                                    Xem chi tiết
                                </th>
                                <?php foreach ($dsSosanh as $p) : ?>
                                    <td class="p-3 text-center align-middle" style="border-color: var(--gs-border);">
                                        <a href="index.php?action=chitiet&id=<?php echo (int) $p['id']; ?>" class="btn-gs btn-gs--outline btn-gs--sm w-100">
                                            Xem chi tiết &rarr;
                                        </a>
                                    </td>
                                <?php endforeach; ?>
                                <?php if (count($dsSosanh) < 3) : ?><td style="border-color: var(--gs-border);"></td><?php endif; ?>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php if (!empty($spGoiySosanh)) : 
    $tenGoiY = !empty($dsSosanh) ? ('cùng loại ' . htmlspecialchars($dsSosanh[0]['tendanhmuc'] ?? '')) : 'nổi bật';
?>
    <section class="gs-section gs-section--grey">
        <div class="container">
            <div class="gs-section__head">
                <div>
                    <h2 class="gs-section__title">Sản phẩm gợi ý <?php echo $tenGoiY; ?></h2>
                    <span class="gs-section__sub">Bấm vào biểu tượng so sánh trên mỗi sản phẩm để đối chiếu trực tiếp</span>
                </div>
                <?php if (!empty($dsSosanh)) : ?>
                    <a class="gs-section__more" href="index.php?action=sanpham&danhmuc_id=<?php echo (int)$dsSosanh[0]['danhmuc_id']; ?>">
                        Xem thêm &rarr;
                    </a>
                <?php else : ?>
                    <a class="gs-section__more" href="index.php?action=sanpham">
                        Xem tất cả &rarr;
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
    </section>
<?php endif; ?>

<script>
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
