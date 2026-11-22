<!-- Widget liên hệ nhanh đa kênh GYMBRO -->
<div class="gs-contact-widget" id="gsContactWidget">
    <!-- Danh sách các nút liên hệ con -->
    <div class="gs-contact-list" id="gsContactList">
        <!-- 1. Gọi Hotline -->
        <a href="tel:19006868" class="gs-contact-item gs-contact-hotline" title="Gọi hotline hỗ trợ">
            <span class="gs-contact-label">Hotline: 1900 6868</span>
            <div class="gs-contact-icon">
                <i class="fa-solid fa-phone"></i>
            </div>
        </a>

        <!-- 2. Chat Zalo -->
        <a href="https://zalo.me" target="_blank" rel="noopener noreferrer" class="gs-contact-item gs-contact-zalo" title="Chat qua Zalo">
            <span class="gs-contact-label">Chat Zalo tư vấn</span>
            <div class="gs-contact-icon">
                <span class="gs-zalo-text">Zalo</span>
            </div>
        </a>

        <!-- 3. Chat Facebook Messenger -->
        <a href="https://m.me" target="_blank" rel="noopener noreferrer" class="gs-contact-item gs-contact-messenger" title="Nhắn tin qua Messenger">
            <span class="gs-contact-label">Facebook Messenger</span>
            <div class="gs-contact-icon">
                <i class="fa-brands fa-facebook-messenger"></i>
            </div>
        </a>

        <!-- 4. Tư vấn thể trạng BMI & Size đồ tập -->
        <a href="index.php?action=goy" class="gs-contact-item gs-contact-bmi" title="Tư vấn size & thể trạng BMI">
            <span class="gs-contact-label">Tư vấn chọn Size &amp; BMI</span>
            <div class="gs-contact-icon">
                <i class="fa-solid fa-weight-scale"></i>
            </div>
        </a>

        <!-- 5. Địa chỉ showroom cửa hàng -->
        <a href="https://maps.google.com" target="_blank" rel="noopener noreferrer" class="gs-contact-item gs-contact-map" title="Hệ thống cửa hàng">
            <span class="gs-contact-label">Xem địa chỉ Showroom</span>
            <div class="gs-contact-icon">
                <i class="fa-solid fa-location-dot"></i>
            </div>
        </a>
    </div>

    <!-- Nút bấm chính mở/đóng menu -->
    <button type="button" class="gs-contact-main-btn" id="gsContactMainBtn" aria-label="Liên hệ hỗ trợ nhanh" title="Hỗ trợ & Tư vấn trực tuyến">
        <i class="fa-solid fa-headset gs-icon-open"></i>
        <i class="fa-solid fa-xmark gs-icon-close"></i>
        <span class="gs-pulse-ring"></span>
    </button>
</div>

<!-- CSS giao diện & hiệu ứng chuyển động -->
<style>
.gs-contact-widget {
    position: fixed;
    right: 22px;
    bottom: 82px;
    z-index: 1050;
    display: flex;
    flex-direction: column;
    align-items: flex-end;
    font-family: inherit;
}

