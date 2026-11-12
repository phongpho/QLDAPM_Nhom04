<?php
if (!isset($gsModule36)) {
    header('Location: ../index.php');
    exit();
}

$cohoso = ($gsGoiy && $gsGoiy['cohoso']);
$hoso = $cohoso ? $gsGoiy : null;

$curCao = ($hoso && !empty($hoso['chieucao'])) ? (float)$hoso['chieucao'] : 0;
$curNang = ($hoso && !empty($hoso['cannang'])) ? (float)$hoso['cannang'] : 0;
$curBmi = ($hoso && !empty($hoso['bmi'])) ? (float)$hoso['bmi'] : 0;
$hasData = ($curCao >= 100 && $curNang >= 25 && $curBmi > 0);

$idealMin = ($curCao >= 100) ? round(18.5 * pow($curCao / 100, 2), 1) : 0;
$idealMax = ($curCao >= 100) ? round(24.9 * pow($curCao / 100, 2), 1) : 0;

$curPhanloai = ($hoso && !empty($hoso['cauhinh'])) ? $hoso['cauhinh']['phanloai'] : '';
$curKhuyenghe = ($hoso && !empty($hoso['cauhinh'])) ? $hoso['cauhinh']['khuyenghe'] : '';
$curSize = ($hoso && !empty($hoso['sizegoiy'])) ? $hoso['sizegoiy']['size'] : '';

