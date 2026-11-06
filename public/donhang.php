<?php
if (!isset($gsModule36)) {
    $_REQUEST['action'] = 'donhang';
    require __DIR__ . '/index.php';
    return;
}
$gsTieude = 'Đơn hàng của tôi';

if (!isset($dsDonhang)) {
    $dsDonhang = array();
}
include(__DIR__ . '/inc/head-client.php');
?>

<section class="gs-section gs-section--grey gs-page-head">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb gs-breadcrumb">
                <li class="breadcrumb-item"><a href="index.php">Trang chủ</a></li>
                <li class="breadcrumb-item active" aria-current="page">Đơn hàng của tôi</li>
            </ol>
        </nav>
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
            <div>
                <h1 class="gs-page-head__title mb-1">
                    Đơn hàng của tôi
                    <?php if (count($dsDonhang) > 0) : ?>
                        <span class="gs-cart-count-badge"><?php echo count($dsDonhang); ?> đơn</span>
                    <?php endif; ?>
                </h1>
                <p class="gs-page-head__sub mb-0">Theo dõi trạng thái và chi tiết các đơn hàng bạn đã đặt tại GYMBRO.</p>
            </div>
            <a class="btn-gs btn-gs--outline btn-gs--sm" href="index.php?action=sanpham">Tiếp tục mua sắm</a>
        </div>
    </div>
</section>

