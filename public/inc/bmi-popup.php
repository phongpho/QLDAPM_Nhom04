<?php
if (!isset($gsModule36)) {
    return;
}

$gsNguoidungHienTai = isset($_SESSION['nguoidung']) ? $_SESSION['nguoidung'] : null;
$gsHosoThetrang = null;
if ($gsNguoidungHienTai && isset($gsNguoidungHienTai['id']) && (int)$gsNguoidungHienTai['id'] > 0 && isset($sp)) {
    $gsHosoThetrang = $sp->layhosothetrang((int)$gsNguoidungHienTai['id']);
}

$popCao     = ($gsHosoThetrang && !empty($gsHosoThetrang['chieucao'])) ? (float)$gsHosoThetrang['chieucao'] : '';
$popNang    = ($gsHosoThetrang && !empty($gsHosoThetrang['cannang'])) ? (float)$gsHosoThetrang['cannang'] : '';
$popGioi    = ($gsHosoThetrang && !empty($gsHosoThetrang['gioitinh'])) ? $gsHosoThetrang['gioitinh'] : 'nam';
$popTuoi    = ($gsHosoThetrang && !empty($gsHosoThetrang['tuoi'])) ? (int)$gsHosoThetrang['tuoi'] : 22;
$popMuctieu = ($gsHosoThetrang && !empty($gsHosoThetrang['muctieu'])) ? $gsHosoThetrang['muctieu'] : 'tangcan';
$popBmi     = ($gsHosoThetrang && !empty($gsHosoThetrang['bmi'])) ? (float)$gsHosoThetrang['bmi'] : 0;
?>

<div id="gsBmiFloatingDock" class="gs-bmi-floating-dock shadow-lg" style="display: none;" onclick="gsMoBmiPopup()">
    <div class="gs-bmi-dock-pill d-flex align-items-center gap-2 px-3 py-2">
        <span class="gs-bmi-dock-pulse"></span>
        <div class="gs-bmi-dock-icon">
            <i class="fa-solid fa-heart-pulse"></i>
        </div>
        <div class="text-start pe-1">
            <div class="fw-bold text-uppercase lh-1 text-white" style="font-size: 0.72rem; letter-spacing: 0.05em;">Hồ sơ BMI</div>
            <small class="text-white-50" style="font-size: 0.68rem;">Đo thể trạng ngay</small>
        </div>
        <div class="gs-bmi-dock-avatar ms-1">
            <img src="../assets/images/banners/tieuvy_bmi_popup.jpg" alt="Tiểu Vy" onerror="this.style.display='none'">
        </div>
    </div>
</div>

