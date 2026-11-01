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
        <h1 class="gs-page-head__title">Đơn hàng của tôi</h1>
        <p class="gs-page-head__sub">Theo dõi trạng thái và chi tiết các đơn hàng bạn đã đặt tại GYMBRO.</p>
    </div>
</section>

<section class="gs-section">
    <div class="container">
        <?php if (count($dsDonhang) === 0) : ?>
            <div class="gs-empty">
                <i class="fa-solid fa-receipt"></i>
                <h3>Bạn chưa có đơn hàng nào</h3>
                <p>Hãy chọn sản phẩm phù hợp với mục tiêu tập luyện và đặt đơn đầu tiên nhé!</p>
                <a class="btn-gs btn-gs--dark" href="index.php?action=sanpham">Mua sắm ngay</a>
            </div>
        <?php else : ?>
            <?php foreach ($dsDonhang as $od) : ?>
                <?php $chitietdon = $dh->laychitietdonhang($od['id']); ?>
                <div class="gs-order">
                    <div class="gs-order__head">
                        <div>
                            <span class="gs-order__ma"><?php echo htmlspecialchars($od['madonhang']); ?></span>
                            <span class="gs-order__ngay"><?php echo htmlspecialchars(date('d/m/Y H:i', strtotime($od['ngay']))); ?></span>
                        </div>
                        <div class="gs-order__right">
                            <span class="badge <?php echo $dh->laymautrangthai($od['trangthai']); ?>">
                                <?php echo htmlspecialchars($dh->laytentrangthai($od['trangthai'])); ?>
                            </span>
                            <span class="gs-order__tt">
                                <?php echo ((int) $od['datt'] === 1) ? 'Đã thanh toán' : 'Chưa thanh toán'; ?>
                                · <?php echo htmlspecialchars($dh->laytenphuongthuc($od['phuongthuc_tt'])); ?>
                            </span>
                        </div>
                    </div>

                    <div class="gs-order__body">
                        <div class="gs-order__items">
                            <?php foreach ($chitietdon as $ct) : ?>
                                <div class="gs-order__item">
                                    <img src="../assets/images/products/<?php echo htmlspecialchars($ct['hinhanh']); ?>"
                                        alt="<?php echo htmlspecialchars($ct['tensanpham']); ?>" onerror="gsOnErrorImg(this)">
                                    <div>
                                        <a class="gs-order__name" href="index.php?action=chitiet&id=<?php echo (int) $ct['sanpham_id']; ?>">
                                            <?php echo htmlspecialchars($ct['tensanpham']); ?>
                                        </a>
                                        <span class="gs-order__meta">
                                            <?php echo ((string) $ct['size'] !== '') ? ('Size ' . htmlspecialchars($ct['size'])) : 'Freesize'; ?>
                                            × <?php echo (int) $ct['soluong']; ?>
                                            · <?php echo gsTien($ct['dongia']); ?>
                                        </span>
                                    </div>
                                    <div class="d-flex flex-column align-items-end justify-content-center gap-2">
                                        <span class="gs-order__price"><?php echo gsTien($ct['thanhtien']); ?></span>
                                        <?php if ((int)$od['trangthai'] !== 6) : 
                                            $daDanhGia = in_array((int)$ct['sanpham_id'], $dsIdSpDaDanhgia ?? array(), true);
                                        ?>
                                            <button type="button" 
                                                    class="btn btn-sm <?php echo $daDanhGia ? 'btn-outline-secondary' : 'btn-outline-danger'; ?> px-2 py-1"
                                                    style="font-size: 0.8rem; border-radius: 6px;"
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#modalDanhGia"
                                                    data-id="<?php echo (int) $ct['sanpham_id']; ?>"
                                                    data-name="<?php echo htmlspecialchars($ct['tensanpham']); ?>"
                                                    data-img="../assets/images/products/<?php echo htmlspecialchars($ct['hinhanh']); ?>"
                                                    data-dadanhgia="<?php echo $daDanhGia ? '1' : '0'; ?>">
                                                <i class="fa-<?php echo $daDanhGia ? 'solid' : 'regular'; ?> fa-star text-warning me-1"></i>
                                                <?php echo $daDanhGia ? 'Đã đánh giá' : 'Đánh giá'; ?>
                                            </button>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>

                        <div class="gs-order__foot">
                            <div class="gs-order__info">
                                <span><i class="fa-solid fa-user"></i> <?php echo htmlspecialchars($od['tennguoinhan']); ?> · <?php echo htmlspecialchars($od['sdtnhan']); ?></span>
                                <span><i class="fa-solid fa-location-dot"></i> <?php echo htmlspecialchars($od['diachinhan']); ?></span>
                                <?php if (!empty($od['ghichu'])) : ?>
                                    <span><i class="fa-solid fa-note-sticky"></i> <?php echo htmlspecialchars($od['ghichu']); ?></span>
                                <?php endif; ?>
                            </div>
                            <div class="gs-order__sum">
                                <span>Tạm tính: <strong><?php echo gsTien($od['tamtinh']); ?></strong></span>
                                <span>Giảm giá: <strong class="<?php echo ((float) $od['giamgia'] > 0) ? 'text-danger' : ''; ?>"><?php echo ((float) $od['giamgia'] > 0 ? '-' : '') . gsTien($od['giamgia']); ?></strong></span>
                                <span>Phí vận chuyển: <strong><?php echo ((float) $od['phivanchuyen'] > 0) ? gsTien($od['phivanchuyen']) : 'Miễn phí'; ?></strong></span>
                                <span class="gs-order__tong">Tổng cộng: <strong><?php echo gsTien($od['tongtien']); ?></strong></span>
                            </div>
                            <?php if ((int) $od['datt'] === 0 && $od['phuongthuc_tt'] === 'vnpay') : ?>
                                <a class="btn-gs btn-gs--dark" href="index.php?action=thanhtoanlai_vnpay&madonhang=<?php echo urlencode($od['madonhang']); ?>">
                                    <i class="fa-solid fa-rotate-right"></i> Thanh toán lại
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
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
                        <div class="gs-modal-star-picker" id="modalStarPicker" style="font-size: 2rem; cursor: pointer; color: #ffc107;">
                            <i class="fa-solid fa-star modal-star-node" data-val="1"></i>
                            <i class="fa-solid fa-star modal-star-node" data-val="2"></i>
                            <i class="fa-solid fa-star modal-star-node" data-val="3"></i>
                            <i class="fa-solid fa-star modal-star-node" data-val="4"></i>
                            <i class="fa-solid fa-star modal-star-node" data-val="5"></i>
                        </div>
                        <div id="modalStarLabel" class="small fw-semibold text-warning mt-1">5 sao - Tuyệt vời</div>
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
        1: '1 sao - Rất tệ',
        2: '2 sao - Chưa hài lòng',
        3: '3 sao - Bình thường',
        4: '4 sao - Hài lòng',
        5: '5 sao - Tuyệt vời'
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