/* Nút bấm chính */
.gs-contact-main-btn {
    position: relative;
    width: 52px;
    height: 52px;
    border-radius: 50%;
    background: linear-gradient(135deg, #D02545 0%, #A0122E 100%);
    color: #FFFFFF;
    border: none;
    outline: none;
    cursor: pointer;
    box-shadow: 0 8px 24px rgba(208, 37, 69, 0.45);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.gs-contact-main-btn:hover {
    transform: scale(1.08);
    box-shadow: 0 10px 28px rgba(208, 37, 69, 0.6);
}

/* Biểu tượng mở / đóng */
.gs-contact-main-btn .gs-icon-close {
    display: none;
    font-size: 22px;
}

.gs-contact-widget.is-open .gs-contact-main-btn {
    background: #1A1A1A;
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.35);
}

.gs-contact-widget.is-open .gs-contact-main-btn .gs-icon-open {
    display: none;
}

.gs-contact-widget.is-open .gs-contact-main-btn .gs-icon-close {
    display: block;
}

/* Hiệu ứng sóng lan tỏa (Pulse Wave) */
.gs-pulse-ring {
    position: absolute;
    inset: -6px;
    border-radius: 50%;
    border: 2px solid rgba(208, 37, 69, 0.6);
    animation: gsWidgetPulse 2s infinite cubic-bezier(0.25, 1, 0.5, 1);
    pointer-events: none;
}

.gs-contact-widget.is-open .gs-pulse-ring {
    display: none;
}

@keyframes gsWidgetPulse {
    0% {
        transform: scale(0.92);
        opacity: 0.9;
    }
    70% {
        transform: scale(1.35);
        opacity: 0;
    }
    100% {
        transform: scale(1.4);
        opacity: 0;
    }
}

/* Danh sách nút con */
.gs-contact-list {
    display: flex;
    flex-direction: column;
    align-items: flex-end;
    gap: 10px;
    margin-bottom: 12px;
    opacity: 0;
    visibility: hidden;
    transform: translateY(16px);
    pointer-events: none;
    transition: all 0.28s cubic-bezier(0.4, 0, 0.2, 1);
}

.gs-contact-widget.is-open .gs-contact-list {
    opacity: 1;
    visibility: visible;
    transform: translateY(0);
    pointer-events: auto;
}

/* Từng nút liên hệ */
.gs-contact-item {
    display: flex;
    align-items: center;
    gap: 10px;
    text-decoration: none;
    color: inherit;
    transition: transform 0.2s ease;
}

.gs-contact-item:hover {
    transform: translateX(-4px);
}

/* Nhãn chú thích (tooltip text) */
.gs-contact-label {
    background: #1A1A1A;
    color: #FFFFFF;
    font-size: 12.5px;
    font-weight: 600;
    padding: 6px 12px;
    border-radius: 20px;
    white-space: nowrap;
    box-shadow: 0 4px 14px rgba(0, 0, 0, 0.22);
    letter-spacing: 0.2px;
}

/* Icon tròn nhỏ */
.gs-contact-icon {
    width: 44px;
    height: 44px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #FFFFFF;
    font-size: 18px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
    transition: transform 0.2s ease;
}

.gs-contact-item:hover .gs-contact-icon {
    transform: scale(1.1);
}

/* Màu sắc từng kênh */
.gs-contact-hotline .gs-contact-icon {
    background: #E11D48;
}

.gs-contact-zalo .gs-contact-icon {
    background: #0068FF;
}

.gs-contact-zalo .gs-zalo-text {
    font-weight: 800;
    font-size: 12px;
    letter-spacing: -0.5px;
}

.gs-contact-messenger .gs-contact-icon {
    background: linear-gradient(135deg, #00B2FE 0%, #006AFF 50%, #A033FF 100%);
}

.gs-contact-bmi .gs-contact-icon {
    background: #EA580C;
}

.gs-contact-map .gs-contact-icon {
    background: #16A34A;
}

/* Responsive trên điện thoại */
@media (max-width: 576px) {
    .gs-contact-widget {
        right: 14px;
        bottom: 74px;
    }
    .gs-contact-main-btn {
        width: 46px;
        height: 46px;
        font-size: 18px;
    }
    .gs-contact-icon {
        width: 38px;
        height: 38px;
        font-size: 15px;
    }
    .gs-contact-label {
        font-size: 11.5px;
        padding: 4px 10px;
    }
}
</style>

<!-- Script xử lý đóng / mở Widget -->
<script>
document.addEventListener('DOMContentLoaded', function () {
    const widget = document.getElementById('gsContactWidget');
    const mainBtn = document.getElementById('gsContactMainBtn');

    if (!widget || !mainBtn) return;

    // Bật / tắt khi click vào nút chính
    mainBtn.addEventListener('click', function (e) {
        e.stopPropagation();
        widget.classList.toggle('is-open');
    });

    // Tự động đóng lại khi click ra bên ngoài
    document.addEventListener('click', function (e) {
        if (!widget.contains(e.target)) {
            widget.classList.remove('is-open');
        }
    });

    // Đóng khi bấm phím Escape
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
            widget.classList.remove('is-open');
        }
    });
});
</script>
