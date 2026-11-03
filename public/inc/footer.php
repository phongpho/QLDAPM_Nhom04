<footer class="gs-footer">
    <div class="container">
        <div class="row g-4">
            <!-- Cột 1: Về GYMBRO -->
            <div class="col-lg-3 col-md-6">
                <a class="gs-footer__logo" href="index.php">GYM<em style="color:#767676;">BRO</em></a>
                <p class="gs-footer__desc">
                    Trang phục tập luyện, phụ kiện và thực phẩm bổ sung được cá nhân hóa theo chỉ số cơ thể, đồng hành cùng bạn trên hành trình chinh phục vóc dáng.
                </p>
                <div class="gs-footer__social">
                    <a href="#" aria-label="Facebook"><i class="fa-brands fa-facebook-f"></i></a>
                    <a href="#" aria-label="Instagram"><i class="fa-brands fa-instagram"></i></a>
                    <a href="#" aria-label="TikTok"><i class="fa-brands fa-tiktok"></i></a>
                    <a href="#" aria-label="YouTube"><i class="fa-brands fa-youtube"></i></a>
                </div>
            </div>

            <!-- Cột 2: Hỗ trợ khách hàng -->
            <div class="col-lg-3 col-md-6">
                <h4 class="gs-footer__title">Hỗ trợ khách hàng</h4>
                <a class="gs-footer__link" href="#">Câu hỏi thường gặp</a>
                <a class="gs-footer__link" href="#">Chính sách đổi trả</a>
                <a class="gs-footer__link" href="#">Chính sách giao hàng</a>
                <a class="gs-footer__link" href="#">Hướng dẫn chọn size</a>
                <a class="gs-footer__link" href="#">Liên hệ</a>
            </div>

            <!-- Cột 3: Danh mục theo mục tiêu -->
            <div class="col-lg-3 col-md-6">
                <h4 class="gs-footer__title">Danh mục theo mục tiêu</h4>
                <a class="gs-footer__link" href="index.php?action=sanpham&muctieu=tangcan">Tăng cân / Tăng cơ</a>
                <a class="gs-footer__link" href="index.php?action=sanpham&muctieu=giamcan">Giảm cân / Giảm mỡ</a>
                <a class="gs-footer__link" href="index.php?action=sanpham&muctieu=duytri">Duy trì vóc dáng</a>
                <a class="gs-footer__link" href="index.php?action=sanpham&loai=quanao">Đồ tập gym</a>
                <a class="gs-footer__link" href="index.php?action=sanpham&loai=tpbs">Thực phẩm bổ sung</a>
            </div>

            <!-- Cột 4: Đăng ký nhận tin & Voucher -->
            <div class="col-lg-3 col-md-6">
                <h4 class="gs-footer__title">Đăng ký nhận tin &amp; Voucher</h4>
                <p style="line-height:1.6;">Nhận ưu đãi độc quyền và mã giảm giá cho đơn hàng đầu tiên của bạn.</p>
                <form class="gs-newsletter-form" id="gsNewsletterForm">
                    <input type="email" class="gs-newsletter-input" placeholder="Nhập email của bạn" aria-label="Email" required>
                    <button type="submit" class="gs-newsletter-btn">Gửi</button>
                </form>
                <div class="gs-pay">
                    <span class="gs-pay-badge"><i class="fa-solid fa-hand-holding-dollar"></i> COD</span>
                    <span class="gs-pay-badge"><i class="fa-solid fa-building-columns"></i> Chuyển khoản</span>
                    <span class="gs-pay-badge"><i class="fa-solid fa-qrcode"></i> VNPay</span>
                </div>
            </div>
        </div>
    </div>

    <div class="gs-footer__bottom">
        <div class="container">
            <div class="gs-footer__bottom-inner">
                <span>&copy; <?php echo date('Y'); ?> GYMBRO. Đồ án Quản lý dự án phần mềm - Nhóm 04.</span>
                <span>Thiết kế lấy cảm hứng từ phong cách Gymshark.</span>
            </div>
        </div>
    </div>
</footer>

<!-- Back to top -->
<button class="gs-backtop" id="gsBackTop" aria-label="Về đầu trang">
    <i class="fa-solid fa-chevron-up"></i>
</button>