<div id="gsBmiPopupOverlay" class="gs-bmi-popup-overlay" style="display: none;">
    <div class="gs-bmi-popup-card shadow-lg" id="gsBmiPopupCard" role="dialog" aria-modal="true">
        <div class="row g-0 align-items-stretch">
            <div class="col-lg-5 d-none d-lg-block position-relative gs-bmi-popup-hero">
                <img src="../assets/images/banners/tieuvy_bmi_popup.jpg" 
                     alt="Hoa hậu Trần Tiểu Vy" 
                     class="gs-bmi-popup-hero__img">
                <div class="gs-bmi-popup-hero__overlay"></div>
                <div class="position-absolute bottom-0 start-0 w-100 p-4 text-white" style="z-index: 2;">
                    <span class="badge text-uppercase px-2 py-1 mb-2" style="background: #D02545; font-size: 0.7rem; letter-spacing: 0.08em; font-weight: 700;">
                        GYMBRO AMBASSADOR
                    </span>
                    <h5 class="fw-bold mb-1 text-white" style="font-size: 1.15rem;">HOA HẬU TRẦN TIỂU VY</h5>
                    <p class="small text-white-50 mb-0" style="line-height: 1.45; font-size: 0.78rem;">
                        "Cùng Tiểu Vy đo chỉ số BMI để GymBro chọn combo đồ tập và dinh dưỡng chuẩn size dành riêng cho bạn!"
                    </p>
                </div>
            </div>

            <div class="col-12 col-lg-7 d-flex flex-column justify-content-between p-4 p-sm-4 bg-white position-relative">
                <button type="button" class="btn-close position-absolute top-0 end-0 m-3" onclick="gsThuNhoBmiPopup()" aria-label="Thu nhỏ"></button>

                <div>
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <span class="badge" style="background: #FFECF3; color: #D02545; border: 1px solid rgba(208, 37, 69, 0.25); font-weight: 700; font-size: 0.72rem;">
                            ĐO CHỈ SỐ THỂ HÌNH
                        </span>
                        <span class="badge bg-light text-dark border small" style="font-size: 0.7rem;">
                            Tiêu chuẩn WHO
                        </span>
                    </div>

                    <h4 class="fw-bold text-dark text-uppercase mb-1" style="font-size: 1.15rem; letter-spacing: -0.01em;">
                        ĐO CHỈ SỐ THỂ TRẠNG BMI
                    </h4>
                    <p class="text-muted small mb-3" style="line-height: 1.45; font-size: 0.8rem;">
                        Nhập chiều cao và cân nặng để hệ thống tự động tính điểm số thể hình, phân loại vóc dáng và tư vấn kích cỡ trang phục, dinh dưỡng phù hợp nhất.
                    </p>

                    <form method="post" action="index.php" id="gsBmiPopupForm">
                        <input type="hidden" name="action" value="luuhoso">
                        <input type="hidden" name="redirect" value="<?php echo htmlspecialchars($_SERVER['REQUEST_URI'] ?? 'index.php'); ?>">

                        <div class="row g-2 mb-2">
                            <div class="col-6">
                                <label class="form-label small fw-semibold text-dark mb-1" style="font-size: 0.78rem;">
                                    Chiều cao (cm) <span class="text-danger">*</span>
                                </label>
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text bg-light"><i class="fa-solid fa-ruler-vertical text-muted"></i></span>
                                    <input type="number" class="form-control" name="chieucao" id="gsPopChieuCao"
                                           min="100" max="250" step="0.1" placeholder="VD: 173" required
                                           value="<?php echo htmlspecialchars($popCao); ?>">
                                </div>
                            </div>
                            <div class="col-6">
                                <label class="form-label small fw-semibold text-dark mb-1" style="font-size: 0.78rem;">
                                    Cân nặng (kg) <span class="text-danger">*</span>
                                </label>
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text bg-light"><i class="fa-solid fa-weight-scale text-muted"></i></span>
                                    <input type="number" class="form-control" name="cannang" id="gsPopCanNang"
                                           min="25" max="300" step="0.1" placeholder="VD: 68" required
                                           value="<?php echo htmlspecialchars($popNang); ?>">
                                </div>
                            </div>
                        </div>

                        <div class="row g-2 mb-2">
                            <div class="col-6">
                                <label class="form-label small fw-semibold text-dark mb-1" style="font-size: 0.78rem;">Giới tính</label>
                                <select class="form-select form-select-sm" name="gioitinh" id="gsPopGioiTinh">
                                    <option value="nam" <?php echo ($popGioi === 'nam') ? 'selected' : ''; ?>>Nam</option>
                                    <option value="nu" <?php echo ($popGioi === 'nu') ? 'selected' : ''; ?>>Nữ</option>
                                </select>
                            </div>
                            <div class="col-6">
                                <label class="form-label small fw-semibold text-dark mb-1" style="font-size: 0.78rem;">Tuổi</label>
                                <input type="number" class="form-control form-control-sm" name="tuoi" id="gsPopTuoi"
                                       min="10" max="100" placeholder="VD: 22" required
                                       value="<?php echo htmlspecialchars($popTuoi); ?>">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label small fw-semibold text-dark mb-1" style="font-size: 0.78rem;">Mục tiêu tập luyện</label>
                            <select class="form-select form-select-sm" name="muctieu" id="gsPopMucTieu">
                                <option value="tangcan" <?php echo ($popMuctieu === 'tangcan') ? 'selected' : ''; ?>>Tăng cân / Tăng cơ bắp</option>
                                <option value="giamcan" <?php echo ($popMuctieu === 'giamcan') ? 'selected' : ''; ?>>Giảm cân / Đốt mỡ thừa</option>
                                <option value="duytri" <?php echo ($popMuctieu === 'duytri') ? 'selected' : ''; ?>>Duy trì vóc dáng săn chắc</option>
                            </select>
                        </div>

                        <div class="d-flex align-items-center gap-2 pt-1">
                            <button type="submit" class="btn btn-danger fw-semibold px-4 py-2 flex-grow-1" style="background: #D02045; border-color: #D02045; font-size: 0.85rem; border-radius: 8px;">
                                <i class="fa-solid fa-calculator me-1"></i> Lưu &amp; Xem gợi ý ngay
                            </button>
                            <button type="button" class="btn btn-outline-secondary px-3 py-2 text-nowrap" style="font-size: 0.85rem; border-radius: 8px;" onclick="gsThuNhoBmiPopup()">
                                Bữa khác nha!
                            </button>
                        </div>
                    </form>
                </div>

                <div class="pt-2 text-center text-muted" style="font-size: 0.72rem;">
                    Bằng việc lưu chỉ số, GymBro sẽ tự động đồng bộ size chuẩn và combo phù hợp cho bạn.
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.gs-bmi-popup-overlay {
    position: fixed;
    inset: 0;
    background: rgba(15, 15, 15, 0.75);
    backdrop-filter: blur(4px);
    z-index: 9998;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 16px;
    animation: gsFadeIn 0.25s ease-out;
}
.gs-bmi-popup-card {
    background: #FFFFFF;
    border-radius: 20px;
    overflow: hidden;
    max-width: 860px;
    width: 100%;
    border: 1px solid rgba(26, 26, 26, 0.1);
    box-shadow: 0 25px 60px rgba(0, 0, 0, 0.45);
    animation: gsScaleIn 0.28s cubic-bezier(0.16, 1, 0.3, 1);
}
.gs-bmi-popup-hero {
    background: linear-gradient(135deg, #141414 0%, #201015 100%);
    min-height: 480px;
}
.gs-bmi-popup-hero__img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    object-position: top center;
    display: block;
}
.gs-bmi-popup-hero__overlay {
    position: absolute;
    inset: 0;
    background: linear-gradient(180deg, rgba(20,20,20,0.1) 0%, rgba(20,20,20,0.3) 50%, rgba(20,20,20,0.92) 100%);
}
.gs-bmi-floating-dock {
    position: fixed;
    bottom: 24px;
    right: 24px;
    z-index: 9990;
    cursor: pointer;
    transition: transform 0.25s cubic-bezier(0.16, 1, 0.3, 1), box-shadow 0.25s ease;
    animation: gsPopIn 0.35s cubic-bezier(0.16, 1, 0.3, 1);
}
.gs-bmi-floating-dock:hover {
    transform: translateY(-4px) scale(1.03);
}
.gs-bmi-dock-pill {
    background: linear-gradient(135deg, #141414 0%, #2A1016 100%);
    border: 1.5px solid rgba(208, 37, 69, 0.55);
    border-radius: 999px;
    color: #FFFFFF;
    position: relative;
    box-shadow: 0 10px 30px rgba(208, 37, 69, 0.35);
}
.gs-bmi-dock-pulse {
    position: absolute;
    top: -3px;
    right: -3px;
    width: 12px;
    height: 12px;
    background: #D02545;
    border-radius: 50%;
    box-shadow: 0 0 0 0 rgba(208, 37, 69, 0.7);
    animation: gsPulse 1.8s infinite;
}
.gs-bmi-dock-icon {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    background: #D02545;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #FFFFFF;
    font-size: 0.85rem;
}
.gs-bmi-dock-avatar {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    overflow: hidden;
    border: 1.5px solid #D02545;
}
.gs-bmi-dock-avatar img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    object-position: top center;
}
@keyframes gsFadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}
@keyframes gsScaleIn {
    from { opacity: 0; transform: scale(0.92) translateY(15px); }
    to { opacity: 1; transform: scale(1) translateY(0); }
}
@keyframes gsPopIn {
    from { opacity: 0; transform: translateY(20px) scale(0.85); }
    to { opacity: 1; transform: translateY(0) scale(1); }
}
@keyframes gsPulse {
    0% { box-shadow: 0 0 0 0 rgba(208, 37, 69, 0.7); }
    70% { box-shadow: 0 0 0 10px rgba(208, 37, 69, 0); }
    100% { box-shadow: 0 0 0 0 rgba(208, 37, 69, 0); }
}
</style>

