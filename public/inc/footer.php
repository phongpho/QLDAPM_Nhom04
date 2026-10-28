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
</script>
</body>
</html>
