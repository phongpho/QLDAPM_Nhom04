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

    function gsCapNhatBadge(soluong) {
        var lienket = document.querySelector('a[href="index.php?action=giohang"] .badge');
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
            .catch(function () {});
    }

    document.addEventListener('DOMContentLoaded', function () {
        if (window.gsFlash && window.gsFlash.noidung) {
            var loai = (window.gsFlash.loai === 'error') ? 'error' : (window.gsFlash.loai === 'warning' ? 'warning' : 'success');
            gsToast(window.gsFlash.noidung, loai);
        }

        gsLaySoLuongGio();

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
                        form.submit();
                    })
                    .then(function () {
                        if (nutBam) {
                            nutBam.disabled = false;
                        }
                    });
            });
        }

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