<!-- CDN Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="../assets/js/main.js"></script>
<script>
// xu ly yeu thich san pham
function gsToggleYeuThich(btn, sanphamId) {
    if (!sanphamId) return;
    fetch('index.php?action=xlyeuthich&sanpham_id=' + sanphamId, {
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(res => res.json())
    .then(data => {
        if (!data.ok) {
            if (data.dangnhap === false) {
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'info',
                        title: 'Đăng nhập',
                        text: 'Vui lòng đăng nhập để lưu sản phẩm vào danh sách yêu thích!',
                        confirmButtonText: 'Đăng nhập ngay',
                        confirmButtonColor: '#dc3545',
                        showCancelButton: true,
                        cancelButtonText: 'Để sau'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = 'index.php?action=dangnhap';
                        }
                    });
                } else {
                    window.location.href = 'index.php?action=dangnhap';
                }
            } else {
                alert(data.message || 'Có lỗi xảy ra!');
            }
            return;
        }
        const icon = btn.querySelector('i');
        if (icon) {
            if (data.thich) {
                icon.className = 'fa-solid fa-heart text-danger';
                btn.setAttribute('title', 'Bỏ yêu thích');
            } else {
                icon.className = 'fa-regular fa-heart text-white';
                btn.setAttribute('title', 'Thêm vào yêu thích');
            }
        }
        const badges = document.querySelectorAll('#badgeYeuthichNavbar, .gs-badge-yeuthich');
        badges.forEach(b => {
            b.textContent = data.tong;
        });
        if (typeof Swal !== 'undefined') {
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 1800,
                timerProgressBar: true
            });
            Toast.fire({
                icon: data.thich ? 'success' : 'info',
                title: data.thich ? 'Đã thêm vào yêu thích' : 'Đã bỏ khỏi yêu thích'
            });
        }
    })
    .catch(() => {
        window.location.href = 'index.php?action=xlyeuthich&sanpham_id=' + sanphamId;
    });
}

// xu ly so sanh san pham (module 5)
function gsToggleSoSanh(btn, sanphamId) {
    if (!sanphamId) return;
    fetch('index.php?action=ajax_sosanh_them&id=' + sanphamId, {
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(res => res.json())
    .then(data => {
        if (!data.ok) {
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'warning',
                    title: data.khac_danhmuc ? 'Khác loại hàng' : 'Thông báo so sánh',
                    html: `<p class="mb-2">${data.message}</p>` + (data.khac_danhmuc ? `<p class="text-white-50 small mb-0">Bạn có thể xóa danh sách so sánh cũ trên thanh dock nếu muốn so sánh nhóm mới.</p>` : ''),
                    confirmButtonColor: '#f97316'
                });
            } else {
                alert(data.message);
            }
            return;
        }

        if (btn) {
            const icon = btn.querySelector('i');
            if (icon) {
                if (data.sosanh) {
                    icon.classList.remove('text-white');
                    icon.classList.add('text-warning');
                    btn.setAttribute('title', 'Bỏ so sánh');
                } else {
                    icon.classList.remove('text-warning');
                    icon.classList.add('text-white');
                    btn.setAttribute('title', 'Thêm vào so sánh');
                }
            }
            const txt = btn.querySelector('.gs-compare-btn-text');
            if (txt) {
                txt.textContent = data.sosanh ? 'Đang so sánh' : 'So sánh';
            }
        }

        document.querySelectorAll('.gs-btn-compare[data-id="' + sanphamId + '"]').forEach(b => {
            const ic = b.querySelector('i');
            if (ic) {
                if (data.sosanh) {
                    ic.classList.remove('text-white');
                    ic.classList.add('text-warning');
                    b.setAttribute('title', 'Bỏ so sánh');
                } else {
                    ic.classList.remove('text-warning');
                    ic.classList.add('text-white');
                    b.setAttribute('title', 'Thêm vào so sánh');
                }
            }
        });

        gsCapNhatCompareDock(data);

        if (typeof Swal !== 'undefined') {
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 1800,
                timerProgressBar: true
            });
            Toast.fire({
                icon: data.sosanh ? 'success' : 'info',
                title: data.sosanh ? 'Đã thêm vào so sánh' : 'Đã bỏ khỏi so sánh'
            });
        }
    })
    .catch(err => {
        console.error(err);
    });
}

function gsBoSoSanhDock(sanphamId) {
    gsToggleSoSanh(null, sanphamId);
}