$isAccountTab = isset($tabActive) && $tabActive === 'thetrang';
$redirectUrl = $isAccountTab ? 'index.php?action=taikhoan&tab=thetrang' : 'index.php?action=goy';
?>
<div class="gs-card gs-chiso p-0 border-0 bg-transparent">
    <form method="<?php echo ($gsNguoidungId > 0) ? 'post' : 'get'; ?>" action="index.php" id="formBmiCalculator" class="row g-3 m-0 p-0">
        <input type="hidden" name="action" value="<?php echo ($gsNguoidungId > 0) ? 'luuhoso' : 'goy'; ?>">
        <input type="hidden" name="redirect" value="<?php echo htmlspecialchars($redirectUrl); ?>">

        <div class="col-6 col-lg-3">
            <label class="gs-form-label" for="gsChieuCao">
                Chiều cao (cm) <span class="text-danger">*</span>
            </label>
            <input class="form-control gs-input" type="number" id="gsChieuCao"
                name="chieucao" min="100" max="250" step="0.1" placeholder="VD: 173" required
                value="<?php echo ($curCao > 0) ? htmlspecialchars($curCao) : ''; ?>">
        </div>

        <div class="col-6 col-lg-3">
            <label class="gs-form-label" for="gsCanNang">
                Cân nặng (kg) <span class="text-danger">*</span>
            </label>
            <input class="form-control gs-input" type="number" id="gsCanNang"
                name="cannang" min="25" max="300" step="0.1" placeholder="VD: 68" required
                value="<?php echo ($curNang > 0) ? htmlspecialchars($curNang) : ''; ?>">
        </div>

        <div class="col-6 col-lg-2">
            <label class="gs-form-label" for="gsGioiTinh">
                Giới tính
            </label>
            <select class="form-select gs-input" id="gsGioiTinh" name="gioitinh">
                <option value="nam" <?php echo ($hoso && $hoso['gioitinh'] === 'nam') ? 'selected' : ''; ?>>Nam</option>
                <option value="nu" <?php echo ($hoso && $hoso['gioitinh'] === 'nu') ? 'selected' : ''; ?>>Nữ</option>
            </select>
        </div>

        <div class="col-6 col-lg-2">
            <label class="gs-form-label" for="gsTuoi">
                Tuổi
            </label>
            <input class="form-control gs-input" type="number" id="gsTuoi"
                name="tuoi" min="10" max="100" placeholder="VD: 22" required
                value="<?php echo ($hoso && !empty($hoso['tuoi'])) ? htmlspecialchars($hoso['tuoi']) : '22'; ?>">
        </div>

        <div class="col-12 col-lg-2">
            <label class="gs-form-label" for="gsMucTieu">
                Mục tiêu
            </label>
            <select class="form-select gs-input" id="gsMucTieu" name="muctieu">
                <option value="giamcan" <?php echo ($hoso && $hoso['muctieu'] === 'giamcan') ? 'selected' : ''; ?>>Giảm mỡ</option>
                <option value="tangcan" <?php echo ($hoso && $hoso['muctieu'] === 'tangcan') ? 'selected' : ''; ?>>Tăng cơ</option>
                <option value="duytri" <?php echo (!$hoso || $hoso['muctieu'] === 'duytri') ? 'selected' : ''; ?>>Duy trì</option>
            </select>
        </div>

        <div class="col-12 mt-4">
            <div class="gs-bmi-card">
                <div class="d-flex align-items-center justify-content-between mb-3 pb-2 border-bottom flex-wrap gap-2">
                    <div class="d-flex align-items-center gap-2">
                        <span class="d-inline-flex align-items-center justify-content-center rounded-circle" style="width: 34px; height: 34px; background: #FFECF3; color: #D02545;">
                            <i class="fa-solid fa-heart-pulse"></i>
                        </span>
                        <div>
                            <h5 class="mb-0 text-dark fw-bold">CHỈ SỐ THỂ TRẠNG &amp; BMI</h5>
                            <span class="small text-muted">Công thức chuẩn WHO: BMI = Cân nặng(kg) / (Chiều cao(m))²</span>
                        </div>
                    </div>
                    <span id="bmiLiveLiveNotice" class="badge bg-light text-dark border small px-3 py-2 rounded-pill">
                        Tự động tính Realtime
                    </span>
                </div>

                <div class="row g-4 align-items-center">
                    <div class="col-12 col-md-5 col-lg-4 text-center text-md-start">
                        <div class="d-flex flex-column align-items-center align-items-md-start gap-2">
                            <div class="d-flex align-items-baseline gap-3">
                                <div class="gs-bmi-score-box">
                                    <span class="gs-bmi-score-val" id="bmiLiveScore">
                                        <?php echo $hasData ? number_format($curBmi, 1) : '--'; ?>
                                    </span>
                                    <span class="gs-bmi-score-lbl">Chỉ số BMI</span>
                                </div>
                                <div>
                                    <div class="small text-muted mb-1">Đánh giá thể trạng:</div>
                                    <div id="bmiLiveBadge">
                                        <?php if ($hasData) : ?>
                                            <?php if ($curBmi < 18.5) : ?>
                                                <span class="badge bg-info-subtle text-info border border-info-subtle fs-6 py-2 px-3 rounded-pill">
                                                    Thiếu cân (Gầy)
                                                </span>
                                            <?php elseif ($curBmi < 25.0) : ?>
                                                <span class="badge bg-success-subtle text-success border border-success-subtle fs-6 py-2 px-3 rounded-pill">
                                                    Chuẩn form (Bình thường)
                                                </span>
                                            <?php elseif ($curBmi < 30.0) : ?>
                                                <span class="badge bg-warning-subtle text-warning border border-warning-subtle fs-6 py-2 px-3 rounded-pill">
                                                    Thừa cân (Tiền béo phì)
                                                </span>
                                            <?php else : ?>
                                                <span class="badge bg-danger-subtle text-danger border border-danger-subtle fs-6 py-2 px-3 rounded-pill">
                                                    Béo phì (Cần giảm mỡ)
                                                </span>
                                            <?php endif; ?>
                                        <?php else : ?>
                                            <span class="badge bg-light text-muted border fs-6 py-2 px-3 rounded-pill">
                                                Nhập số để xem kết quả
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-md-7 col-lg-8">
                        <div class="row g-2">
                            <div class="col-6 col-sm-6">
                                <div class="p-3 rounded border h-100" style="background: #F7F4EC;">
                                    <span class="text-muted small d-block mb-1">
                                        Cân nặng lý tưởng:
                                    </span>
                                    <strong class="fs-6 text-dark" id="bmiIdealWeight">
                                        <?php echo ($idealMin > 0) ? ($idealMin . ' kg - ' . $idealMax . ' kg') : '-- kg'; ?>
                                    </strong>
                                </div>
                            </div>
                            <div class="col-6 col-sm-6">
                                <div class="p-3 rounded border h-100" style="background: #F7F4EC;">
                                    <span class="text-muted small d-block mb-1">
                                        Size gợi ý:
                                    </span>
                                    <strong class="fs-6 text-danger" id="bmiLiveSizeBadge">
                                        <?php echo !empty($curSize) ? ('Size ' . htmlspecialchars($curSize)) : 'Chuẩn theo vóc dáng'; ?>
                                    </strong>
                                </div>
                            </div>
                            <div class="col-12 mt-2">
                                <div class="p-3 rounded border bg-light">
                                    <div class="small text-muted" id="bmiLiveAdvice">
                                        <?php 
                                        if (!empty($curKhuyenghe)) {
                                            echo htmlspecialchars($curKhuyenghe);
                                        } else {
                                            echo 'Nhập chiều cao và cân nặng để nhận ngay khuyến nghị dinh dưỡng và các combo sản phẩm phù hợp nhất với thể trạng của bạn.';
                                        }
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-4 pt-3 border-top">
                    <div class="d-flex justify-content-between align-items-center mb-2 small">
                        <span class="text-muted">Thước đo phân vùng thể trạng BMI quốc tế:</span>
                        <span id="bmiPointerValueText" class="fw-bold text-danger">
                            <?php echo $hasData ? ('Chỉ số của bạn: ' . number_format($curBmi, 1)) : 'Chưa có dữ liệu'; ?>
                        </span>
                    </div>

                    <div class="position-relative" style="height: 14px; border-radius: 9999px; overflow: hidden; display: flex;">
                        <div style="width: 25%; background: #06b6d4;" title="Thiếu cân (< 18.5)"></div>
                        <div style="width: 35%; background: #10b981;" title="Bình thường (18.5 - 24.9)"></div>
                        <div style="width: 25%; background: #f59e0b;" title="Thừa cân (25.0 - 29.9)"></div>
                        <div style="width: 15%; background: #D02545;" title="Béo phì (≥ 30.0)"></div>
                    </div>

                    <?php
                    $initLeft = 50;
                    if ($hasData) {
                        $initLeft = max(3, min(97, (($curBmi - 15) / (35 - 15)) * 100));
                    }
                    ?>
                    <div class="position-relative" style="height: 20px;">
                        <div id="bmiPointer" style="position: absolute; top: -3px; left: <?php echo $initLeft; ?>%; transform: translateX(-50%); transition: left 0.35s cubic-bezier(0.16, 1, 0.3, 1);">
                            <i class="fa-solid fa-caret-up text-dark" style="font-size: 1.4rem;"></i>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between flex-wrap gap-1 fw-semibold" style="font-size: 0.76rem; margin-top: -6px;">
                        <span style="color: #0891b2;">&lt; 18.5 (Gầy)</span>
                        <span style="color: #059669;">18.5 - 24.9 (Chuẩn form)</span>
                        <span style="color: #d97706;">25 - 29.9 (Thừa cân)</span>
                        <span style="color: #D02545;">&ge; 30 (Béo phì)</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 d-flex flex-wrap align-items-center gap-2 mt-4">
            <button type="submit" class="btn-gs btn-gs--primary btn-gs--md">
                <?php echo ($gsNguoidungId > 0) ? 'LƯU CHỈ SỐ THỂ TRẠNG' : 'TÍNH CHỈ SỐ NGAY'; ?>
            </button>
            <a class="btn-gs btn-gs--outline btn-gs--sm" href="index.php">
                Bỏ qua và về trang chủ
            </a>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const inputCao = document.getElementById('gsChieuCao');
    const inputNang = document.getElementById('gsCanNang');
    const scoreVal = document.getElementById('bmiLiveScore');
    const badgeBox = document.getElementById('bmiLiveBadge');
    const idealWeightBox = document.getElementById('bmiIdealWeight');
    const pointer = document.getElementById('bmiPointer');
    const pointerText = document.getElementById('bmiPointerValueText');
    const adviceBox = document.getElementById('bmiLiveAdvice');

    if (!inputCao || !inputNang) return;

    function calculateLiveBMI() {
        const h = parseFloat(inputCao.value);
        const w = parseFloat(inputNang.value);

        if (!h || !w || h < 100 || h > 250 || w < 25 || w > 300) {
            return;
        }

        const m = h / 100;
        const bmi = (w / (m * m)).toFixed(1);
        const bmiNum = parseFloat(bmi);

        if (scoreVal) {
            scoreVal.textContent = bmi;
            scoreVal.style.transform = 'scale(1.1)';
            setTimeout(() => { scoreVal.style.transform = 'scale(1)'; }, 200);
        }

        if (idealWeightBox) {
            const minW = (18.5 * m * m).toFixed(1);
            const maxW = (24.9 * m * m).toFixed(1);
            idealWeightBox.textContent = minW + ' kg - ' + maxW + ' kg';
        }

        let badgeHtml = '';
        let adviceHtml = '';
        if (bmiNum < 18.5) {
            badgeHtml = '<span class="badge bg-info-subtle text-info border border-info-subtle fs-6 py-2 px-3 rounded-pill">Thiếu cân (Gầy)</span>';
            adviceHtml = 'Chỉ số BMI cho thấy bạn đang hơi nhẹ cân. Nên ưu tiên tập luyện tăng cơ (Hypertrophy), hạn chế cardio cường độ cao kéo dài và bổ sung dinh dưỡng giàu Calo, Protein & Mass Gainer.';
        } else if (bmiNum < 25.0) {
            badgeHtml = '<span class="badge bg-success-subtle text-success border border-success-subtle fs-6 py-2 px-3 rounded-pill">Chuẩn form (Bình thường)</span>';
            adviceHtml = 'Chúc mừng bạn! Vóc dáng và chỉ số thể trạng đang rất cân đối. Hãy duy trì chế độ tập gym đều đặn, bổ sung Whey Protein tinh khiết và trang bị phụ kiện bảo hộ để nâng cao hiệu suất.';
        } else if (bmiNum < 30.0) {
            badgeHtml = '<span class="badge bg-warning-subtle text-warning border border-warning-subtle fs-6 py-2 px-3 rounded-pill">Thừa cân (Tiền béo phì)</span>';
            adviceHtml = 'Bạn đang trong mức thừa cân nhẹ. Nên kết hợp tập tạ kháng lực cùng các bài tập đốt mỡ (HIIT / Cardio), kiểm soát lượng tinh bột và dùng thêm thực phẩm bổ sung hỗ trợ chuyển hóa mỡ.';
        } else {
            badgeHtml = '<span class="badge bg-danger-subtle text-danger border border-danger-subtle fs-6 py-2 px-3 rounded-pill">Béo phì (Cần giảm mỡ)</span>';
            adviceHtml = 'Chỉ số BMI ở ngưỡng cao. Cần thiết lập kế hoạch giảm mỡ khoa học: tập luyện bài bản, thâm hụt calo an toàn và sử dụng các sản phẩm hỗ trợ đốt mỡ chính hãng để bảo vệ tim mạch & khớp gối.';
        }

        if (badgeBox) {
            badgeBox.innerHTML = badgeHtml;
        }
        if (adviceBox) {
            adviceBox.textContent = adviceHtml;
        }

        if (pointer) {
            const pct = Math.max(3, Math.min(97, ((bmiNum - 15) / (35 - 15)) * 100));
            pointer.style.left = pct + '%';
        }
        if (pointerText) {
            pointerText.textContent = 'Chỉ số của bạn: ' + bmi;
        }
    }

    inputCao.addEventListener('input', calculateLiveBMI);
    inputNang.addEventListener('input', calculateLiveBMI);
});
</script>
