<?php /* ============================================================
   GYMSHOP - Footer + Scripts
   ============================================================ */ ?>
<footer class="gs-footer">
    <div class="container">
        <div class="row g-4">
            <!-- Cột 1: Về GYMSHOP -->
            <div class="col-lg-3 col-md-6">
                <a class="gs-footer__logo" href="index.php">GYM<em style="color:#767676;">SHOP</em></a>
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
                <a class="gs-footer__link" href="sanpham.php?muctieu=tangcan">Tăng cân / Tăng cơ</a>
                <a class="gs-footer__link" href="sanpham.php?muctieu=giamcan">Giảm cân / Giảm mỡ</a>
                <a class="gs-footer__link" href="sanpham.php?muctieu=duytri">Duy trì vóc dáng</a>
                <a class="gs-footer__link" href="sanpham.php?loai=quanao">Đồ tập gym</a>
                <a class="gs-footer__link" href="sanpham.php?loai=tpbs">Thực phẩm bổ sung</a>
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
                <span>&copy; <?php echo date('Y'); ?> GYMSHOP. Đồ án Quản lý dự án phần mềm - Nhóm 04.</span>
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
</body>
</html>
