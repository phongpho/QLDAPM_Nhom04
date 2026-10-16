/* ============================================================
   GYMBRO - Module 3 & 6: giỏ hàng, gợi ý, thanh toán
   - Hiển thị thông báo (flash) bằng SweetAlert2
   - Thêm vào giỏ hàng / thêm combo bằng AJAX (có fallback khi JS lỗi)
   - Cập nhật badge số lượng trên navbar theo giỏ hàng thật
   ============================================================ */
(function () {
    'use strict';

    if (window.gsCartLoaded) {
        return;
    }
    window.gsCartLoaded = true;

    function gsToast(message, type) {
        if (typeof Swal === 'undefined') {
            return;
        }
        Swal.fire({
            toast: true,
            position: 'top-end',
            icon: type || 'success',
            title: message,
            showConfirmButton: false,
            timer: 2600,
            timerProgressBar: true,
            customClass: { popup: 'gs-swal-popup' }
        });
    }

    /* Badge giỏ hàng trên navbar (navbar.php giữ nguyên markup) */
    function gsCapNhatBadge(soluong) {
        var lienket = document.querySelector('a[href="giohang.php"] .badge');
        if (!lienket) {
            return;
        }
        lienket.textContent = soluong;
        lienket.style.display = (parseInt(soluong, 10) > 0) ? '' : 'none';
    }

    function gsLaySoLuongGio() {
        if (typeof fetch !== 'function') {
            return;
        }
        fetch('index.php?action=demgiohang', { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
            .then(function (kq) { return kq.json(); })
            .then(function (duLieu) { gsCapNhatBadge(duLieu.soluong); })
            .catch(function () { /* bỏ qua: không ảnh hưởng luồng mua hàng */ });
    }

    document.addEventListener('DOMContentLoaded', function () {
        /* Thông báo sau khi chuyển trang (thêm giỏ, áp voucher, đặt hàng...) */
        if (window.gsFlash && window.gsFlash.noidung) {
            var loai = (window.gsFlash.loai === 'error') ? 'error' : (window.gsFlash.loai === 'warning' ? 'warning' : 'success');
            gsToast(window.gsFlash.noidung, loai);
        }

        gsLaySoLuongGio();

        /* Thêm vào giỏ / thêm combo bằng AJAX, không tải lại trang */
        if (typeof fetch === 'function') {
            document.addEventListener('submit', function (su) {
                var form = su.target;
                if (!form.classList || !form.classList.contains('gs-form-cart')) {
                    return;
                }

                su.preventDefault();
                var duLieu = new FormData(form);
                var nutBam = form.querySelector('button[type="submit"]');
                if (nutBam) {
                    nutBam.disabled = true;
                }

                fetch(form.getAttribute('action') || 'index.php', {
                    method: 'POST',
                    body: duLieu,
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                })
                    .then(function (kq) { return kq.json(); })
                    .then(function (kq) {
                        gsToast(kq.message, kq.ok ? 'success' : 'error');
                        if (typeof kq.soluonggio !== 'undefined') {
                            gsCapNhatBadge(kq.soluonggio);
                        }
                    })
                    .catch(function () {
                        /* Không gọi được AJAX (JS/PHP lỗi tạm thời) -> gửi form theo cách thông thường */
                        form.submit();
                    })
                    .then(function () {
                        if (nutBam) {
                            nutBam.disabled = false;
                        }
                    });
            });
        }

        /* Trang thanh toán: chọn địa chỉ có sẵn -> điền nhanh vào form */
        var oDiaChi = document.querySelectorAll('input[name="id_diachi"]');
        if (oDiaChi.length > 0) {
            var ten = document.getElementById('gsTenNguoiNhan');
            var sdt = document.getElementById('gsSdtNhan');
            var dc = document.getElementById('gsDiaChiNhan');

            oDiaChi.forEach(function (o) {
                o.addEventListener('change', function () {
                    var than = o.closest('.gs-address');
                    if (!than || parseInt(o.value, 10) === 0) {
                        return;
                    }
                    var spans = than.querySelectorAll('.gs-address__body span');
                    if (ten && spans[0]) { ten.value = spans[0].textContent.trim(); }
                    if (sdt && spans[1]) { sdt.value = spans[1].textContent.trim(); }
                    if (dc && spans[2]) { dc.value = spans[2].textContent.trim(); }
                });
            });
        }
    });
})();