<script>
(function() {
    var STORAGE_KEY_MINIMIZED = 'gs_bmi_dock_minimized';
    var isUserLoggedIn = <?php echo ($gsNguoidungHienTai ? 'true' : 'false'); ?>;
    var hasHoso = <?php echo (($popCao > 0 && $popNang > 0) ? 'true' : 'false'); ?>;

    function init() {
        if (!isUserLoggedIn) {
            return;
        }

        var overlay = document.getElementById('gsBmiPopupOverlay');
        var dock = document.getElementById('gsBmiFloatingDock');
        if (!overlay || !dock) return;

        var isMinimized = sessionStorage.getItem(STORAGE_KEY_MINIMIZED) === '1';

        if (isMinimized) {
            dock.style.display = 'block';
            overlay.style.display = 'none';
        } else {
            overlay.style.display = 'flex';
            dock.style.display = 'none';
        }
    }

    window.gsThuNhoBmiPopup = function() {
        var overlay = document.getElementById('gsBmiPopupOverlay');
        var dock = document.getElementById('gsBmiFloatingDock');
        if (overlay) overlay.style.display = 'none';
        if (dock) dock.style.display = 'block';
        sessionStorage.setItem(STORAGE_KEY_MINIMIZED, '1');
    };

    window.gsMoBmiPopup = function() {
        var overlay = document.getElementById('gsBmiPopupOverlay');
        var dock = document.getElementById('gsBmiFloatingDock');
        if (overlay) overlay.style.display = 'flex';
        if (dock) dock.style.display = 'none';
        sessionStorage.removeItem(STORAGE_KEY_MINIMIZED);
    };

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();
</script>

