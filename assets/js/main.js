/* ============================================================
   GYMSHOP - Tương tác Frontend (Vanilla JS)
   Bootstrap 5 + SweetAlert2
   ============================================================ */
(function () {
    'use strict';

    /* Tránh nạp trùng: file này được include ở <head> (để có gsOnErrorImg sớm)
       và ở footer.php, nhưng chỉ nên khởi tạo một lần. */
    if (window.gsMainLoaded) {
        return;
    }
    window.gsMainLoaded = true;

    /* Ảnh lỗi -> placeholder SVG */
    window.gsOnErrorImg = function (img) {
        img.onerror = null;
        img.src = 'data:image/svg+xml;utf8,' + encodeURIComponent(
            '<svg xmlns="http://www.w3.org/2000/svg" width="600" height="800" viewBox="0 0 600 800">' +
            '<rect width="600" height="800" fill="#f5f5f5"/>' +
            '<g fill="none" stroke="#d0d0d0" stroke-width="2">' +
            '<rect x="170" y="260" width="260" height="280" rx="8"/>' +
            '<circle cx="300" cy="210" r="70"/>' +
            '</g>' +
            '<text x="300" y="600" text-anchor="middle" font-family="Arial" font-size="32" fill="#a3a3a3" letter-spacing="6">GYMSHOP</text>' +
            '</svg>'
        );
    };

    /* Thông báo SweetAlert2 khi thêm vào giỏ hàng */
    function gsToast(message) {
        if (typeof Swal === 'undefined') {
            window.alert(message);
            return;
        }

        Swal.fire({
            toast: true,
            position: 'top-end',
            icon: 'success',
            title: message,
            showConfirmButton: false,
            timer: 2200,
            timerProgressBar: true,
            customClass: { popup: 'gs-swal-popup' }
        });
    }

    function gsAlert(options) {
        if (typeof Swal === 'undefined') {
            window.alert(options.title);
            return;
        }
        Swal.fire(options);
    }

    document.addEventListener('DOMContentLoaded', function () {
        var navbar = document.querySelector('.navbar.sticky-top');
        var backTop = document.getElementById('gsBackTop');

        /* Sticky navbar thêm shadow khi cuộn + nút về đầu trang */
        function onScroll() {
            if (navbar) {
                navbar.classList.toggle('is-scrolled', window.scrollY > 0);
            }
            if (backTop) {
                backTop.classList.toggle('is-visible', window.scrollY > 400);
            }
        }

        window.addEventListener('scroll', onScroll, { passive: true });
        onScroll();

        if (backTop) {
            backTop.addEventListener('click', function () {
                window.scrollTo({ top: 0, behavior: 'smooth' });
            });
        }

        /* Chọn size nhanh / thêm nhanh trên thẻ sản phẩm */
        document.addEventListener('click', function (e) {
            var pill = e.target.closest('.gs-size-pill');
            if (pill) {
                e.preventDefault();
                var card = pill.closest('.gs-product-card');
                var name = card ? card.getAttribute('data-name') : 'Sản phẩm';
                gsToast('Đã thêm ' + name + ' - Size ' + pill.getAttribute('data-size') + ' vào giỏ hàng!');
                return;
            }

            var quick = e.target.closest('.gs-quick-add');
            if (quick) {
                e.preventDefault();
                var card2 = quick.closest('.gs-product-card');
                var name2 = card2 ? card2.getAttribute('data-name') : 'Sản phẩm';
                gsToast('Đã thêm ' + name2 + ' vào giỏ hàng!');
            }
        });

        /* Form đăng ký nhận tin ở footer */
        var newsForm = document.getElementById('gsNewsletterForm');
        if (newsForm) {
            newsForm.addEventListener('submit', function (e) {
                e.preventDefault();
                var input = newsForm.querySelector('input[type="email"]');
                var email = input ? input.value.trim() : '';

                if (!email) {
                    gsAlert({
                        icon: 'warning',
                        title: 'Vui lòng nhập email!',
                        customClass: { popup: 'gs-swal-popup' }
                    });
                    return;
                }

                gsAlert({
                    icon: 'success',
                    title: 'Đăng ký thành công!',
                    text: 'Cảm ơn bạn đã đăng ký nhận tin & voucher từ GYMSHOP.',
                    confirmButtonText: 'Đóng',
                    customClass: { popup: 'gs-swal-popup' }
                });
                newsForm.reset();
            });
        }
    });
})();