<section class="gs-section">
    <div class="container">
        <?php if (count($dsDonhang) === 0) : ?>
            <div class="gs-empty gs-cart-empty-box">
                <h3 class="mt-2 mb-2">Bạn chưa có đơn hàng nào</h3>
                <p class="text-muted mb-4">Hãy chọn sản phẩm phù hợp với mục tiêu tập luyện và đặt đơn đầu tiên nhé!</p>
                <a class="btn-gs btn-gs--primary" href="index.php?action=sanpham">Mua sắm ngay</a>
            </div>
        <?php else : ?>
            <div class="gs-orders-list">
                <?php foreach ($dsDonhang as $od) : ?>
                    <?php $chitietdon = $dh->laychitietdonhang($od['id']); ?>
                    <div class="gs-order-card">
                        <div class="gs-order-card__head">
                            <div class="d-flex flex-wrap align-items-center gap-2">
                                <span class="gs-order-card__code"><?php echo htmlspecialchars($od['madonhang']); ?></span>
                                <span class="text-white-50 small">•</span>
                                <span class="text-white-50 small"><?php echo htmlspecialchars(date('d/m/Y H:i', strtotime($od['ngay']))); ?></span>
                            </div>
                            <div class="d-flex flex-wrap align-items-center gap-2 mt-2 mt-md-0">
                                <span class="badge <?php echo $dh->laymautrangthai($od['trangthai']); ?>">
                                    <?php echo htmlspecialchars($dh->laytentrangthai($od['trangthai'])); ?>
                                </span>
                                <?php if ((int) $od['datt'] === 1) : ?>
                                    <span class="badge bg-success-subtle text-success border border-success-subtle">Đã thanh toán</span>
                                <?php else : ?>
                                    <span class="badge bg-danger-subtle text-danger border border-danger-subtle">Chưa thanh toán</span>
                                <?php endif; ?>
                                <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle">
                                    <?php echo htmlspecialchars($dh->laytenphuongthuc($od['phuongthuc_tt'])); ?>
                                </span>
                            </div>
                        </div>

                        <div class="gs-order-card__body">
                            <div class="gs-order-card__items">
                                <?php foreach ($chitietdon as $ct) : ?>
                                    <?php
                                    $spId = (int) ($ct['sanpham_id'] ?? 0);
                                    $sizeText = ((string) $ct['size'] !== '') ? htmlspecialchars($ct['size']) : 'Freesize';
                                    $dongSoluong = (int) $ct['soluong'];
                                    $dongDongia = (float) $ct['dongia'];
                                    $dongThanhtien = (float) $ct['thanhtien'];
                                    ?>
                                    <div class="gs-order-item">
                                        <a class="gs-order-item__thumb" href="index.php?action=chitiet&id=<?php echo $spId; ?>" target="_blank">
                                            <img src="../assets/images/products/<?php echo htmlspecialchars($ct['hinhanh']); ?>"
                                                alt="<?php echo htmlspecialchars($ct['tensanpham']); ?>" onerror="gsOnErrorImg(this)">
                                            <span class="gs-order-item__qty-pill"><?php echo $dongSoluong; ?></span>
                                        </a>
                                        <div class="gs-order-item__info">
                                            <a class="gs-order-item__name" href="index.php?action=chitiet&id=<?php echo $spId; ?>" target="_blank">
                                                <?php echo htmlspecialchars($ct['tensanpham']); ?>
                                            </a>
                                            <div class="gs-order-item__meta mt-1">
                                                <span class="gs-badge gs-badge--size"><?php echo $sizeText; ?></span>
                                                <span class="small text-white-50 ms-1"><?php echo gsTien($dongDongia); ?> × <?php echo $dongSoluong; ?></span>
                                            </div>
                                        </div>
                                        <div class="gs-order-item__total">
                                            <?php echo gsTien($dongThanhtien); ?>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>

                            <div class="gs-order-card__foot">
                                <div class="gs-order-recipient">
                                    <div class="small text-white fw-bold mb-1">Thông tin giao hàng:</div>
                                    <div class="small text-white-50">
                                        <strong><?php echo htmlspecialchars($od['tennguoinhan']); ?></strong> · <?php echo htmlspecialchars($od['sdtnhan']); ?>
                                    </div>
                                    <div class="small text-white-50">
                                        <?php echo htmlspecialchars($od['diachinhan']); ?>
                                    </div>
                                    <?php if (!empty($od['ghichu'])) : ?>
                                        <div class="small text-warning mt-1">
                                            Ghi chú: <?php echo htmlspecialchars($od['ghichu']); ?>
                                        </div>
                                    <?php endif; ?>
                                </div>

                                <div class="gs-order-summary">
                                    <div class="gs-order-summary__line">
                                        <span>Tạm tính:</span>
                                        <strong><?php echo gsTien($od['tamtinh']); ?></strong>
                                    </div>
                                    <?php if ((float) $od['giamgia'] > 0) : ?>
                                        <div class="gs-order-summary__line">
                                            <span>Giảm giá:</span>
                                            <strong class="text-success">-<?php echo gsTien($od['giamgia']); ?></strong>
                                        </div>
                                    <?php endif; ?>
                                    <div class="gs-order-summary__line">
                                        <span>Phí vận chuyển:</span>
                                        <strong>
                                            <?php echo ((float) $od['phivanchuyen'] > 0) ? gsTien($od['phivanchuyen']) : 'Miễn phí'; ?>
                                        </strong>
                                    </div>
                                    <div class="gs-order-summary__line gs-order-summary__line--total">
                                        <span>Tổng cộng:</span>
                                        <strong class="gs-order-total-highlight"><?php echo gsTien($od['tongtien']); ?></strong>
                                    </div>

                                    <?php if ((int) $od['datt'] === 0 && $od['phuongthuc_tt'] === 'vnpay') : ?>
                                        <a class="btn-gs btn-gs--primary btn-gs--sm w-100 mt-2 text-center"
                                            href="index.php?action=thanhtoanlai_vnpay&madonhang=<?php echo urlencode($od['madonhang']); ?>">
                                            THANH TOÁN LẠI QUA VNPAY
                                        </a>
                                    <?php endif; ?>

                                    <?php if ((int) $od['trangthai'] === 1) : ?>
                                        <a class="btn-gs btn-gs--outline btn-gs--sm w-100 mt-2 text-center text-danger border-danger"
                                            href="index.php?action=huydonhang&madonhang=<?php echo urlencode($od['madonhang']); ?>"
                                            onclick="return confirm('Bạn có chắc chắn muốn hủy đơn hàng này? Các sản phẩm sẽ được tự động hoàn trả lại kho hàng.');">
                                            <i class="fa-solid fa-xmark me-1"></i> HỦY ĐƠN HÀNG
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<!-- Modal Đánh Giá Sản Phẩm -->
<div class="modal fade" id="modalDanhGia" tabindex="-1" aria-labelledby="modalDanhGiaLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content bg-dark border-secondary text-white shadow-lg">
            <div class="modal-header border-secondary">
                <h5 class="modal-title fw-bold" id="modalDanhGiaLabel">
                    <i class="fa-solid fa-star text-warning me-2"></i>Đánh giá sản phẩm
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="index.php?action=danhgiasanpham" method="POST" id="formModalDanhGia">
                <div class="modal-body">
                    <input type="hidden" name="sanpham_id" id="modalSpId" value="0">
                    <input type="hidden" name="sao" id="modalRatingStar" value="5">

                    <!-- Thông tin sản phẩm đang đánh giá -->
                    <div class="d-flex align-items-center gap-3 p-2 mb-3 bg-black rounded border border-secondary border-opacity-50">
                        <img src="" id="modalSpImg" alt="Sản phẩm" style="width: 50px; height: 50px; object-fit: cover; border-radius: 4px;" onerror="gsOnErrorImg(this)">
                        <div>
                            <h6 class="mb-0 fw-semibold text-truncate" id="modalSpName" style="max-width: 320px;"></h6>
                            <span class="small text-white-50">Đơn hàng đã hoàn thành</span>
                        </div>
                    </div>

                    <!-- Chọn số sao -->
                    <div class="mb-3 text-center py-2 bg-black bg-opacity-50 rounded">
                        <label class="form-label d-block text-white-50 small mb-2">Chạm để chọn số sao:</label>
                        <div class="gs-modal-star-picker" id="modalStarPicker">
                            <i class="fa-solid fa-star modal-star-node" data-val="1"></i>
                            <i class="fa-solid fa-star modal-star-node" data-val="2"></i>
                            <i class="fa-solid fa-star modal-star-node" data-val="3"></i>
                            <i class="fa-solid fa-star modal-star-node" data-val="4"></i>
                            <i class="fa-solid fa-star modal-star-node" data-val="5"></i>
                        </div>
                        <div class="mt-2"><span id="modalStarLabel" class="gs-star-badge">5 sao - Sản phẩm tốt mà shop còn đẹp trai nữa</span></div>
                    </div>

                    <!-- Nội dung đánh giá -->
                    <div class="mb-3">
                        <label for="modalNoidung" class="form-label small text-white-50">Nhận xét của bạn (không bắt buộc):</label>
                        <textarea class="form-control bg-black text-white border-secondary" 
                                  id="modalNoidung" 
                                  name="noidung" 
                                  rows="3" 
                                  placeholder="Hãy chia sẻ trải nghiệm của bạn về sản phẩm này nhé (chất lượng, độ bền, size vừa vặn...)..."></textarea>
                    </div>
                </div>
                <div class="modal-footer border-secondary">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Đóng</button>
                    <button type="submit" class="btn btn-danger px-4 fw-semibold" id="modalBtnSubmit">
                        <i class="fa-solid fa-paper-plane me-1"></i> Gửi đánh giá
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const modalEl = document.getElementById('modalDanhGia');
    if (!modalEl) return;

    const modalSpId = document.getElementById('modalSpId');
    const modalSpName = document.getElementById('modalSpName');
    const modalSpImg = document.getElementById('modalSpImg');
    const modalRatingStar = document.getElementById('modalRatingStar');
    const modalStarLabel = document.getElementById('modalStarLabel');
    const modalBtnSubmit = document.getElementById('modalBtnSubmit');
    const modalStarPicker = document.getElementById('modalStarPicker');
    const stars = modalStarPicker.querySelectorAll('.modal-star-node');

    const starTexts = {
        1: '1 sao - Có gì nhắn shop nhen',
        2: '2 sao - Bình tĩnh nhắn cho sop đi',
        3: '3 sao - Nhắn shop đi mò',
        4: '4 sao - Sản phẩm rất tốt',
        5: '5 sao - Sản phẩm tốt mà shop còn đẹp trai nữa'
    };

    function setStars(val) {
        stars.forEach(s => {
            const v = parseInt(s.dataset.val, 10);
            if (v <= val) {
                s.classList.remove('fa-regular');
                s.classList.add('fa-solid');
            } else {
                s.classList.remove('fa-solid');
                s.classList.add('fa-regular');
            }
        });
        if (modalStarLabel && starTexts[val]) {
            modalStarLabel.textContent = starTexts[val];
        }
    }

    stars.forEach(s => {
        s.addEventListener('mouseenter', function () {
            setStars(parseInt(this.dataset.val, 10));
        });
        s.addEventListener('click', function () {
            const v = parseInt(this.dataset.val, 10);
            modalRatingStar.value = v;
            setStars(v);
        });
    });

    modalStarPicker.addEventListener('mouseleave', function () {
        setStars(parseInt(modalRatingStar.value, 10));
    });

    modalEl.addEventListener('show.bs.modal', function (event) {
        const btn = event.relatedTarget;
        if (!btn) return;

        const id = btn.getAttribute('data-id');
        const name = btn.getAttribute('data-name');
        const img = btn.getAttribute('data-img');
        const dadanhgia = btn.getAttribute('data-dadanhgia');

        modalSpId.value = id;
        modalSpName.textContent = name || '';
        modalSpImg.src = img || '';

        modalRatingStar.value = 5;
        setStars(5);
        document.getElementById('modalNoidung').value = '';

        if (dadanhgia === '1') {
            modalBtnSubmit.innerHTML = '<i class="fa-solid fa-pen-to-square me-1"></i> Cập nhật đánh giá';
            document.getElementById('modalDanhGiaLabel').innerHTML = '<i class="fa-solid fa-star text-warning me-2"></i>Cập nhật đánh giá sản phẩm';
        } else {
            modalBtnSubmit.innerHTML = '<i class="fa-solid fa-paper-plane me-1"></i> Gửi đánh giá';
            document.getElementById('modalDanhGiaLabel').innerHTML = '<i class="fa-solid fa-star text-warning me-2"></i>Đánh giá sản phẩm';
        }
    });
});
</script>

<?php include(__DIR__ . '/inc/footer.php'); ?>