function gsXoaTatCaSoSanh() {
    fetch('index.php?action=ajax_sosanh_xoatatca', {
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(r => r.json())
    .then(data => {
        const dock = document.getElementById('gsCompareDock');
        if (dock) dock.style.display = 'none';

        const badge = document.getElementById('badgeSosanhNavbar');
        if (badge) badge.textContent = '0';

        document.querySelectorAll('.gs-btn-compare i').forEach(ic => {
            ic.classList.remove('text-warning');
            ic.classList.add('text-white');
        });
        document.querySelectorAll('.gs-btn-detail-compare').forEach(b => {
            const txt = b.querySelector('.gs-compare-btn-text');
            if (txt) txt.textContent = 'So sánh';
        });

        if (window.location.href.includes('action=sosanh')) {
            window.location.reload();
        }
    })
    .catch(() => {
        window.location.href = 'index.php?action=ajax_sosanh_xoatatca';
    });
}

function gsCapNhatCompareDock(data) {
    const dock = document.getElementById('gsCompareDock');
    const badgeNavbar = document.getElementById('badgeSosanhNavbar');
    const countBadge = document.getElementById('gsCompareDockCount');
    const container = document.getElementById('gsCompareDockItems');

    if (badgeNavbar) badgeNavbar.textContent = data.count;

    if (!dock) return;

    if (data.count <= 0) {
        dock.style.display = 'none';
        return;
    }

    dock.style.display = 'block';
    if (countBadge) countBadge.textContent = data.count + '/3';

    if (container && data.items) {
        container.innerHTML = data.items.map(it => `
            <div class="position-relative gs-dock-item" data-id="${it.id}" title="${it.tensanpham}">
                <img src="${it.hinhanh}" class="rounded-2 border border-secondary" style="width: 38px; height: 38px; object-fit: cover; background: #000;" onerror="gsOnErrorImg(this)">
                <button type="button" class="btn btn-danger btn-sm p-0 rounded-circle position-absolute top-0 start-100 translate-middle d-flex align-items-center justify-content-center" style="width: 16px; height: 16px; font-size: 10px;" onclick="gsBoSoSanhDock(${it.id})">×</button>
            </div>
        `).join('');
    }
}
</script>

<?php
$dsDockIds = isset($_SESSION['gs_sosanh']) && is_array($_SESSION['gs_sosanh']) ? $_SESSION['gs_sosanh'] : array();
$dsDockItems = (!empty($dsDockIds) && isset($sp)) ? $sp->laysanphamtheomangid($dsDockIds) : array();
$dockCount = count($dsDockIds);
?>
<!-- dock so sanh noi o chan trang -->
<div id="gsCompareDock" class="gs-compare-dock shadow-lg" style="display: <?php echo ($dockCount > 0) ? 'block' : 'none'; ?>; position: fixed; bottom: 0; left: 0; right: 0; z-index: 1040; background: rgba(15, 23, 42, 0.95); backdrop-filter: blur(12px); border-top: 1px solid rgba(249, 115, 22, 0.3);">
    <div class="container d-flex align-items-center justify-content-between flex-wrap gap-2 py-2">
        <div class="d-flex align-items-center gap-3">
            <div class="d-flex align-items-center gap-2">
                <div class="rounded-circle d-flex align-items-center justify-content-center bg-warning text-dark fw-bold" style="width: 32px; height: 32px;">
                    <i class="fa-solid fa-code-compare"></i>
                </div>
                <span class="text-white fw-bold d-none d-sm-inline">So sánh:</span>
                <span class="badge bg-warning text-dark fw-bold" id="gsCompareDockCount"><?php echo $dockCount; ?>/3</span>
            </div>
            <div class="d-flex align-items-center gap-2" id="gsCompareDockItems">
                <?php foreach ($dsDockItems as $it): 
                    $thumb = !empty($it['hinhanh']) ? '../assets/images/products/' . $it['hinhanh'] : '../assets/images/systems/no-image.png';
                ?>
                    <div class="position-relative gs-dock-item" data-id="<?php echo (int)$it['id']; ?>" title="<?php echo htmlspecialchars($it['tensanpham']); ?>">
                        <img src="<?php echo htmlspecialchars($thumb); ?>" class="rounded-2 border border-secondary" style="width: 38px; height: 38px; object-fit: cover; background: #000;" onerror="gsOnErrorImg(this)">
                        <button type="button" class="btn btn-danger btn-sm p-0 rounded-circle position-absolute top-0 start-100 translate-middle d-flex align-items-center justify-content-center" style="width: 16px; height: 16px; font-size: 10px;" onclick="gsBoSoSanhDock(<?php echo (int)$it['id']; ?>)">×</button>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <div class="d-flex align-items-center gap-2">
            <button type="button" class="btn btn-outline-secondary btn-sm text-white-50 px-2" onclick="gsXoaTatCaSoSanh()">
                <i class="fa-solid fa-trash-can"></i> <span class="d-none d-md-inline">Xóa hết</span>
            </button>
            <a href="index.php?action=sosanh" class="btn btn-danger btn-sm fw-bold px-3">
                <i class="fa-solid fa-arrows-split-up-and-left me-1"></i> So sánh ngay
            </a>
        </div>
    </div>
</div>

</body>
</html>
